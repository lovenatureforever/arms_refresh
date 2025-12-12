<?php

namespace App\Jobs;

use App\Models\Tenant\TaxReminder;
use App\Services\TaxNotificationService;
use App\Services\TaxReminderService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessTaxReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public $backoff = 60;

    /**
     * Execute the job.
     */
    public function handle(
        TaxNotificationService $notificationService,
        TaxReminderService $reminderService
    ): void {
        Log::info('Processing tax reminders...');

        // Get all reminders that need to be sent today
        $reminders = TaxReminder::where('next_notification_at', '<=', now())
                                ->whereIn('status', ['pending', 'scheduled'])
                                ->get();

        Log::info("Found {$reminders->count()} tax reminders to process");

        $successCount = 0;
        $failureCount = 0;

        foreach ($reminders as $reminder) {
            try {
                // Send notification via email and WhatsApp
                $sent = $notificationService->sendReminderNotification($reminder);

                if ($sent) {
                    $successCount++;
                    Log::info("Successfully sent reminder {$reminder->id} for company {$reminder->company_id}");
                } else {
                    $failureCount++;
                    Log::warning("Failed to send reminder {$reminder->id} for company {$reminder->company_id}");
                }

            } catch (\Exception $e) {
                $failureCount++;
                Log::error("Error processing reminder {$reminder->id}: " . $e->getMessage());

                // Update reminder status to failed
                $reminder->update([
                    'status' => 'failed',
                    'last_notified_at' => now(),
                ]);
            }
        }

        // Process overdue reminders
        $overdueCount = $reminderService->processOverdueReminders();

        Log::info("Tax reminder processing complete: {$successCount} sent, {$failureCount} failed, {$overdueCount} marked overdue");
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('ProcessTaxReminders job failed: ' . $exception->getMessage());
    }
}
