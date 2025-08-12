<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use Livewire\Component;
use Livewire\Attributes\Locked;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Tenant\CosecOrder;
use Livewire\Attributes\On;

class OrderCosec extends Component
{

    #[Locked]
    public $id;

    public function mount($id)
    {
        $this->id = $id;
    }

    public function render() {
        $orders = [];
        tenancy()->central(function () use (&$orders) {
            $orders = CosecOrder::where('tenant_company_id', $this->id)->get();
        });

        return view('livewire.tenant.pages.cosec.order', ['orders' => $orders]);
    }

    public function printForm($orderId) {
        tenancy()->central(function () use ($orderId, &$order) {
            $order = CosecOrder::find($orderId);
        });

        switch ($order->form_type) {
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

        $pdf = Pdf::loadView($targetRender, ['order' => $order])
            ->setOption('dpi', 96)
            ->setOption('defaultFontSize', '12px')
            ->setOption('defaultFont', 'sans-serif')
            ->setOption('defaultPaperSize', 'a4');

        // Return the PDF as a downloadable file
        return response()->streamDownload(
            fn () => print($pdf->output()),
            'cosec-order-' . $this->id . '.pdf'
        );
    }
}
