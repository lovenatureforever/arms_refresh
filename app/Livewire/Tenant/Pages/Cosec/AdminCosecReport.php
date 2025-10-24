<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use App\Models\Tenant\CosecOrder;
use App\Models\Central\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;

class AdminCosecReport extends Component
{
    #[Locked]
    public $id;

    public function mount($id) {
        $this->id = $id;
    }

    #[On('ReportUpdated')]
    public function render()
    {
        $order = CosecOrder::find($this->id);
        return view('livewire.tenant.pages.cosec.admin-cosec-report', [
            'order' => $order
        ]);
    }

    public function approve() {
        $order = CosecOrder::find($this->id);
        $order->update(['status' => 1]);

        $cost = $order->getEffectiveCost();
        // $tenantId = $order->tenant_id;
        $userId = $order->tenant_user_id;

        // $tenant = Tenant::find($tenantId);
        // tenancy()->initialize($tenant);

        $user = User::find($userId);
        $credit = $user->credit - $cost;
        $user->update(['credit' => $credit]);

        // tenancy()->end();

        LivewireAlert::withOptions([
            "position" => "top-end",
            "icon" => "success",
            "title" => "Approved successfully!",
            "showConfirmButton" => false,
            "timer" => 1500
        ])->show();
        redirect()->route('admin.cosec.index');
    }

    public function deny() {
        $order = CosecOrder::find($this->id);
        $order->update(['status' => 2]);

        $cost = $order->getEffectiveCost();
        // $tenantId = $order->tenant_id;
        $userId = $order->tenant_user_id;

        // $tenant = Tenant::find($tenantId);
        // tenancy()->initialize($tenant);

        $user = User::find($userId);
        $credit = $user->credit - $cost;
        $user->update(['credit' => $credit]);

        // tenancy()->end();

        LivewireAlert::withOptions([
            "position" => "top-end",
            "icon" => "success",
            "title" => "Denied successfully!",
            "showConfirmButton" => false,
            "timer" => 1500
        ])->show();
        redirect()->route('admin.cosec.index');
    }

}
