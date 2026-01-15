<?php

namespace App\Jobs;

use App\Models\Tenant\EConfirmationRequest;
use App\Services\EConfirmationNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessEConfirmationReminders implements ShouldQueue
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
    public function handle(EConfirmationNotificationService $notificationService): void
    {
        Log::info('Processing e-confirmation reminders...');

        $reminder3DayCount = 0;
        $reminder1DayCount = 0;
        $expiredCount = 0;

        // 1. Send 3-day expiry reminders (only once)
        $expiring3Days = EConfirmationRequest::pending()
            ->expiringSoon(3)
            ->whereDoesntHave('logs', function ($query) {
                $query->where('log_type', 'reminder_sent')
                      ->where('metadata->reminder_type', '3_day')
                      ->where('created_at', '>=', now()->subDays(1));
            })
            ->with(['company', 'bankPdfs.signatures'])
            ->get();

        Log::info("Found {$expiring3Days->count()} requests expiring in 3 days");

        foreach ($expiring3Days as $request) {
            try {
                $sent = $notificationService->sendReminder($request, '3_day');
                if ($sent > 0) {
                    $reminder3DayCount++;
                    Log::info("Sent 3-day reminder for request {$request->id} ({$request->company->name})");
                }
            } catch (\Exception $e) {
                Log::error("Failed to send 3-day reminder for request {$request->id}: " . $e->getMessage());
            }
        }

        // 2. Send 1-day (urgent) expiry reminders
        $expiring1Day = EConfirmationRequest::pending()
            ->expiringSoon(1)
            ->whereDoesntHave('logs', function ($query) {
                $query->where('log_type', 'reminder_sent')
                      ->where('metadata->reminder_type', '1_day')
                      ->where('created_at', '>=', now()->subHours(12));
            })
            ->with(['company', 'bankPdfs.signatures'])
            ->get();

        Log::info("Found {$expiring1Day->count()} requests expiring in 1 day");

        foreach ($expiring1Day as $request) {
            try {
                $sent = $notificationService->sendReminder($request, '1_day');
                if ($sent > 0) {
                    $reminder1DayCount++;
                    Log::info("Sent 1-day urgent reminder for request {$request->id} ({$request->company->name})");
                }
            } catch (\Exception $e) {
                Log::error("Failed to send 1-day reminder for request {$request->id}: " . $e->getMessage());
            }
        }

        // 3. Mark expired requests
        $expiredCount = EConfirmationRequest::where('token_expires_at', '<', now())
            ->where('status', EConfirmationRequest::STATUS_PENDING_SIGNATURES)
            ->update(['status' => EConfirmationRequest::STATUS_EXPIRED]);

        if ($expiredCount > 0) {
            Log::info("Marked {$expiredCount} requests as expired");
        }

        Log::info("E-confirmation reminder processing complete: {$reminder3DayCount} 3-day reminders, {$reminder1DayCount} 1-day reminders, {$expiredCount} marked expired");
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('ProcessEConfirmationReminders job failed: ' . $exception->getMessage());
    }
}
