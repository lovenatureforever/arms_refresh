<?php

namespace App\Livewire\Tenant\Pages\CorporateInfo;

use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyDividendChange;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Dividends extends Component
{
    #[Locked]
    public $id;

    #[Locked]
    public $company;

    #[Locked]
    public $declaredDividends;

    #[Locked]
    public $proposedDividends;

    #[Locked]
    public $dividendResults;

    public function mount($id)
    {
        $this->id = $id;
        $this->company = Company::with(relations: ['dividendChanges'])->find($this->id);
    }

    public function render()
    {
        $this->declaredDividends = CompanyDividendChange::where('company_id', $this->company->id)
            ->where('is_declared', true)
            ->whereBetween('effective_date', [$this->company->current_year_from, $this->company->end_date_report])
            ->get();

        $this->proposedDividends = CompanyDividendChange::where('company_id', $this->company->id)
            ->where('is_declared', false)
            ->whereBetween('effective_date', [$this->company->current_year_from, $this->company->end_date_report])
            ->get();

        $this->calculateDividendResults();

        return view('livewire.tenant.pages.corporate-info.dividends');
    }

    public function deleteDividendChange($id)
    {
        $res = CompanyDividendChange::find($id);
        if ($res) {
            $res->delete();
            // session()->flash('success', 'Address Deleted');
            LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Dividend Deleted successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
        }
    }

    #[On('successCreated')]
    public function successCreated()
    {
        // session()->flash('success', 'Address Created');
        LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Dividend Created successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
    }

    #[On('successUpdated')]
    public function successUpdated()
    {
        // session()->flash('success', 'Address Updated');
        LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Dividend Updated successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
    }

    private function calculateDividendResults()
    {
        $this->dividendResults = [];
    }

}
