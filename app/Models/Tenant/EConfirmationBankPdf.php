<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EConfirmationBankPdf extends Model
{
    use HasFactory;

    protected $table = 'econfirmation_bank_pdfs';

    const STATUS_PENDING = 'pending';
    const STATUS_PARTIAL = 'partial';
    const STATUS_SIGNED = 'signed';

    protected $fillable = [
        'econfirmation_request_id',
        'bank_branch_id',
        'unsigned_pdf_path',
        'signed_pdf_path',
        'status',
        'signatures_required',
        'signatures_collected',
        'version',
    ];

    // Relationships
    public function request(): BelongsTo
    {
        return $this->belongsTo(EConfirmationRequest::class, 'econfirmation_request_id');
    }

    public function bankBranch(): BelongsTo
    {
        return $this->belongsTo(BankBranch::class);
    }

    public function signatures(): HasMany
    {
        return $this->hasMany(EConfirmationSignature::class, 'econfirmation_bank_pdf_id');
    }

    // Helper methods
    public function isFullySigned(): bool
    {
        return $this->signatures_collected >= $this->signatures_required
               && $this->signatures_required > 0;
    }

    public function getProgressPercentage(): int
    {
        if ($this->signatures_required === 0) {
            return 0;
        }
        return (int) round(
            ($this->signatures_collected / $this->signatures_required) * 100
        );
    }

    public function updateSignatureCount(): void
    {
        $count = $this->signatures()->where('status', 'signed')->count();

        $status = self::STATUS_PENDING;
        if ($count > 0 && $count < $this->signatures_required) {
            $status = self::STATUS_PARTIAL;
        } elseif ($count >= $this->signatures_required) {
            $status = self::STATUS_SIGNED;
        }

        $this->update([
            'signatures_collected' => $count,
            'status' => $status,
        ]);

        // If all signatures collected, generate signed PDF
        if ($status === self::STATUS_SIGNED) {
            $pdfService = new \App\Services\EConfirmationPdfService();
            $pdfService->generateSignedPdf($this);
        }

        // Trigger request progress recalculation
        $this->request->recalculateProgress();
    }

    public function getPendingDirectors()
    {
        return $this->signatures()
                    ->where('status', 'pending')
                    ->with('director')
                    ->get()
                    ->pluck('director');
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'secondary',
            self::STATUS_PARTIAL => 'warning',
            self::STATUS_SIGNED => 'success',
            default => 'secondary',
        };
    }
}
