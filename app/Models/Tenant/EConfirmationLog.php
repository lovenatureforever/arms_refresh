<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EConfirmationLog extends Model
{
    use HasFactory;

    protected $table = 'econfirmation_logs';

    // Log type constants
    const TYPE_REQUEST_CREATED = 'request_created';
    const TYPE_EMAIL_SENT = 'email_sent';
    const TYPE_REMINDER_SENT = 'reminder_sent';
    const TYPE_SIGNATURE_COLLECTED = 'signature_collected';
    const TYPE_PDF_GENERATED = 'pdf_generated';
    const TYPE_PDF_REGENERATED = 'pdf_regenerated';
    const TYPE_TOKEN_ACCESSED = 'token_accessed';
    const TYPE_REQUEST_EXPIRED = 'request_expired';
    const TYPE_REQUEST_COMPLETED = 'request_completed';

    protected $fillable = [
        'econfirmation_request_id',
        'log_type',
        'director_id',
        'recipient_email',
        'success',
        'error_message',
        'metadata',
    ];

    protected $casts = [
        'success' => 'boolean',
        'metadata' => 'array',
    ];

    // Relationships
    public function request(): BelongsTo
    {
        return $this->belongsTo(EConfirmationRequest::class, 'econfirmation_request_id');
    }

    public function director(): BelongsTo
    {
        return $this->belongsTo(CompanyDirector::class, 'director_id');
    }

    public function getLogTypeLabelAttribute(): string
    {
        return match($this->log_type) {
            self::TYPE_REQUEST_CREATED => 'Request Created',
            self::TYPE_EMAIL_SENT => 'Email Sent',
            self::TYPE_REMINDER_SENT => 'Reminder Sent',
            self::TYPE_SIGNATURE_COLLECTED => 'Signature Collected',
            self::TYPE_PDF_GENERATED => 'PDF Generated',
            self::TYPE_PDF_REGENERATED => 'PDF Regenerated',
            self::TYPE_TOKEN_ACCESSED => 'Token Accessed',
            self::TYPE_REQUEST_EXPIRED => 'Request Expired',
            self::TYPE_REQUEST_COMPLETED => 'Request Completed',
            default => ucfirst(str_replace('_', ' ', $this->log_type)),
        };
    }
}
