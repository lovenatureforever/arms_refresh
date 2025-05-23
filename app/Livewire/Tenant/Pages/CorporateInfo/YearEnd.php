<?php

namespace App\Livewire\Tenant\Pages\CorporateInfo;

use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyDetailChange;
use App\Models\Tenant\CompanyReportSetting;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Component;

class YearEnd extends Component
{
    #[Locked]
    public $id;

    #[Locked]
    public $company;

    public $lastIsFirstYear;
    public $lastYearFrom;
    public $lastYearTo;
    public $lastYear;
    public $lastYearType;
    public $lastReportHeaderFormat;

    public $currentIsFirstYear;
    public $currentYearFrom;
    public $currentYearTo;
    public $currentYear;
    public $currentYearType;
    public $currentReportHeaderFormat;

    public function mount($id)
    {
        $this->id = $id;
        $company = Company::find($id);

        $this->currentIsFirstYear = $company->current_is_first_year;
        $this->currentYearFrom = $company->current_year_from->format('Y-m-d');
        $this->currentYearTo = $company->current_year_to->format('Y-m-d');
        $this->currentYear = $company->current_year;
        $this->currentYearType = $company->current_year_type;
        $this->currentReportHeaderFormat = $company->current_report_header_format;
        if (!$this->currentIsFirstYear) {
            $this->lastIsFirstYear = $company->last_is_first_year;
            $this->lastYearFrom = $company->last_year_from->format('Y-m-d');
            $this->lastYearTo = $company->last_year_to->format('Y-m-d');
            $this->lastYear = $company->last_year;
            $this->lastYearType = $company->last_year_type;
            $this->lastReportHeaderFormat = $company->last_report_header_format;
        }
    }

    public function render()
    {
        // $director_report_date = CompanyReportSetting::where('company_id', $this->companyId)->where('report_type', 'director_report')->first()->report_date;
        // $companyLast = CompanyDetailChange::where('company_id', $this->companyId)
        //     ->when($director_report_date, function ($query, $date) {
        //         $query->where('effective_date', '<', $date)->latest('effective_date');
        //     }, function ($query) {
        //         $query->latest();
        //     })
        //     ->first();
        // $companyFirst = CompanyDetailChange::where('company_id', $this->companyId)->orderBy('created_at')->first();
        // $companyLast = $companyLast ?? $companyFirst;

        return view('livewire.tenant.pages.corporate-info.year-end', []);
    }

    public function submit()
    {
        DB::beginTransaction();
        try {
            $company = Company::find($this->companyId);
            $company->update([

            ]);
            DB::commit();

            session()->flash('success', 'Year End Info Updated');
        } catch (Exception $e) {
            info($e->getMessage());
            DB::rollBack();
            session()->flash('error', 'System Error. Please Try Again');
        }
    }

    public function updatedLastIsFirstYear($val)
    {
        if ($val) {
            $this->lastYearType = 'first year';
            $this->lastReportHeaderFormat = 'date';
            $this->currentIsFirstYear = null;
        } else {
            $this->reset(['lastYearType', 'lastReportHeaderFormat']);
        }
    }

    public function updatedCurrentIsFirstYear($val)
    {
        if ($val) {
            $this->currentYearType = 'first year';
            $this->currentReportHeaderFormat = 'date';
            $this->lastIsFirstYear = null;
            $this->lastYearFrom = null;
            $this->lastYearTo = null;
            $this->lastYear = null;
        } else {
            $this->reset(['currentYearType', 'currentReportHeaderFormat', 'lastYearFrom', 'LastYearTo', 'LastYear']);
        }
    }

    public function updatingLastYearType($val)
    {
        if ($val == 'full year') {
            $this->lastReportHeaderFormat = 'year';
        } else if ($val == 'partial year') {
            $this->lastReportHeaderFormat = 'date';
        }
    }

    public function updatingCurrentYearType($val)
    {
        if ($val == 'full year') {
            $this->currentReportHeaderFormat = 'year';
        } else if ($val == 'partial year') {
            $this->currentReportHeaderFormat = 'date';
        }
    }
}
