<?php

namespace App\Mail;

use App\Models\Tenant\TaxReminder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaxReminderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $reminder;
    public $recipient;

    /**
     * Create a new message instance.
     */
    public function __construct(TaxReminder $reminder, array $recipient)
    {
        $this->reminder = $reminder;
        $this->recipient = $recipient;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = $this->reminder->reminder_title;

        // Add priority prefix to subject
        if ($this->reminder->reminder_priority === 'urgent') {
            $subject = '[URGENT] ' . $subject;
        } elseif ($this->reminder->reminder_priority === 'high') {
            $subject = '[IMPORTANT] ' . $subject;
        }

        // Determine if this is a final reminder
        $isFinalReminder = $this->reminder->metadata['is_final_reminder'] ?? false;

        return $this->subject($subject)
                    ->view('emails.tax.reminder')
                    ->with([
                        'reminderTitle' => $this->reminder->reminder_title,
                        'reminderMessage' => $this->reminder->reminder_message,
                        'dueDate' => $this->reminder->action_due_date->format('d F Y'),
                        'companyName' => $this->reminder->company->name,
                        'fiscalYearPeriod' => $this->reminder->company->fiscal_year_period,
                        'recipientName' => $this->recipient['name'],
                        'priority' => $this->reminder->reminder_priority,
                        'reminderType' => $this->reminder->reminder_type,
                        'reminderCategory' => $this->reminder->reminder_category,
                        'isFinalReminder' => $isFinalReminder,
                        'reminderSequence' => $this->reminder->metadata['reminder_sequence'] ?? 1,
                        'totalReminders' => $this->reminder->metadata['total_reminders'] ?? 2,
                        'daysUntilDue' => $this->reminder->daysUntilDue(),
                    ]);
    }
}
