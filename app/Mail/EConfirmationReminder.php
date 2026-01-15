<?php

namespace App\Mail;

use App\Models\Tenant\EConfirmationRequest;
use App\Models\Tenant\CompanyDirector;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EConfirmationReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $request;
    public $director;
    public $signingUrl;
    public $reminderType;

    /**
     * Create a new message instance.
     *
     * @param EConfirmationRequest $request
     * @param CompanyDirector $director
     * @param string $signingUrl
     * @param string $reminderType '3_day' or '1_day'
     */
    public function __construct(
        EConfirmationRequest $request,
        CompanyDirector $director,
        string $signingUrl,
        string $reminderType = '3_day'
    ) {
        $this->request = $request;
        $this->director = $director;
        $this->signingUrl = $signingUrl;
        $this->reminderType = $reminderType;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $daysRemaining = $this->request->daysUntilExpiry();
        $isUrgent = $this->reminderType === '1_day';

        $subject = $isUrgent
            ? "[URGENT] Bank Confirmation Expires Tomorrow - {$this->request->company->name}"
            : "[REMINDER] Bank Confirmation Expires in {$daysRemaining} Days - {$this->request->company->name}";

        return $this->subject($subject)
                    ->view('emails.econfirmation.reminder')
                    ->with([
                        'companyName' => $this->request->company->name,
                        'directorName' => $this->director->name,
                        'yearEndDate' => $this->request->year_end_date->format('d F Y'),
                        'expiresAt' => $this->request->token_expires_at->format('d F Y'),
                        'daysUntilExpiry' => $daysRemaining,
                        'signingUrl' => $this->signingUrl,
                        'bankCount' => $this->request->total_banks,
                        'isUrgent' => $isUrgent,
                        'reminderType' => $this->reminderType,
                    ]);
    }
}
