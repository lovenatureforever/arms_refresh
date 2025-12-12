<?php

namespace App\Livewire\Tenant\Pages\Tax;

use App\Models\Tenant\Company;
use App\Models\Tenant\TaxCp204Estimate;
use App\Services\TaxReminderService;
use Illuminate\Pagination\LengthAwarePaginator;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Cp204Index extends Component
{
    use WithPagination;

    public $selectedCompany = null;
    public $companies;
    public $showCreateModal = false;

    // Form fields
    public $estimate_type = 'cp204_initial';
    public $revision_month = null;
    public $estimated_tax_amount = 0;
    public $monthly_installment = 0;
    public $remarks = '';

    protected $rules = [
        'selectedCompany' => 'required|exists:companies,id',
        'estimate_type' => 'required|in:cp204_initial,cp204a_revision',
        'revision_month' => 'nullable|integer|in:6,9,11',
        'estimated_tax_amount' => 'required|numeric|min:0',
        'monthly_installment' => 'required|numeric|min:0',
        'remarks' => 'nullable|string',
    ];

    public function mount()
    {
        $this->companies = Company::all()->sortBy('name');
        $this->selectedCompany = $this->companies->first()?->id;
    }

    public function render()
    {
        // Use a paginator instance even when no company is selected so the view
        // can safely call pagination helpers.
        $estimates = new LengthAwarePaginator([], 0, 10);
        $currentCompany = null;

        if ($this->selectedCompany) {
            $currentCompany = Company::find($this->selectedCompany);
            $estimates = TaxCp204Estimate::where('company_id', $this->selectedCompany)
                ->with(['submittedBy', 'previousEstimate'])
                ->orderBy('basis_period_year', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('livewire.tenant.pages.tax.cp204-index', [
            'estimates' => $estimates,
            'currentCompany' => $currentCompany,
        ]);
    }

    public function calculateInstallment()
    {
        if ($this->estimated_tax_amount) {
            $this->monthly_installment = round($this->estimated_tax_amount / 12, 2);
        }
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function createEstimate()
    {
        $this->validate();

        $company = Company::find($this->selectedCompany);

        $estimate = TaxCp204Estimate::create([
            'company_id' => $this->selectedCompany,
            'basis_period_year' => $company->current_year,
            'basis_period_from' => $company->current_year_from,
            'basis_period_to' => $company->current_year_to,
            'estimate_type' => $this->estimate_type,
            'revision_month' => $this->revision_month,
            'estimated_tax_amount' => $this->estimated_tax_amount,
            'monthly_installment' => $this->monthly_installment,
            'remarks' => $this->remarks,
            'submission_status' => 'draft',
            'submitted_by' => auth()->id(),
        ]);

        // Check 85% compliance if not first year
        if ($this->estimate_type === 'cp204_initial') {
            $previousEstimate = TaxCp204Estimate::where('company_id', $this->selectedCompany)
                ->where('basis_period_year', $company->current_year - 1)
                ->latest('created_at')
                ->first();

            if ($previousEstimate) {
                $estimate->update(['previous_estimate_id' => $previousEstimate->id]);
                $estimate->check85PercentCompliance();
            }
        }

        // Link to reminders
        $reminderService = app(TaxReminderService::class);
        $reminderService->linkEstimateToReminders($estimate);

        LivewireAlert::withOptions([
            "position" => "top-end",
            "icon" => "success",
            "title" => "CP204 estimate created successfully",
            "showConfirmButton" => false,
            "timer" => 2000
        ])->show();

        $this->closeCreateModal();
        $this->resetPage();
    }

    public function generateReminders()
    {
        if (!$this->selectedCompany) {
            LivewireAlert::withOptions([
                "position" => "top-end",
                "icon" => "error",
                "title" => "Please select a company",
                "showConfirmButton" => false,
                "timer" => 1500
            ])->show();
            return;
        }

        $company = Company::find($this->selectedCompany);
        $reminderService = app(TaxReminderService::class);

        try {
            $reminders = $reminderService->generateAllRemindersForCompany($company);

            LivewireAlert::withOptions([
                "position" => "top-end",
                "icon" => "success",
                "title" => "Tax reminders generated successfully",
                "showConfirmButton" => false,
                "timer" => 2000
            ])->show();
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

    private function resetForm()
    {
        $this->estimate_type = 'cp204_initial';
        $this->revision_month = null;
        $this->estimated_tax_amount = 0;
        $this->monthly_installment = 0;
        $this->remarks = '';
        $this->resetErrorBag();
    }
}
