<?php

namespace App\Livewire\Tenant\Pages\CorporateInfo;

use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyBusinessChange;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;

class BusinessNature extends Component
{
    #[Locked]
    public $id;

    #[Locked]
    public $company;

    #[Locked]
    public $businessAtStart;

    #[Locked]
    public $businessAtLast;

    #[Locked]
    public $businessChangesCurrentYear;

    public function mount($id)
    {
        $this->id = $id;
        $this->company = Company::with('businessChanges')->find($this->id);
        if (!CompanyBusinessChange::where('company_id', $this->id)->count()) {
            CompanyBusinessChange::create([
                'company_id' => $id,
                'paragraph1' => '',
                'paragraph2' => '',
                'effective_date' => '2000-01-01',
                'remarks' => '',
            ]);
        }
    }

    public function render()
    {
        $this->businessAtLast = $this->company->businessAtLast();
        $this->businessAtStart = $this->company->businessAtStart();
        $this->businessChangesCurrentYear = $this->company->businessChangesCurrentYear();

        return view('livewire.tenant.pages.corporate-info.business-nature');
    }

    public function deleteBusinessNature($id)
    {
        $res = CompanyBusinessChange::find($id);
        if ($res) {
            $res->delete();
            // session()->flash('success', 'Company Business Nature Deleted');
            LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Business Nature Deleted.", "showConfirmButton" => false, "timer" => 1500])->show();
        }
    }

    #[On('successCreated')]
    public function successCreated()
    {
        // session()->flash('success', 'Business Nature Created');
        LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Business Nature Created.", "showConfirmButton" => false, "timer" => 1500])->show();
    }

    #[On('successUpdated')]
    public function successUpdated()
    {
        // session()->flash('success', 'Business Nature Updated');
        LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Business Nature Updated.", "showConfirmButton" => false, "timer" => 1500])->show();
    }
}
