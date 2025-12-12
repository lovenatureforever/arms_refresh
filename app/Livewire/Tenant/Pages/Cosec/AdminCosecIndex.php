<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use App\Models\Tenant\Company;
use App\Models\Tenant\CosecOrder;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class AdminCosecIndex extends Component
{


    public function render()
    {
        $user = auth()->user();

        $query = CosecOrder::with(['template', 'user'])->orderBy('created_at', 'desc');

        if ($user->isCosecAdmin()) {
            // Admin sees all orders
            $orders = $query->get();
        } elseif ($user->isCosecSubscriber()) {
            // Subscriber sees only orders for companies they created
            $companyIds = Company::where('created_by', $user->id)->pluck('id')->toArray();
            $orders = $query->whereIn('tenant_company_id', $companyIds)->get();
        } else {
            // Director or other - shouldn't reach here due to route protection
            $orders = collect();
        }

        return view('livewire.tenant.pages.cosec.admin-cosec-index', [
            'orders' => $orders
        ]);
    }

    public function approve($id) {
        $order = CosecOrder::find($id);

        // Only update status - credits already deducted when director placed the order
        $order->update(['status' => CosecOrder::STATUS_APPROVED]);

        // Note: Don't deduct credits here - they were already deducted when the order was placed
    }

    public function deny($id) {
        // Status 2 = Rejected, no credit deduction
        CosecOrder::find($id)->update(['status' => CosecOrder::STATUS_REJECTED]);
    }

    public function printWord($id)
    {
        return redirect()->route('cosec.print-word', $id);
    }

    public function printPdf($id)
    {
        return redirect()->route('cosec.print-pdf', $id);
    }
}
