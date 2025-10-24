<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use Livewire\Component;
use App\Models\Tenant\CosecOrder;
use Livewire\Attributes\Locked;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class AdminCosecOrderEdit extends Component
{
    #[Locked]
    public $id;

    public $documentContent;
    public $customCreditCost;

    public function mount($id)
    {
        $this->id = $id;
        $order = CosecOrder::find($id);
        $this->documentContent = $order->document_content;
        $this->customCreditCost = $order->custom_credit_cost;
    }

    public function save()
    {
        $this->validate([
            'documentContent' => 'nullable|string',
            'customCreditCost' => 'nullable|integer|min:0',
        ]);

        $order = CosecOrder::find($this->id);
        $order->update([
            'document_content' => $this->documentContent,
            'custom_credit_cost' => $this->customCreditCost,
        ]);

        LivewireAlert::withOptions([
            "position" => "top-end",
            "icon" => "success",
            "title" => "Order updated successfully!",
            "showConfirmButton" => false,
            "timer" => 1500
        ])->show();
    }

    public function render()
    {
        $order = CosecOrder::find($this->id);
        return view('livewire.tenant.pages.cosec.admin-cosec-order-edit', [
            'order' => $order
        ]);
    }
}
