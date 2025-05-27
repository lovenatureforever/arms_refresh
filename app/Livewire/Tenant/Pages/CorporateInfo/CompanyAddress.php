<?php

namespace App\Livewire\Tenant\Pages\CorporateInfo;

use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyAddressChange;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;

class CompanyAddress extends Component
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

    public function mount($id)
    {
        $this->id = $id;
        $this->company = Company::with(relations: ['addressChanges', 'reportSetting'])->find($this->id);
        if (!CompanyAddressChange::where('company_id', $this->id)->count()) {
            CompanyAddressChange::create([
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
        }
    }

    public function render()
    {
        $this->addressAtStart = $this->company->addressAtStart();
        $this->addressChanges = $this->company->addressChangesCurrentYear();
        if ($this->company->reportSetting && $this->company->reportSetting->report_date) {
            $this->addressAtLast = $this->company->addressAtLast($this->company->reportSetting->report_date);
        } else {
            $this->addressAtLast = $this->company->addressAtLast();
        }
        return view('livewire.tenant.pages.corporate-info.company-address');
    }

    public function deleteAddress($id)
    {
        $res = CompanyAddressChange::find($id);
        if ($res) {
            $res->delete();
            // session()->flash('success', 'Address Deleted');
            LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Address Deleted successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
        }
    }

    #[On('successCreated')]
    public function successCreated()
    {
        // session()->flash('success', 'Address Created');
        LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Address Created successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
    }

    #[On('successUpdated')]
    public function successUpdated()
    {
        // session()->flash('success', 'Address Updated');
        LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Address Updated successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
    }
}
