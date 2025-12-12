<?php

namespace App\Models\Tenant;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Company extends Model
{
    use HasFactory, SoftDeletes, HasChangeRecords;

    protected $fillable = [
        'name',
        'registration_no',
        'registration_no_old',
        'current_is_first_year',
        'current_year_type',
        'current_report_header_format',
        'current_year',
        'current_year_from',
        'current_year_to',
        'last_is_first_year',
        'last_year_type',
        'last_report_header_format',
        'last_year',
        'last_year_from',
        'last_year_to',
        'audit_fee',
        'no_business_address',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'current_is_first_year' => 'boolean',
        'last_is_first_year' => 'boolean',
        'no_business_address' => 'boolean',
        'current_year_from' => 'date',
        'current_year_to' => 'date',
        'last_year_from' => 'date',
        'last_year_to' => 'date',
    ];

    /**
     * The company detail changes.
     */

    public function detailChanges()
    {
        return $this->hasMany(CompanyDetailChange::class);
    }

    public function getEndDateReportAttribute()
    {
        $date = $this->current_year_to;
        if ($this->reportSetting && $this->reportSetting->report_date) {
            $date = $this->reportSetting->report_date;
        }
        return $date;
    }

    /**
     * The company business changes.
     */
    public function businessChanges()
    {
        return $this->hasMany(CompanyBusinessChange::class);
    }

    /**
     * The company address changes.
     */
    public function addressChanges()
    {
        return $this->hasMany(CompanyAddressChange::class);
    }

    /**
     * The company business address changes.
     */
    public function bizAddressChanges()
    {
        return $this->hasMany(CompanyBizAddressChange::class);
    }

    /**
     * The company share capital changes.
     */
    public function sharecapitalChanges()
    {
        return $this->hasMany(CompanyShareCapitalChange::class);
    }

    /**
     * The company directors
     */
    public function directors()
    {
        return $this->hasMany(CompanyDirector::class);
    }

    /**
     * The default COSEC signer for this company.
     */
    public function defaultCosecSigner()
    {
        return $this->hasOne(CompanyDirector::class)->where('is_default_signer_cosec', true)->where('is_active', true);
    }

    /**
     * The company director changes.
     */
    public function directorChanges()
    {
        return $this->hasManyThrough(CompanyDirectorChange::class, CompanyDirector::class);
    }

    /**
     * The company shareholders.
     */
    public function shareholders()
    {
        return $this->hasMany(CompanyShareholder::class);
    }

    /**
     * The company shareholder changes.
     */
    public function shareholderChanges()
    {
        return $this->hasManyThrough(CompanyShareholderChange::class, CompanyShareholder::class);
    }

    /**
     * The company secretaries.
     */
    public function secretaries()
    {
        return $this->hasMany(CompanySecretary::class);
    }
    /**
     * The company secretary changes.
     */
    public function secretaryChanges()
    {
        return $this->hasManyThrough(CompanySecretaryChange::class, CompanySecretary::class);
    }

    /**
     * The company charge changes.
     */
    public function chargeChanges()
    {
        return $this->hasMany(CompanyChargeChange::class);
    }

    /**
     * The company dividend changes.
     */
    public function dividendChanges()
    {
        return $this->hasMany(CompanyDividendChange::class);
    }

    /**
     * The company report settings.
     */
    public function reportSetting()
    {
        return $this->hasOne(CompanyReportSetting::class);
    }

    public function auditorSetting()
    {
        return $this->hasOne(CompanyAuditorSetting::class);
    }

    /**
     * The user who created this company.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getNameAttribute()
    {
        // First try to get name from detail changes, fall back to direct attribute
        return $this->detailAtLast()->name ?? $this->attributes['name'] ?? null;
    }

    public function ordinaryShareCapitalAtStart()
    {
        return $this->sharecapitalChanges()->where('effective_date', '<=', $this->current_year_from)->where('share_type', 'Ordinary shares')->latest('effective_date')->first();
    }

    public function preferenceShareCapitalAtStart()
    {
        return $this->sharecapitalChanges()->where('effective_date', '<=', $this->current_year_from)->where('share_type', 'Preference shares')->latest('effective_date')->first();
    }

    /**
     * Tax-related relationships
     */
    public function taxEstimates()
    {
        return $this->hasMany(TaxCp204Estimate::class);
    }

    public function taxReminders()
    {
        return $this->hasMany(TaxReminder::class);
    }

    public function taxSettings()
    {
        return $this->hasOne(CompanyTaxSetting::class);
    }

    /**
     * CP204 Helper Methods
     * These methods use the customizable fiscal year (current_year_from/to)
     * to calculate CP204 submission deadlines and revision dates
     */

    /**
     * Get current year's latest tax estimate
     */
    public function currentYearTaxEstimate()
    {
        return $this->taxEstimates()
                    ->where('basis_period_year', $this->current_year)
                    ->latest('created_at')
                    ->first();
    }

    /**
     * Calculate CP204 submission deadline
     * CP204 must be filed at least 30 days before basis period begins
     * Uses customizable fiscal year (current_year_from)
     */
    public function cp204SubmissionDeadline()
    {
        if (!$this->current_year_from) {
            return null;
        }

        return Carbon::parse($this->current_year_from)->subDays(30);
    }

    /**
     * Calculate CP204A revision dates (6th, 9th, and 11th month of basis period)
     * These dates are based on the customizable fiscal year period
     *
     * @return array Array with keys: 6th_month, 9th_month, 11th_month
     */
    public function cp204aRevisionDates()
    {
        if (!$this->current_year_from || !$this->current_year_to) {
            return [];
        }

        $from = Carbon::parse($this->current_year_from);
        $to = Carbon::parse($this->current_year_to);

        $totalMonths = $from->diffInMonths($to);

        // Only provide revision dates if basis period is long enough
        $revisionDates = [];

        if ($totalMonths >= 6) {
            $revisionDates['6th_month'] = $from->copy()->addMonths(6);
        }

        if ($totalMonths >= 9) {
            $revisionDates['9th_month'] = $from->copy()->addMonths(9);
        }

        if ($totalMonths >= 11) {
            $revisionDates['11th_month'] = $from->copy()->addMonths(11);
        }

        return $revisionDates;
    }

    /**
     * Get fiscal year period in readable format
     * Example: "1 Jan 2024 - 31 Dec 2024" or "1 Apr 2024 - 31 Mar 2025"
     */
    public function getFiscalYearPeriodAttribute()
    {
        if (!$this->current_year_from || !$this->current_year_to) {
            return null;
        }

        return Carbon::parse($this->current_year_from)->format('d M Y') . ' - ' .
               Carbon::parse($this->current_year_to)->format('d M Y');
    }

    /**
     * Check if fiscal year follows standard calendar year (Jan-Dec)
     */
    public function isStandardFiscalYear()
    {
        if (!$this->current_year_from || !$this->current_year_to) {
            return false;
        }

        $from = Carbon::parse($this->current_year_from);
        $to = Carbon::parse($this->current_year_to);

        return $from->month === 1 && $from->day === 1 &&
               $to->month === 12 && $to->day === 31;
    }

    /* public function getLatestDetailBefore($date)
    {
        if (!$date) {
            return null;
        }

        return $this->detailChanges()
            ->where('effective_date', '<=', $date)
            ->orderByDesc('effective_date')
            ->first();
    }

    public function getDetailAtStart()
    {
        return $this->getLatestDetailBefore($this->current_year_from);
    }

    public function getDetailAtLast($date = null)
    {
        $date = $date ? Carbon::parse($date) : $this->current_year_to;

        return $this->getLatestDetailBefore($date);
    }

    public function getDetailChangesCurrentYear()
    {
        if (!$this->current_year_from || !$this->current_year_to) {
            return null;
        }

        return $this->detailChanges()
            ->whereBetween('effective_date', [$this->current_year_from, $this->current_year_to])
            ->orderBy('effective_date')
            ->get();
    } */

    /* public function getLatestBusinessBefore($date)
    {
        if (!$date) {
            return null;
        }

        return $this->businessChanges()
            ->where('effective_date', '<=', $date)
            ->orderByDesc('effective_date')
            ->first();
    }

    public function getBusinessAtStart()
    {
        return $this->getLatestBusinessBefore($this->current_year_from);
    }

    public function getBusinessAtLast($date = null)
    {
        $date = $date ? Carbon::parse($date) : $this->current_year_to;

        return $this->getLatestBusinessBefore($date);
    }

    public function getBusinessChangesCurrentYear()
    {
        if (!$this->current_year_from || !$this->current_year_to) {
            return null;
        }

        return $this->businessChanges()
            ->whereBetween('effective_date', [$this->current_year_from, $this->current_year_to])
            ->orderBy('effective_date')
            ->get();
    } */
}
