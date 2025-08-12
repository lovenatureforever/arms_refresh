<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use Livewire\Component;
use App\Livewire\Tenant\OrderService;
use App\Models\Tenant\CosecOrder;
use App\Models\Tenant\Company;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Illuminate\Support\Facades\Log;

class CartCosec extends Component
{
    #[Locked]
    public $id;

    public $needTopUp = false;

    public function mount($id)
    {
        $this->id = $id;
    }

    #[On('orderRemoved')]
    public function render(OrderService $orderService)
    {
        $creditBalance = auth()->user()->credit;
        $orders = $orderService->getOrders();
        $credit = 0;

        foreach ($orders as $order) {
            $credit += $order['cost'];
        }

        $this->needTopUp = $creditBalance < $credit;

        return view('livewire.tenant.pages.cosec.cart', [
            'orders' => $orders,
            'credit' => $credit,
            'creditBalance' => $creditBalance,
        ]);
    }

    public function removeOrder(OrderService $orderService, $index) {
        $orders = $orderService->getOrders();

        if (isset($orders[$index])) {
            unset($orders[$index]);
        }

        $orderService->setOrders(array_values($orders));

        $this->dispatch('orderRemoved', $index);
    }

    public function checkoutOrder(OrderService $orderService) {
        $orders = $orderService->getOrders();

        $tenantId = tenant()->id;
        $firmName = tenant()->firmName;
        $company = Company::find($this->id);
        $userId = auth()->user()->id;
        $userName = auth()->user()->name;

        tenancy()->central(function () use ($orders, $tenantId, $userId, $company, $firmName, $userName) {
            foreach ($orders as $order)
                CosecOrder::create([
                    'tenant_id' => $tenantId,
                    'tenant_company_id' => $this->id,
                    'firm' => $firmName,
                    'company_name' => $company->company_name,
                    'company_no' => $company->company_registration_no,
                    'company_old_no' => $company->company_registration_no_old,
                    'tenant_user_id' => $userId,
                    'uuid' => $order['itemID'],
                    'form_type' => $order['itemType'],
                    'form_name' => $order['itemName'],
                    'user' => $userName,
                    'data' => json_encode($order['data']),
                    'requested_at' => $order['modifiedAt'],
                    'cost' => $order['cost'],
                    'status' => 0
                ]);
        });

        session()->forget('orders');

        LivewireAlert::withOptions([
            'position' => 'top-end',
            'icon' => 'success',
            'title' => 'Requested forms approval',
            'showConfirmButton' => false,
            'timer' => 1500
        ])->show();
    }
}
