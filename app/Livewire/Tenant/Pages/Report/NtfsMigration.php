<?php

namespace App\Livewire\Tenant\Pages\Report;

use Livewire\Attributes\On;
use Exception;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use App\Models\Tenant\CompanyReport;
use App\Models\Tenant\CompanyReportItem;
use App\Models\Tenant\CompanyReportNtfsItem;
use App\Models\Tenant\CompanyReportNtfsSection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class NtfsMigration extends Component
{
    #[Locked]
    public $report;
    #[Locked]
    public $id;

    public $company_report;
    public $company_report_item;
    public $sections;

    public $current_year_from;
    public $current_year_to;

    public $prior_year;
    public $current_year;
    public $currentPeriodType;

    public $col_1_values = [];
    public $col_2_values = [];
    public $col_3_values = [];
    public $col_4_values = [];

    public $total_1_values = [];
    public $total_2_values = [];
    public $total_3_values = [];
    public $total_4_values = [];



    public function mount($report, $id)
    {
        $this->report = $report;
        $this->id = $id;
        $this->company_report = CompanyReport::findOrFail($this->report);
        $this->company_report_item = CompanyReportItem::findOrFail($this->id);
        $this->sections = CompanyReportNtfsSection::where('company_report_id', $this->report)->where('company_report_item_id', $this->id)->orderBy('sort')->get();

        $this->current_year_from = 'As at '.Carbon::parse($this->company_report->company->current_audit_period_from)->format('j M Y');
        $this->current_year_to = 'As at '.Carbon::parse($this->company_report->company->current_audit_period_to)->format('j M Y');
        $this->current_year = Carbon::parse($this->company_report->company->current_audit_period_to)->format('Y');
        $this->prior_year = Carbon::parse($this->company_report->company->last_audit_period_from)->format('Y');

        $this->currentPeriodType = $this->company_report->company['current_year_period_type'];

        // If current company report ntfs sections is empty, create default 3 sections
        if ($this->sections->isEmpty()) {
            CompanyReportNtfsSection::insert([
                [
                    'company_report_id' => $this->report,
                    'company_report_item_id' => $this->id,
                    'name' => 'Cost',
                    'type' => 4,
                    'sort' => 1
                ],
                [
                    'company_report_id' => $this->report,
                    'company_report_item_id' => $this->id,
                    'name' => 'Accumulated depreciation',
                    'type' => 4,
                    'sort' => 2
                ],
                [
                    'company_report_id' => $this->report,
                    'company_report_item_id' => $this->id,
                    'name' => 'Carrying Amounts',
                    'type' => 2,
                    'sort' => 3
                ],
            ]);
        }
        $this->load();
    }

    public function render()
    {
        $this->load();
        // return view('livewire.tenants.data-migration');
        return view('livewire.tenant.pages.report.ntfs-migration', [
            'company_report_id' => $this->report,
        ]);
    }

    public function save()
    {
        try {
            foreach ($this->col_1_values as $id => $value) {
                $item = CompanyReportNtfsItem::findOrFail($id);
                $item->col_1 = readNumber($value);
                $item->col_2 = readNumber($this->col_2_values[$id]);
                if (isset($this->col_3_values[$id])) {
                    $item->col_3 = readNumber($this->col_3_values[$id]);
                    $item->col_4 = readNumber($this->col_4_values[$id]);
                }
                $item->save();
            }
            $this->load();
            LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "NTFS migration saved.", "showConfirmButton" => false, "timer" => 1500])->show();
        } catch (Exception $e) {
            LivewireAlert::withOptions(["position" => "top-end", "icon" => "error", "title" => $e->getMessage(), "showConfirmButton" => false, "timer" => 1500])->show();
        }
    }

    public function deleteSection($id)
    {
        try {
            $section = CompanyReportNtfsSection::findOrFail($id);
            $section->delete();
            $this->sections = $this->sections->except($section->id);
            LivewireAlert::withOptions([
                "position" => "top-end",
                "icon" => "success",
                "title" => "Section deleted.",
                "showConfirmButton" => false,
                "timer" => 1500
            ])->show();
        } catch (Exception $e) {
            LivewireAlert::withOptions([
                "position" => "top-end",
                "icon" => "error",
                "title" => $e->getMessage(),
                "showConfirmButton" => false,
                "timer" => 1500
            ])->show();
        }
    }

    public function deleteItem($id)
    {
        try {
            $item = CompanyReportNtfsItem::findOrFail($id);
            $item->delete();
            // $this->sections = $this->sections->except($section->id);
            $this->load();
            LivewireAlert::withOptions([
                "position" => "top-end",
                "icon" => "success",
                "title" => "Section deleted.",
                "showConfirmButton" => false,
                "timer" => 1500
            ])->show();
        } catch (Exception $e) {
            LivewireAlert::withOptions([
                "position" => "top-end",
                "icon" => "error",
                "title" => $e->getMessage(),
                "showConfirmButton" => false,
                "timer" => 1500
            ])->show();
        }
    }

    public function updateSectionSort($orderItem)
    {
        foreach ($orderItem as $item) {
            $report = CompanyReportNtfsSection::find($item['value']);
            // Log::info($item);
            $report->sort = $item['order'];
            $report->save();
        }

        LivewireAlert::withOptions([
            "position" => "top-end",
            "icon" => "success",
            "title" => "Order Saved!",
            "showConfirmButton" => false,
            "timer" => 1500
        ])->show();
    }

    #[On('successCreatedSection')]
    public function successCreatedSection()
    {
        $this->load();
        LivewireAlert::withOptions([
            "position" => "top-end",
            "icon" => "success",
            "title" => "New section was created.",
            "showConfirmButton" => false,
            "timer" => 1500
        ])->show();
        // session()->flash('success', 'New section was created');
    }

    #[On('successUpdatedSection')]
    public function successUpdatedSection()
    {
        $this->load();
        LivewireAlert::withOptions([
            "position" => "top-end",
            "icon" => "success",
            "title" => "Section was updated.",
            "showConfirmButton" => false,
            "timer" => 1500
        ])->show();
    }

    private function load() {
        $this->sections = CompanyReportNtfsSection::where('company_report_id', $this->report)->where('company_report_item_id', $this->id)->orderBy('sort')->get();
        $items = CompanyReportNtfsItem::whereIn('company_report_ntfs_section_id', $this->sections->pluck('id'))->get();
        foreach ($items as $item) {
            $this->col_1_values[$item->id] = displayNumber($item->col_1);
            $this->col_2_values[$item->id] = displayNumber($item->col_2);
            $this->col_3_values[$item->id] = displayNumber($item->col_3);
            $this->col_4_values[$item->id] = displayNumber($item->col_4);
        }

        foreach ($this->sections as $section) {
            $total_1 = 0;
            $total_2 = 0;
            $total_3 = 0;
            $total_4 = 0;
            foreach ($section->items as $item) {
                $total_1 += $item->col_1;
                $total_2 += $item->col_2;
                $total_3 += $item->col_3;
                $total_4 += $item->col_4;
            }
            $this->total_1_values[$section->id] = displayNumber($total_1);
            $this->total_2_values[$section->id] = displayNumber($total_2);
            $this->total_3_values[$section->id] = displayNumber($total_3);
            $this->total_4_values[$section->id] = displayNumber($total_4);
        }
    }
}
