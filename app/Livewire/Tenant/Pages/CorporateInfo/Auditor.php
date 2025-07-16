<?php

namespace App\Livewire\Tenant\Pages\CorporateInfo;

use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyAuditorSetting;
use App\Models\Tenant\AuditFirmAddress;
use App\Models\Tenant\AuditFirmSetting;
use App\Models\Tenant\AuditorLicense;
use App\Models\Tenant\Auditor as AuditorModel;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Auditor extends Component
{

    #[Locked]
    public $id;

    public $auditSetting;

    public $auditor;

    public $company;

    public $auditFirmChanged;

    #[Validate('required_if:auditFirmChanged,1')]
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
        $this->company = Company::with(relations: ['auditorSetting'])->find($this->id);

        $this->auditSetting = $this->company->auditorSetting;

        $this->auditFirmChanged = $this->auditSetting->audit_firm_changed ? '1' : '0';
        $this->priorAuditFirm = $this->auditSetting->audit_firm_changed ? $this->auditSetting->prior_audit_firm : '';
        $this->priorReportDate = $this->auditSetting->audit_firm_changed ? $this->auditSetting->prior_report_date?->format('Y-m-d') : null;
        $this->priorReportOpinion = $this->auditSetting->audit_firm_changed ? $this->auditSetting->prior_report_opinion : '';

        $this->isLetterheadRepeat = $this->auditSetting->is_letterhead_repeat ? '1' : '0';
        $this->isDefaultLetterhead = $this->auditSetting->is_default_letterhead ? '1' : '0';
        $this->isFirmAddressUppercase = $this->auditSetting->is_firm_address_uppercase;
        $this->blankHeaderSpacing = $this->auditSetting->blank_header_spacing;
        $this->withBreakline = $this->auditSetting->with_breakline ? '1' : '0';
        $this->auditFirmDescription = $this->auditSetting->audit_firm_description;
        $this->isShowFirmName = $this->auditSetting->is_show_firm_name;
        $this->isShowFirmTitle = $this->auditSetting->is_show_firm_title;
        $this->isShowFirmAddress = $this->auditSetting->is_show_firm_address;
        $this->isShowFirmContact = $this->auditSetting->is_show_firm_contact;
        $this->isShowFirmFax = $this->auditSetting->is_show_firm_fax;
        $this->isShowFirmEmail = $this->auditSetting->is_show_firm_email;
        $this->isShowFirmLicense = $this->auditSetting->is_show_firm_license;
        $this->selectedFirmAddressId = $this->auditSetting->selected_firm_address_id;

        $this->firmName = $this->auditSetting->firm_name;
        $this->firmNo = $this->auditSetting->firm_no;
        $this->firmTitle = $this->auditSetting->firm_title;
        $this->contactNo = $this->auditSetting->firm_contact;
        $this->faxNo = $this->auditSetting->firm_fax;
        $this->email = $this->auditSetting->firm_email;

        $address = AuditFirmAddress::find($this->selectedFirmAddressId);
        $this->firmAddress = getFullAddress($address);

        $this->auditorModel = AuditorModel::with('user', 'licenses')->first();

        $this->selectedAuditorLicenseId = $this->auditorModel->selected_license ? $this->auditorModel->selected_license->id : null;
    }

    public function render()
    {
        $addresses = AuditFirmAddress::paginate(10);
        return view('livewire.tenant.pages.corporate-info.auditor', ['addresses' => $addresses]);
    }

    public function submit()
    {
        // session()->flash('success', 'Address Updated');
        $this->validate();
        $this->auditSetting->update([
            'audit_firm_changed' => $this->auditFirmChanged == '1',
            'prior_audit_firm' => $this->auditFirmChanged == '1' ? $this->priorAuditFirm : null,
            'prior_report_date' => $this->auditFirmChanged == '1' ? Carbon::parse($this->priorReportDate) : null,
            'prior_report_opinion' => $this->auditFirmChanged == '1' ? $this->priorReportOpinion : null,
            'is_letterhead_repeat' => $this->isLetterheadRepeat == '1',
            'is_default_letterhead' => $this->isDefaultLetterhead,
            'is_firm_address_uppercase' => $this->isFirmAddressUppercase,
            'blank_header_spacing' => $this->blankHeaderSpacing,
            'with_breakline' => $this->withBreakline == '1',
            'is_show_firm_name' => $this->isShowFirmName,
            'is_show_firm_title' => $this->isShowFirmTitle,
            'is_show_firm_license' => $this->isShowFirmLicense,
            'is_show_firm_address' => $this->isShowFirmAddress,
            'is_show_firm_contact' => $this->isShowFirmContact,
            'is_show_firm_email' => $this->isShowFirmEmail,
            'is_show_firm_fax' => $this->isShowFirmFax,
            'selected_firm_address_id' => $this->selectedFirmAddressId,
        ]);
        LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Auditor Setting Updated successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
    }

    public function cancel()
    {
        // $this->cancel();
    }

    public function updatedAuditFirmChanged($value)
    {
        if ($value == '0') {
            $this->priorAuditFirm = '';
            $this->priorReportDate = null;
            $this->priorReportOpinion = '';
        } else {
            $this->priorAuditFirm = $this->auditSetting->prior_audit_firm;
            $this->priorReportDate = $this->auditSetting->prior_report_date ? $this->auditSetting->prior_report_date->format('Y-m-d') : null;
            $this->priorReportOpinion = $this->auditSetting->prior_report_opinion;
        }
    }

    public function updatedIsDefaultLetterhead($value)
    {
        if ($value == '0') {
            $this->isFirmAddressUppercase = false;
        }
    }

}
