<?php

namespace App\Livewire\Tenant\Pages\Report\DataMigration;

use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use App\Models\Tenant\CompanyReport;
use App\Models\Tenant\CompanyReportItem;
use App\Models\Tenant\CompanyReportType;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Models\Tenant\CompanyReportAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class SofpDataMigration extends Component
{
    #[Locked]
    public $id;

    public $hideEmpty = false;

    public $this_year_values = [];

    public $last_year_values = [];

    public $actual_accounts = [];

    public $actual_displays = [];

    public $check_boxes = [];

    public $skin_check_boxes = [];

    #[Locked]
    public $company_report_items;

    #[Locked]
    public $company_report_type;

    public $activeTab = 'sofp';


    public function mount($id)
    {
        $this->id = $id;
        CompanyReport::findOrFail($this->id);
        $this->refresh();
    }

    public function render()
    {
        return view('livewire.tenant.pages.report.data-migration.sofp-data-migration');

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
        // update the UI with saved values
        $this->company_report_type = CompanyReportType::where('company_report_id', $this->id)->where('name', 'SOFP')->first();
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

        // DB::statement("UPDATE company_report_items SET this_year_amount = (SELECT SUM(company_report_items.this_year_amount) FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name IN ('CA', 'NCA') AND company_report_items.type = 'value' AND company_report_items.company_report_id = ? AND company_report_items.company_report_type_id = ?) WHERE company_report_items.item = ?", [$this->id, $this->type, 'TOTAL ASSETS']);

        // sofp: TOTAL NON-CURRENT ASSET
        // sofp: TOTAL CURRENT ASSET
        // sofp: TOTAL EQUITY
        // sofp: TOTAL NON-CURRENT LIABILITY
        // sofp: TOTAL CURRENT LIABILITY
        foreach ($this->company_report_type->company_report_accounts as $group) {
            $total_item = CompanyReportItem::where('company_report_type_id', $this->company_report_type->id)->where('company_report_account_id', $group->id)->where('type', CompanyReportItem::TYPE_TOTAL)->first();
            $total_sum = DB::selectOne("SELECT SUM(this_year_amount) as this_year_amount, SUM(last_year_amount) as last_year_amount FROM company_report_items WHERE company_report_type_id = ? AND company_report_account_id = ? AND type = ? AND is_report = 1", [$this->company_report_type->id, $group->id, CompanyReportItem::TYPE_VALUE]);
            $total_item->this_year_amount = $total_sum->this_year_amount;
            $total_item->last_year_amount = $total_sum->last_year_amount;
            $total_item->save();
        }
        // sofp: total assets
        $total_asset_sum = DB::selectOne("SELECT SUM(company_report_items.this_year_amount) as this_year_amount, SUM(company_report_items.last_year_amount) as last_year_amount FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name IN ('CA', 'NCA') AND company_report_items.type = 'total' AND company_report_items.company_report_type_id = ?", [$this->company_report_type->id]);
        $total_asset_item = CompanyReportItem::where('company_report_type_id', $this->company_report_type->id)->where('item', 'TOTAL ASSETS')->first();
        $total_asset_item->this_year_amount = $total_asset_sum->this_year_amount;
        $total_asset_item->last_year_amount = $total_asset_sum->last_year_amount;
        $total_asset_item->save();
        // sofp: TOTAL LIABILITIES
        $total_liability_sum = DB::selectOne("SELECT SUM(company_report_items.this_year_amount) as this_year_amount, SUM(company_report_items.last_year_amount) as last_year_amount FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name IN ('CL', 'NCL') AND company_report_items.type = 'total' AND company_report_items.company_report_type_id = ?", [$this->company_report_type->id]);
        $total_liability_item = CompanyReportItem::where('company_report_type_id', $this->company_report_type->id)->where('item', 'TOTAL LIABILITIES')->first();
        $total_liability_item->this_year_amount = $total_liability_sum->this_year_amount;
        $total_liability_item->last_year_amount = $total_liability_sum->last_year_amount;
        $total_liability_item->save();
        // sofp: TOTAL EQUITY AND LIABILITIES
        $total_equity_item = CompanyReportItem::where('company_report_type_id', $this->company_report_type->id)->where('item', 'LIKE', 'TOTAL EQUIT%')->first();
        $total_equity_liability_item = CompanyReportItem::whereRaw("company_report_id = ? AND company_report_type_id = ? AND item LIKE 'TOTAL EQUIT%' AND item LIKE '%AND LIABILIT%'", [$this->id, $this->company_report_type->id])->first();
        $total_equity_liability_item->this_year_amount = $total_equity_item->this_year_amount + $total_liability_sum->this_year_amount;
        $total_equity_liability_item->last_year_amount = $total_equity_item->last_year_amount + $total_liability_sum->last_year_amount;
        $total_equity_liability_item->save();

        // sofp: Retained Profits/(Accumulated Losses)
        $retainedProfit = CompanyReportItem::whereRaw("`company_report_id` = ? AND `company_report_type_id` = ? AND LOWER(`item`) = 'retained profits/(accumulated losses)'", [$this->id, $this->company_report_type->id])->first();
        if ($retainedProfit->this_year_amount > $retainedProfit->last_year_amount) {
            // $retainedProfit->item = "Retained profits";
            $retainedProfit->display = "Retained profits";
            $retainedProfit->save();
        } else {
            // $retainedProfit->item = "Accumulated losses";
            $retainedProfit->display = "Accumulated losses";
            $retainedProfit->save();
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
