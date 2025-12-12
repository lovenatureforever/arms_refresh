<?php

namespace App\Livewire\Tenant\Pages\Tax;

use App\Models\Tenant\Company;
use App\Models\Tenant\TaxReminderLog;
use Livewire\Component;
use Livewire\WithPagination;

class ReminderLogs extends Component
{
    use WithPagination;

    public $selectedCompany = null;
    public $companies;
    public $logTypeFilter = 'all';
    public $successFilter = 'all';
    public $dateFrom = null;
    public $dateTo = null;

    public function mount()
    {
        $this->companies = Company::all()->sortBy('name');
        $this->selectedCompany = $this->companies->first()?->id;
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function render()
    {
        $logs = collect();
        $stats = [
            'total' => 0,
            'email_sent' => 0,
            'email_failed' => 0,
            'whatsapp_sent' => 0,
            'whatsapp_failed' => 0,
        ];

        if ($this->selectedCompany) {
            $query = TaxReminderLog::whereHas('reminder', function ($q) {
                $q->where('company_id', $this->selectedCompany);
            })
                ->with(['reminder.company', 'recipient']);

            if ($this->logTypeFilter !== 'all') {
                $query->where('log_type', $this->logTypeFilter);
            }

            if ($this->successFilter !== 'all') {
                $query->where('success', $this->successFilter === 'success');
            }

            if ($this->dateFrom) {
                $query->whereDate('created_at', '>=', $this->dateFrom);
            }

            if ($this->dateTo) {
                $query->whereDate('created_at', '<=', $this->dateTo);
            }

            $logs = $query->orderBy('created_at', 'desc')
                ->paginate(20);

            // Calculate stats
            $baseQuery = TaxReminderLog::whereHas('reminder', function ($q) {
                $q->where('company_id', $this->selectedCompany);
            });

            if ($this->dateFrom) {
                $baseQuery->whereDate('created_at', '>=', $this->dateFrom);
            }

            if ($this->dateTo) {
                $baseQuery->whereDate('created_at', '<=', $this->dateTo);
            }

            $stats = [
                'total' => (clone $baseQuery)->count(),
                'email_sent' => (clone $baseQuery)->where('log_type', 'email_sent')->count(),
                'email_failed' => (clone $baseQuery)->where('log_type', 'email_failed')->count(),
                'whatsapp_sent' => (clone $baseQuery)->where('log_type', 'whatsapp_sent')->count(),
                'whatsapp_failed' => (clone $baseQuery)->where('log_type', 'whatsapp_failed')->count(),
            ];
        }

        return view('livewire.tenant.pages.tax.reminder-logs', [
            'logs' => $logs,
            'stats' => $stats,
        ]);
    }

    public function updatedLogTypeFilter()
    {
        $this->resetPage();
    }

    public function updatedSuccessFilter()
    {
        $this->resetPage();
    }

    public function updatedSelectedCompany()
    {
        $this->resetPage();
    }

    public function updatedDateFrom()
    {
        $this->resetPage();
    }

    public function updatedDateTo()
    {
        $this->resetPage();
    }
}
