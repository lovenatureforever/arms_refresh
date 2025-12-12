<?php

namespace App\Livewire\Tenant\Pages;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tenant\CreditTransaction;

class UserCreditHistory extends Component
{
    use WithPagination;

    public $filterType = '';
    public $filterDateFrom = '';
    public $filterDateTo = '';

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
        $userId = auth()->id();

        $query = CreditTransaction::where('user_id', $userId)
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
        $totalCredits = CreditTransaction::where('user_id', $userId)
            ->where('type', 'credit')
            ->sum('amount');

        $totalDebits = CreditTransaction::where('user_id', $userId)
            ->where('type', 'debit')
            ->sum('amount');

        return view('livewire.tenant.pages.user-credit-history', [
            'transactions' => $transactions,
            'totalCredits' => $totalCredits,
            'totalDebits' => $totalDebits,
            'currentBalance' => auth()->user()->credit ?? 0,
        ]);
    }
}
