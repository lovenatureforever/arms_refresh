<?php

namespace App\Models\Tenant;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class EConfirmationRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'econfirmation_requests';

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING_SIGNATURES = 'pending_signatures';
    const STATUS_COMPLETED = 'completed';
    const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'company_id',
        'created_by',
        'year_end_date',
        'year_end_period',
        'token',
        'token_expires_at',
        'status',
        'total_banks',
        'banks_completed',
        'total_signatures_required',
        'total_signatures_collected',
        'sent_at',
        'completed_at',
        'validity_days',
        'account_no',
        'charge_code',
        'approved_by',
        'authorization_letter_path',
        'client_consent_acknowledged',
        'metadata',
    ];

    protected $casts = [
        'year_end_date' => 'date',
        'token_expires_at' => 'datetime',
        'sent_at' => 'datetime',
        'completed_at' => 'datetime',
        'client_consent_acknowledged' => 'boolean',
        'metadata' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->token)) {
                $model->token = Str::random(64);
            }
            if (empty($model->token_expires_at)) {
                $model->token_expires_at = now()->addDays($model->validity_days ?? 14);
            }
        });
    }

    // Relationships
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function bankPdfs(): HasMany
    {
        return $this->hasMany(EConfirmationBankPdf::class, 'econfirmation_request_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(EConfirmationLog::class, 'econfirmation_request_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING_SIGNATURES);
    }

    public function scopeExpiringSoon($query, $days = 3)
    {
        return $query->where('status', self::STATUS_PENDING_SIGNATURES)
                     ->where('token_expires_at', '<=', now()->addDays($days))
                     ->where('token_expires_at', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('token_expires_at', '<', now())
                     ->where('status', '!=', self::STATUS_COMPLETED);
    }

    // Helper methods
    public function isExpired(): bool
    {
        return $this->token_expires_at->isPast();
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function canSign(): bool
    {
        return !$this->isExpired() &&
               $this->status === self::STATUS_PENDING_SIGNATURES;
    }

    public function getProgressPercentage(): int
    {
        if ($this->total_signatures_required === 0) {
            return 0;
        }
        return (int) round(
            ($this->total_signatures_collected / $this->total_signatures_required) * 100
        );
    }

    public function daysUntilExpiry(): int
    {
        return (int) now()->diffInDays($this->token_expires_at, false);
    }

    public function regenerateToken(int $validityDays = null): void
    {
        $this->update([
            'token' => Str::random(64),
            'token_expires_at' => now()->addDays($validityDays ?? $this->validity_days),
            'status' => self::STATUS_PENDING_SIGNATURES,
        ]);
    }

    public function recalculateProgress(): void
    {
        $totalSignatures = 0;
        $collectedSignatures = 0;
        $banksCompleted = 0;

        foreach ($this->bankPdfs as $bankPdf) {
            $totalSignatures += $bankPdf->signatures_required;
            $collectedSignatures += $bankPdf->signatures_collected;

            if ($bankPdf->status === EConfirmationBankPdf::STATUS_SIGNED) {
                $banksCompleted++;
            }
        }

        $this->update([
            'total_signatures_required' => $totalSignatures,
            'total_signatures_collected' => $collectedSignatures,
            'banks_completed' => $banksCompleted,
        ]);

        // Auto-complete if all banks are signed
        if ($banksCompleted === $this->total_banks && $this->total_banks > 0) {
            $this->update([
                'status' => self::STATUS_COMPLETED,
                'completed_at' => now(),
            ]);
        }
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_DRAFT => 'secondary',
            self::STATUS_PENDING_SIGNATURES => 'warning',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_EXPIRED => 'danger',
            default => 'secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PENDING_SIGNATURES => 'Pending Signatures',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_EXPIRED => 'Expired',
            default => ucfirst($this->status),
        };
    }
}
