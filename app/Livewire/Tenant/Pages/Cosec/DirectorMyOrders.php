<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use Livewire\Component;
use App\Models\Tenant\CosecOrder;
use App\Models\Tenant\CompanyDirector;

class DirectorMyOrders extends Component
{
    public $companyId;
    public $director;

    public function mount()
    {
        $user = auth()->user();

        // Find director linked to current user
        $this->director = CompanyDirector::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if ($this->director) {
            $this->companyId = $this->director->company_id;
        }
    }

    public function downloadPdf($orderId)
    {
        $order = CosecOrder::find($orderId);

        // Only allow download for approved orders in director's company
        if ($order && $order->tenant_company_id == $this->companyId && $order->status == CosecOrder::STATUS_APPROVED) {
            return redirect()->route('cosec.print-pdf', $orderId);
        }
    }

    public function render()
    {
        $orders = collect();

        if ($this->companyId) {
            $orders = CosecOrder::where('tenant_company_id', $this->companyId)
                ->with(['template'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('livewire.tenant.pages.cosec.director-my-orders', [
            'orders' => $orders,
        ]);
    }
}
