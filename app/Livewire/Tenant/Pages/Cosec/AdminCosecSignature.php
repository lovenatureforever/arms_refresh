<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use Livewire\Component;
use App\Models\Tenant\CompanyDirector;
use App\Models\Tenant\DirectorSignature;
use Livewire\WithFileUploads;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class AdminCosecSignature extends Component
{
    use WithFileUploads;

    public $companyId;
    public $selectedDirector;
    public $signatureFile;
    public $pendingSignatures = [];

    public function mount($companyId)
    {
        $this->companyId = $companyId;
    }

    public function render()
    {
        $directors = CompanyDirector::where('company_id', $this->companyId)
            ->where('is_active', true)
            ->with('signatures')
            ->get();

        return view('livewire.tenant.pages.cosec.admin-cosec-signature', [
            'directors' => $directors
        ]);
    }

    public function uploadSignature()
    {
        $this->validate([
            'selectedDirector' => 'required',
            'signatureFile' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        // Remove existing default signature
        DirectorSignature::where('director_id', $this->selectedDirector)
            ->where('is_default', true)
            ->delete();

        // $path = $this->signatureFile->store('signatures', 'public');
        $path = Storage::putFile('public', $this->signatureFile);
        DirectorSignature::create([
            'director_id' => $this->selectedDirector,
            'signature_path' => basename($path),
            'is_default' => true,
            'uploaded_by' => auth()->id()
        ]);

        $this->reset(['selectedDirector', 'signatureFile']);

        LivewireAlert::withOptions([
            'position' => 'top-end',
            'icon' => 'success',
            'title' => 'Signature uploaded successfully',
            'timer' => 1500
        ])->show();
    }

    public function setDefaultSigner($directorId)
    {
        DB::transaction(function () use ($directorId) {
            // Remove default signer from all directors in this company
            CompanyDirector::where('company_id', $this->companyId)
                ->update(['is_default_signer_cosec' => false]);
            
            // Set new default signer
            CompanyDirector::where('id', $directorId)
                ->update(['is_default_signer_cosec' => true]);
        });

        LivewireAlert::withOptions([
            'position' => 'top-end',
            'icon' => 'success',
            'title' => 'Default signer updated successfully',
            'timer' => 1500
        ])->show();
    }

    public function deleteSignature($directorId)
    {
        $signature = DirectorSignature::where('director_id', $directorId)
            ->where('is_default', true);
        if ($signature) {
            Storage::disk('public')->delete($signature->signature_path);
            $signature->delete();
            $this->loadSignatures();

            LivewireAlert::withOptions([
                "position" => "top-end",
                "icon" => "success",
                "title" => "Signature deleted successfully!",
                "showConfirmButton" => false,
                "timer" => 1500
            ])->show();
        }
    }
}
