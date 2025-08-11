<?php

namespace App\Livewire\Tenant\Pages\Report\DataMigration;

use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use App\Models\Tenant\CompanyReport;
use App\Models\Tenant\CompanyReportItem;
use App\Models\Tenant\CompanyReportType;
use App\Models\Tenant\CompanyReportAccount;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Illuminate\Support\Facades\DB;

class SocfDataMigration extends Component
{
    #[Locked]
    public $id;

    public $hideEmpty = false;
    public $company_report_type;
    public $company_report_items;
    public $this_year_values = [];
    public $last_year_values = [];
    public $actual_displays = [];
    public $check_boxes = [];
    public $skin_check_boxes = [];
    public $activeTab = 'socf';

    public function mount($id)
    {
        $this->id = $id;
        CompanyReport::findOrFail($this->id);
        $this->refresh();
    }

    public function render()
    {
        return view('livewire.tenant.pages.report.data-migration.socf-data-migration');
    }

    public function updatingSkinCheckBoxes($value, $id) {
        if (!$value) {
            $this->actual_displays[$id] = null;
        }
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
        $this->company_report_type = CompanyReportType::where('company_report_id', $this->id)->where('name', 'SOCF')->first();
        $this->company_report_items = CompanyReportItem::where('company_report_type_id', $this->company_report_type->id)->orderBy('sort')->orderBy('id')->get();
        foreach ($this->company_report_items as $item) {
            $this->actual_displays[$item->id] = $item->display;
            $this->check_boxes[$item->id] = !!$item->is_report;
            $this->skin_check_boxes[$item->id] = $item->show_display;
            $this->this_year_values[$item->id] = displayNumber($item->this_year_amount);
            $this->last_year_values[$item->id] = displayNumber($item->last_year_amount);
        }
    }

    public function save()
    {
        foreach ($this->company_report_items as $item) {
            if ($item->type == CompanyReportItem::TYPE_VALUE) {
                $item->display = $this->actual_displays[$item->id];
                $item->this_year_amount = readNumber($this->this_year_values[$item->id]);
                $item->last_year_amount = readNumber($this->last_year_values[$item->id]);
                $item->is_report = $this->check_boxes[$item->id];
                $item->show_display = $this->skin_check_boxes[$item->id];
                $item->save();
            }
        }

        // SOCF-specific calculations
        // Operating profit before working capital changes
        $pbtax_item = CompanyReportItem::where('company_report_type_id', $this->company_report_type->id)->where('item', 'Profit before tax')->first();
        $opbwcc_item = CompanyReportItem::where('company_report_type_id', $this->company_report_type->id)->where('item', 'Operating profit before working capital changes')->first();
        $sort = $opbwcc_item ? substr($opbwcc_item->sort, 0, -2) : null;
        if ($opbwcc_item && $sort !== null) {
            $total_opbwcc = DB::selectOne("SELECT SUM(this_year_amount) as this_year_amount, SUM(last_year_amount) as last_year_amount FROM company_report_items WHERE company_report_type_id = ? AND sort = ? AND type = ? AND is_report = 1", [$this->company_report_type->id, $sort, CompanyReportItem::TYPE_VALUE]);
            $opbwcc_item->this_year_amount = ($total_opbwcc->this_year_amount??0) + ($pbtax_item->this_year_amount??0);
            $opbwcc_item->last_year_amount = ($total_opbwcc->last_year_amount??0) + ($pbtax_item->last_year_amount??0);
            $opbwcc_item->save();
        }

        // Net change in operations
        $net_operations_item = CompanyReportItem::where('company_report_type_id', $this->company_report_type->id)->where('item', 'Net change in operations')->first();
        $sort = $net_operations_item ? substr($net_operations_item->sort, 0, -2) : null;
        if ($net_operations_item && $sort !== null && $opbwcc_item) {
            $total_nco = DB::selectOne("SELECT SUM(this_year_amount) as this_year_amount, SUM(last_year_amount) as last_year_amount FROM company_report_items WHERE company_report_type_id = ? AND sort = ? AND type = ? AND is_report = 1", [$this->company_report_type->id, $sort, CompanyReportItem::TYPE_VALUE]);
            $net_operations_item->this_year_amount = ($total_nco->this_year_amount??0) + ($opbwcc_item->this_year_amount??0);
            $net_operations_item->last_year_amount = ($total_nco->last_year_amount??0) + ($opbwcc_item->last_year_amount??0);
            $net_operations_item->save();
        }

        // Net change in operating activities
        $oa_group = CompanyReportAccount::where('description', 'CASH FLOWS FROM OPERATING ACTIVITIES')->first();
        $total_oa_item = CompanyReportItem::where('company_report_type_id', $this->company_report_type->id)->where('item', 'Net change in operating activities')->first();
        if ($oa_group && $total_oa_item) {
            $total_sum = DB::selectOne("SELECT SUM(this_year_amount) as this_year_amount, SUM(last_year_amount) as last_year_amount FROM company_report_items WHERE company_report_type_id = ? AND company_report_account_id = ? AND type = ? AND is_report = 1", [$this->company_report_type->id, $oa_group->id, CompanyReportItem::TYPE_VALUE]);
            $total_oa_item->this_year_amount = $total_sum->this_year_amount;
            $total_oa_item->last_year_amount = $total_sum->last_year_amount;
            $total_oa_item->save();
        }

        // Net change in investing activities
        $ia_group = CompanyReportAccount::where('description', 'CASH FLOWS FROM INVESTING ACTIVITIES')->first();
        $total_ia_item = CompanyReportItem::where('company_report_type_id', $this->company_report_type->id)->where('item', 'Net change in investing activities')->first();
        if ($ia_group && $total_ia_item) {
            $total_sum_ia = DB::selectOne("SELECT SUM(this_year_amount) as this_year_amount, SUM(last_year_amount) as last_year_amount FROM company_report_items WHERE company_report_type_id = ? AND company_report_account_id = ? AND type = ? AND is_report = 1", [$this->company_report_type->id, $ia_group->id, CompanyReportItem::TYPE_VALUE]);
            $total_ia_item->this_year_amount = $total_sum_ia->this_year_amount;
            $total_ia_item->last_year_amount = $total_sum_ia->last_year_amount;
            $total_ia_item->save();
        }

        // Net change in financing activities
        $fa_group = CompanyReportAccount::where('description', 'CASH FLOWS FROM FINANCING ACTIVITIES')->first();
        $total_fa_item = CompanyReportItem::where('company_report_type_id', $this->company_report_type->id)->where('item', 'Net change in financing activities')->first();
        if ($fa_group && $total_fa_item) {
            $total_sum_fa = DB::selectOne("SELECT SUM(this_year_amount) as this_year_amount, SUM(last_year_amount) as last_year_amount FROM company_report_items WHERE company_report_type_id = ? AND company_report_account_id = ? AND type = ? AND is_report = 1", [$this->company_report_type->id, $fa_group->id, CompanyReportItem::TYPE_VALUE]);
            $total_fa_item->this_year_amount = $total_sum_fa->this_year_amount;
            $total_fa_item->last_year_amount = $total_sum_fa->last_year_amount;
            $total_fa_item->save();
        }

        // Cash and cash equivalents at end of the year
        $ccey_item = CompanyReportItem::where('company_report_type_id', $this->company_report_type->id)->where('item', 'Cash and cash equivalents at end of the year')->first();
        $net_change_cce_item = CompanyReportItem::where('company_report_type_id', $this->company_report_type->id)->where('item', 'Net change in cash and cash equivalents')->first();
        $ccby_item = CompanyReportItem::where('company_report_type_id', $this->company_report_type->id)->where('item', 'Cash and cash equivalents at beginning of the year')->first();
        if ($ccey_item && $net_change_cce_item && $ccby_item) {
            $ccey_item->this_year_amount = ($net_change_cce_item?->this_year_amount??0) + ($ccby_item?->this_year_amount??0);
            $ccey_item->last_year_amount = ($net_change_cce_item?->last_year_amount??0) + ($ccby_item?->last_year_amount??0);
            $ccey_item->save();
        }

        // Cash and bank balances + Bank overdraft
        $last_item = CompanyReportItem::where('company_report_type_id', $this->company_report_type->id)->whereNull('item')->where('type', 'total')->first();
        $ccb_item = CompanyReportItem::where('company_report_type_id', $this->company_report_type->id)->where('item', 'Cash and bank balances')->first();
        $bo_item = CompanyReportItem::where('company_report_type_id', $this->company_report_type->id)->where('item', 'Bank overdraft')->first();
        if ($last_item && $ccb_item && $bo_item) {
            $last_item->this_year_amount = ($ccb_item?->this_year_amount??0) + ($bo_item?->this_year_amount??0);
            $last_item->last_year_amount = ($ccb_item?->last_year_amount??0) + ($bo_item?->last_year_amount??0);
            $last_item->save();
        }

        $this->refresh();
        LivewireAlert::withOptions([
            "position" => "top-end",
            "icon" => "success",
            "title" => "Saved successful!",
            "showConfirmButton" => false,
            "timer" => 1500
        ])->show();
    }
}
