<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use App\Models\Tenant\CosecOrder;
use App\Models\Central\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class AdminCosecIndex extends Component
{


    public function render()
    {
        $orders = CosecOrder::all();
        return view('livewire.tenant.pages.cosec.admin-cosec-index', [
            'orders' => $orders
        ]);
    }

    public function approve($id) {

        $order = CosecOrder::find($id);
        $cost = $order->getEffectiveCost();
        $userId = $order->tenant_user_id;
        // $tenantId = $order->tenant_id;
        $order->update(['status' => 1]);

        // $tenant = Tenant::find($tenantId);
        // tenancy()->initialize($tenant);

        $user = User::find($userId);
        $credit = $user->credit - $cost;
        $user->update(['credit' => $credit]);

        // tenancy()->end();
    }

    public function deny($id) {
        CosecOrder::find($id)->update(['status' => 'approved']);
    }
}
