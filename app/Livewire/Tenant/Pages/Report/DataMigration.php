<?php

namespace App\Livewire\Tenant\Pages\Report;

use App\Models\Tenant\CompanyReportSoceCol;
use App\Models\Tenant\CompanyReportSoceItem;
use Livewire\Attributes\On;
use Exception;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use App\Models\Tenant\CompanyReport;
use App\Models\Tenant\CompanyReportAccount;
use App\Models\Tenant\CompanyReportItem;
use App\Models\Tenant\CompanyReportType;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\CompanyReportSoceRow;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DataMigration extends Component
{
    #[Locked]
    public $id;

    public $type;
    public $hideEmpty = false;

    public $company_report_type;

    public $this_year_values = [];

    public $last_year_values = [];

    public $actual_accounts = [];

    public $actual_displays = [];

    public $check_boxes = [];

    public $skin_check_boxes = [];



    public $soce_rows = [];
    public $soce_cols = [];
    public $soce_items = [];

    #[Locked]
    public $company_report_items;

    #[Locked]
    public $company_report_types;

    public function mount($id)
    {
        $this->id = $id;
        CompanyReport::findOrFail($this->id);
        $this->company_report_types = CompanyReportType::where('company_report_id', $this->id)->get();
    }

    public function render()
    {
        // return view('livewire.tenants.data-migration');
        return view('livewire.tenant.pages.report.data-migration', [
            'company_report_id' => $this->id,
        ]);
    }

    public function updatingType($value)
    {
        $this->company_report_type = CompanyReportType::find($value);
        $this->check_boxes = [];
        $this->actual_displays = [];
        $this->this_year_values = [];
        $this->last_year_values = [];
        $this->company_report_items = [];

        $this->refresh($value);
    }

    public function _updatingThisYearValues($value, $id)
    {

        if ($this->company_report_types->firstWhere('id', $this->type)->name == 'SOFP') {
            // sofp: total assets
            $asset_items = DB::select("SELECT company_report_items.id FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name IN ('CA', 'NCA') AND company_report_items.type = 'total' AND company_report_items.company_report_id = ? AND company_report_items.company_report_type_id = ?", [$this->id, $this->type]);
            $total_asset = 0;
            foreach ($asset_items as $asset_item) {
                $total_asset += (float) $this->this_year_values[$asset_item->id];
            }
            /*
            $total_asset = DB::select(
                "
                SELECT SUM(company_report_items.this_year_amount)
                FROM company_report_items
                LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id
                WHERE company_report_accounts.name IN ('CA', 'NCA') AND company_report_items.type = 'value'
                AND company_report_items.company_report_id = ? AND company_report_items.company_report_type_id = ?
                ",
                [$this->id, $this->type]
            );
            */
            $total_asset_item = CompanyReportItem::where('company_report_id', $this->id)->where('company_report_type_id', $this->type)->where('item', 'TOTAL ASSETS')->first();
            $this->this_year_values[$total_asset_item->id] = $total_asset;

            // sofp: total liabilities
            $liability_items = DB::select("SELECT company_report_items.id FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name IN ('CL', 'NCL') AND company_report_items.type = 'total' AND company_report_items.company_report_id = ? AND company_report_items.company_report_type_id = ?", [$this->id, $this->type]);
            $total_liability = 0;
            foreach ($liability_items as $liability_item) {
                $total_liability += (float) $this->this_year_values[$liability_item->id];
            }
            $total_liability_item = CompanyReportItem::where('company_report_id', $this->id)->where('company_report_type_id', $this->type)->where('item', 'TOTAL LIABILITIES')->first();
            $this->this_year_values[$total_liability_item->id] = $total_liability;

            // sofp: total equities and liabilities
            $total_equity_item = CompanyReportItem::where('company_report_id', $this->id)->where('company_report_type_id', $this->type)->where('item', 'LIKE', 'TOTAL EQUIT%')->first();
            $total_equity_liability_item = CompanyReportItem::whereRaw("company_report_id = ? AND company_report_type_id = ? AND item LIKE 'TOTAL EQUIT%' AND item LIKE '%AND LIABILIT%'", [$this->id, $this->type])->first();
            $this->this_year_values[$total_equity_liability_item->id] = (float) $this->this_year_values[$total_equity_item->id] + $total_liability;
        }

        if ($this->company_report_types->firstWhere('id', $this->type)->name == 'STSOO') {
            // stsoo: GROSS PROFIT
            $revenue_total_item = DB::select("SELECT company_report_items.id FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'REV' AND company_report_items.type = 'total' AND company_report_items.company_report_id = ? AND company_report_items.company_report_type_id = ?", [$this->id, $this->type]);
            $cost_total_item = DB::select("SELECT company_report_items.id FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'COS' AND company_report_items.type = 'total' AND company_report_items.company_report_id = ? AND company_report_items.company_report_type_id = ?", [$this->id, $this->type]);

            $gross_profit_item = CompanyReportItem::where('company_report_id', $this->id)->where('company_report_type_id', $this->type)->where('item', 'GROSS PROFIT')->first();
            $this->this_year_values[$gross_profit_item->id] = (float) $this->this_year_values[$revenue_total_item[0]->id] - (float) $this->this_year_values[$cost_total_item[0]->id];

            // stsoo: Total Operating Expenses
            $toe_item = CompanyReportItem::where('company_report_id', $this->id)->where('company_report_type_id', $this->type)->where('item', 'TOTAL OPERATING EXPENSES')->first();
            $pbtax_item = CompanyReportItem::where('company_report_id', $this->id)->where('company_report_type_id', $this->type)->where('item', 'PROFIT BEFORE TAX')->first();
            $profit_this_item = CompanyReportItem::where('company_report_id', $this->id)->where('company_report_type_id', $this->type)->where('item', 'PROFIT FOR THE YEAR')->first();
            $tae_item = DB::select("SELECT company_report_items.id FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'AE' AND company_report_items.type = 'total' AND company_report_items.company_report_id = ? AND company_report_items.company_report_type_id = ?", [$this->id, $this->type])[0];
            $tooe_item = DB::select("SELECT company_report_items.id FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'OOE' AND company_report_items.type = 'total' AND company_report_items.company_report_id = ? AND company_report_items.company_report_type_id = ?", [$this->id, $this->type])[0];
            $tooi_item = DB::select("SELECT company_report_items.id FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'OOI' AND company_report_items.type = 'total' AND company_report_items.company_report_id = ? AND company_report_items.company_report_type_id = ?", [$this->id, $this->type])[0];
            $tfc_item = DB::select("SELECT company_report_items.id FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'FC' AND company_report_items.type = 'total' AND company_report_items.company_report_id = ? AND company_report_items.company_report_type_id = ?", [$this->id, $this->type])[0];
            $ttax_item = DB::select("SELECT company_report_items.id FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'TAX' AND company_report_items.type = 'total' AND company_report_items.company_report_id = ? AND company_report_items.company_report_type_id = ?", [$this->id, $this->type])[0];
            $this->this_year_values[$toe_item->id] = (float) $this->this_year_values[$tae_item->id] + (float) $this->this_year_values[$tooe_item->id] + (float) $this->this_year_values[$tfc_item->id];
            $this->this_year_values[$pbtax_item->id] = (float) $this->this_year_values[$gross_profit_item->id] + (float) $this->this_year_values[$tooi_item->id] - (float) $this->this_year_values[$tae_item->id] - (float) $this->this_year_values[$tooe_item->id] - (float) $this->this_year_values[$tfc_item->id];
            $this->this_year_values[$profit_this_item->id] = (float) $this->this_year_values[$pbtax_item->id] - (float) $this->this_year_values[$ttax_item->id];
        }

        if ($this->company_report_types->firstWhere('id', $this->type)->name == 'SOCF') {
        } else {
            $item = CompanyReportItem::find($id);
            if ($this->check_boxes[$item->id]) {
                $group = $item->company_report_account;
                $total_item = CompanyReportItem::where('company_report_id', $this->id)->where('company_report_type_id', $item->company_report_type_id)->where('company_report_account_id', $group->id)->where('type', CompanyReportItem::TYPE_TOTAL)->first();
                // $total_group = CompanyReportItem::where('company_report_id', $this->id)->where('company_report_type_id', $this->type)->where('company_report_account_id', $group->id)->where('type', CompanyReportItem::TYPE_VALUE)->sum('this_year_amount');
                // $this->this_year_values[$total_item->id] = $this->this_year_values[$total_item->id] + $value - $item->this_year_amount;
                $this->this_year_values[$total_item->id] = (float) $this->this_year_values[$total_item->id] + (float) $value - (float) $this->this_year_values[$item->id];
            }
        }
    }

    public function updatingSkinCheckBoxes($value, $id) {
        if (!$value) {
            $this->actual_displays[$id] = null;
        }
    }

    public function save()
    {
        // try {
        if ($this->company_report_type->name === 'SOCE') {
            $total_col = CompanyReportSoceCol::where('company_report_id', $this->id)->where('name', 'Total (RM)')->first();
            foreach ($this->soce_rows as $soce_row) {
                $total = 0.0;
                foreach ($this->soce_cols as $soce_col) {
                    if ($soce_col->name === 'Total (RM)') {
                        $total_item = CompanyReportSoceItem::where('company_report_id', $this->id)->where('row_id', $soce_row->id)->where('col_id', $total_col->id)->first();
                        if (!$total_item) {
                            $total_item = new CompanyReportSoceItem();
                            $total_item->company_report_id = $this->id;
                            $total_item->row_id = $soce_row->id;
                            $total_item->col_id = $total_col->id;
                            // $total_item->value = $total;
                            // $total_item->save();
                        }
                        $total_item->value = $total;
                        $total_item->save();
                        continue;
                    }
                    if (isset($this->soce_items[$soce_row->id][$soce_col->id])) {
                        $item = CompanyReportSoceItem::where('company_report_id', $this->id)->where('row_id', $soce_row->id)->where('col_id', $soce_col->id)->first();
                        if (!$item) {
                            $item = new CompanyReportSoceItem();
                            $item->company_report_id = $this->id;
                            $item->row_id = $soce_row->id;
                            $item->col_id = $soce_col->id;
                        }
                        $item->value = $this->soce_items[$soce_row->id][$soce_col->id];
                        if ($soce_col->data_type == 'number') {
                            $item->value = readNumber($this->soce_items[$soce_row->id][$soce_col->id]);
                            $total += (float) $item->value;
                        }
                        $item->save();
                    }
                }
            }
            $this->refresh($this->type);
            $this->alert('success', 'Saved successful!');
        } else {
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
            if ($this->company_report_type->name == 'SOFP') {
                // sofp: TOTAL NON-CURRENT ASSET
                // sofp: TOTAL CURRENT ASSET
                // sofp: TOTAL EQUITY
                // sofp: TOTAL NON-CURRENT LIABILITY
                // sofp: TOTAL CURRENT LIABILITY
                foreach ($this->company_report_type->company_report_accounts as $group) {
                    $total_item = CompanyReportItem::where('company_report_type_id', $this->type)->where('company_report_account_id', $group->id)->where('type', CompanyReportItem::TYPE_TOTAL)->first();
                    $total_sum = DB::selectOne("SELECT SUM(this_year_amount) as this_year_amount, SUM(last_year_amount) as last_year_amount FROM company_report_items WHERE company_report_type_id = ? AND company_report_account_id = ? AND type = ? AND is_report = 1", [$this->type, $group->id, CompanyReportItem::TYPE_VALUE]);
                    $total_item->this_year_amount = $total_sum->this_year_amount;
                    $total_item->last_year_amount = $total_sum->last_year_amount;
                    $total_item->save();
                }
                // sofp: total assets
                $total_asset_sum = DB::selectOne("SELECT SUM(company_report_items.this_year_amount) as this_year_amount, SUM(company_report_items.last_year_amount) as last_year_amount FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name IN ('CA', 'NCA') AND company_report_items.type = 'total' AND company_report_items.company_report_type_id = ?", [$this->type]);
                $total_asset_item = CompanyReportItem::where('company_report_type_id', $this->type)->where('item', 'TOTAL ASSETS')->first();
                $total_asset_item->this_year_amount = $total_asset_sum->this_year_amount;
                $total_asset_item->last_year_amount = $total_asset_sum->last_year_amount;
                $total_asset_item->save();
                // sofp: TOTAL LIABILITIES
                $total_liability_sum = DB::selectOne("SELECT SUM(company_report_items.this_year_amount) as this_year_amount, SUM(company_report_items.last_year_amount) as last_year_amount FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name IN ('CL', 'NCL') AND company_report_items.type = 'total' AND company_report_items.company_report_type_id = ?", [$this->type]);
                $total_liability_item = CompanyReportItem::where('company_report_type_id', $this->type)->where('item', 'TOTAL LIABILITIES')->first();
                $total_liability_item->this_year_amount = $total_liability_sum->this_year_amount;
                $total_liability_item->last_year_amount = $total_liability_sum->last_year_amount;
                $total_liability_item->save();
                // sofp: TOTAL EQUITY AND LIABILITIES
                $total_equity_item = CompanyReportItem::where('company_report_type_id', $this->type)->where('item', 'LIKE', 'TOTAL EQUIT%')->first();
                $total_equity_liability_item = CompanyReportItem::whereRaw("company_report_id = ? AND company_report_type_id = ? AND item LIKE 'TOTAL EQUIT%' AND item LIKE '%AND LIABILIT%'", [$this->id, $this->type])->first();
                $total_equity_liability_item->this_year_amount = $total_equity_item->this_year_amount + $total_liability_sum->this_year_amount;
                $total_equity_liability_item->last_year_amount = $total_equity_item->last_year_amount + $total_liability_sum->last_year_amount;
                $total_equity_liability_item->save();

                // sofp: Retained Profits/(Accumulated Losses)
                $retainedProfit = CompanyReportItem::whereRaw("`company_report_id` = ? AND `company_report_type_id` = ? AND LOWER(`item`) = 'retained profits/(accumulated losses)'", [$this->id, $this->type])->first();
                if ($retainedProfit->this_year_amount > $retainedProfit->last_year_amount) {
                    // $retainedProfit->item = "Retained profits";
                    $retainedProfit->display = "Retained profits";
                    $retainedProfit->save();
                } else {
                    // $retainedProfit->item = "Accumulated losses";
                    $retainedProfit->display = "Accumulated losses";
                    $retainedProfit->save();
                }
            } elseif ($this->company_report_type->name == 'STSOO') {
                // STSOO: TOTAL Revenue
                // STSOO: TOTAL Cost of sales
                // STSOO: TOTAL Other operating income
                // STSOO: TOTAL Administrative expenses
                // STSOO: TOTAL Other operating expenses
                // STSOO: TOTAL Finance costs
                // STSOO: TOTAL Tax expenses
                foreach ($this->company_report_type->company_report_accounts as $group) {
                    $total_item = CompanyReportItem::where('company_report_type_id', $this->type)->where('company_report_account_id', $group->id)->where('type', CompanyReportItem::TYPE_TOTAL)->first();
                    if ($total_item) {
                        $total_sum = DB::selectOne("SELECT SUM(this_year_amount) as this_year_amount, SUM(last_year_amount) as last_year_amount FROM company_report_items WHERE company_report_type_id = ? AND company_report_account_id = ? AND type = ? AND is_report = 1", [$this->type, $group->id, CompanyReportItem::TYPE_VALUE]);
                        $total_item->this_year_amount = $total_sum?->this_year_amount;
                        $total_item->last_year_amount = $total_sum?->last_year_amount;
                        $total_item->save();
                    }
                }
                // STSOO: GROSS PROFIT
                $revenue_total_item = DB::selectOne("SELECT (company_report_items.this_year_amount) as this_year_amount, (company_report_items.last_year_amount) as last_year_amount FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'REV' AND company_report_items.type = 'total' AND company_report_items.company_report_type_id = ?", [$this->type]);
                $cost_total_item = DB::selectOne("SELECT (company_report_items.this_year_amount) as this_year_amount, (company_report_items.last_year_amount) as last_year_amount FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'COS' AND company_report_items.type = 'total' AND company_report_items.company_report_type_id = ?", [$this->type]);

                $gross_profit_item = CompanyReportItem::where('company_report_type_id', $this->type)->where('item', 'GROSS PROFIT')->first();
                $gross_profit_item->this_year_amount = $revenue_total_item->this_year_amount - $cost_total_item->this_year_amount;
                $gross_profit_item->last_year_amount = $revenue_total_item->last_year_amount - $cost_total_item->last_year_amount;
                $gross_profit_item->save();
                //
                $tae_item = DB::selectOne("SELECT (company_report_items.this_year_amount) as this_year_amount, (company_report_items.last_year_amount) as last_year_amount FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'AE' AND company_report_items.type = 'total' AND company_report_items.company_report_type_id = ?", [$this->type]);
                $tooe_item = DB::selectOne("SELECT (company_report_items.this_year_amount) as this_year_amount, (company_report_items.last_year_amount) as last_year_amount FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'OOE' AND company_report_items.type = 'total' AND company_report_items.company_report_type_id = ?", [$this->type]);
                $tfc_item = DB::selectOne("SELECT (company_report_items.this_year_amount) as this_year_amount, (company_report_items.last_year_amount) as last_year_amount FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'FC' AND company_report_items.type = 'total' AND company_report_items.company_report_type_id = ?", [$this->type]);
                $tooi_item = DB::selectOne("SELECT (company_report_items.this_year_amount) as this_year_amount, (company_report_items.last_year_amount) as last_year_amount FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'OOI' AND company_report_items.type = 'total' AND company_report_items.company_report_type_id = ?", [$this->type]);
                $ttax_item = DB::selectOne("SELECT (company_report_items.this_year_amount) as this_year_amount, (company_report_items.last_year_amount) as last_year_amount FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'TAX' AND company_report_items.type = 'total' AND company_report_items.company_report_type_id = ?", [$this->type]);

                // STSOO: Total Operating Expenses
                $toe_item = CompanyReportItem::where('company_report_type_id', $this->type)->where('item', 'TOTAL OPERATING EXPENSES')->first();
                $toe_item->this_year_amount = $tae_item->this_year_amount + $tooe_item->this_year_amount + $tfc_item->this_year_amount;
                $toe_item->last_year_amount = $tae_item->last_year_amount + $tooe_item->last_year_amount + $tfc_item->last_year_amount;
                $toe_item->save();

                // STSOO: PROFIT BEFORE TAX
                $pbtax_item = CompanyReportItem::where('company_report_type_id', $this->type)->where('item', 'PROFIT BEFORE TAX')->first();
                $pbtax_item->this_year_amount = $gross_profit_item->this_year_amount + $tooi_item->this_year_amount - $tae_item->this_year_amount - $tooe_item->this_year_amount - $tfc_item->this_year_amount;
                $pbtax_item->last_year_amount = $gross_profit_item->last_year_amount + $tooi_item->last_year_amount - $tae_item->last_year_amount - $tooe_item->last_year_amount - $tfc_item->last_year_amount;
                $pbtax_item->save();

                // STSOO: PROFIT FOR THE YEAR
                $profit_this_item = CompanyReportItem::where('company_report_type_id', $this->type)->where('item', 'PROFIT FOR THE YEAR')->first();
                $profit_this_item->this_year_amount = $pbtax_item->this_year_amount - $ttax_item->this_year_amount;
                $profit_this_item->last_year_amount = $pbtax_item->last_year_amount - $ttax_item->last_year_amount;;
                $profit_this_item->save();
            } elseif ($this->company_report_type->name == 'SOCF') {
                // SOCF: Operating profit before working capital changes
                $pbtax_item = CompanyReportItem::where('company_report_type_id', $this->type)->where('item', 'Profit before tax')->first();
                $opbwcc_item = CompanyReportItem::where('company_report_type_id', $this->type)->where('item', 'Operating profit before working capital changes')->first();
                $sort = substr($opbwcc_item->sort, 0, -2);
                $total_opbwcc = DB::selectOne("SELECT SUM(this_year_amount) as this_year_amount, SUM(last_year_amount) as last_year_amount FROM company_report_items WHERE company_report_type_id = ? AND sort = ? AND type = ? AND is_report = 1", [$this->type, $sort, CompanyReportItem::TYPE_VALUE]);
                $opbwcc_item->this_year_amount = ($total_opbwcc->this_year_amount??0) + ($pbtax_item->this_year_amount??0);
                $opbwcc_item->last_year_amount = ($total_opbwcc->last_year_amount??0) + ($pbtax_item->last_year_amount??0);
                $opbwcc_item->save();

                // SOCF: Net change in operations
                $net_operations_item = CompanyReportItem::where('company_report_type_id', $this->type)->where('item', 'Net change in operations')->first();
                $sort = substr($net_operations_item->sort, 0, -2);
                $total_nco = DB::selectOne("SELECT SUM(this_year_amount) as this_year_amount, SUM(last_year_amount) as last_year_amount FROM company_report_items WHERE company_report_type_id = ? AND sort = ? AND type = ? AND is_report = 1", [$this->type, $sort, CompanyReportItem::TYPE_VALUE]);
                $net_operations_item->this_year_amount = ($total_nco->this_year_amount??0) + ($opbwcc_item->this_year_amount??0);
                $net_operations_item->last_year_amount = ($total_nco->last_year_amount??0) + ($opbwcc_item->last_year_amount??0);
                $net_operations_item->save();

                // SOCF: Net change in operating activities
                $oa_group = CompanyReportAccount::where('description', 'CASH FLOWS FROM OPERATING ACTIVITIES')->first();
                $total_oa_item = CompanyReportItem::where('company_report_type_id', $this->type)->where('item', 'Net change in operating activities')->first();
                $total_sum = DB::selectOne("SELECT SUM(this_year_amount) as this_year_amount, SUM(last_year_amount) as last_year_amount FROM company_report_items WHERE company_report_type_id = ? AND company_report_account_id = ? AND type = ? AND is_report = 1", [$this->type, $oa_group->id, CompanyReportItem::TYPE_VALUE]);
                $total_oa_item->this_year_amount = $total_sum->this_year_amount;
                $total_oa_item->last_year_amount = $total_sum->last_year_amount;
                $total_oa_item->save();

                // SOCF: Net change in investing activities
                $ia_group = CompanyReportAccount::where('description', 'CASH FLOWS FROM INVESTING ACTIVITIES')->first();
                $total_ia_item = CompanyReportItem::where('company_report_type_id', $this->type)->where('item', 'Net change in investing activities')->first();
                $total_sum_ia = DB::selectOne("SELECT SUM(this_year_amount) as this_year_amount, SUM(last_year_amount) as last_year_amount FROM company_report_items WHERE company_report_type_id = ? AND company_report_account_id = ? AND type = ? AND is_report = 1", [$this->type, $ia_group->id, CompanyReportItem::TYPE_VALUE]);
                $total_ia_item->this_year_amount = $total_sum_ia->this_year_amount;
                $total_ia_item->last_year_amount = $total_sum_ia->last_year_amount;
                $total_ia_item->save();

                // SOCF: Net change in financing activities
                $fa_group = CompanyReportAccount::where('description', 'CASH FLOWS FROM FINANCING ACTIVITIES')->first();
                $total_fa_item = CompanyReportItem::where('company_report_type_id', $this->type)->where('item', 'Net change in financing activities')->first();
                $total_sum_fa = DB::selectOne("SELECT SUM(this_year_amount) as this_year_amount, SUM(last_year_amount) as last_year_amount FROM company_report_items WHERE company_report_type_id = ? AND company_report_account_id = ? AND type = ? AND is_report = 1", [$this->type, $fa_group->id, CompanyReportItem::TYPE_VALUE]);
                $total_fa_item->this_year_amount = $total_sum_fa->this_year_amount;
                $total_fa_item->last_year_amount = $total_sum_fa->last_year_amount;
                $total_fa_item->save();

                // SOCF: Cash and cash equivalents at end of the year
                $ccey_item = CompanyReportItem::where('company_report_type_id', $this->type)->where('item', 'Cash and cash equivalents at end of the year')->first();
                $net_change_cce_item = CompanyReportItem::where('company_report_type_id', $this->type)->where('item', 'Net change in cash and cash equivalents')->first();
                $ccby_item = CompanyReportItem::where('company_report_type_id', $this->type)->where('item', 'Cash and cash equivalents at beginning of the year')->first();
                $ccey_item->this_year_amount = ($net_change_cce_item?->this_year_amount??0) + ($ccby_item?->this_year_amount??0);
                $ccey_item->last_year_amount = ($net_change_cce_item?->last_year_amount??0) + ($ccby_item?->last_year_amount??0);
                $ccey_item->save();

                // SOCF: Cash and bank balances + Bank overdraft
                $last_item = CompanyReportItem::where('company_report_type_id', $this->type)->whereNull('item')->where('type', 'total')->first();
                $ccb_item = CompanyReportItem::where('company_report_type_id', $this->type)->where('item', 'Cash and bank balances')->first();
                $bo_item = CompanyReportItem::where('company_report_type_id', $this->type)->where('item', 'Bank overdraft')->first();
                $last_item->this_year_amount = ($ccb_item?->this_year_amount??0) + ($bo_item?->this_year_amount??0);
                $last_item->last_year_amount = ($ccb_item?->last_year_amount??0) + ($bo_item?->last_year_amount??0);
                $last_item->save();

            }

            $this->refresh($this->type);
            $this->alert('success', 'Saved successful!');
            // $this->redirect(DataImport::class);
        }
        // } catch (Exception $e) {
        //     info($e->getMessage());
        //     $this->alert('error', $e->getMessage());
        // }
    }

    #[On('successCreated')]
    public function successCreated()
    {
        session()->flash('success', 'Report item was created');
        $this->refresh($this->type);
    }

    private function refresh($type) {
        if ($this->company_report_type->name === 'SOCE') {
            $this->loadSOCE();
        }
        elseif ($this->company_report_type->name === 'NTFS') {
            $this->loadNTFS();
        }
        else {
            // update the UI with saved values
            $this->company_report_items = CompanyReportItem::where('company_report_type_id', $type)->orderBy('sort')->orderBy('id')->get();
            foreach ($this->company_report_items as $item) {
                $this->actual_displays[$item->id] = $item->display;
                $this->check_boxes[$item->id] = !!$item->is_report;
                $this->skin_check_boxes[$item->id] = $item->show_display;
                $this->this_year_values[$item->id] = displayNumber($item->this_year_amount);
                $this->last_year_values[$item->id] = displayNumber($item->last_year_amount);
            }
        }
    }

    private function loadSOCE() {
        $this->soce_rows = CompanyReportSoceRow::where('company_report_id', $this->id)->orderBy('sort')->get();
        $this->soce_cols = CompanyReportSoceCol::where('company_report_id', $this->id)->orderBy('sort')->get();
        $this->soce_items = [];
        foreach ($this->soce_rows as $soce_row) {
            foreach ($this->soce_cols as $soce_col) {
                $soce_item = CompanyReportSoceItem::where('company_report_id', $this->id)->where('row_id', $soce_row->id)->where('col_id', $soce_col->id)->first();
                $this->soce_items[$soce_row->id][$soce_col->id] = $soce_item?->value;
                if ($soce_col->data_type == 'number') {
                    $this->soce_items[$soce_row->id][$soce_col->id] = displayNumber($soce_item?->value);
                }
            }
        }
    }

    private function loadNTFS() {
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
