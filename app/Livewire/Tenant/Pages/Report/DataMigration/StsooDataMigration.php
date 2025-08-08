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

class StsooDataMigration extends Component
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
    public $activeTab = 'stsoo';

    public function mount($id)
    {
        $this->id = $id;
        CompanyReport::findOrFail($this->id);
        $this->refresh();
    }

    public function render()
    {
        return view('livewire.tenant.pages.report.data-migration.stsoo-data-migration');
    }

    public function updatingSkinCheckBoxes($value, $id) {
        if (!$value) {
            $this->actual_displays[$id] = null;
        }
    }

    #[On('successCreated')]
    public function successCreated()
    {
        session()->flash('success', 'Report item was created');
        $this->refresh();
    }

    private function refresh() {
        $this->company_report_type = CompanyReportType::where('company_report_id', $this->id)->where('name', 'STSOO')->first();
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

        // STSOO-specific calculations
        foreach ($this->company_report_type->company_report_accounts as $group) {
            $total_item = CompanyReportItem::where('company_report_type_id', $this->company_report_type->id)->where('company_report_account_id', $group->id)->where('type', CompanyReportItem::TYPE_TOTAL)->first();
            if ($total_item) {
                $total_sum = DB::selectOne("SELECT SUM(this_year_amount) as this_year_amount, SUM(last_year_amount) as last_year_amount FROM company_report_items WHERE company_report_type_id = ? AND company_report_account_id = ? AND type = ? AND is_report = 1", [$this->company_report_type->id, $group->id, CompanyReportItem::TYPE_VALUE]);
                $total_item->this_year_amount = $total_sum?->this_year_amount;
                $total_item->last_year_amount = $total_sum?->last_year_amount;
                $total_item->save();
            }
        }
        // GROSS PROFIT
        $revenue_total_item = DB::selectOne("SELECT (company_report_items.this_year_amount) as this_year_amount, (company_report_items.last_year_amount) as last_year_amount FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'REV' AND company_report_items.type = 'total' AND company_report_items.company_report_type_id = ?", [$this->company_report_type->id]);
        $cost_total_item = DB::selectOne("SELECT (company_report_items.this_year_amount) as this_year_amount, (company_report_items.last_year_amount) as last_year_amount FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'COS' AND company_report_items.type = 'total' AND company_report_items.company_report_type_id = ?", [$this->company_report_type->id]);
        $gross_profit_item = CompanyReportItem::where('company_report_type_id', $this->company_report_type->id)->where('item', 'GROSS PROFIT')->first();
        $gross_profit_item->this_year_amount = $revenue_total_item->this_year_amount - $cost_total_item->this_year_amount;
        $gross_profit_item->last_year_amount = $revenue_total_item->last_year_amount - $cost_total_item->last_year_amount;
        $gross_profit_item->save();

        $tae_item = DB::selectOne("SELECT (company_report_items.this_year_amount) as this_year_amount, (company_report_items.last_year_amount) as last_year_amount FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'AE' AND company_report_items.type = 'total' AND company_report_items.company_report_type_id = ?", [$this->company_report_type->id]);
        $tooe_item = DB::selectOne("SELECT (company_report_items.this_year_amount) as this_year_amount, (company_report_items.last_year_amount) as last_year_amount FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'OOE' AND company_report_items.type = 'total' AND company_report_items.company_report_type_id = ?", [$this->company_report_type->id]);
        $tfc_item = DB::selectOne("SELECT (company_report_items.this_year_amount) as this_year_amount, (company_report_items.last_year_amount) as last_year_amount FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'FC' AND company_report_items.type = 'total' AND company_report_items.company_report_type_id = ?", [$this->company_report_type->id]);
        $tooi_item = DB::selectOne("SELECT (company_report_items.this_year_amount) as this_year_amount, (company_report_items.last_year_amount) as last_year_amount FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'OOI' AND company_report_items.type = 'total' AND company_report_items.company_report_type_id = ?", [$this->company_report_type->id]);
        $ttax_item = DB::selectOne("SELECT (company_report_items.this_year_amount) as this_year_amount, (company_report_items.last_year_amount) as last_year_amount FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'TAX' AND company_report_items.type = 'total' AND company_report_items.company_report_type_id = ?", [$this->company_report_type->id]);

        // Total Operating Expenses
        $toe_item = CompanyReportItem::where('company_report_type_id', $this->company_report_type->id)->where('item', 'TOTAL OPERATING EXPENSES')->first();
        $toe_item->this_year_amount = $tae_item->this_year_amount + $tooe_item->this_year_amount + $tfc_item->this_year_amount;
        $toe_item->last_year_amount = $tae_item->last_year_amount + $tooe_item->last_year_amount + $tfc_item->last_year_amount;
        $toe_item->save();

        // PROFIT BEFORE TAX
        $pbtax_item = CompanyReportItem::where('company_report_type_id', $this->company_report_type->id)->where('item', 'PROFIT BEFORE TAX')->first();
        $pbtax_item->this_year_amount = $gross_profit_item->this_year_amount + $tooi_item->this_year_amount - $tae_item->this_year_amount - $tooe_item->this_year_amount - $tfc_item->this_year_amount;
        $pbtax_item->last_year_amount = $gross_profit_item->last_year_amount + $tooi_item->last_year_amount - $tae_item->last_year_amount - $tooe_item->last_year_amount - $tfc_item->last_year_amount;
        $pbtax_item->save();

        // PROFIT FOR THE YEAR
        $profit_this_item = CompanyReportItem::where('company_report_type_id', $this->company_report_type->id)->where('item', 'PROFIT FOR THE YEAR')->first();
        $profit_this_item->this_year_amount = $pbtax_item->this_year_amount - $ttax_item->this_year_amount;
        $profit_this_item->last_year_amount = $pbtax_item->last_year_amount - $ttax_item->last_year_amount;
        $profit_this_item->save();

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
