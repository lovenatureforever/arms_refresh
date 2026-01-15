<?php

namespace App\Livewire\Tenant\Pages\v2\Company;

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

    public $companyGroup;

    #[Validate('required')]
    public $registrationNo;

    public $registrationNoOld;

    public $taxFileNo;

    public $employerFileNo;

    public $sstRegistrationNo;

    public $yearEnd;


    public function render()
    {
        return view('livewire.tenant.pages.v2.company.create-company');
    }

    public function create()
    {

        DB::beginTransaction();
        try {
            $this->validate();

            $company = Company::create([
                'registration_no' => $this->registrationNo,
                'registration_no_old' => $this->registrationNoOld,
                'company_group' => $this->companyGroup,
                'tax_file_no' => $this->taxFileNo,
                'employer_file_no' => $this->employerFileNo,
                'sst_registration_no' => $this->sstRegistrationNo,
                'year_end' => $this->yearEnd,
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
                'with_breakline' => tenant()->withBreakline ?? false, // Audit license no. position
                'audit_firm_description' => tenant()->auditFirmDescription,
                'is_default_letterhead' => tenant()->isDefaultLetterhead ?? true,
                'is_letterhead_repeat' => tenant()->isLetterheadRepeat ?? false,
                'blank_header_spacing' => tenant()->blankHeaderSpacing,
                'is_show_firm_name' => tenant()->isShowFirmName ?? true,
                'is_show_firm_title' => tenant()->isShowFirmTitle ?? true,
                'is_show_firm_address' => tenant()->isShowFirmAddress ?? true,
                'is_show_firm_contact' => tenant()->isShowFirmContact ?? true,
                'is_show_firm_email' => tenant()->isShowFirmEmail ?? true,
                'is_show_firm_fax' => tenant()->isShowFirmFax ?? true,
                'is_firm_address_uppercase' => tenant()->isFirmAddressUppercase ?? false,
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
