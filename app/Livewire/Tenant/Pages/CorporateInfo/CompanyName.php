<?php

namespace App\Livewire\Tenant\Pages\CorporateInfo;

use App\Models\Tenant\CompanyDetailChange;
use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyReportSetting;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class CompanyName extends Component
{
    #[Locked]
    public $id;

    #[Locked]
    public $company;

    public $registrationNo;

    public $registrationNoOld;

    #[Locked]
    public $companyDetailAtStart;

    #[Locked]
    public $companyDetailAtLast;

    #[Locked]
    public $companyDetailChanges;

    public function mount($id)
    {
        $this->id = $id;
        $this->company = Company::with(['detailChanges', 'reportSetting'])->find($this->id);
        $this->registrationNo = $this->company->registration_no;
        $this->registrationNoOld = $this->company->registration_no_old;
    }

    public function render()
    {
        $this->companyDetailAtStart = $this->company->detailAtStart();
        if ($this->company->reportSetting && $this->company->reportSetting->report_date) {
            $this->companyDetailAtLast = $this->company->detailAtLast($this->company->reportSetting->report_date);
        } else {
            $this->companyDetailAtLast = $this->company->detailAtLast();
        }
        $this->companyDetailChanges = $this->company->detailChangesCurrentYear();

        return view('livewire.tenant.pages.corporate-info.company-name', []);
    }

    public function saveRegistrationNo()
    {
        $this->company->registration_no = trim($this->registrationNo);
        $this->company->registration_no_old = trim($this->registrationNoOld);
        $this->company->save();
        session()->flash('success', 'Company Registration No. Updated');
    }

    public function deleteCompanyName($id)
    {
        $res = CompanyDetailChange::find($id);

        if ($res) {
            $res->delete();

            // session()->flash('success', 'Company Name Deleted');
            LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Name Deleted successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
        }
    }

    #[On('successCreated')]
    public function successCreated()
    {
        // session()->flash('success', 'Company Name Created');
        LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Name Created successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
    }

    #[On('successUpdated')]
    public function successUpdated()
    {
        // session()->flash('success', 'Company Name Updated');
        LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Name Updated successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
    }
}
