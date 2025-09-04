<?php

namespace App\Livewire\Tenant\Pages\CorporateInfo;

use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyDetailChange;
use App\Models\Tenant\CompanyReportSetting;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class YearEnd extends Component
{
    #[Locked]
    public $id;

    #[Locked]
    public $company;

    #[Locked]
    public $companyDetailAtStart;

    #[Locked]
    public $companyDetailAtLast;

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
        $this->company = Company::with('detailChanges', 'reportSetting')->find($id);
        $this->companyDetailAtStart = $this->company->detailAtStart();
        $this->companyDetailAtLast = $this->company->detailAtLast($this->company->end_date_report);

        $this->currentIsFirstYear = $this->company->current_is_first_year;
        $this->currentYearFrom = $this->company->current_year_from->format('Y-m-d');
        $this->currentYearTo = $this->company->current_year_to->format('Y-m-d');
        $this->currentYear = $this->company->current_year;
        $this->currentYearType = $this->company->current_year_type;
        $this->currentReportHeaderFormat = $this->company->current_report_header_format;
        if (!$this->currentIsFirstYear) {
            $this->lastIsFirstYear = $this->company->last_is_first_year;
            $this->lastYearFrom = $this->company->last_year_from->format('Y-m-d');
            $this->lastYearTo = $this->company->last_year_to->format('Y-m-d');
            $this->lastYear = $this->company->last_year;
            $this->lastYearType = $this->company->last_year_type;
            $this->lastReportHeaderFormat = $this->company->last_report_header_format;
        }
    }

    public function render()
    {
        return view('livewire.tenant.pages.corporate-info.year-end', [])->layout('layouts.corporate', ['id' => $this->id]);
    }

    public function submit()
    {
        DB::beginTransaction();
        try {
            $this->company->update([
                'current_is_first_year' => $this->currentIsFirstYear,
                'current_year_type' => $this->currentYearType,
                'current_report_header_format' => $this->currentReportHeaderFormat,
                'current_year' => $this->currentYear,
                'current_year_from' => $this->currentYearFrom,
                'current_year_to' => $this->currentYearTo,
                'last_is_first_year' => $this->lastIsFirstYear,
                'last_year_type' => $this->lastYearType,
                'last_report_header_format' => $this->lastReportHeaderFormat,
                'last_year' => $this->lastYear,
                'last_year_from' => $this->lastYearFrom,
                'last_year_to' => $this->lastYearTo,
            ]);
            DB::commit();

            // session()->flash('success', 'Year End Info Updated');
            LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Year End Info Updated successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
        } catch (Exception $e) {
            info($e->getMessage());
            DB::rollBack();
            // session()->flash('error', 'System Error. Please Try Again');
            LivewireAlert::withOptions(["position" => "top-end", "icon" => "error", "title" => "YSystem Error. Please Try Again.", "showConfirmButton" => false, "timer" => 1500])->show();
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
