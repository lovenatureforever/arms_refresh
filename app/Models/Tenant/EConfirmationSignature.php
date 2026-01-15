<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EConfirmationSignature extends Model
{
    use HasFactory;

    protected $table = 'econfirmation_signatures';

    const STATUS_PENDING = 'pending';
    const STATUS_SIGNED = 'signed';
    const STATUS_WAIVED = 'waived';

    protected $fillable = [
        'econfirmation_bank_pdf_id',
        'director_id',
        'status',
        'signature_path_used',
        'signed_at',
        'signed_ip',
        'director_name',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
    ];

    // Relationships
    public function bankPdf(): BelongsTo
    {
        return $this->belongsTo(EConfirmationBankPdf::class, 'econfirmation_bank_pdf_id');
    }

    public function director(): BelongsTo
    {
        return $this->belongsTo(CompanyDirector::class, 'director_id');
    }

    // Helper methods
    public function isSigned(): bool
    {
        return $this->status === self::STATUS_SIGNED;
    }

    public function sign(string $ip = null): bool
    {
        $director = $this->director;
        $signature = $director->defaultSignature;

        if (!$signature) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_SIGNED,
            'signature_path_used' => $signature->signature_path,
            'signed_at' => now(),
            'signed_ip' => $ip,
        ]);

        // Update bank PDF signature count
        $this->bankPdf->updateSignatureCount();

        return true;
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'secondary',
            self::STATUS_SIGNED => 'success',
            self::STATUS_WAIVED => 'info',
            default => 'secondary',
        };
    }
}
