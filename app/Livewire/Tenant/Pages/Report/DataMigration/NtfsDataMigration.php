<?php

namespace App\Livewire\Tenant\Pages\Report\DataMigration;

use Livewire\Component;


use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use App\Models\Tenant\CompanyReport;
use App\Models\Tenant\CompanyReportItem;
use App\Models\Tenant\CompanyReportType;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Illuminate\Support\Facades\DB;

class NtfsDataMigration extends Component
{
    #[Locked]
    public $id;

    public $company_report_type;
    public $company_report_items;
    public $this_year_values = [];
    public $last_year_values = [];
    public $actual_displays = [];
    public $check_boxes = [];
    public $skin_check_boxes = [];
    public $activeTab = 'ntfs';

    public function mount($id)
    {
        $this->id = $id;
        CompanyReport::findOrFail($this->id);
        $this->refresh();
    }

    public function render()
    {
        return view('livewire.tenant.pages.report.data-migration.ntfs-data-migration');
    }

    #[On('successCreated')]
    public function successCreated()
    {
        LivewireAlert::withOptions([
            "position" => "top-end",
            "icon" => "success",
            "title" => "Report item was created",
            "showConfirmButton" => false,
            "timer" => 1500
        ])->show();
        $this->refresh();
    }

    private function refresh() {
        $this->company_report_type = CompanyReportType::where('company_report_id', $this->id)->where('name', 'NTFS')->first();
        $sofp_type = CompanyReportType::where('company_report_id', $this->id)->where('name', 'SOFP')->first();
        $soci_type = CompanyReportType::where('company_report_id', $this->id)->where('name', 'SOCI')->first();
        $this->company_report_items = CompanyReportItem::where('company_report_type_id', $sofp_type->id)->where('type', 'value')->where('is_report', 1)->orderBy('sort')->orderBy('id')->get();
        $soci_profit_operation = CompanyReportItem::where('company_report_type_id', $soci_type->id)->where('item', 'Less from operations')->orWhere('item', 'Profit from operations')->first();
        $soci_profit_before_tax = CompanyReportItem::where('company_report_type_id', $soci_type->id)->where('item', 'Less before tax')->orWhere('item', 'Profit before tax')->first();
        $soci_taxation = CompanyReportItem::where('company_report_type_id', $soci_type->id)->where('item', 'Tax expense')->orWhere('item', 'Taxation')->first();
        $this->company_report_items->push($soci_profit_operation, $soci_profit_before_tax, $soci_taxation);
        foreach ($this->company_report_items as $item) {
            $this->actual_displays[$item->id] = $item->display;
            $this->check_boxes[$item->id] = !!$item->is_report;
            $this->skin_check_boxes[$item->id] = $item->show_display;
            $this->this_year_values[$item->id] = displayNumber($item->this_year_amount);
            $this->last_year_values[$item->id] = displayNumber($item->last_year_amount);
        }
    }
}
