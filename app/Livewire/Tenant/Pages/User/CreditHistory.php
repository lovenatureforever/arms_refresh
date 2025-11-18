<?php

namespace App\Livewire\Tenant\Pages\User;

use Livewire\Component;
use App\Models\Tenant\CosecOrder;
use App\Models\User;
use Livewire\WithPagination;

class CreditHistory extends Component
{
    use WithPagination;

    public $userId;

    public function mount($id)
    {
        $this->userId = $id;
    }

    public function render()
    {
        $user = User::findOrFail($this->userId);
        $cosecOrders = CosecOrder::where('tenant_user_id', $this->userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.tenant.pages.user.credit-history', [
            'user' => $user,
            'cosecOrders' => $cosecOrders
        ]);
    }
}