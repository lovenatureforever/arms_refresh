<?php

namespace App\Mail;

use App\Models\Tenant\EConfirmationRequest;
use App\Models\Tenant\CompanyDirector;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EConfirmationSigningRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $request;
    public $director;
    public $signingUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(EConfirmationRequest $request, CompanyDirector $director, string $signingUrl)
    {
        $this->request = $request;
        $this->director = $director;
        $this->signingUrl = $signingUrl;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = "Bank Confirmation Signing Required - {$this->request->company->name}";

        return $this->subject($subject)
                    ->view('emails.econfirmation.signing-request')
                    ->with([
                        'companyName' => $this->request->company->name,
                        'directorName' => $this->director->name,
                        'yearEndDate' => $this->request->year_end_date->format('d F Y'),
                        'expiresAt' => $this->request->token_expires_at->format('d F Y'),
                        'daysUntilExpiry' => $this->request->daysUntilExpiry(),
                        'signingUrl' => $this->signingUrl,
                        'bankCount' => $this->request->total_banks,
                    ]);
    }
}
