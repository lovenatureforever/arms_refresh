<?php

namespace App\Livewire\Tenant\Pages\CorporateInfo;

use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyDirector;
use App\Models\Tenant\CompanyDirectorChange;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;

class Director extends Component
{
    #[Locked]
    public $id;

    #[Locked]
    public $company;

    #[Locked]
    public $directorChangesAtStart;

    #[Locked]
    public $directorsAtLast;

    #[Locked]
    public $directorChangesCurrentYear;

    public function mount($id)
    {
        $this->id = $id;
        $this->company = Company::with(relations: ['directors', 'directorChanges'])->find($this->id);
    }

    public function render()
    {
        $this->directorChangesAtStart = CompanyDirectorChange::with(['companyDirector' => function($query) {
                $query->where('company_id', $this->id);
            }])
            ->whereHas('companyDirector', function($query) {
                $query->where('company_id', $this->id);
            }, '=', 1) // Explicit count for better performance
            ->where('effective_date', '<', $this->company->current_year_from)
            ->latest('effective_date')
            ->get();
        $date = $this->company->current_year_to;
        if ($this->company->reportSetting && $this->company->reportSetting->report_date) {
            $date = $this->company->reportSetting->report_date;
        }
        $this->directorsAtLast = CompanyDirector::where('company_id', $this->id)
            ->whereHas('changes', function($query) use ($date) {
                $query->where('effective_date', '<=', $date)
                    ->latest('effective_date');
            })
            ->orderBy('sort')
            ->get();
        foreach ($this->directorsAtLast as $director) {
            $director_changes_current = $director->changes()->whereBetween('effective_date', [$this->company->current_year_from, $date])
                                                            ->orderBy('effective_date')
                                                            ->get();
            $d = [];
            foreach ($director_changes_current as $change) {
                // Director appointed on 29/04/2024, Director resigned on 20/08/2024
                $d[] = $change->change_nature . ' on ' . $change->effective_date->format('Y-m-d');
            }
            $director->changes_current = implode(', ', $d);
        }
        $this->directorChangesCurrentYear = $this->company->directorChangesCurrentYear();
        return view('livewire.tenant.pages.corporate-info.director', ['end_date' => $date]);
    }

    public function deleteDirector($id)
    {
        $res = CompanyDirectorChange::find($id);
        if ($res) {
            $res->delete();
            // session()->flash('success', 'Address Deleted');
            LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Director Deleted successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
        }
    }

    public function updateDirectorOrder($order)
    {
        foreach ($order as $item) {
            CompanyDirector::where('id', $item['value'])->update(['sort' => $item['order']]);
        }
        $this->directorsAtLast = CompanyDirector::orderBy('sort')->get();
    }

    #[On('successCreated')]
    public function successCreated()
    {
        // session()->flash('success', 'Address Created');
        LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Director Created successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
    }

    #[On('successUpdated')]
    public function successUpdated()
    {
        // session()->flash('success', 'Address Updated');
        LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Director Updated successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
    }
}
