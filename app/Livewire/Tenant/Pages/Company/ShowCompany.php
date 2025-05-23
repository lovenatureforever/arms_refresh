<?php

namespace App\Livewire\Tenant\Pages\Company;

use Livewire\Attributes\Locked;
use Livewire\Component;

use App\Models\Tenant\Company;

class ShowCompany extends Component
{
    public $name;
    public $registrationNo;
    public $registrationNoOld;
    public $currentYear;
    public $is_active;

    #[Locked]
    public $id;

    public function mount($id)
    {
        $this->id = $id;
        $company = Company::find($id);

        $this->name = $company->name;
        $this->registrationNo = $company->registration_no;
        $this->registrationNoOld = $company->registration_no_old;
        $this->currentYear = $company->current_year;
        $this->is_active = $company->is_active;
    }

    public function render()
    {
        return view('livewire.tenant.pages.company.show-company');
    }

}
