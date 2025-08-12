<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Locked;

class AdminCosecReportContent extends Component
{

    #[Locked]
    public $order;

    public function mount($order) {
        $this->order = $order;
    }

    public function render()
    {
        $targetRender = "";
        switch ($this->order->form_type) {
            case 1:
                $targetRender = 'livewire.tenant.components.cosec.appointment-director';
                break;

            case 2:
                $targetRender = 'livewire.tenant.components.cosec.resignation-director';
                break;

            case 3:
                $targetRender = 'livewire.tenant.components.cosec.transfer-share';
                break;

            case 4:
                $targetRender = 'livewire.tenant.components.cosec.increase-paidup';
                break;

            case 5:
                $data = json_decode($this->order->data, true);
                if ($data['changeOrNew'] == 'change') {
                    $targetRender = 'livewire.tenant.components.cosec.change-business-address';
                } else {
                    $targetRender = 'livewire.tenant.components.cosec.register-business-address';
                }
                break;

            case 6:
                $data = json_decode($this->order->data, true);
                if ($data['addOrClose'] == 'add') {
                    $targetRender = 'livewire.tenant.components.cosec.register-branch-address';
                } else {
                    $targetRender = 'livewire.tenant.components.cosec.close-branch-address';
                }
                break;

            case 7:
                $targetRender = 'livewire.tenant.components.cosec.register-branch-address';
                break;

            case 8:
                $targetRender = 'livewire.tenant.components.cosec.register-branch-address';
                break;

            case 9:
                $targetRender = 'livewire.tenant.components.cosec.register-branch-address';
                break;
        }

        return view($targetRender, [
            'order' => $this->order
        ]);
    }

}
