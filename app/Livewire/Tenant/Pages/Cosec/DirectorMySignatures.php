<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use Livewire\Component;
use App\Models\Tenant\CompanyDirector;
use App\Models\Tenant\DirectorSignature;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class DirectorMySignatures extends Component
{
    use WithFileUploads;

    public $signatureFile;

    public function uploadSignature()
    {
        $this->validate([
            'signatureFile' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        $user = auth()->user();

        // Find director linked to current user by user_id
        $director = CompanyDirector::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if (!$director) {
            LivewireAlert::withOptions([
                'position' => 'top-end',
                'icon' => 'error',
                'title' => 'Director profile not found',
                'timer' => 2000
            ])->show();
            return;
        }

        // Remove existing default signature
        DirectorSignature::where('director_id', $director->id)
            ->where('is_default', true)
            ->delete();

        // Upload new signature
        $path = Storage::putFile('public', $this->signatureFile);

        DirectorSignature::create([
            'director_id' => $director->id,
            'signature_path' => basename($path),
            'is_default' => true,
            'uploaded_by' => auth()->id()
        ]);

        $this->reset('signatureFile');

        LivewireAlert::withOptions([
            'position' => 'top-end',
            'icon' => 'success',
            'title' => 'Signature uploaded successfully!',
            'timer' => 1500
        ])->show();
    }

    public function deleteSignature()
    {
        $user = auth()->user();

        $director = CompanyDirector::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if ($director) {
            $signature = DirectorSignature::where('director_id', $director->id)
                ->where('is_default', true)
                ->first();

            if ($signature) {
                Storage::disk('public')->delete($signature->signature_path);
                $signature->delete();

                LivewireAlert::withOptions([
                    'position' => 'top-end',
                    'icon' => 'success',
                    'title' => 'Signature deleted successfully!',
                    'timer' => 1500
                ])->show();
            }
        }
    }

    public function render()
    {
        $user = auth()->user();

        // Find director by user_id (consistent with uploadSignature and deleteSignature methods)
        $director = CompanyDirector::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        $currentSignature = null;

        if ($director) {
            $currentSignature = DirectorSignature::where('director_id', $director->id)
                ->where('is_default', true)
                ->first();
        }

        return view('livewire.tenant.pages.cosec.director-my-signatures', [
            'director' => $director,
            'currentSignature' => $currentSignature,
        ]);
    }
}
