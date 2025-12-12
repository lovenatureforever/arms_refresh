<?php

namespace App\Models\Tenant;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxCp204Estimate extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tax_cp204_estimates';

    protected $fillable = [
        'company_id',
        'basis_period_year',
        'basis_period_from',
        'basis_period_to',
        'estimate_type',
        'revision_month',
        'estimated_tax_amount',
        'monthly_installment',
        'submission_status',
        'submitted_at',
        'submitted_by',
        'approved_at',
        'previous_estimate_id',
        'is_85_percent_compliant',
        'compliance_notes',
        'remarks',
        'metadata',
    ];

    protected $casts = [
        'basis_period_from' => 'date',
        'basis_period_to' => 'date',
        'estimated_tax_amount' => 'decimal:2',
        'monthly_installment' => 'decimal:2',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'is_85_percent_compliant' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Relationships
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function previousEstimate()
    {
        return $this->belongsTo(TaxCp204Estimate::class, 'previous_estimate_id');
    }

    public function reminders()
    {
        return $this->hasMany(TaxReminder::class, 'tax_estimate_id');
    }

    /**
     * Scopes
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeForYear($query, $year)
    {
        return $query->where('basis_period_year', $year);
    }

    public function scopeLatestForYear($query, $companyId, $year)
    {
        return $query->forCompany($companyId)
                     ->forYear($year)
                     ->latest('created_at')
                     ->first();
    }

    /**
     * Business Logic - Check 85% Compliance
     * The 85% rule states that from the 2nd year onwards,
     * the estimate must be at least 85% of the previous year's latest estimate
     */
    public function check85PercentCompliance()
    {
        if (!$this->previous_estimate_id) {
            return true; // First year, no compliance check needed
        }

        $previousEstimate = $this->previousEstimate;
        if (!$previousEstimate) {
            return null;
        }

        $minimumRequired = $previousEstimate->estimated_tax_amount * 0.85;
        $isCompliant = $this->estimated_tax_amount >= $minimumRequired;

        $this->update([
            'is_85_percent_compliant' => $isCompliant,
            'compliance_notes' => $isCompliant
                ? 'Compliant: RM ' . number_format($this->estimated_tax_amount, 2) . ' >= 85% of RM ' . number_format($previousEstimate->estimated_tax_amount, 2)
                : 'Non-compliant: RM ' . number_format($this->estimated_tax_amount, 2) . ' < 85% of RM ' . number_format($previousEstimate->estimated_tax_amount, 2) . ' (Minimum required: RM ' . number_format($minimumRequired, 2) . ')',
        ]);

        return $isCompliant;
    }

    /**
     * Calculate monthly installment from estimated tax amount
     */
    public function calculateMonthlyInstallment()
    {
        if (!$this->estimated_tax_amount) {
            return 0;
        }

        // Typically divided by 12 months
        return round($this->estimated_tax_amount / 12, 2);
    }
}
