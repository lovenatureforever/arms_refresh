<?php

namespace App\Livewire\Tenant\Pages\Tax;

use App\Models\Tenant\Company;
use App\Models\Tenant\TaxReminder;
use App\Services\TaxReminderService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class RemindersIndex extends Component
{
    use WithPagination;

    public $selectedCompany = null;
    public $companies;
    public $statusFilter = 'all';
    public $categoryFilter = 'all';

    public function mount()
    {
        $this->companies = Company::all()->sortBy('name');
        $this->selectedCompany = $this->companies->first()?->id;
    }

    public function render()
    {
        $reminders = collect();
        $stats = [
            'total' => 0,
            'pending' => 0,
            'sent' => 0,
            'overdue' => 0,
            'completed' => 0,
        ];

        if ($this->selectedCompany) {
            $query = TaxReminder::where('company_id', $this->selectedCompany)
                ->with(['company', 'taxEstimate', 'primaryRecipient']);

            if ($this->statusFilter !== 'all') {
                $query->where('status', $this->statusFilter);
            }

            if ($this->categoryFilter !== 'all') {
                $query->where('reminder_category', $this->categoryFilter);
            }

            $reminders = $query->orderBy('action_due_date', 'asc')
                ->paginate(15);

            // Calculate stats
            $stats = [
                'total' => TaxReminder::where('company_id', $this->selectedCompany)->count(),
                'pending' => TaxReminder::where('company_id', $this->selectedCompany)->where('status', 'pending')->count(),
                'sent' => TaxReminder::where('company_id', $this->selectedCompany)->where('status', 'sent')->count(),
                'overdue' => TaxReminder::where('company_id', $this->selectedCompany)->where('status', 'overdue')->count(),
                'completed' => TaxReminder::where('company_id', $this->selectedCompany)->where('status', 'completed')->count(),
            ];
        }

        return view('livewire.tenant.pages.tax.reminders-index', [
            'reminders' => $reminders,
            'stats' => $stats,
        ]);
    }

    public function acknowledgeReminder($reminderId)
    {
        $reminder = TaxReminder::find($reminderId);

        if (!$reminder) {
            LivewireAlert::withOptions([
                "position" => "top-end",
                "icon" => "error",
                "title" => "Reminder not found",
                "showConfirmButton" => false,
                "timer" => 1500
            ])->show();
            return;
        }

        $reminder->update([
            'status' => 'acknowledged',
            'acknowledged_at' => now(),
            'acknowledged_by' => auth()->id(),
        ]);

        $reminder->logs()->create([
            'log_type' => 'acknowledged',
            'user_action' => 'acknowledged',
            'action_at' => now(),
            'action_by' => auth()->id(),
            'created_at' => now(),
        ]);

        LivewireAlert::withOptions([
            "position" => "top-end",
            "icon" => "success",
            "title" => "Reminder acknowledged",
            "showConfirmButton" => false,
            "timer" => 1500
        ])->show();

        $this->resetPage();
    }

    public function completeReminder($reminderId)
    {
        $reminder = TaxReminder::find($reminderId);

        if (!$reminder) {
            LivewireAlert::withOptions([
                "position" => "top-end",
                "icon" => "error",
                "title" => "Reminder not found",
                "showConfirmButton" => false,
                "timer" => 1500
            ])->show();
            return;
        }

        $reminderService = app(TaxReminderService::class);
        $reminderService->markReminderCompleted($reminder, auth()->id());

        LivewireAlert::withOptions([
            "position" => "top-end",
            "icon" => "success",
            "title" => "Reminder marked as completed",
            "showConfirmButton" => false,
            "timer" => 1500
        ])->show();

        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatedSelectedCompany()
    {
        $this->resetPage();
    }
}
