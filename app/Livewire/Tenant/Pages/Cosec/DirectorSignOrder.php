<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use Livewire\Component;
use App\Models\Tenant\CosecOrder;
use App\Models\Tenant\CosecOrderSignature;
use App\Models\Tenant\CompanyDirector;
use App\Models\Tenant\DirectorSignature;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Locked;

class DirectorSignOrder extends Component
{
    #[Locked]
    public $id;

    public function mount($id)
    {
        $this->id = $id;
    }

    public function signOrder()
    {
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

        // Check if director has a signature uploaded
        $signature = DirectorSignature::where('director_id', $director->id)
            ->where('is_default', true)
            ->first();

        if (!$signature) {
            LivewireAlert::withOptions([
                'position' => 'top-end',
                'icon' => 'error',
                'title' => 'Please upload your signature first',
                'timer' => 2000
            ])->show();
            return redirect()->route('director.cosec.my-signatures');
        }

        // Update signature status
        $orderSignature = CosecOrderSignature::where('cosec_order_id', $this->id)
            ->where('director_id', $director->id)
            ->first();

        if ($orderSignature) {
            $orderSignature->update([
                'signature_status' => 'signed',
                'signed_at' => now(),
            ]);

            // Check if all signatures are complete
            $order = CosecOrder::find($this->id);
            $pendingCount = CosecOrderSignature::where('cosec_order_id', $this->id)
                ->where('signature_status', 'pending')
                ->count();

            if ($pendingCount === 0) {
                $order->update(['signature_status' => 'complete']);
            } else {
                $order->update(['signature_status' => 'partial']);
            }

            LivewireAlert::withOptions([
                'position' => 'top-end',
                'icon' => 'success',
                'title' => 'Document signed successfully!',
                'timer' => 1500
            ])->show();

            return redirect()->route('director.cosec.signatures');
        }

        LivewireAlert::withOptions([
            'position' => 'top-end',
            'icon' => 'error',
            'title' => 'Signature request not found',
            'timer' => 2000
        ])->show();
    }

    public function render()
    {
        $order = CosecOrder::with(['template', 'company'])->find($this->id);
        $user = auth()->user();

        $director = CompanyDirector::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        $signatureRequest = null;
        $hasSignature = false;

        if ($director) {
            $signatureRequest = CosecOrderSignature::where('cosec_order_id', $this->id)
                ->where('director_id', $director->id)
                ->first();

            $hasSignature = DirectorSignature::where('director_id', $director->id)
                ->where('is_default', true)
                ->exists();
        }

        return view('livewire.tenant.pages.cosec.director-sign-order', [
            'order' => $order,
            'director' => $director,
            'signatureRequest' => $signatureRequest,
            'hasSignature' => $hasSignature,
        ]);
    }
}
