<?php

namespace App\Services\Tenant\Report;

use App\Models\Tenant\CompanyReport;
use App\Models\Tenant\CompanyReportAccount;
use App\Models\Tenant\CompanyReportType;
use App\Models\Tenant\CompanyReportItem;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Events\BeforeSheet;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DataImportService implements ToCollection, WithHeadingRow, WithEvents, WithCalculatedFormulas, HasReferencesToOtherSheets, SkipsEmptyRows
{
    public $currentSheetName;

    public function __construct(public $company_report_id)
    {
        $this->company_report_id = $company_report_id;
    }

    public function collection(Collection $rows)
    {
        $report_type = CompanyReportType::whereRaw('`company_report_id` = ? AND LOWER(`name`) LIKE ?', [$this->company_report_id, strtolower($this->currentSheetName)])->first();
        if ($report_type) {
            if ($report_type->name == "SOCF") {
                $last_company_account_id = null;
                $type = CompanyReportItem::TYPE_VALUE;
                $sort = "";
                foreach ($rows as $row) {
                    $report_wording = trim($row['report_wording']);
                    $ty = is_numeric($row['ty']) ? $row['ty'] : 0;
                    $ly = is_numeric($row['ly']) ? $row['ly'] : 0;
                    // when 'Report Wording column is not empty
                    if (($report_wording != null && $report_wording != "-" && $report_wording != "")) {

                        // When meet acc type: blue and capital 'report wording'
                        if ($report_wording === strtoupper($report_wording) && str_contains(strtoupper($report_wording), "CASH FLOW")) {
                            $report_account = CompanyReportAccount::create([
                                'company_report_id' => $this->company_report_id,
                                'company_report_type_id' => $report_type->id,
                                'name' => null,
                                'display' => null,
                                'description' => $report_wording,
                            ]);
                            CompanyReportItem::create([
                                'company_report_id' => $this->company_report_id,
                                'company_report_type_id' => $report_type->id,
                                'company_report_account_id' => $report_account->id,
                                'item' => $report_wording,
                                'type' => CompanyReportItem::TYPE_GROUP,
                                'sort' => $report_account->id,
                                'is_report' => true,
                            ]);
                            $last_company_account_id = $report_account->id;
                            $sort = "{$last_company_account_id}";
                        } else { // items

                            // label
                            if (str_contains(strtolower($report_wording), "adjustments for:")) {
                                CompanyReportItem::create([
                                    'company_report_id' => $this->company_report_id,
                                    'company_report_type_id' => $report_type->id,
                                    'company_report_account_id' => $last_company_account_id,
                                    'item' => $report_wording,
                                    'type' => CompanyReportItem::TYPE_LABEL,
                                    'sort' => $last_company_account_id,
                                    'is_report' => true,
                                ]);

                                // from now subgroup 1 (OPBWCC)
                                $sort = "{$last_company_account_id}.1";
                            }
                            // subgroup "Operating profit before working capital changes"
                            elseif (str_contains(strtolower($report_wording), "operating profit before working capital change")) {
                                $sort = "{$last_company_account_id}.1.8";
                                CompanyReportItem::create([
                                    'company_report_id' => $this->company_report_id,
                                    'company_report_type_id' => $report_type->id,
                                    'company_report_account_id' => $last_company_account_id,
                                    'item' => $report_wording,
                                    'this_year_amount' => $ty,
                                    'last_year_amount' => $ly,
                                    'type' => CompanyReportItem::TYPE_TOTAL,
                                    'sort' => $sort,
                                    'is_report' => true,
                                ]);
                                // from now subgroup 2 (NCIO)
                                $sort = "{$last_company_account_id}.2";
                            }
                            // subgroup "Net change in operations"
                            elseif (str_contains(strtolower($report_wording), "net change in operations")) {
                                $sort = "{$last_company_account_id}.2.8";
                                CompanyReportItem::create([
                                    'company_report_id' => $this->company_report_id,
                                    'company_report_type_id' => $report_type->id,
                                    'company_report_account_id' => $last_company_account_id,
                                    'item' => $report_wording,
                                    'this_year_amount' => $ty,
                                    'last_year_amount' => $ly,
                                    'type' => CompanyReportItem::TYPE_TOTAL,
                                    'sort' => $sort,
                                    'is_report' => true,
                                ]);
                                // from now subgroup 3 (NCIOA)
                                $sort = "{$last_company_account_id}.3";
                            } elseif (str_contains(strtolower($report_wording), "net change in operating activities")) {
                                $sort = "$last_company_account_id.3.8";
                                CompanyReportItem::create([
                                    'company_report_id' => $this->company_report_id,
                                    'company_report_type_id' => $report_type->id,
                                    'company_report_account_id' => $last_company_account_id,
                                    'item' => $report_wording,
                                    'this_year_amount' => $ty,
                                    'last_year_amount' => $ly,
                                    'type' => CompanyReportItem::TYPE_TOTAL,
                                    'sort' => $sort,
                                    'is_report' => true,
                                ]);
                            } elseif (str_contains(strtolower($report_wording), "net change in investing activities")) {
                                $sort = "$last_company_account_id.8";
                                CompanyReportItem::create([
                                    'company_report_id' => $this->company_report_id,
                                    'company_report_type_id' => $report_type->id,
                                    'company_report_account_id' => $last_company_account_id,
                                    'item' => $report_wording,
                                    'this_year_amount' => $ty,
                                    'last_year_amount' => $ly,
                                    'type' => CompanyReportItem::TYPE_TOTAL,
                                    'sort' => $sort,
                                    'is_report' => true,
                                ]);
                            } elseif (str_contains(strtolower($report_wording), "net change in financing activities")) {
                                $sort = "$last_company_account_id.8";
                                CompanyReportItem::create([
                                    'company_report_id' => $this->company_report_id,
                                    'company_report_type_id' => $report_type->id,
                                    'company_report_account_id' => $last_company_account_id,
                                    'item' => $report_wording,
                                    'this_year_amount' => $ty,
                                    'last_year_amount' => $ly,
                                    'type' => CompanyReportItem::TYPE_TOTAL,
                                    'sort' => $sort,
                                    'is_report' => true,
                                ]);
                            } elseif (str_contains(strtolower($report_wording), "net change in cash and cash equivalents")) {
                                $last_company_account_id = null;
                                CompanyReportItem::create([
                                    'company_report_id' => $this->company_report_id,
                                    'company_report_type_id' => $report_type->id,
                                    'company_report_account_id' => $last_company_account_id,
                                    'item' => $report_wording,
                                    'this_year_amount' => $ty,
                                    'last_year_amount' => $ly,
                                    'type' => CompanyReportItem::TYPE_TOTAL,
                                    'sort' => $sort,
                                    'is_report' => true,
                                ]);
                            } elseif (str_contains(strtolower($report_wording), "cash and cash equivalents at beginning of the year")) {
                                CompanyReportItem::create([
                                    'company_report_id' => $this->company_report_id,
                                    'company_report_type_id' => $report_type->id,
                                    'company_report_account_id' => $last_company_account_id,
                                    'item' => $report_wording,
                                    'this_year_amount' => $ty,
                                    'last_year_amount' => $ly,
                                    'type' => CompanyReportItem::TYPE_TOTAL,
                                    'sort' => $sort,
                                    'is_report' => true,
                                ]);
                            } elseif (str_contains(strtolower($report_wording), "cash and cash equivalents at end of the year")) {
                                CompanyReportItem::create([
                                    'company_report_id' => $this->company_report_id,
                                    'company_report_type_id' => $report_type->id,
                                    'company_report_account_id' => $last_company_account_id,
                                    'item' => $report_wording,
                                    'this_year_amount' => $ty,
                                    'last_year_amount' => $ly,
                                    'type' => CompanyReportItem::TYPE_TOTAL,
                                    'sort' => $sort,
                                    'is_report' => true,
                                ]);
                            } elseif (str_contains(strtolower($report_wording), "cash and bank balances")) {
                                $sort = "$sort.9";
                                CompanyReportItem::create([
                                    'company_report_id' => $this->company_report_id,
                                    'company_report_type_id' => $report_type->id,
                                    'company_report_account_id' => $last_company_account_id,
                                    'item' => $report_wording,
                                    'this_year_amount' => $ty,
                                    'last_year_amount' => $ly,
                                    'type' => $type,
                                    'sort' => $sort,
                                    'is_report' => true,
                                ]);
                            }
                            else {
                                // if (str_contains(strtolower($report_wording), "Cash and bank balances")) {
                                //     $type = CompanyReportItem::TYPE_TOTAL;
                                // }
                                // if (str_contains(strtolower($report_wording), "Bank overdraft")) {
                                //     $type = CompanyReportItem::TYPE_TOTAL;
                                // }
                                CompanyReportItem::create([
                                    'company_report_id' => $this->company_report_id,
                                    'company_report_type_id' => $report_type->id,
                                    'company_report_account_id' => $last_company_account_id,
                                    'item' => $report_wording,
                                    'this_year_amount' => $ty,
                                    'last_year_amount' => $ly,
                                    'type' => $type,
                                    'sort' => $sort,
                                    'is_report' => true,
                                ]);
                            }
                        }
                    } else { // when 'Report Wording column is empty
                        CompanyReportItem::create([
                            'company_report_id' => $this->company_report_id,
                            'company_report_type_id' => $report_type->id,
                            'company_report_account_id' => $last_company_account_id,
                            'item' => null,
                            'display' => null,
                            'this_year_amount' => $ty,
                            'last_year_amount' => $ly,
                            'type' => CompanyReportItem::TYPE_TOTAL,
                            'sort' => "{$sort}.8",
                            'is_report' => true,
                        ]);
                    }
                }
            } elseif ($report_type->name == "STSOO") {
                $last_acc_type_description = null;
                $last_acc_type_name = null;
                $last_acc_id = null;
                $last_item = null;
                for ($i = 0; $i < $rows->count(); $i++) {
                    $report_wording = trim($rows[$i]['report_wording']);
                    $ty = is_numeric($rows[$i]['ty']) ? $rows[$i]['ty'] : 0;
                    $ly = is_numeric($rows[$i]['ly']) ? $rows[$i]['ly'] : 0;
                    if ($rows[$i]['acc_type'] != null && $rows[$i]['acc_type'] != '') {
                        $report_account = CompanyReportAccount::whereRaw('`company_report_id` = ? AND LOWER(`name`) LIKE ?', [$this->company_report_id, strtolower($rows[$i]['acc_type'])])->first();

                        if ($last_acc_type_name != $rows[$i]['acc_type']) {
                            $last_item = CompanyReportItem::create([
                                'company_report_id' => $this->company_report_id,
                                'company_report_type_id' => $report_type->id,
                                'company_report_account_id' => $report_account->id,
                                'item' => $report_account->description,
                                'display' => $rows[$i - 1]['report_wording'],
                                'show_display' => true,
                                'type' => CompanyReportItem::TYPE_GROUP,
                                'sort' => $report_account->id,
                                'is_report' => true,
                            ]);
                            $last_acc_type_name = $rows[$i]['acc_type'];
                            $last_acc_type_description = $report_account->description;
                            $last_acc_id = $report_account->id;
                        }

                        $last_item = CompanyReportItem::create([
                            'company_report_id' => $this->company_report_id,
                            'company_report_type_id' => $report_type->id,
                            'company_report_account_id' => $report_account->id,
                            'item' => $report_wording,
                            'this_year_amount' => $ty,
                            'last_year_amount' => $ly,
                            'type' => CompanyReportItem::TYPE_VALUE,
                            'sort' => $report_account->id,
                            'is_report' => true,
                        ]);
                    } elseif ($report_wording == null || $report_wording == '') {
                        $last_item = CompanyReportItem::create([
                            'company_report_id' => $this->company_report_id,
                            'company_report_type_id' => $report_type->id,
                            'company_report_account_id' => $last_acc_id,
                            'item' => null,
                            'display' => null,
                            'this_year_amount' => $ty,
                            'last_year_amount' => $ly,
                            'type' => CompanyReportItem::TYPE_TOTAL,
                            'sort' => "{$last_acc_id}.8",
                            'is_report' => true,
                        ]);
                    } elseif (($ty != 0) || ($ly != 0)) {
                        CompanyReportItem::create([
                            'company_report_id' => $this->company_report_id,
                            'company_report_type_id' => $report_type->id,
                            'company_report_account_id' => $last_acc_id,
                            'item' => $report_wording,
                            'this_year_amount' => $ty,
                            'last_year_amount' => $ly,
                            'type' => CompanyReportItem::TYPE_TOTAL,
                            'sort' => "{$last_acc_id}.9",
                            'is_report' => true,
                        ]);
                    }
                }
            } elseif ($report_type->name == "SOFP") {
                $last_acc_type_description = null;
                $last_acc_type_name = null;
                $last_acc_id = null;
                for ($i = 0; $i < $rows->count(); $i++) {
                    $ty = is_numeric($rows[$i]['ty']) ? $rows[$i]['ty'] : 0;
                    $ly = is_numeric($rows[$i]['ly']) ? $rows[$i]['ly'] : 0;
                    $report_wording = trim($rows[$i]['report_wording']);
                    if (($report_wording != null && $report_wording != '') && ($rows[$i]['acc_type'] == null || $rows[$i]['acc_type'] == '-') && (($rows[$i]['ty'] != null && $rows[$i]['ty'] != '-' && $rows[$i]['ty'] != '') || ($rows[$i]['ly'] != null && $rows[$i]['ly'] != '-' && $rows[$i]['ly'] != ''))) {
                        $report_item_gt = CompanyReportItem::whereRaw('`company_report_id` = ? AND `company_report_type_id` = ? AND LOWER(`item`) LIKE ?', [$this->company_report_id, $report_type->id, strtolower($report_wording)])->first();
                        if ($report_item_gt) {
                            $report_item_gt->this_year_amount = $ty;
                            $report_item_gt->last_year_amount = $ly;
                            $report_item_gt->save();
                        }
                    }
                    if (($rows[$i]['acc_type'] == null || $rows[$i]['acc_type'] == '-') && ($report_wording == null || $report_wording == '')) {
                        $report_item_t = CompanyReportItem::whereRaw('`company_report_id` = ? AND `company_report_account_id` = ? AND `type` = ? AND LOWER(`item`) LIKE ?', [$this->company_report_id, $last_acc_id, CompanyReportItem::TYPE_TOTAL, strtolower("total $last_acc_type_description")])->first();
                        $report_item_t->this_year_amount = $ty;
                        $report_item_t->last_year_amount = $ly;
                        $report_item_t->save();
                    }

                    if ($rows[$i]['acc_type'] != null && $rows[$i]['acc_type'] != '-') {
                        $report_account = CompanyReportAccount::whereRaw('`company_report_id` = ? AND LOWER(`name`) LIKE ?', [$this->company_report_id, strtolower($rows[$i]['acc_type'])])->first();

                        if ($last_acc_type_name != $rows[$i]['acc_type']) {
                            $report_item_1 = CompanyReportItem::whereRaw('`company_report_id` = ? AND `company_report_account_id` = ? AND `type` = ?', [$this->company_report_id, $report_account->id, CompanyReportItem::TYPE_GROUP])->first();
                            $report_item_1->display = $rows[$i - 1]['report_wording'];
                            $report_item_1->show_display = true;
                            $report_item_1->save();
                            $last_acc_type_name = $rows[$i]['acc_type'];
                            $last_acc_type_description = $report_account->description;
                            $last_acc_id = $report_account->id;
                        }
                        $report_item = CompanyReportItem::whereRaw('`company_report_id` = ? AND `company_report_account_id` = ? AND LOWER(`item`) LIKE ?', [$this->company_report_id, $report_account->id, strtolower($rows[$i]['main_group'])])->first();
                        $report_item->display = $report_wording;
                        $report_item->show_display = true;
                        $report_item->this_year_amount = $ty;
                        $report_item->last_year_amount = $ly;
                        $report_item->is_report = true;
                        $report_item->save();
                    }
                }
            }
        }
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $this->currentSheetName = $event->getSheet()->getTitle();
            },
        ];
    }
}
