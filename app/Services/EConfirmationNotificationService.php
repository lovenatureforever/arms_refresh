<?php

namespace App\Services;

use App\Models\Tenant\EConfirmationRequest;
use App\Models\Tenant\EConfirmationLog;
use App\Models\Tenant\CompanyDirector;
use App\Mail\EConfirmationSigningRequest;
use App\Mail\EConfirmationReminder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EConfirmationNotificationService
{
    /**
     * Send signing request emails to all directors
     */
    public function sendSigningRequest(EConfirmationRequest $request): int
    {
        $successCount = 0;
        $directors = $this->getDirectorsToNotify($request);

        foreach ($directors as $director) {
            if ($this->sendSigningRequestEmail($request, $director)) {
                $successCount++;
            }
        }

        return $successCount;
    }

    /**
     * Send reminder emails to directors who haven't signed yet
     */
    public function sendReminder(EConfirmationRequest $request, string $reminderType = '3_day'): int
    {
        $successCount = 0;
        $directors = $this->getPendingDirectors($request);

        foreach ($directors as $director) {
            if ($this->sendReminderEmail($request, $director, $reminderType)) {
                $successCount++;
            }
        }

        return $successCount;
    }

    /**
     * Send signing request email to a specific director
     */
    protected function sendSigningRequestEmail(EConfirmationRequest $request, CompanyDirector $director): bool
    {
        $email = $this->getDirectorEmail($director);

        if (!$email) {
            $this->logNotification($request, $director, EConfirmationLog::TYPE_EMAIL_SENT, false, 'No email address found');
            return false;
        }

        $signingUrl = $this->getSigningUrl($request);

        try {
            Mail::to($email)
                ->send(new EConfirmationSigningRequest($request, $director, $signingUrl));

            $this->logNotification($request, $director, EConfirmationLog::TYPE_EMAIL_SENT, true);
            Log::info("E-Confirmation signing request sent to {$director->name} ({$email}) for request {$request->id}");

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send e-confirmation signing request to {$email}: " . $e->getMessage());
            $this->logNotification($request, $director, EConfirmationLog::TYPE_EMAIL_SENT, false, $e->getMessage());

            return false;
        }
    }

    /**
     * Send reminder email to a specific director
     */
    protected function sendReminderEmail(EConfirmationRequest $request, CompanyDirector $director, string $reminderType): bool
    {
        $email = $this->getDirectorEmail($director);

        if (!$email) {
            $this->logNotification($request, $director, EConfirmationLog::TYPE_REMINDER_SENT, false, 'No email address found');
            return false;
        }

        $signingUrl = $this->getSigningUrl($request);

        try {
            Mail::to($email)
                ->send(new EConfirmationReminder($request, $director, $signingUrl, $reminderType));

            $this->logNotification($request, $director, EConfirmationLog::TYPE_REMINDER_SENT, true, null, [
                'reminder_type' => $reminderType,
                'days_until_expiry' => $request->daysUntilExpiry(),
            ]);
            Log::info("E-Confirmation reminder ({$reminderType}) sent to {$director->name} ({$email}) for request {$request->id}");

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send e-confirmation reminder to {$email}: " . $e->getMessage());
            $this->logNotification($request, $director, EConfirmationLog::TYPE_REMINDER_SENT, false, $e->getMessage());

            return false;
        }
    }

    /**
     * Get all active directors for a request's company
     */
    protected function getDirectorsToNotify(EConfirmationRequest $request): \Illuminate\Support\Collection
    {
        return $request->company->directors()
            ->where('is_active', true)
            ->get();
    }

    /**
     * Get directors who haven't signed yet
     */
    protected function getPendingDirectors(EConfirmationRequest $request): \Illuminate\Support\Collection
    {
        $signedDirectorIds = $request->bankPdfs()
            ->with('signatures')
            ->get()
            ->flatMap(function ($bankPdf) {
                return $bankPdf->signatures
                    ->where('status', 'signed')
                    ->pluck('director_id');
            })
            ->unique()
            ->toArray();

        return $request->company->directors()
            ->where('is_active', true)
            ->whereNotIn('id', $signedDirectorIds)
            ->get();
    }

    /**
     * Get director's email address
     */
    protected function getDirectorEmail(CompanyDirector $director): ?string
    {
        // First try to get email from the linked user
        if ($director->user && $director->user->email) {
            return $director->user->email;
        }

        // Fallback to director's email field if exists
        if (isset($director->email) && $director->email) {
            return $director->email;
        }

        return null;
    }

    /**
     * Generate signing URL for the request
     */
    protected function getSigningUrl(EConfirmationRequest $request): string
    {
        return route('econfirmation.sign', ['token' => $request->token]);
    }

    /**
     * Log notification attempt
     */
    protected function logNotification(
        EConfirmationRequest $request,
        CompanyDirector $director,
        string $logType,
        bool $success,
        ?string $error = null,
        ?array $metadata = null
    ): void {
        EConfirmationLog::create([
            'econfirmation_request_id' => $request->id,
            'log_type' => $logType,
            'director_id' => $director->id,
            'recipient_email' => $this->getDirectorEmail($director),
            'success' => $success,
            'error_message' => $error,
            'metadata' => $metadata,
        ]);
    }
}
