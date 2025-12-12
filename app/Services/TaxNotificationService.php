<?php

namespace App\Services;

use App\Models\Tenant\TaxReminder;
use App\Mail\TaxReminderNotification;
use App\Models\Tenant\TaxReminderLog;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class TaxNotificationService
{
    /**
     * Send reminder notification via email and WhatsApp
     */
    public function sendReminderNotification(TaxReminder $reminder): bool
    {
        try {
            $recipients = $this->getRecipients($reminder);
            $successCount = 0;

            foreach ($recipients as $recipient) {
                // Send Email
                if (!empty($recipient['email'])) {
                    try {
                        Mail::to($recipient['email'])
                            ->send(new TaxReminderNotification($reminder, $recipient));

                        $this->logNotification($reminder, $recipient, 'email_sent', true);
                        $successCount++;
                    } catch (\Exception $e) {
                        Log::error("Failed to send email to {$recipient['email']}: " . $e->getMessage());
                        $this->logNotification($reminder, $recipient, 'email_failed', false, $e->getMessage());
                    }
                }

                // Send WhatsApp
                if (!empty($recipient['phone'])) {
                    try {
                        $this->sendWhatsAppNotification($reminder, $recipient);
                        $this->logNotification($reminder, $recipient, 'whatsapp_sent', true);
                        $successCount++;
                    } catch (\Exception $e) {
                        Log::error("Failed to send WhatsApp to {$recipient['phone']}: " . $e->getMessage());
                        $this->logNotification($reminder, $recipient, 'whatsapp_failed', false, $e->getMessage());
                    }
                }
            }

            // Update reminder status
            $reminder->update([
                'status' => $successCount > 0 ? 'sent' : 'failed',
                'notification_count' => $reminder->notification_count + 1,
                'last_notified_at' => now(),
                'next_notification_at' => null, // Clear next notification as reminder is sent
            ]);

            return $successCount > 0;

        } catch (\Exception $e) {
            Log::error("Failed to send reminder notification {$reminder->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send WhatsApp notification
     */
    protected function sendWhatsAppNotification(TaxReminder $reminder, array $recipient): bool
    {
        // Format phone number (remove spaces, dashes, add country code if needed)
        $phone = $this->formatPhoneNumber($recipient['phone']);

        // Prepare WhatsApp message
        $message = $this->formatWhatsAppMessage($reminder, $recipient);

        // Check which WhatsApp service to use
        $whatsappService = config('services.whatsapp.provider', 'whatsapp-api'); // twilio, whatsapp-api, etc.

        switch ($whatsappService) {
            case 'twilio':
                return $this->sendViaTwilio($phone, $message);

            case 'whatsapp-api':
                return $this->sendViaWhatsAppApi($phone, $message);

            case 'fonnte':
                return $this->sendViaFonnte($phone, $message);

            default:
                Log::warning("WhatsApp provider '{$whatsappService}' not configured. Message not sent.");
                return false;
        }
    }

    /**
     * Send via Twilio WhatsApp API
     */
    protected function sendViaTwilio(string $phone, string $message): bool
    {
        $accountSid = config('services.twilio.account_sid');
        $authToken = config('services.twilio.auth_token');
        $fromNumber = config('services.twilio.whatsapp_number');

        if (!$accountSid || !$authToken || !$fromNumber) {
            Log::warning("Twilio credentials not configured");
            return false;
        }

        $response = Http::withBasicAuth($accountSid, $authToken)
            ->asForm()
            ->post("https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json", [
                'From' => "whatsapp:{$fromNumber}",
                'To' => "whatsapp:{$phone}",
                'Body' => $message,
            ]);

        return $response->successful();
    }

    /**
     * Send via WhatsApp Business API (Official)
     */
    protected function sendViaWhatsAppApi(string $phone, string $message): bool
    {
        $token = config('services.whatsapp.access_token');
        $phoneNumberId = config('services.whatsapp.phone_number_id');

        if (!$token || !$phoneNumberId) {
            Log::warning("WhatsApp Business API credentials not configured");
            return false;
        }

        $response = Http::withToken($token)
            ->post("https://graph.facebook.com/v17.0/{$phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $phone,
                'type' => 'text',
                'text' => [
                    'body' => $message,
                ],
            ]);

        return $response->successful();
    }

    /**
     * Send via Fonnte (Popular in Malaysia/Indonesia)
     */
    protected function sendViaFonnte(string $phone, string $message): bool
    {
        $token = config('services.fonnte.token');

        if (!$token) {
            Log::warning("Fonnte credentials not configured");
            return false;
        }

        $response = Http::withHeaders([
            'Authorization' => $token,
        ])->post('https://api.fonnte.com/send', [
            'target' => $phone,
            'message' => $message,
            'countryCode' => '60', // Malaysia country code
        ]);

        return $response->successful();
    }

    /**
     * Format WhatsApp message
     */
    protected function formatWhatsAppMessage(TaxReminder $reminder, array $recipient): string
    {
        $message = "*{$reminder->reminder_title}*\n\n";
        $message .= "Hello {$recipient['name']},\n\n";
        $message .= $reminder->reminder_message . "\n\n";
        $message .= "📅 *Due Date:* {$reminder->action_due_date->format('d M Y')}\n";
        $message .= "🏢 *Company:* {$reminder->company->name}\n";
        $message .= "📊 *Fiscal Year:* {$reminder->company->fiscal_year_period}\n\n";

        // Add priority indicator
        if ($reminder->reminder_priority === 'urgent') {
            $message .= "⚠️ *URGENT - Action Required Today*\n\n";
        } elseif ($reminder->reminder_priority === 'high') {
            $message .= "🔔 *High Priority*\n\n";
        }

        // Add specific instructions based on reminder type
        if (str_contains($reminder->reminder_type, 'cp204_initial')) {
            $message .= "Please submit your CP204 tax estimate form to LHDN.\n\n";
        } elseif (str_contains($reminder->reminder_type, 'cp204a_revision')) {
            $message .= "This is an optional revision period. You may revise your tax estimate if needed.\n\n";
        } elseif (str_contains($reminder->reminder_type, 'monthly_installment')) {
            $message .= "Please ensure your monthly tax installment payment is processed.\n\n";
        }

        $message .= "---\n";
        $message .= "This is an automated reminder from ARMS Tax Management System.";

        return $message;
    }

    /**
     * Format phone number for WhatsApp
     * Converts formats like:
     * - 0123456789 -> 60123456789
     * - +60123456789 -> 60123456789
     * - 012-345-6789 -> 60123456789
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Add Malaysia country code if not present
        if (strlen($phone) === 10 && substr($phone, 0, 1) === '0') {
            $phone = '60' . substr($phone, 1);
        } elseif (strlen($phone) === 9) {
            $phone = '60' . $phone;
        }

        return $phone;
    }

    /**
     * Get all recipients for the reminder
     */
    protected function getRecipients(TaxReminder $reminder): array
    {
        $recipients = [];

        // Primary recipient
        if ($reminder->primary_recipient_email || $reminder->primaryRecipient) {
            $recipients[] = [
                'email' => $reminder->primary_recipient_email ?? $reminder->primaryRecipient->email ?? null,
                'phone' => $reminder->primaryRecipient->phone_number ?? null,
                'name' => $reminder->primaryRecipient->name ?? 'Tax Contact',
                'role' => 'primary',
            ];
        }

        // CC recipients
        if ($reminder->cc_recipients) {
            foreach ($reminder->cc_recipients as $cc) {
                $recipients[] = [
                    'email' => $cc['email'] ?? null,
                    'phone' => $cc['phone'] ?? null,
                    'name' => $cc['name'] ?? 'Recipient',
                    'role' => $cc['role'] ?? 'cc',
                ];
            }
        }

        return array_filter($recipients, function ($recipient) {
            // Only include recipients with at least email or phone
            return !empty($recipient['email']) || !empty($recipient['phone']);
        });
    }

    /**
     * Log notification attempt
     */
    protected function logNotification(
        TaxReminder $reminder,
        array $recipient,
        string $logType,
        bool $success,
        ?string $error = null
    ): void {
        TaxReminderLog::create([
            'tax_reminder_id' => $reminder->id,
            'log_type' => $logType,
            'recipient_email' => $recipient['email'] ?? null,
            'recipient_user_id' => $reminder->primary_recipient_user_id,
            'sent_at' => now(),
            'success' => $success,
            'error_message' => $error,
            'metadata' => [
                'phone' => $recipient['phone'] ?? null,
                'role' => $recipient['role'] ?? null,
            ],
            'created_at' => now(),
        ]);
    }

    /**
     * Send test notification
     */
    public function sendTestNotification(string $email, string $phone): array
    {
        $results = [
            'email' => false,
            'whatsapp' => false,
        ];

        // Test email
        try {
            Mail::raw('This is a test notification from ARMS Tax Reminder System.', function ($message) use ($email) {
                $message->to($email)
                        ->subject('ARMS Tax Reminder - Test Notification');
            });
            $results['email'] = true;
        } catch (\Exception $e) {
            Log::error("Test email failed: " . $e->getMessage());
            $results['email_error'] = $e->getMessage();
        }

        // Test WhatsApp
        try {
            $phone = $this->formatPhoneNumber($phone);
            $message = "*Test Notification*\n\nThis is a test message from ARMS Tax Reminder System.\n\nIf you received this, WhatsApp notifications are working correctly!";

            $whatsappService = config('services.whatsapp.provider', 'whatsapp-api');
            switch ($whatsappService) {
                case 'twilio':
                    $results['whatsapp'] = $this->sendViaTwilio($phone, $message);
                    break;
                case 'whatsapp-api':
                    $results['whatsapp'] = $this->sendViaWhatsAppApi($phone, $message);
                    break;
                case 'fonnte':
                    $results['whatsapp'] = $this->sendViaFonnte($phone, $message);
                    break;
            }
        } catch (\Exception $e) {
            Log::error("Test WhatsApp failed: " . $e->getMessage());
            $results['whatsapp_error'] = $e->getMessage();
        }

        return $results;
    }
}
