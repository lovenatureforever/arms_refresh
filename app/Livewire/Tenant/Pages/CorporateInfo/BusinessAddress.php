<?php

namespace App\Livewire\Tenant\Pages\CorporateInfo;

use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyBizAddressChange;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;

class BusinessAddress extends Component
{
    #[Locked]
    public $id;

    #[Locked]
    public $company;

    #[Locked]
    public $addressAtStart;

    #[Locked]
    public $addressAtLast;

    #[Locked]
    public $addressChanges;

    public $hasNoBusinessAddress;

    public function mount($id)
    {
        $this->id = $id;
        $this->company = Company::with(relations: ['addressChanges', 'reportSetting'])->find($this->id);
        /* if (!CompanyBizAddressChange::where('company_id', $this->id)->count()) {
            CompanyBizAddressChange::create([
                'company_id' => $this->id,
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
        } */
    }

    public function render()
    {
        $this->addressAtStart = $this->company->bizAddressAtStart();
        $this->addressChanges = $this->company->bizAddressChangesCurrentYear();
        if ($this->company->reportSetting && $this->company->reportSetting->report_date) {
            $this->addressAtLast = $this->company->bizAddressAtLast($this->company->reportSetting->report_date);
        } else {
            $this->addressAtLast = $this->company->bizAddressAtLast();
        }
        $this->hasNoBusinessAddress = $this->company->no_business_address;
        return view('livewire.tenant.pages.corporate-info.business-address');
    }

    public function updatedHasNoBusinessAddress($value)
    {
        if ($this->company) {
            $this->company->update(['no_business_address' => (bool) $value]);
            LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Business Address Updated successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
        }
    }

    public function deleteAddress($id)
    {
        $res = CompanyBizAddressChange::find($id);
        if ($res) {
            $res->delete();
            // session()->flash('success', 'Address Deleted');
            LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Business Address Deleted successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
        }
    }

    #[On('successCreated')]
    public function successCreated()
    {
        // session()->flash('success', 'Address Created');
        LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Business Address Created successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
    }

    #[On('successUpdated')]
    public function successUpdated()
    {
        // session()->flash('success', 'Address Updated');
        LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Business Address Updated successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
    }
}
