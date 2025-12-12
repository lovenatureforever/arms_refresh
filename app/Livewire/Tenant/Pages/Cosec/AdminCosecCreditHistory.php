<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Tenant\CreditTransaction;

class AdminCosecCreditHistory extends Component
{
    use WithPagination;

    public $userId;
    public $user;
    public $filterType = '';
    public $filterDateFrom = '';
    public $filterDateTo = '';

    public function mount($userId)
    {
        $this->userId = $userId;
        $this->user = User::findOrFail($userId);
    }

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function updatingFilterDateFrom()
    {
        $this->resetPage();
    }

    public function updatingFilterDateTo()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->filterType = '';
        $this->filterDateFrom = '';
        $this->filterDateTo = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = CreditTransaction::where('user_id', $this->userId)
            ->orderBy('created_at', 'desc');

        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        if ($this->filterDateFrom) {
            $query->whereDate('created_at', '>=', $this->filterDateFrom);
        }

        if ($this->filterDateTo) {
            $query->whereDate('created_at', '<=', $this->filterDateTo);
        }

        $transactions = $query->paginate(20);

        // Calculate totals
        $totalCredits = CreditTransaction::where('user_id', $this->userId)
            ->where('type', 'credit')
            ->sum('amount');

        $totalDebits = CreditTransaction::where('user_id', $this->userId)
            ->where('type', 'debit')
            ->sum('amount');

        return view('livewire.tenant.pages.cosec.admin-cosec-credit-history', [
            'transactions' => $transactions,
            'totalCredits' => $totalCredits,
            'totalDebits' => $totalDebits,
        ]);
    }
}
