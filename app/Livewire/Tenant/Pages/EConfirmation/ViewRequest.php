<?php

namespace App\Livewire\Tenant\Pages\EConfirmation;

use App\Models\Tenant\EConfirmationRequest;
use App\Services\EConfirmationNotificationService;
use Livewire\Component;

class ViewRequest extends Component
{
    public EConfirmationRequest $request;

    public function mount($id)
    {
        $this->request = EConfirmationRequest::with([
            'company',
            'creator',
            'bankPdfs.bankBranch.bank',
            'bankPdfs.signatures.director',
        ])->findOrFail($id);
    }

    public function sendToDirectors()
    {
        $service = new EConfirmationNotificationService();
        $sentCount = $service->sendSigningRequest($this->request);

        $this->request->update([
            'status' => EConfirmationRequest::STATUS_PENDING_SIGNATURES,
            'sent_at' => now(),
        ]);

        if ($sentCount > 0) {
            session()->flash('success', "Signing request sent to {$sentCount} director(s) successfully.");
        } else {
            session()->flash('error', 'Failed to send emails. Please check director email addresses.');
        }
    }

    public function render()
    {
        return view('livewire.tenant.pages.econfirmation.view-request');
    }
}
