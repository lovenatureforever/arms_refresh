<?php

namespace App\Livewire\Tenant\Pages\Company;

use Livewire\Attributes\Locked;
use Livewire\Component;

use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyDirector;
use App\Models\User;

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
        $user = auth()->user();

        // Directors can only access their own company (the one they're linked to)
        if ($user->user_type === User::USER_TYPE_DIRECTOR) {
            $director = CompanyDirector::where('user_id', $user->id)
                ->where('is_active', true)
                ->first();

            if (!$director || $director->company_id != $id) {
                abort(403, 'You can only access your own company.');
            }
        }

        // Subscribers can only access their own companies
        if ($user->user_type === User::USER_TYPE_SUBSCRIBER && $company->created_by !== $user->id) {
            abort(403, 'You can only access your own companies.');
        }

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
