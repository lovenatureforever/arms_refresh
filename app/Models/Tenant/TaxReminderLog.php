<?php

namespace App\Models\Tenant;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxReminderLog extends Model
{
    use HasFactory;

    protected $table = 'tax_reminder_logs';

    public $timestamps = false;

    protected $fillable = [
        'tax_reminder_id',
        'log_type',
        'recipient_email',
        'recipient_user_id',
        'sent_at',
        'success',
        'error_message',
        'user_action',
        'action_at',
        'action_by',
        'action_notes',
        'metadata',
        'created_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'success' => 'boolean',
        'action_at' => 'datetime',
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function reminder()
    {
        return $this->belongsTo(TaxReminder::class, 'tax_reminder_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_user_id');
    }

    public function actionBy()
    {
        return $this->belongsTo(User::class, 'action_by');
    }

    /**
     * Scopes
     */
    public function scopeSuccessful($query)
    {
        return $query->where('success', true);
    }

    public function scopeFailed($query)
    {
        return $query->where('success', false);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('log_type', $type);
    }

    public function scopeEmailSent($query)
    {
        return $query->where('log_type', 'email_sent');
    }

    public function scopeEmailFailed($query)
    {
        return $query->where('log_type', 'email_failed');
    }
}
