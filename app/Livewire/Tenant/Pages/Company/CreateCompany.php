<?php

namespace App\Livewire\Tenant\Pages\Company;

use App\Models\Tenant\CompanyAddressChange;
use App\Models\Tenant\CompanyBusinessChange;
use App\Models\Tenant\CompanyDetailChange;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Component;

use App\Livewire\Tenant\Pages\Dashboard;
use App\Models\Tenant\Company;
// use App\Models\Centeral\User;

class CreateCompany extends Component
{
    #[Validate('required')]
    public $name;

    #[Validate('required')]
    public $registrationNo;

    public $registrationNoOld;

    #[Validate('required')]
    public $currentIsFirstYear;

    #[Validate('required_if:currentIsFirstYear,0', message: 'Current year period type is required')]
    public $currentYearType;

    #[Validate('required_if:currentIsFirstYear,0', message: 'Current year report header display format is required')]
    public $currentReportHeaderFormat;

    #[Validate('required')]
    public $currentYear;

    #[Validate('required')]
    public $currentYearFrom;

    #[Validate('required')]
    public $currentYearTo;

    public $lastIsFirstYear;

    #[Validate('required_if:lastIsFirstYear,0', message: 'Last year period type is required')]
    public $lastYearType;

    #[Validate('required_if:lastIsFirstYear,0', message: 'Last year report header display format is required')]
    public $lastReportHeaderFormat;

    #[Validate('required_if:currenctIsFirstYear,0', message: 'Last financial year is required')]
    public $lastYear;

    #[Validate('required_if:currenctIsFirstYear,0', message: 'Last audit period from is required')]
    public $lastYearFrom;

    #[Validate('required_if:currenctIsFirstYear,0', message: 'Last audit period to is required')]
    public $lastYearTo;

    public $auditFee;


    public function render()
    {
        return view('livewire.tenant.pages.companies.create-company');
    }

    public function updatingCurrentIsFirstYear($val)
    {
        if ($val) {
            $this->currentYearType = 'first year';
            $this->currentReportHeaderFormat = 'date';

            $this->reset(['lastIsFirstYear', 'lastYearType', 'lastReportHeaderFormat']);
        } else {

            $this->reset(['currentYearType', 'currentReportHeaderFormat']);
        }
    }

    public function updatingLastIsFirstYear($val)
    {
        if ($val) {
            $this->lastYearType = 'first year';
            $this->lastReportHeaderFormat = 'date';
        } else {
            $this->reset(['lastYearType', 'lastReportHeaderFormat']);
        }
    }

    public function updatingCurrentYearType($val)
    {
        if ($val == 'full year') {
            $this->currentReportHeaderFormat = 'year';

            $this->reset(['lastIsFirstYear', 'lastYearType', 'lastReportHeaderFormat']);
        } else if ($val == 'partial year') {
            $this->currentReportHeaderFormat = 'date';

            $this->reset(['lastIsFirstYear', 'lastYearType', 'lastReportHeaderFormat']);
        }
    }

    public function updatingLastYearType($val)
    {
        if ($val == 'full year') {
            $this->lastReportHeaderFormat = 'year';

            // $this->reset(['lastAuditFirstYear', 'lastYearType', 'lastReportHeaderFormat']);
        } else if ($val == 'partial year') {
            $this->lastReportHeaderFormat = 'date';

            // $this->reset(['lastAuditFirstYear', 'lastYearType', 'lastReportHeaderFormat']);
        }
    }

    public function create()
    {

        DB::beginTransaction();
        try {
            $this->validate();

            $company = Company::create([
                'registration_no' => $this->registrationNo,
                'registration_no_old' => $this->registrationNoOld,
                'current_is_first_year' => $this->currentIsFirstYear,
                'current_year_type' => $this->currentYearType,
                'current_report_header_format' => $this->currentReportHeaderFormat,
                'current_year' => $this->currentYear,
                'current_year_from' => $this->currentYearFrom,
                'current_year_to' => $this->currentYearTo,
                'last_is_first_year' => $this->lastIsFirstYear,
                'last_year_type' => $this->lastYearType,
                'last_report_header_format' => $this->lastReportHeaderFormat,
                'last_year' => $this->lastYear,
                'last_year_from' => $this->lastYearFrom,
                'last_year_to' => $this->lastYearTo,
                'audit_fee' => $this->auditFee
            ]);

            CompanyDetailChange::create([
                'company_id' => $company->id,
                'change_nature' => 'initial',
                'name' => $this->name,
                // 'company_type' => '',
                'presentation_currency' => 'Ringgit Malaysia',
                'presentation_currency_code' => 'RM',
                'functional_currency' => 'Ringgit Malaysia',
                'functional_currency_code' => 'RM',
                'domicile' => 'Malaysia',
                'effective_date' => '2000-01-01',
            ]);

            CompanyBusinessChange::create([
                'company_id' => $company->id,
                'paragraph1' => '',
                'paragraph2' => '',
                'effective_date' => '2000-01-01',
                'remarks' => '',
            ]);

            CompanyAddressChange::create([
                'company_id' => $company->id,
                'change_nature' => 'initial',
                'country' => 'Malaysia',
                'address_line1' => '',
                'address_line2' => '',
                'address_line3' => '',
                'postcode' => '',
                'town' => '',
                'state' => '',
                'effective_date' => '2000-01-01',
                'remarks' => '',
            ]);

            DB::commit();

            $this->redirect(Dashboard::class);
        } catch (Exception $e) {
            info($e->getMessage());
            DB::rollBack();

            session()->flash('error', $e->getMessage());
        }
    }
}
