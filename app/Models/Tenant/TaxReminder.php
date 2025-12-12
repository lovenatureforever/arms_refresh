<?php

namespace App\Models\Tenant;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxReminder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tax_reminders';

    protected $fillable = [
        'company_id',
        'reminder_category',
        'reminder_type',
        'tax_estimate_id',
        'basis_period_year',
        'basis_period_from',
        'basis_period_to',
        'action_due_date',
        'reminder_trigger_date',
        'final_reminder_date',
        'status',
        'completed_at',
        'acknowledged_at',
        'acknowledged_by',
        'notification_count',
        'last_notified_at',
        'next_notification_at',
        'primary_recipient_user_id',
        'primary_recipient_email',
        'cc_recipients',
        'reminder_title',
        'reminder_message',
        'reminder_priority',
        'metadata',
    ];

    protected $casts = [
        'basis_period_from' => 'date',
        'basis_period_to' => 'date',
        'action_due_date' => 'date',
        'reminder_trigger_date' => 'date',
        'final_reminder_date' => 'date',
        'completed_at' => 'datetime',
        'acknowledged_at' => 'datetime',
        'last_notified_at' => 'datetime',
        'next_notification_at' => 'datetime',
        'cc_recipients' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Relationships
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function taxEstimate()
    {
        return $this->belongsTo(TaxCp204Estimate::class, 'tax_estimate_id');
    }

    public function primaryRecipient()
    {
        return $this->belongsTo(User::class, 'primary_recipient_user_id');
    }

    public function acknowledgedBy()
    {
        return $this->belongsTo(User::class, 'acknowledged_by');
    }

    public function logs()
    {
        return $this->hasMany(TaxReminderLog::class, 'tax_reminder_id');
    }

    /**
     * Scopes
     */
    public function scopeDueToday($query)
    {
        return $query->where('action_due_date', '=', now()->toDateString());
    }

    public function scopeOverdue($query)
    {
        return $query->where('action_due_date', '<', now()->toDateString())
                     ->whereNotIn('status', ['completed', 'cancelled']);
    }

    public function scopeNeedsNotification($query)
    {
        return $query->where('next_notification_at', '<=', now())
                     ->whereIn('status', ['pending', 'scheduled', 'sent']);
    }

    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('reminder_category', $category);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Helper Methods
     */
    public function daysUntilDue()
    {
        if (!$this->action_due_date) {
            return null;
        }

        return now()->diffInDays($this->action_due_date, false);
    }

    public function isOverdue()
    {
        return $this->action_due_date && $this->action_due_date->isPast() &&
               !in_array($this->status, ['completed', 'cancelled']);
    }

    public function markAsCompleted($userId = null)
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'acknowledged_by' => $userId,
        ]);

        $this->logs()->create([
            'log_type' => 'acknowledged',
            'user_action' => 'completed',
            'action_at' => now(),
            'action_by' => $userId,
            'action_notes' => 'Reminder marked as completed',
            'created_at' => now(),
        ]);
    }
}
