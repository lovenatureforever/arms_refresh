<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Tenant\CosecOrder;
use App\Models\Tenant\CosecTemplate;
use Livewire\Attributes\On;

class OrderCosec extends Component
{
    use WithPagination;

    #[Locked]
    public $id;

    // Filters
    public $filterStatus = '';
    public $filterTemplate = '';
    public $filterDateFrom = '';
    public $filterDateTo = '';
    public $searchQuery = '';

    public function mount($id)
    {
        $this->id = $id;
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterTemplate()
    {
        $this->resetPage();
    }

    public function updatingSearchQuery()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->filterStatus = '';
        $this->filterTemplate = '';
        $this->filterDateFrom = '';
        $this->filterDateTo = '';
        $this->searchQuery = '';
        $this->resetPage();
    }

    public function render() {
        $query = CosecOrder::where('tenant_company_id', $this->id)
            ->with('template')
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($this->filterStatus !== '') {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterTemplate) {
            $query->where('template_id', $this->filterTemplate);
        }

        if ($this->filterDateFrom) {
            $query->whereDate('created_at', '>=', $this->filterDateFrom);
        }

        if ($this->filterDateTo) {
            $query->whereDate('created_at', '<=', $this->filterDateTo);
        }

        if ($this->searchQuery) {
            $query->where(function($q) {
                $q->where('uuid', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('form_name', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('user', 'like', '%' . $this->searchQuery . '%');
            });
        }

        $orders = $query->paginate(15);
        $templates = CosecTemplate::where('is_active', true)->get();

        // Order statistics
        $stats = [
            'total' => CosecOrder::where('tenant_company_id', $this->id)->count(),
            'pending' => CosecOrder::where('tenant_company_id', $this->id)->where('status', 0)->count(),
            'approved' => CosecOrder::where('tenant_company_id', $this->id)->where('status', 1)->count(),
            'rejected' => CosecOrder::where('tenant_company_id', $this->id)->where('status', 2)->count(),
        ];

        return view('livewire.tenant.pages.cosec.order', [
            'orders' => $orders,
            'templates' => $templates,
            'stats' => $stats,
        ]);
    }

    public function printForm($orderId) {
        $order = CosecOrder::find($orderId);

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
                $data = json_decode($order->data, true);
                if (isset($data['changeOrNew']) && $data['changeOrNew'] == 'change') {
                    $targetRender = 'livewire.tenant.components.cosec.change-business-address';
                } else {
                    $targetRender = 'livewire.tenant.components.cosec.register-business-address';
                }
                break;

            case 6:
                $data = json_decode($order->data, true);
                if (isset($data['addOrClose']) && $data['addOrClose'] == 'add') {
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

            default:
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
