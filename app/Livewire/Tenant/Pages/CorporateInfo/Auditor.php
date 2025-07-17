<?php

namespace App\Livewire\Tenant\Pages\CorporateInfo;

use App\Models\Tenant\AuditFirmAddress;
use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyAuditorSetting;
use App\Models\Tenant\Auditor as AuditorModel;
use Exception;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Illuminate\Support\Facades\Log;


class Auditor extends Component
{
    use WithPagination;

    #[Locked]
    public $id;

    #[Locked]
    public $company;

    public $addresses;

    public $auditorSetting;

    public $auditFirmChanged;
    public $priorAuditFirm;
    public $priorReportDate;
    public $priorReportOpinion;

    public $isLetterheadRepeat;
    public $isDefaultLetterhead;
    public $isFirmAddressUppercase;
    public $blankHeaderSpacing;
    public $withBreakline;
    public $auditFirmDescription;

    public $isShowFirmName;
    public $isShowFirmTitle;
    public $isShowFirmLicense;
    public $isShowFirmAddress;
    public $isShowFirmContact;
    public $isShowFirmFax;
    public $isShowFirmEmail;

    public $firmAddress;

    public $selectedFirmAddressId;
    public $selectedAuditorLicenseId;

    public $auditorModel;


    public function mount($id)
    {
        $this->id = $id;
        $this->init();
    }

    private function init() {
        $this->company = Company::find($this->id);
        $this->addresses = AuditFirmAddress::all();
        $this->auditorSetting = CompanyAuditorSetting::where('company_id', $this->id)->first();

        $this->auditFirmChanged = $this->auditorSetting->audit_firm_changed ? '1' : '0';
        $this->priorAuditFirm = $this->auditorSetting->audit_firm_changed ? $this->auditorSetting->prior_audit_firm : '';
        $this->priorReportDate = $this->auditorSetting->audit_firm_changed ? $this->auditorSetting->prior_report_date?->format('Y-m-d') : null;
        $this->priorReportOpinion = $this->auditorSetting->audit_firm_changed ? $this->auditorSetting->prior_report_opinion : '';

        $this->isLetterheadRepeat = $this->auditorSetting->is_letterhead_repeat ? '1' : '0';
        $this->isDefaultLetterhead = $this->auditorSetting->is_default_letterhead ? '1' : '0';
        $this->isFirmAddressUppercase = $this->auditorSetting->is_firm_address_uppercase;
        $this->blankHeaderSpacing = $this->auditorSetting->blank_header_spacing;
        $this->withBreakline = $this->auditorSetting->with_breakline ? '1' : '0';
        $this->auditFirmDescription = $this->auditorSetting->audit_firm_description;
        $this->isShowFirmName = $this->auditorSetting->is_show_firm_name;
        $this->isShowFirmTitle = $this->auditorSetting->is_show_firm_title;
        $this->isShowFirmAddress = $this->auditorSetting->is_show_firm_address;
        $this->isShowFirmContact = $this->auditorSetting->is_show_firm_contact;
        $this->isShowFirmFax = $this->auditorSetting->is_show_firm_fax;
        $this->isShowFirmEmail = $this->auditorSetting->is_show_firm_email;
        $this->isShowFirmLicense = $this->auditorSetting->is_show_firm_license;
        $this->selectedFirmAddressId = $this->auditorSetting->selected_firm_address_id ?? $this->addresses->first()?->id;

        $this->firmName = $this->auditorSetting->firm_name;
        $this->firmNo = $this->auditorSetting->firm_no;
        $this->firmTitle = $this->auditorSetting->firm_title;
        $this->contactNo = $this->auditorSetting->firm_contact;
        $this->faxNo = $this->auditorSetting->firm_fax;
        $this->email = $this->auditorSetting->firm_email;

        $address = AuditFirmAddress::find($this->selectedFirmAddressId);
        $this->firmAddress = getFullAddress($address);

        $this->auditorModel = AuditorModel::with('user', 'licenses')->first();

        $this->selectedAuditorLicenseId = $this->auditorSetting->selected_auditor_license ?? $this->auditorModel->selected_license?->id;
    }

    public function render()
    {
        return view('livewire.tenant.pages.corporate-info.auditor');
    }

    public function save()
    {
        try {
            $this->validate([
                'priorAuditFirm' => 'required_if:auditFirmChanged,1',
                'priorReportDate' => 'required_if:auditFirmChanged,1',
                'priorReportOpinion' => 'required_if:auditFirmChanged,1',
                'blankHeaderSpacing' => 'required_if:isDefaultLetterhead,0',
                'selectedFirmAddressId' => 'required',
            ]);

            $this->auditorSetting->update([
                'audit_firm_changed' => $this->auditFirmChanged == '1',
                'prior_audit_firm' => $this->priorAuditFirm,
                'prior_report_date' => $this->priorReportDate ? \Carbon\Carbon::parse($this->priorReportDate) : null,
                'prior_report_opinion' => $this->priorReportOpinion,
                'is_letterhead_repeat' => $this->isLetterheadRepeat == '1',
                'is_default_letterhead' => $this->isDefaultLetterhead == '1',
                'is_firm_address_uppercase' => $this->isFirmAddressUppercase,
                'blank_header_spacing' => $this->blankHeaderSpacing,
                'with_breakline' => $this->withBreakline == '1',
                'audit_firm_description' => $this->auditFirmDescription,
                'is_show_firm_name' => $this->isShowFirmName,
                'is_show_firm_title' => $this->isShowFirmTitle,
                'is_show_firm_license' => $this->isShowFirmLicense,
                'is_show_firm_address' => $this->isShowFirmAddress,
                'is_show_firm_contact' => $this->isShowFirmContact,
                'is_show_firm_fax' => $this->isShowFirmFax,
                'is_show_firm_email' => $this->isShowFirmEmail,
                'selected_auditor_license' => $this->selectedAuditorLicenseId,
                'selected_firm_address_id' => $this->selectedFirmAddressId,
            ]);

            LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Auditor Setting Updated successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
        } catch (Exception $e) {
            info($e->getMessage());
            LivewireAlert::withOptions(["position" => "top-end", "icon" => "error", "title" => $e->getMessage(), "showConfirmButton" => false, "timer" => 1500])->show();
        }
    }

    public function resetForm()
    {
        $this->init();
    }

    public function updatedAuditFirmChanged($value)
    {
        if ($value == '0') {
            $this->priorAuditFirm = '';
            $this->priorReportDate = null;
            $this->priorReportOpinion = '';
        } else {
            $this->priorAuditFirm = $this->auditorSetting->prior_audit_firm;
            $this->priorReportDate = $this->auditorSetting->prior_report_date ? $this->auditorSetting->prior_report_date->format('Y-m-d') : null;
            $this->priorReportOpinion = $this->auditorSetting->prior_report_opinion;
        }
    }

    public function updatedIsDefaultLetterhead($value)
    {
        if ($value == '0') {
            $this->isFirmAddressUppercase = false;
        } else {
            $this->blankHeaderSpacing = '';
        }
    }
}
