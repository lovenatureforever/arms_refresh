<?php

namespace App\Livewire\Tenant\Pages\Tax;

use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyTaxSetting;
use App\Models\User;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class TaxSettings extends Component
{
    public $selectedCompany = null;
    public $companies;

    // Settings fields
    public $enable_cp204_reminders = true;
    public $cp204_submission_days_before = 30;
    public $enable_cp204a_reminders = true;
    public $enable_monthly_installment_reminders = true;
    public $installment_reminder_days_before = 7;
    public $primary_tax_contact_user_id = null;
    public $primary_tax_contact_email = '';
    public $cc_company_directors = true;
    public $cc_auditors = false;
    public $first_reminder_days_before = 30;
    public $second_reminder_days_before = 14;
    public $final_reminder_days_before = 3;

    public $users = [];

    protected $rules = [
        'enable_cp204_reminders' => 'boolean',
        'cp204_submission_days_before' => 'integer|min:1',
        'enable_cp204a_reminders' => 'boolean',
        'enable_monthly_installment_reminders' => 'boolean',
        'installment_reminder_days_before' => 'integer|min:1',
        'primary_tax_contact_user_id' => 'nullable|exists:users,id',
        'primary_tax_contact_email' => 'nullable|email',
        'cc_company_directors' => 'boolean',
        'cc_auditors' => 'boolean',
        'first_reminder_days_before' => 'integer|min:1',
        'second_reminder_days_before' => 'integer|min:1',
        'final_reminder_days_before' => 'integer|min:1',
    ];

    public function mount()
    {
        $this->companies = Company::all()->sortBy('name');
        $this->selectedCompany = $this->companies->first()?->id;
        $this->users = User::orderBy('name')->get();

        $this->loadSettings();
    }

    public function render()
    {
        return view('livewire.tenant.pages.tax.tax-settings');
    }

    public function updatedSelectedCompany()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        if (!$this->selectedCompany) {
            return;
        }

        $company = Company::find($this->selectedCompany);
        $settings = $company->taxSettings;

        if ($settings) {
            $this->enable_cp204_reminders = $settings->enable_cp204_reminders ?? true;
            $this->cp204_submission_days_before = $settings->cp204_submission_days_before ?? 30;
            $this->enable_cp204a_reminders = $settings->enable_cp204a_reminders ?? true;
            $this->enable_monthly_installment_reminders = $settings->enable_monthly_installment_reminders ?? true;
            $this->installment_reminder_days_before = $settings->installment_reminder_days_before ?? 7;
            $this->primary_tax_contact_user_id = $settings->primary_tax_contact_user_id;
            $this->primary_tax_contact_email = $settings->primary_tax_contact_email ?? '';
            $this->cc_company_directors = $settings->cc_company_directors ?? true;
            $this->cc_auditors = $settings->cc_auditors ?? false;
            $this->first_reminder_days_before = $settings->first_reminder_days_before ?? 30;
            $this->second_reminder_days_before = $settings->second_reminder_days_before ?? 14;
            $this->final_reminder_days_before = $settings->final_reminder_days_before ?? 3;
        } else {
            // Reset to defaults
            $this->resetToDefaults();
        }
    }

    public function saveSettings()
    {
        $this->validate();

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

        $company->taxSettings()->updateOrCreate(
            ['company_id' => $this->selectedCompany],
            [
                'enable_cp204_reminders' => $this->enable_cp204_reminders,
                'cp204_submission_days_before' => $this->cp204_submission_days_before,
                'enable_cp204a_reminders' => $this->enable_cp204a_reminders,
                'enable_monthly_installment_reminders' => $this->enable_monthly_installment_reminders,
                'installment_reminder_days_before' => $this->installment_reminder_days_before,
                'primary_tax_contact_user_id' => $this->primary_tax_contact_user_id,
                'primary_tax_contact_email' => $this->primary_tax_contact_email,
                'cc_company_directors' => $this->cc_company_directors,
                'cc_auditors' => $this->cc_auditors,
                'first_reminder_days_before' => $this->first_reminder_days_before,
                'second_reminder_days_before' => $this->second_reminder_days_before,
                'final_reminder_days_before' => $this->final_reminder_days_before,
            ]
        );

        LivewireAlert::withOptions([
            "position" => "top-end",
            "icon" => "success",
            "title" => "Tax settings saved successfully",
            "showConfirmButton" => false,
            "timer" => 2000
        ])->show();
    }

    private function resetToDefaults()
    {
        $this->enable_cp204_reminders = true;
        $this->cp204_submission_days_before = 30;
        $this->enable_cp204a_reminders = true;
        $this->enable_monthly_installment_reminders = true;
        $this->installment_reminder_days_before = 7;
        $this->primary_tax_contact_user_id = null;
        $this->primary_tax_contact_email = '';
        $this->cc_company_directors = true;
        $this->cc_auditors = false;
        $this->first_reminder_days_before = 30;
        $this->second_reminder_days_before = 14;
        $this->final_reminder_days_before = 3;
    }
}
