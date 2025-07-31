<?php

namespace App\Livewire\Tenant\Pages\Report;

use App\Models\Tenant\CompanyReportSoceCol;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Services\Tenant\Report\DataImportService;
use App\Models\Tenant\CompanyReport;
use App\Models\Tenant\CompanyReportItem;
use App\Models\Central\ReportType;
use App\Models\Tenant\CompanyReportAccount;
use App\Models\Tenant\CompanyReportSoceRow;
use App\Models\Tenant\CompanyReportType;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Carbon\Carbon;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class DataImport extends Component
{
    use WithFileUploads;
    use WithPagination;

    #[Validate('required|file|mimes:xlsx,xls,csv')]
    public $importFile;
    public $name;

    #[Locked]
    public $id;

    public function mount($id)
    {
        $this->id = $id;
    }

    public function render()
    {
        $company_reports = CompanyReport::orderBy('created_at', 'desc')->where('company_id', $this->id)->get();

        return view('livewire.tenant.pages.report.data-import', [
            'company_reports' => $company_reports
        ]);
    }

    public function importData()
    {
        // try {
        $this->validate();
        $stored = Storage::putFile('uploads', $this->importFile);
        $company_report = CompanyReport::create([
            'company_id' => $this->id,
            'file_path' => $stored,
        ]);
        $report_types = ReportType::with('report_accounts.report_groups')->get();

        foreach ($report_types as $report_type) {
            $report_accounts = $report_type->report_accounts;
            $crt = CompanyReportType::create([
                'company_report_id' => $company_report->id,
                'name' => $report_type->name,
                'full_name' => $report_type->full_name,
            ]);

            foreach ($report_accounts as $report_account) {
                $groups = $report_account->report_groups;
                $cra = CompanyReportAccount::create([
                    'company_report_id' => $company_report->id,
                    'company_report_type_id' => $crt->id,
                    'name' => $report_account->name,
                    'description' => $report_account->description,
                ]);

                if ($crt->name == "SOFP") {
                    CompanyReportItem::create([
                        'company_report_id' => $company_report->id,
                        'company_report_type_id' => $crt->id,
                        'company_report_account_id' => $cra->id,
                        'item' => $cra->description,
                        'type' => CompanyReportItem::TYPE_GROUP,
                        'sort' => $cra->id,
                        'is_report' => true,
                    ]);
                }

                foreach ($groups as $group) {
                    CompanyReportItem::create([
                        'company_report_id' => $company_report->id,
                        'company_report_type_id' => $crt->id,
                        'company_report_account_id' => $cra->id,
                        'item' => $group->description,
                        'type' => CompanyReportItem::TYPE_VALUE,
                        'sort' => $cra->id,
                        'is_report' => false,
                    ]);
                }

                if ($groups->count()) {
                    CompanyReportItem::create([
                        'company_report_id' => $company_report->id,
                        'company_report_type_id' => $crt->id,
                        'company_report_account_id' => $cra->id,
                        'item' => strtoupper("Total $cra->description"),
                        'type' => CompanyReportItem::TYPE_TOTAL,
                        'sort' => $cra->id,
                        'is_report' => true,
                    ]);
                }

                if ($report_account->description == "Current asset") {
                    CompanyReportItem::create([
                        'company_report_id' => $company_report->id,
                        'company_report_type_id' => $crt->id,
                        'company_report_account_id' => null,
                        'item' => strtoupper("Total assets"),
                        'type' => CompanyReportItem::TYPE_TOTAL,
                        'sort' => $cra->id,
                        'is_report' => true,
                    ]);
                }

                if ($report_account->description == "Current liability") {
                    CompanyReportItem::insert([
                        [
                            'company_report_id' => $company_report->id,
                            'company_report_type_id' => $crt->id,
                            'company_report_account_id' => null,
                            'item' => strtoupper("Total liabilities"),
                            'type' => CompanyReportItem::TYPE_TOTAL,
                            'sort' => $cra->id,
                            'is_report' => true,
                        ],
                        [
                            'company_report_id' => $company_report->id,
                            'company_report_type_id' => $crt->id,
                            'company_report_account_id' => null,
                            'item' => strtoupper("Total equity and liabilities"),
                            'type' => CompanyReportItem::TYPE_TOTAL,
                            'sort' => $cra->id,
                            'is_report' => true,
                        ]
                    ]);
                }
            }
        }

        Excel::import(new DataImportService($company_report->id), $this->importFile);

        $soci_type_id = CompanyReportType::where('company_report_id', $company_report->id)->where('name', 'SOCI')->first()->id;
        $stsoo_type_id = CompanyReportType::where('company_report_id', $company_report->id)->where('name', 'STSOO')->first()->id;
        $total_revenue = DB::select("SELECT SUM(company_report_items.this_year_amount) as this_year, SUM(company_report_items.last_year_amount) as last_year FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'REV' AND company_report_items.type = 'value' AND company_report_items.company_report_id = ? AND company_report_items.company_report_type_id = ?", [$company_report->id, $stsoo_type_id])[0];
        $total_costsales = DB::select("SELECT SUM(company_report_items.this_year_amount) as this_year, SUM(company_report_items.last_year_amount) as last_year FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'COS' AND company_report_items.type = 'value' AND company_report_items.company_report_id = ? AND company_report_items.company_report_type_id = ?", [$company_report->id, $stsoo_type_id])[0];
        $total_ooi = DB::select("SELECT SUM(company_report_items.this_year_amount) as this_year, SUM(company_report_items.last_year_amount) as last_year FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'OOI' AND company_report_items.type = 'value' AND company_report_items.company_report_id = ? AND company_report_items.company_report_type_id = ?", [$company_report->id, $stsoo_type_id])[0];
        $total_ae = DB::select("SELECT SUM(company_report_items.this_year_amount) as this_year, SUM(company_report_items.last_year_amount) as last_year FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'AE' AND company_report_items.type = 'value' AND company_report_items.company_report_id = ? AND company_report_items.company_report_type_id = ?", [$company_report->id, $stsoo_type_id])[0];
        $total_ooe = DB::select("SELECT SUM(company_report_items.this_year_amount) as this_year, SUM(company_report_items.last_year_amount) as last_year FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'OOE' AND company_report_items.type = 'value' AND company_report_items.company_report_id = ? AND company_report_items.company_report_type_id = ?", [$company_report->id, $stsoo_type_id])[0];
        $total_fc = DB::select("SELECT SUM(company_report_items.this_year_amount) as this_year, SUM(company_report_items.last_year_amount) as last_year FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'FC' AND company_report_items.type = 'value' AND company_report_items.company_report_id = ? AND company_report_items.company_report_type_id = ?", [$company_report->id, $stsoo_type_id])[0];
        $total_tax = DB::select("SELECT SUM(company_report_items.this_year_amount) as this_year, SUM(company_report_items.last_year_amount) as last_year FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'TAX' AND company_report_items.type = 'value' AND company_report_items.company_report_id = ? AND company_report_items.company_report_type_id = ?", [$company_report->id, $stsoo_type_id])[0];
        $total_sde = DB::select("SELECT SUM(company_report_items.this_year_amount) as this_year, SUM(company_report_items.last_year_amount) as last_year FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'SDE' AND company_report_items.type = 'value' AND company_report_items.company_report_id = ? AND company_report_items.company_report_type_id = ?", [$company_report->id, $stsoo_type_id])[0];
        $total_grossprofit = CompanyReportItem::whereRaw("`company_report_id` = ? AND `company_report_type_id` = ? AND LOWER(`item`) = 'gross profit'", [$company_report->id, $stsoo_type_id])->first();
        $total_profitpoerations = ['this_year' => ($total_grossprofit->this_year_amount + $total_ooi->this_year - $total_ae->this_year - $total_sde->this_year - $total_ooe->this_year), 'last_year' => ($total_grossprofit->last_year_amount + $total_ooi->last_year - $total_ae->last_year - $total_sde->last_year - $total_ooe->last_year)];
        $total_profitbeforetax = ['this_year' => $total_profitpoerations['this_year'] - $total_fc->this_year, 'last_year' => $total_profitpoerations['last_year'] - $total_fc->last_year];
        $total_profittotal = ['this_year' => $total_profitbeforetax['this_year'] - $total_tax->this_year, 'last_year' => $total_profitbeforetax['last_year'] - $total_tax->last_year];
        CompanyReportItem::insert(
        [
            [
                'company_report_id' => $company_report->id,
                'company_report_type_id' => $soci_type_id,
                'company_report_account_id' => null,
                'item' => 'Revenue',
                'this_year_amount' => $total_revenue->this_year,
                'last_year_amount' => $total_revenue->last_year,
                'type' => CompanyReportItem::TYPE_VALUE,
                'sort' => '1',
                'is_report' => true,
            ],
            [
                'company_report_id' => $company_report->id,
                'company_report_type_id' => $soci_type_id,
                'company_report_account_id' => null,
                'item' => 'Cost of sales',
                'this_year_amount' => $total_costsales->this_year,
                'last_year_amount' => $total_costsales->last_year,
                'type' => CompanyReportItem::TYPE_VALUE,
                'sort' => '1',
                'is_report' => true,
            ],
            [
                'company_report_id' => $company_report->id,
                'company_report_type_id' => $soci_type_id,
                'company_report_account_id' => null,
                'item' => 'Gross profit',
                'this_year_amount' => $total_grossprofit['this_year_amount'],
                'last_year_amount' => $total_grossprofit['last_year_amount'],
                'type' => CompanyReportItem::TYPE_VALUE,
                'sort' => '1',
                'is_report' => true,
            ],
            [
                'company_report_id' => $company_report->id,
                'company_report_type_id' => $soci_type_id,
                'company_report_account_id' => null,
                'item' => 'Other operating income',
                'this_year_amount' => $total_ooi->this_year,
                'last_year_amount' => $total_ooi->last_year,
                'type' => CompanyReportItem::TYPE_VALUE,
                'sort' => '1',
                'is_report' => true,
            ],
            [
                'company_report_id' => $company_report->id,
                'company_report_type_id' => $soci_type_id,
                'company_report_account_id' => null,
                'item' => 'Administrative expenses',
                'this_year_amount' => $total_ae->this_year,
                'last_year_amount' => $total_ae->last_year,
                'type' => CompanyReportItem::TYPE_VALUE,
                'sort' => '1',
                'is_report' => true,
            ],
            [
                'company_report_id' => $company_report->id,
                'company_report_type_id' => $soci_type_id,
                'company_report_account_id' => null,
                'item' => 'Selling and distribution expenses',
                'this_year_amount' => $total_sde->this_year,
                'last_year_amount' => $total_sde->last_year,
                'type' => CompanyReportItem::TYPE_VALUE,
                'sort' => '1',
                'is_report' => true,
            ],
            [
                'company_report_id' => $company_report->id,
                'company_report_type_id' => $soci_type_id,
                'company_report_account_id' => null,
                'item' => 'Other operating expenses',
                'this_year_amount' => $total_ooe->this_year,
                'last_year_amount' => $total_ooe->last_year,
                'type' => CompanyReportItem::TYPE_VALUE,
                'sort' => '1',
                'is_report' => true,
            ],
            [
                'company_report_id' => $company_report->id,
                'company_report_type_id' => $soci_type_id,
                'company_report_account_id' => null,
                'item' => 'Profit from operations',
                'this_year_amount' => $total_profitpoerations['this_year'],
                'last_year_amount' => $total_profitpoerations['last_year'],
                'type' => CompanyReportItem::TYPE_VALUE,
                'sort' => '1',
                'is_report' => true,
            ],
            [
                'company_report_id' => $company_report->id,
                'company_report_type_id' => $soci_type_id,
                'company_report_account_id' => null,
                'item' => 'Finance costs',
                'this_year_amount' => $total_fc->this_year,
                'last_year_amount' => $total_fc->last_year,
                'type' => CompanyReportItem::TYPE_VALUE,
                'sort' => '1',
                'is_report' => true,
            ],
            [
                'company_report_id' => $company_report->id,
                'company_report_type_id' => $soci_type_id,
                'company_report_account_id' => null,
                'item' => 'Profit before tax',
                'this_year_amount' => $total_profitbeforetax['this_year'],
                'last_year_amount' => $total_profitbeforetax['last_year'],
                'type' => CompanyReportItem::TYPE_VALUE,
                'sort' => '1',
                'is_report' => true,
            ],
            [
                'company_report_id' => $company_report->id,
                'company_report_type_id' => $soci_type_id,
                'company_report_account_id' => null,
                'item' => 'Tax expense',
                'this_year_amount' => $total_tax->this_year,
                'last_year_amount' => $total_tax->last_year,
                'type' => CompanyReportItem::TYPE_VALUE,
                'sort' => '1',
                'is_report' => true,
            ],
            [
                'company_report_id' => $company_report->id,
                'company_report_type_id' => $soci_type_id,
                'company_report_account_id' => null,
                'item' => 'Profit for the financial year representing total comprehensive income for the financial year',
                'this_year_amount' => $total_profittotal['this_year'],
                'last_year_amount' => $total_profittotal['last_year'],
                'type' => CompanyReportItem::TYPE_VALUE,
                'sort' => '1',
                'is_report' => true,
            ],
        ]);

        // to check wording(s) in sofp
        $sofp_type_id = CompanyReportType::where('company_report_id', $company_report->id)->where('name', 'SOFP')->first()->id;
        $nca_account = CompanyReportAccount::whereRaw("`company_report_id` = ? AND `company_report_type_id` = ? AND LOWER(`name`) = 'nca'", [$company_report->id, $sofp_type_id])->first();
        $nca = CompanyReportItem::whereRaw("`company_report_account_id` = ? AND `type` = 'group'", [$nca_account->id])->first();
        $nca_count = CompanyReportItem::whereRaw("`company_report_account_id` = ? AND `type` = 'value'", [$nca_account->id])->count();
        if ($nca_count > 1) {
            $nca_total = CompanyReportItem::whereRaw("`company_report_account_id` = ? AND `type` = 'total'", [$nca_account->id])->first();
            $nca_total->item = strtoupper("Total non-current assets");
            $nca_total->save();

            $nca->item = "Non-current assets";
            $nca->display = strtoupper("Non-current assets");
            $nca->save();
        }
        $ca_account = CompanyReportAccount::whereRaw("`company_report_id` = ? AND `company_report_type_id` = ? AND LOWER(`name`) = 'ca'", [$company_report->id, $sofp_type_id])->first();
        $ca = CompanyReportItem::whereRaw("`company_report_account_id` = ? AND `type` = 'group'", [$ca_account->id])->first();
        $ca_count = CompanyReportItem::whereRaw("`company_report_account_id` = ? AND `type` = 'value'", [$ca_account->id])->count();
        if ($ca_count > 1) {
            $ca_total = CompanyReportItem::whereRaw("`company_report_account_id` = ? AND `type` = 'total'", [$ca_account->id])->first();
            $ca_total->item = strtoupper("Total current assets");
            $ca_total->save();

            $ca->item = "Current assets";
            $ca->display = strtoupper("Current assets");
            $ca->save();
        }
        $ncl_account = CompanyReportAccount::whereRaw("`company_report_id` = ? AND `company_report_type_id` = ? AND LOWER(`name`) = 'ncl'", [$company_report->id, $sofp_type_id])->first();
        $ncl = CompanyReportItem::whereRaw("`company_report_account_id` = ? AND `type` = 'group'", [$ncl_account->id])->first();
        $ncl_count = CompanyReportItem::whereRaw("`company_report_account_id` = ? AND `type` = 'value'", [$ncl_account->id])->count();
        if ($ncl_count > 1) {
            $ncl_total = CompanyReportItem::whereRaw("`company_report_account_id` = ? AND `type` = 'total'", [$ncl_account->id])->first();
            $ncl_total->item = strtoupper("Total non-current liabilities");
            $ncl_total->save();

            $ncl->item = "Non-current liabilities";
            $ncl->display = strtoupper("Non-current liabilities");
            $ncl->save();
        }
        $cl_account = CompanyReportAccount::whereRaw("`company_report_id` = ? AND `company_report_type_id` = ? AND LOWER(`name`) = 'cl'", [$company_report->id, $sofp_type_id])->first();
        $cl = CompanyReportItem::whereRaw("`company_report_account_id` = ? AND `type` = 'group'", [$cl_account->id])->first();
        $cl_count = CompanyReportItem::whereRaw("`company_report_account_id` = ? AND `type` = 'value'", [$cl_account->id])->count();
        if ($cl_count > 1) {
            $cl_total = CompanyReportItem::whereRaw("`company_report_account_id` = ? AND `type` = 'total'", [$cl_account->id])->first();
            $cl_total->item = strtoupper("Total current liabilities");
            $cl_total->save();

            $cl->item = "Current liabilities";
            $cl->display = strtoupper("Current liabilities");
            $cl->save();
        }

        $retainedProfit = CompanyReportItem::whereRaw("`company_report_id` = ? AND `company_report_type_id` = ? AND LOWER(`item`) = 'retained profits/(accumulated losses)'", [$company_report->id, $sofp_type_id])->first();
        if ($retainedProfit->this_year_amount > $retainedProfit->last_year_amount) {
            // $retainedProfit->item = "Retained profits";
            $retainedProfit->display = "Retained profits";
            $retainedProfit->save();
        } else {
            // $retainedProfit->item = "Accumulated losses";
            $retainedProfit->display = "Accumulated losses";
            $retainedProfit->save();
        }

        // soce
        $last_year_from = $company_report->company->last_year_from;
        $last_year_to = $company_report->company->last_year_to;
        $current_year_from = $company_report->company->current_year_from;
        $current_year_to = $company_report->company->current_year_to;
        // Carbon\Carbon::parse($current_year_end['account_closing_date'])->format('j M Y')
        CompanyReportSoceRow::insert(
            [
            [
                'name' => 'As at ' . Carbon::parse($last_year_from)->format('j M Y'),
                'company_report_id' =>  $company_report->id,
                'sort' => '0',
            ],
            [
                'name' => 'Issuance of shares',
                'company_report_id' =>  $company_report->id,
                'sort' => '0.1',
            ],
            [
                'name' => 'Profit for the year',
                'company_report_id' =>  $company_report->id,
                'sort' => '0.2',
            ],
            [
                'name' => 'Dividends',
                'company_report_id' =>  $company_report->id,
                'sort' => '0.3',
            ],
            [
                'name' => 'As at '.Carbon::parse($last_year_to)->format('j M Y'),
                'company_report_id' =>  $company_report->id,
                'sort' => '1',
            ],
            [
                'name' => 'Issuance of shares',
                'company_report_id' =>  $company_report->id,
                'sort' => '1.1',
            ],
            [
                'name' => 'Profit for the year',
                'company_report_id' =>  $company_report->id,
                'sort' => '1.2',
            ],
            [
                'name' => 'Dividends',
                'company_report_id' =>  $company_report->id,
                'sort' => '1.3',
            ],
            [
                'name' => 'As at ' . Carbon::parse($current_year_to)->format('j M Y'),
                'company_report_id' =>  $company_report->id,
                'sort' => '2',
            ]
        ]);

        CompanyReportSoceCol::insert(
            [
                [
                    'name' => 'Note',
                    'company_report_id' =>  $company_report->id,
                    'data_type' => 'text',
                    'sort' => '1',
                ],
                [
                    'name' => 'Share capital (RM)',
                    'company_report_id' =>  $company_report->id,
                    'data_type' => 'number',
                    'sort' => '2',
                ],
                [
                    'name' => 'Retained earnings (RM)',
                    'company_report_id' =>  $company_report->id,
                    'data_type' => 'number',
                    'sort' => '3',
                ],
                [
                    'name' => 'Total (RM)',
                    'company_report_id' =>  $company_report->id,
                    'data_type' => 'number',
                    'sort' => '9',
                ]
        ]);
        LivewireAlert::withOptions([
            'position' => 'top-end',
            'icon' => 'success',
            'title' => 'Import successful!',
            'showConfirmButton' => false,
            'timer' => 1500
        ])->show();
    }
}
