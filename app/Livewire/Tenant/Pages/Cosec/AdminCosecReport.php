<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use App\Models\Tenant\CosecOrder;
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

        // Only update status - credits are already deducted when director places the order
        $order->update(['status' => CosecOrder::STATUS_APPROVED]);

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
        $order->update(['status' => CosecOrder::STATUS_REJECTED]);

        // Note: Credits are NOT refunded when order is denied (already deducted on order placement)

        LivewireAlert::withOptions([
            "position" => "top-end",
            "icon" => "warning",
            "title" => "Order denied!",
            "showConfirmButton" => false,
            "timer" => 1500
        ])->show();
        redirect()->route('admin.cosec.index');
    }

}
