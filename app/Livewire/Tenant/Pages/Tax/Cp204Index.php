<?php

namespace App\Livewire\Tenant\Pages\Tax;

use App\Models\Tenant\Company;
use App\Services\TaxReminderService;
use Illuminate\Pagination\LengthAwarePaginator;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Cp204Index extends Component
{
    use WithPagination;

    public $selectedYearEnd = null;
    public $selectedFormType = 'cp204';
    public $selectedCompanies = [];
    public $yearEndOptions = [];
    public $searchName = '';
    public $searchGroup = '';
    public $searchRegNo = '';
    public $showConfirmModal = false;
    public $confirmationMessage = '';

    public function mount()
    {
        // Get unique year_end values from companies
        $this->yearEndOptions = Company::whereNotNull('year_end')
            ->distinct()
            ->pluck('year_end')
            ->sort()
            ->values()
            ->toArray();

        if (count($this->yearEndOptions) > 0) {
            $this->selectedYearEnd = $this->yearEndOptions[0];
        }
    }

    public function updatedSearchName()
    {
        $this->resetPage();
    }

    public function updatedSearchGroup()
    {
        $this->resetPage();
    }

    public function updatedSearchRegNo()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Company::query()
            ->with(['detailChanges' => function ($q) {
                $q->orderBy('effective_date', 'desc');
            }])
            ->orderBy('id');

        if ($this->selectedYearEnd) {
            $query->where('year_end', $this->selectedYearEnd);
        }

        if ($this->searchName) {
            $query->whereHas('detailChanges', function ($q) {
                $q->where('name', 'like', '%' . $this->searchName . '%');
            });
        }

        if ($this->searchGroup) {
            $query->where('company_group', 'like', '%' . $this->searchGroup . '%');
        }

        if ($this->searchRegNo) {
            $query->where('registration_no', 'like', '%' . $this->searchRegNo . '%');
        }

        $companies = $query->paginate(10);

        return view('livewire.tenant.pages.tax.cp204-index', [
            'companies' => $companies,
        ]);
    }

    public function toggleSelectAll()
    {
        // Get IDs of companies on current page
        $query = Company::query()
            ->with(['detailChanges' => function ($q) {
                $q->orderBy('effective_date', 'desc');
            }])
            ->orderBy('id');

        if ($this->selectedYearEnd) {
            $query->where('year_end', $this->selectedYearEnd);
        }

        if ($this->searchName) {
            $query->whereHas('detailChanges', function ($q) {
                $q->where('name', 'like', '%' . $this->searchName . '%');
            });
        }

        if ($this->searchGroup) {
            $query->where('company_group', 'like', '%' . $this->searchGroup . '%');
        }

        if ($this->searchRegNo) {
            $query->where('registration_no', 'like', '%' . $this->searchRegNo . '%');
        }

        $currentPageCompanies = $query->paginate(10);
        $currentPageIds = $currentPageCompanies->pluck('id')->toArray();

        // If all current page companies are selected, deselect them
        // Otherwise, select all on current page
        if (count(array_intersect($this->selectedCompanies, $currentPageIds)) === count($currentPageIds)) {
            $this->selectedCompanies = array_diff($this->selectedCompanies, $currentPageIds);
        } else {
            $this->selectedCompanies = array_unique(array_merge($this->selectedCompanies, $currentPageIds));
        }
    }

    public function confirmGeneration()
    {
        if (empty($this->selectedCompanies)) {
            LivewireAlert::withOptions([
                "position" => "top-end",
                "icon" => "error",
                "title" => "Please select at least one company",
                "showConfirmButton" => false,
                "timer" => 1500
            ])->show();
            return;
        }

        $companyCount = count($this->selectedCompanies);

        // Calculate reminders based on form type
        $remindersPerCompany = $this->selectedFormType === 'cp204' ? 2 : 6;
        $totalReminders = $companyCount * $remindersPerCompany;

        $formTypeLabel = $this->selectedFormType === 'cp204' ? 'CP204' : 'CP204A';

        $this->confirmationMessage = "Generate {$totalReminders} {$formTypeLabel} reminders for {$companyCount} " .
                                     ($companyCount === 1 ? 'company' : 'companies') . "?";
        $this->showConfirmModal = true;
    }

    public function cancelGeneration()
    {
        $this->showConfirmModal = false;
    }

    public function generateReminders()
    {
        // Close modal first
        $this->showConfirmModal = false;

        if (empty($this->selectedCompanies)) {
            LivewireAlert::withOptions([
                "position" => "top-end",
                "icon" => "error",
                "title" => "Please select at least one company",
                "showConfirmButton" => false,
                "timer" => 1500
            ])->show();
            return;
        }

        $reminderService = app(TaxReminderService::class);
        $successCount = 0;

        try {
            foreach ($this->selectedCompanies as $companyId) {
                $company = Company::find($companyId);
                if ($company) {
                    // Pass the selected form type to the service
                    $reminderService->generateRemindersByFormType($company, $this->selectedFormType);
                    $successCount++;
                }
            }

            $formTypeLabel = $this->selectedFormType === 'cp204' ? 'CP204' : 'CP204A';

            LivewireAlert::withOptions([
                "position" => "top-end",
                "icon" => "success",
                "title" => "{$formTypeLabel} reminders generated for {$successCount} companies",
                "showConfirmButton" => false,
                "timer" => 2000
            ])->show();

            $this->selectedCompanies = [];
        } catch (\Exception $e) {
            LivewireAlert::withOptions([
                "position" => "top-end",
                "icon" => "error",
                "title" => "Failed to generate reminders: " . $e->getMessage(),
                "showConfirmButton" => false,
                "timer" => 3000
            ])->show();
        }
    }
}
