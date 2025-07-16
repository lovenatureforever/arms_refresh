<?php

namespace App\Livewire\Tenant\Pages\Company;

use App\Models\Tenant\CompanyAddressChange;
use App\Models\Tenant\CompanyAuditorSetting;
use App\Models\Tenant\CompanyBizAddressChange;
use App\Models\Tenant\CompanyBusinessChange;
use App\Models\Tenant\CompanyDetailChange;
use App\Models\Tenant\CompanyReportSetting;
use App\Models\Tenant\CompanyShareCapitalChange;
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

            CompanyBizAddressChange::create([
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

            CompanyShareCapitalChange::create([
                'company_id' => $company->id,
                'share_type' => 'Ordinary shares',
                'allotment_type' => 'Cash allotment',
                'issuance_term' => 'Cash',
                'issuance_purpose' => 'Working Capital',
                'fully_paid_shares' => 0,
                'fully_paid_amount' => 0,
                'partially_paid_shares' => 0,
                'partially_paid_amount' => 0,
                'effective_date' => '2000-01-01',
                'remarks' => '',
            ]);
            CompanyShareCapitalChange::create([
                'company_id' => $company->id,
                'share_type' => 'Preference shares',
                'allotment_type' => 'Cash allotment',
                'issuance_term' => 'Cash',
                'issuance_purpose' => 'Working Capital',
                'fully_paid_shares' => 0,
                'fully_paid_amount' => 0,
                'partially_paid_shares' => 0,
                'partially_paid_amount' => 0,
                'effective_date' => '2000-01-01',
                'remarks' => '',
            ]);

            // Auditor settings
            CompanyAuditorSetting::create([
                'company_id' => $company->id,
                'auditor_id' => null,
                'audit_firm_changed' => false,
                'prior_audit_firm' => null,
                'prior_report_date' => null,
                'prior_report_opinion' => null,
                'with_breakline' => tenant()->withBreakline, // Audit license no. position
                'audit_firm_description' => tenant()->auditFirmDescription,
                'is_default_letterhead' => tenant()->isDefaultLetterhead,
                'is_letterhead_repeat' => tenant()->isLetterheadRepeat,
                'blank_header_spacing' => tenant()->blankHeaderSpacing,
                'is_show_firm_name' => tenant()->isShowFirmName,
                'is_show_firm_title' => tenant()->isShowFirmTitle,
                'is_show_firm_address' => tenant()->isShowFirmAddress,
                'is_show_firm_contact' => tenant()->isShowFirmContact,
                'is_show_firm_email' => tenant()->isShowFirmEmail,
                'is_show_firm_fax' => tenant()->isShowFirmFax,
                'is_firm_address_uppercase' => tenant()->isFirmAddressUppercase,
                'selected_firm_address_id' => tenant()->selectedAddressId,
                'selected_auditor_license' => null,
            ]);

            // Report settings
            CompanyReportSetting::create([
                'company_id' => $company->id,
                'officer_name' => '',
                'officer_id_type' => '',
                'officer_id' => '',
                'officer_mia_no' => '',
                'cover_sign_position' => '',
                'cover_sign_name' => '',
                'cover_signature_position' => '',
                'cover_sign_secretary_no' => '',
                'report_date' => null,
                'director_report_location' => '',
                'statement_date' => null,
                'statement_location' => '',
                'statement_as_report_date' => false,
                'statutory_date' => null,
                'statutory_location' => '',
                'statutory_as_report_date' => false,
                'auditor_report_date' => null,
                'auditor_report_location' => '',
                'auditor_report_as_report_date' => false,
                'circulation_date' => null,
                'declaration_country' => 'Malaysia',
                'foreign_act' => '',
                'declaration_commissioner' => 'Commissioner for Oaths',
                'auditor_remuneration' => 0.00,
                'is_declaration_officer' => false,
                'is_declaration_mia' => false,
                'selected_director' => null,
                'selected_secretary' => null,
                'is_approved_application' => false,
                'is_exempt' => false,
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
