<?php

namespace App\Livewire\Tenant\Pages\CorporateInfo;

use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyChargeChange;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Charges extends Component
{
    #[Locked]
    public $id;

    #[Locked]
    public $company;

    #[Locked]
    public $chargeChangesAtStart;

    #[Locked]
    public $chargeChangesCurrentYear;

    #[Locked]
    public $chargeResults;

    public function mount($id)
    {
        $this->id = $id;
        $this->company = Company::with(relations: ['chargeChanges'])->find($this->id);
    }

    public function render()
    {
        $this->chargeChangesAtStart = CompanyChargeChange::where('effective_date', '<', $this->company->current_year_from)
            ->latest('effective_date')
            ->get();
        $date = $this->company->current_year_to;

        $this->chargeChangesCurrentYear = CompanyChargeChange::whereBetween('effective_date', [$this->company->current_year_from, $date])
            ->get();

        $this->calculateChargeResults();

        return view('livewire.tenant.pages.corporate-info.charges');
    }

    public function deleteChargeChange($id)
    {
        $res = CompanyChargeChange::find($id);
        if ($res) {
            $res->delete();
            // session()->flash('success', 'Address Deleted');
            LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Charge Change Deleted successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
        }
    }

    #[On('successCreated')]
    public function successCreated()
    {
        // session()->flash('success', 'Address Created');
        LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Charge Created successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
    }

    #[On('successUpdated')]
    public function successUpdated()
    {
        // session()->flash('success', 'Address Updated');
        LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Charge Updated successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
    }

    private function calculateChargeResults()
    {
        $createdCharges = CompanyChargeChange::where('company_id', $this->company->id)
            ->where('change_nature', CompanyChargeChange::CHANGE_NATURE_CREATE)
            ->get();
        foreach ($createdCharges as $charge) {
            $discharge = CompanyChargeChange::where('creating_charge_id', $charge->id)
                ->where('change_nature', CompanyChargeChange::CHANGE_NATURE_DISCHARGE)
                ->first();
            $charge->discharge_date = $discharge ? $discharge->discharge_date : $charge->discharge_date;
        }
        $this->chargeResults = $createdCharges;
    }

}
