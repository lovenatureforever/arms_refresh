<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use App\Models\Tenant\CosecOrder;
use Livewire\Component;

class AdminCosecReportContent extends Component
{
    public $order;

    public function mount($order)
    {
        $this->order = $order;
    }

    public function render()
    {
        return view('livewire.tenant.pages.cosec.admin-cosec-report-content');
    }
}
