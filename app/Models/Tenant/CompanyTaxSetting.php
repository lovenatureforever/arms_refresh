<?php

namespace App\Models\Tenant;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyTaxSetting extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'company_tax_settings';

    protected $fillable = [
        'company_id',
        'enable_cp204_reminders',
        'cp204_submission_days_before',
        'enable_cp204a_reminders',
        'enable_monthly_installment_reminders',
        'installment_reminder_days_before',
        'primary_tax_contact_user_id',
        'primary_tax_contact_email',
        'cc_company_directors',
        'cc_auditors',
        'additional_recipients',
        'first_reminder_days_before',
        'second_reminder_days_before',
        'final_reminder_days_before',
        'custom_reminder_schedule',
        'notification_channels',
    ];

    protected $casts = [
        'enable_cp204_reminders' => 'boolean',
        'enable_cp204a_reminders' => 'boolean',
        'enable_monthly_installment_reminders' => 'boolean',
        'cc_company_directors' => 'boolean',
        'cc_auditors' => 'boolean',
        'additional_recipients' => 'array',
        'custom_reminder_schedule' => 'array',
        'notification_channels' => 'array',
    ];

    /**
     * Relationships
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function primaryTaxContact()
    {
        return $this->belongsTo(User::class, 'primary_tax_contact_user_id');
    }

    /**
     * Helper Methods
     */
    public function isReminderEnabled($type)
    {
        switch ($type) {
            case 'cp204':
                return $this->enable_cp204_reminders ?? true;
            case 'cp204a':
                return $this->enable_cp204a_reminders ?? true;
            case 'monthly_installment':
                return $this->enable_monthly_installment_reminders ?? true;
            default:
                return false;
        }
    }

    public function getReminderDaysBefore($type)
    {
        switch ($type) {
            case 'cp204':
                return $this->cp204_submission_days_before ?? 30;
            case 'installment':
                return $this->installment_reminder_days_before ?? 7;
            default:
                return 30;
        }
    }

    /**
     * Get the reminder schedule (first, second, final reminders)
     */
    public function getReminderSchedule()
    {
        return [
            'first' => $this->first_reminder_days_before ?? 30,
            'second' => $this->second_reminder_days_before ?? 14,
            'final' => $this->final_reminder_days_before ?? 3,
        ];
    }
}
