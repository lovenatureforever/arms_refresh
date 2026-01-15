<?php

namespace App\Livewire\Tenant\Pages\EConfirmation;

use App\Models\Tenant\EConfirmationLog;
use App\Models\Tenant\EConfirmationRequest;
use App\Models\Tenant\EConfirmationSignature;
use App\Models\Tenant\CompanyDirector;
use Livewire\Component;

class DirectorSigning extends Component
{
    public $token;
    public $request;
    public $director;
    public $pendingSignatures = [];
    public $completedSignatures = [];
    public $errorMessage = null;

    public function mount($token)
    {
        $this->token = $token;

        // Find the request by token
        $this->request = EConfirmationRequest::where('token', $token)
            ->with(['company', 'bankPdfs.bankBranch.bank', 'bankPdfs.signatures'])
            ->first();

        if (!$this->request) {
            $this->errorMessage = 'Invalid or expired signing link.';
            return;
        }

        // Check if token is expired
        if ($this->request->isExpired()) {
            $this->errorMessage = 'This signing link has expired. Please contact the audit firm for a new link.';
            return;
        }

        // Check if request is already completed
        if ($this->request->isCompleted()) {
            $this->errorMessage = 'This confirmation request has already been completed.';
            return;
        }

        // Get the current authenticated user's director record
        $user = auth()->user();
        if ($user) {
            $this->director = CompanyDirector::where('company_id', $this->request->company_id)
                ->where('user_id', $user->id)
                ->where('is_active', true)
                ->first();
        }

        if (!$this->director) {
            $this->errorMessage = 'You are not authorized to sign this confirmation request.';
            return;
        }

        $this->loadSignatures();
    }

    public function loadSignatures()
    {
        if (!$this->request || !$this->director) {
            return;
        }

        $this->pendingSignatures = [];
        $this->completedSignatures = [];

        foreach ($this->request->bankPdfs as $bankPdf) {
            $signature = $bankPdf->signatures()
                ->where('director_id', $this->director->id)
                ->first();

            if ($signature) {
                $data = [
                    'bank_pdf_id' => $bankPdf->id,
                    'signature_id' => $signature->id,
                    'bank_name' => $bankPdf->bankBranch->bank->bank_name,
                    'branch_name' => $bankPdf->bankBranch->branch_name,
                    'status' => $signature->status,
                    'signed_at' => $signature->signed_at,
                ];

                if ($signature->status === 'signed') {
                    $this->completedSignatures[] = $data;
                } else {
                    $this->pendingSignatures[] = $data;
                }
            }
        }
    }

    public function sign($signatureId)
    {
        if (!$this->director) {
            session()->flash('error', 'You are not authorized to sign.');
            return;
        }

        $signature = EConfirmationSignature::find($signatureId);

        if (!$signature) {
            session()->flash('error', 'Signature record not found.');
            return;
        }

        // Verify this signature belongs to this director
        if ($signature->director_id !== $this->director->id) {
            session()->flash('error', 'You are not authorized to sign this.');
            return;
        }

        // Check if director has a default signature uploaded
        $directorSignature = $this->director->defaultSignature;

        if (!$directorSignature) {
            session()->flash('error', 'You have not uploaded your e-signature. Please upload your signature in your profile first.');
            return;
        }

        // Perform the signing
        $ip = request()->ip();
        $result = $signature->sign($ip);

        if ($result) {
            session()->flash('success', 'Successfully signed!');
            $this->loadSignatures();
        } else {
            EConfirmationLog::create([
                'econfirmation_request_id' => $signature->bankPdf->econfirmation_request_id,
                'log_type' => EConfirmationLog::TYPE_SIGNATURE_COLLECTED,
                'director_id' => $this->director->id,
                'success' => false,
                'error_message' => 'Signature operation failed',
            ]);
            session()->flash('error', 'Failed to sign. Please try again.');
        }
    }

    public function signAll()
    {
        if (!$this->director) {
            session()->flash('error', 'You are not authorized to sign.');
            return;
        }

        // Check if director has a default signature uploaded
        $directorSignature = $this->director->defaultSignature;

        if (!$directorSignature) {
            session()->flash('error', 'You have not uploaded your e-signature. Please upload your signature in your profile first.');
            return;
        }

        $ip = request()->ip();
        $signedCount = 0;

        foreach ($this->pendingSignatures as $pending) {
            $signature = EConfirmationSignature::find($pending['signature_id']);
            if ($signature && $signature->director_id === $this->director->id) {
                if ($signature->sign($ip)) {
                    $signedCount++;
                }
            }
        }

        if ($signedCount > 0) {
            session()->flash('success', "Successfully signed {$signedCount} confirmation(s)!");
            $this->loadSignatures();
        } else {
            session()->flash('error', 'No confirmations were signed.');
        }
    }

    public function downloadPdf($bankPdfId)
    {
        $bankPdf = $this->request->bankPdfs()->find($bankPdfId);

        if (!$bankPdf || !$bankPdf->unsigned_pdf_path) {
            session()->flash('error', 'PDF not available.');
            return;
        }

        return response()->download(
            storage_path('app/public/' . $bankPdf->unsigned_pdf_path)
        );
    }

    public function render()
    {
        return view('livewire.tenant.pages.econfirmation.director-signing');
    }
}
