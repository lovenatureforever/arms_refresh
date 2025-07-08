<?php

namespace App\Livewire\Tenant\Pages\CorporateInfo;

use App\Models\Tenant\Company;
use App\Models\Tenant\CompanySecretary;
use App\Models\Tenant\CompanyDirector;
use App\Models\Tenant\CompanySecretaryChange;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Secretaries extends Component
{
    #[Locked]
    public $id;

    #[Locked]
    public $company;

    #[Locked]
    public $secretaryChangesAtStart;

    #[Locked]
    public $secretariesAtLast;

    #[Locked]
    public $secretaryChangesCurrentYear;

    public function mount($id)
    {
        $this->id = $id;
        $this->company = Company::with(relations: ['secretaries', 'secretaryChanges'])->find($this->id);
    }

    public function render()
    {
        $this->secretaryChangesAtStart = CompanySecretaryChange::with(['companySecretary' => function($query) {
                $query->where('company_id', $this->id);
            }])
            ->whereHas('companySecretary', function($query) {
                $query->where('company_id', $this->id);
            }, '=', 1) // Explicit count for better performance
            ->where('effective_date', '<', $this->company->current_year_from)
            ->latest('effective_date')
            ->get();
        $date = $this->company->end_date_report;

        $this->secretariesAtLast = CompanySecretary::where('company_id', $this->id)
            ->whereHas('changes', function($query) use ($date) {
                $query->where('effective_date', '<=', $date);
            })
            ->where('is_active', true)
            ->get();

        $this->secretaryChangesCurrentYear = CompanySecretaryChange::with(['companySecretary' => function($query) {
                $query->where('company_id', $this->id);
            }])
            ->whereHas('companySecretary', function($query) {
                $query->where('company_id', $this->id);
            }, '=', 1) // Explicit count for better performance
            ->whereBetween('effective_date', [$this->company->current_year_from, $date])
            ->get();

        return view('livewire.tenant.pages.corporate-info.secretaries');
    }

    public function deleteSecretaryChange($id)
    {
        $res = CompanySecretaryChange::find($id);
        if ($res) {
            $res->delete();
            // session()->flash('success', 'Address Deleted');
            LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Secretary Change Deleted successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
        }
    }

    #[On('successCreated')]
    public function successCreated()
    {
        // session()->flash('success', 'Address Created');
        LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Secretary Created successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
    }

    #[On('successUpdated')]
    public function successUpdated()
    {
        // session()->flash('success', 'Address Updated');
        LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Secretary Updated successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
    }

}
