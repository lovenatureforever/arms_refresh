<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use Livewire\Component;
use App\Models\Tenant\CosecOrder;
use App\Models\Tenant\CosecOrderSignature;
use App\Models\Tenant\CompanyDirector;

class DirectorPendingSignatures extends Component
{
    public $filter = 'pending'; // pending, signed, all

    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    public function render()
    {
        $user = auth()->user();

        // Find director linked to current user by user_id
        $director = CompanyDirector::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        $signatures = collect();

        if ($director) {
            $query = CosecOrderSignature::where('director_id', $director->id)
                ->with(['order', 'order.template']);

            if ($this->filter === 'pending') {
                $query->where('signature_status', 'pending');
            } elseif ($this->filter === 'signed') {
                $query->where('signature_status', 'signed');
            }

            $signatures = $query->orderBy('created_at', 'desc')->get();
        }

        return view('livewire.tenant.pages.cosec.director-pending-signatures', [
            'director' => $director,
            'signatures' => $signatures,
        ]);
    }
}
