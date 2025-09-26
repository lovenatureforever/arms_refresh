<?php

namespace App\Http\Controllers\Tenant;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

use App\Http\Controllers\Controller;
use App\Models\Tenant\CompanyReport;
use App\Models\Tenant\CompanyReportItem;
use App\Models\Tenant\CompanyReportType;
use App\Models\Tenant\CompanyReportAccount;
use App\Models\Tenant\CompanyDividendChange;
use App\Models\Tenant\CompanyDirector;
use App\Models\Tenant\CompanySecretary;
use App\Models\Tenant\CompanyDirectorChange;
use App\Models\Tenant\CompanyShareCapitalChange;
use App\Models\Tenant\CompanyAddressChange;
use App\Models\Tenant\DirectorReportConfig;
use App\Models\Tenant\NtfsConfigItem;
use App\Models\Tenant\CompanyShareholder;
use App\Models\Tenant\CompanyShareholderChange;

class CompanyReportController extends Controller
{
    public function test()
    {
        return view('livewire.tenant.pages.report.test');
    }
    public function viewFinancialReport($id)
    {
        $report = CompanyReport::find($id);

        $company = $report->company;
        $prior_company_name = $company->detailAtStart()?->name;
        $business_nature = $company->businessAtLast();
        $declared_dividends = CompanyDividendChange::where('company_id', $company->id)
            ->where('is_declared', true)
            ->whereBetween('effective_date', [$company->current_year_from, $company->end_date_report])
            ->get();
        $proposed_dividends = CompanyDividendChange::where('company_id', $company->id)
            ->where('is_declared', false)
            ->whereBetween('effective_date', [$company->current_year_from, $company->end_date_report])
            ->get();
        $share_capitals = $company->sharecapitalChangesCurrentYear();
        $date = $company->end_date_report;
        $directors = CompanyDirector::with([
            'changes' => function ($query) use ($date) {
                $query->where('effective_date', '<=', $date)->latest('effective_date');
            }
        ])->where('company_id', $company->id)
            ->orderBy('sort')
            ->get();
        $prior_ordinary_share = $company->ordinaryShareCapitalAtStart();
        $prior_preference_share = $company->preferenceShareCapitalAtStart();
        $current_ordinary_shares = CompanyShareCapitalChange::whereBetween('effective_date', [$company->current_year_from, $company->current_year_to])->where('share_type', CompanyShareCapitalChange::SHARETYPE_ORDINARY)->get();
        $current_preference_shares = CompanyShareCapitalChange::whereBetween('effective_date', [$company->current_year_from, $company->current_year_to])->where('share_type', CompanyShareCapitalChange::SHARETYPE_PREFERENCE)->get();
        $business_address = $company->bizAddressAtLast($company->end_date_report);
        $prior_business_address = $company->bizAddressAtStart();
        $statement_directors = CompanyDirector::where('company_id', $company->id)
            ->where('is_active', true)
            ->where('is_rep_statement', true)
            ->whereHas('changes', function ($query) use ($company) {
                $query->where('effective_date', '<=', $company->end_date_report);
            })
            ->orderBy('sort')
            ->get();
        $report_setting = $company->reportSetting;
        $statutory_director = CompanyDirector::where('company_id', $company->id)
            ->where('is_active', true)
            ->where('is_rep_statutory', true)
            ->whereHas('changes', function ($query) use ($company) {
                $query->where('effective_date', '<=', $company->end_date_report);
            })
            ->first();

        $statement_director_names = $statement_directors->pluck('name')->toArray();
        $statutory_director_name = $statutory_director ? $statutory_director->pluck('name', 'id_no')->toArray() : [];
        $statutory_director_name = collect($statutory_director_name)->flatten();
        $shareholders_count = CompanyShareholder::where('company_id', $company->id)->count();

        $shareholders_data = [
            CompanyShareholderChange::SHARETYPE_ORDINARY => [],
            CompanyShareholderChange::SHARETYPE_PREFERENCE => [],
        ];
        $results = DB::select('
            SELECT
                s.`id`,
                s.`company_director_id`,
                s.`name`,
                s.`type`,
                sc.`change_nature`,
                sc.`share_type`,
                SUM(sc.`shares`) as `shares`,
                sc.`effective_date`
            FROM `company_shareholders` s
            JOIN `company_shareholder_changes` sc
                ON s.`id` = sc.`company_shareholder_id`
            WHERE s.`company_id` = ?
            AND sc.`effective_date` <= ?
            GROUP BY s.`id`, s.`type`, s.`company_director_id`, s.`name`, sc.`change_nature`, sc.`share_type`, sc.`effective_date`
            ORDER BY sc.`share_type`, s.`name`
        ', [$company->id, $company->current_year_to]);

        foreach ($results as $row) {
            $shareType = $row->share_type;
            $name = $row->name;

            // Initialize if not set
            if (!isset($shareholders_data[$shareType][$name])) {
                $shareholders_data[$shareType][$name] = [
                    'id' => $row->id,
                    'name' => $name,
                    'bf' => 0,
                    'bought' => 0,
                    'sold' => 0,
                    'cf' => 0,
                ];
            }
            $tmp = &$shareholders_data[$shareType][$name];

            // Determine action
            if (Carbon::parse($row->effective_date)->lt($company->current_year_from)) {
                $tmp['bf'] += $row->shares;
            } elseif (
                $row->change_nature === CompanyShareholderChange::CHANGE_NATURE_ALLOT ||
                $row->change_nature === CompanyShareholderChange::CHANGE_NATURE_TRANSFER_IN
            ) {
                $tmp['bought'] += $row->shares;
            } elseif (
                $row->change_nature === CompanyShareholderChange::CHANGE_NATURE_TRANSFER_OUT ||
                $row->change_nature === CompanyShareholderChange::CHANGE_NATURE_REDUCTION
            ) {
                $tmp['sold'] += $row->shares;
            }
            $tmp['cf'] = $tmp['bf'] + $tmp['bought'] - $tmp['sold'];
        }

        # Report data
        // DRY loading for report types
        $reportTypes = ['SOFP', 'SOCI', 'STSOO', 'SOCF'];
        $reportTypeModels = [];
        $reportTypeItems = [];
        foreach ($reportTypes as $type) {
            $model = CompanyReportType::where('company_report_id', $id)->where('name', $type)->first();
            $reportTypeModels[$type] = $model;
            $reportTypeItems[$type] = $model ? $model->company_report_items()->where('is_report', 1)->orderBy('sort')->orderBy('id')->get() : collect();
        }
        $sofp_type = $reportTypeModels['SOFP'];
        $soci_type = $reportTypeModels['SOCI'];
        $sofp = $reportTypeItems['SOFP'];
        $soci = $reportTypeItems['SOCI'];
        $stsoo = $reportTypeItems['STSOO'];
        $socf = $reportTypeItems['SOCF'];

        $tax_account = CompanyReportAccount::where('name', 'TAX')->where('company_report_id', $report->id)->first();
        $tax_expenses = ($stsoo && $tax_account) ? $stsoo->where('company_report_account_id', $tax_account->id)->where('type', 'value') : collect();

        $ae_account = CompanyReportAccount::where('company_report_id', $id)->where('name', 'AE')->first();
        $director_renumerations = $ae_account ? $ae_account->company_report_items()->whereRaw("item LIKE '%Director%' AND is_report = 1")->get() : collect();

        $sign_position = $company->reportSetting?->cover_sign_position;
        $sign_director = $sign_position === "Director" ? $company->reportSetting->cover_sign_name : null;
        $sign_secretary = $sign_position === "Secretary" ? $company->reportSetting->cover_sign_name : null;
        if ($sign_director) {
            $director = CompanyDirector::find($sign_director);
            $signed_by = ['name' => $director->name, 'type' => 'Director', 'title' => $company->reportSetting->cover_signature_position];
        } elseif ($sign_secretary) {
            $secretary = CompanySecretary::find($sign_secretary);
            $signed_by = ['name' => $secretary->name, 'type' => 'Secretary', 'id_no' => $company->reportSetting->cover_sign_secretary_no, 'title' => $company->reportSetting->cover_signature_position];
        } else {
            $signed_by = null;
        }
        // Log::debug("x: ", [$soci->where('item', 'LIKE', 'Profit for the financial year%')]);

        // SOCE data
        // $sc = collect(isset($sofp_equity_data['EQUITY']) ? $sofp_equity_data['EQUITY'] : $sofp_equity_data['EQUITIES'])->where('group_name', 'Share capital')->all();
        // $sc = DB::select("SELECT company_report_items.id FROM company_report_items LEFT JOIN company_report_accounts ON company_report_items.company_report_account_id = company_report_accounts.id WHERE company_report_accounts.name = 'EQ' AND company_report_items.item = 'Share capital' AND company_report_items.company_report_id = ? AND company_report_items.company_report_type_id = ?", [$id, $sofp_type->id]);
        $sc = CompanyReportItem::whereRaw("`item` = 'Share capital' AND `company_report_id` = ? AND `company_report_type_id` = ?", [$id, $sofp_type->id])->get();
        $soci_total_profit = CompanyReportItem::whereRaw("`item` = 'Profit for the financial year representing total comprehensive income for the financial year' AND `company_report_id` = ? AND `company_report_type_id` = ?", [$id, $soci_type->id])->first();
        $last_sc_sum = $sc->sum('last_year_amount');
        $this_sc_sum = $sc->sum('this_year_amount');
        // $rp = collect(isset($sofp_equity_data['EQUITY']) ? $sofp_equity_data['EQUITY'] : $sofp_equity_data['EQUITIES'])->where('group_name', 'Retained profits/(Accumulated losses)')->all();
        $rp = CompanyReportItem::whereRaw("`item` = 'Retained profits/(Accumulated losses)' AND `company_report_id` = ? AND `company_report_type_id` = ?", [$id, $sofp_type->id])->get();
        $last_rp_sum = $rp->sum('last_year_amount');
        $this_rp_sum = $rp->sum('this_year_amount');
        $last_rp_value = floatval($last_rp_sum) + floatval($soci_total_profit['last_year_amount']);
        $this_rp_value = floatval($soci_total_profit['this_year_amount']) - floatval($last_rp_sum);
        $last_sc_value = floatval($last_sc_sum) - 0;
        $total_last_year = floatval($last_rp_sum) - floatval($last_sc_sum);
        $total_this_year = floatval($this_sc_sum) + floatval($this_rp_value);
        $total_last_last_year = floatval($last_rp_value) - floatval($last_sc_value);

        $registered_address = $company->addressAtLast($company->end_date_report);

        $address_string = $registered_address?->full_address ?? '';

        $company_data = [
            'tenant' => tenant(),
            'report' => $report,
            'company' => $company,
            'registered_address' => $address_string,
            'prior_company_name' => $prior_company_name,
            'business_nature' => $business_nature,
            'declared_dividends' => $declared_dividends,
            'proposed_dividends' => $proposed_dividends,
            'share_capitals' => $share_capitals,
            'directors' => $directors,
            'shareholders_count' => $shareholders_count,
            'shareholders_data' => $shareholders_data,
            'prior_ordinary_share' => $prior_ordinary_share,
            'prior_preference_share' => $prior_preference_share,
            'current_ordinary_shares' => $current_ordinary_shares,
            'current_preference_shares' => $current_preference_shares,
            'business_address' => $business_address,
            'prior_business_address' => $prior_business_address,
            'statement_director_names' => $statement_director_names,
            'statutory_director_name' => $statutory_director_name,
            'report_setting' => $report_setting,
            // 'statement_info' => $statement_info,
            'signed_by' => $signed_by,
            'director_renumerations' => $director_renumerations,
            'related_party_transactions' => collect(),
            // 'statutory_info' => $statutory_info,
            'sofp' => $sofp,
            'stsoo' => $stsoo,
            'socf' => $socf,
            'soci' => $soci,
            'tax_expenses' => $tax_expenses,
            //
            'soci_total_profit' => $soci_total_profit,
            'last_sc_value' => $last_sc_value,
            'last_rp_value' => $last_rp_value,
            'total_last_last_year' => $total_last_last_year,
            'last_sc_sum' => $last_sc_sum,
            'last_rp_sum' => $last_rp_sum,
            'total_last_year' => $total_last_year,
            'this_sc_sum' => $this_sc_sum,
            'this_rp_value' => $this_rp_value,
            'total_this_year' => $total_this_year,
        ];

        $director_report = $this->renderDirectorReport($company->id, $company_data);
        $company_data['director_report'] = $director_report;

        $company_data['ntfs_config_gen_info'] = $this->generalInformation($company_data);
        $company_data['ntfs_config_sig_acc_policies'] = $this->significantAccountingPolices($company_data);
        $company_data['ntfs_config_est_uncertainties'] = $this->estimationUncertainty($company_data);


        return view('livewire.tenant.pages.report.index', $company_data);

        // $pdf = Pdf::loadView('livewire.tenant.pages.report.index', $company_data)
        //     ->setOption(['dpi' => 96, 'defaultFontSize' => '11px', 'defaultFont' => 'sans-serif', 'defaultPaperSize' => 'a4']);
        // $pdf->render();
        // $canvas = $pdf->getCanvas();
        // $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
        //     if ($pageNumber > 1) {
        //         $text = $pageNumber - 1;
        //         $font = $fontMetrics->getFont('sans-serif');
        //         $pageWidth = $canvas->get_width();
        //         $pageHeight = $canvas->get_height();
        //         $size = 12;
        //         $width = $fontMetrics->getTextWidth($text, $font, $size);
        //         $canvas->text($pageWidth - $width - 60, $pageHeight - 50, $text, $font, $size);
        //     }
        // });
        // return $pdf->stream();

    }

    public function downloadExcel($id)
    {
        $report = CompanyReport::find($id);

        return Storage::download($report->file_path);
    }

    private function replaceTerms(&$content, $companyData)
    {
        $content = str_replace('#ToDate#', Carbon::parse($companyData['company']->current_year_to)->format('d F Y'), $content);
        $content = str_replace('#AuditorName#', $companyData['tenant']->firmName, $content);
        $content = str_replace('#AuditFee#', $companyData['company']->audit_fee, $content);
        $content = str_replace('#CompanyName#', $companyData['company']->company_name, $content);
        $content = str_replace('#RegisteredAddress#', $companyData['registered_address'], $content);
    }

    private function adjustWords(&$content, $companyData)
    {
        if (count($companyData['directors']) > 1) {
            $content = str_replace('{director}', 'directors', $content);
            // Director's
            $content = str_replace('{Director}', 'Directors', $content);
            $content = str_replace('{director\'s}', 'directors’', $content);
            $content = str_replace('{Director\'s}', 'Directors’', $content);
            $content = str_replace('{director is a member}', 'directors are members', $content);
            $content = str_replace('{director is}', 'directors are', $content);
            $content = str_replace('{director has}', 'directors have', $content);
            $content = str_replace('{DIRECTOR}', 'DIRECTORS', $content);
            $content = str_replace('{DIRECTOR\'S}', 'DIRECTORS’', $content);
            $content = str_replace('{his}', 'their', $content);
            $content = str_replace('{is}', 'are', $content);
        } else {
            $content = str_replace('{director}', 'director', $content);
            $content = str_replace('{Director}', 'Director', $content);
            $content = str_replace('{director\'s}', 'director’s', $content);
            $content = str_replace('{Director\'s}', 'Director’s', $content);
            $content = str_replace('{director is a member}', 'director is a member', $content);
            $content = str_replace('{director is}', 'director is', $content);
            $content = str_replace('{director has}', 'director has', $content);
            $content = str_replace('{DIRECTOR}', 'DIRECTOR', $content);
            $content = str_replace('{DIRECTOR\'S}', 'DIRECTOR’S', $content);
            $content = str_replace('{his}', 'his', $content);
            $content = str_replace('{is}', 'is', $content);
        }

        $content = str_replace('{period}', $companyData['company']->current_year_period_type == 'full year' ? 'year' : 'period', $content);

        if ($companyData['company']->last_year_period_type == 'first year') {
            $content = str_replace('{end of the previous financial year}', 'date of incorporation', $content);
        } elseif ($companyData['company']->last_year_period_type == 'partial year') {
            $content = str_replace('{end of the previous financial year}', 'end of the previous financial period', $content);
        } else {
            $content = str_replace('{end of the previous financial year}', 'end of the previous financial year', $content);
        }

        if (count($companyData['directors']) == 1) {
            $content = str_replace('{on behalf of the Board of Directors in accordance with a resolution}', 'in accordance with a resolution', $content);
        } elseif (count($companyData['directors']) == 2) {
            $content = str_replace('{on behalf of the Board of Directors in accordance with a resolution}', 'by the Board of Directors in accordance with a resolution', $content);
        } else {
            $content = str_replace('{on behalf of the Board of Directors in accordance with a resolution}', 'on behalf of the Board of Directors in accordance with a resolution', $content);
        }
    }

    private function renderTables(&$content, $companyData)
    {
        if ($content == '-- Content of Principal activity --') {
            if ($companyData['business_nature']) {
                $content = '<p>' . $companyData['business_nature']['paragraph1'] . '</p>';
                $content .= '<p>' . $companyData['business_nature']['paragraph2'] . '</p>';
            }
        } elseif ($content == '-- Content of Financial results --') {
            $content =
                '<table style="width: 100%">
                <tr>
                    <td>Profit for the financial year</td>
                    <td>RM ' . format_number($companyData['soci']->firstWhere('item', 'Profit for the financial year representing total comprehensive income for the financial year')?->this_year_amount) . '</td>
                </tr>
            </table>';
        } elseif ($content == '-- Content of Dividends --') {
            $content =
                '<table>
                <tr>
                    <th style="width:70%, text-align: left;">During the financial year</th>
                    <th style="text-align:center">RM</th>
                </tr>';
            if ($companyData['declared_dividends']) {
                foreach ($companyData['declared_dividends'] as $declared_dividend) {
                    $content .= '<tr>';
                    $content .= '<td style="width:70%">' . $declared_dividend->dividend_type . ' dividend of ' . $declared_dividend->rate_unit . $declared_dividend->rate . ' per ' . $declared_dividend->dividend_type . ' in respect of financial year end ' . Carbon::parse($companyData['company']['current_year_to'])->format('d F Y') . '</td>';
                    $content .= '<td style="text-align:center">' . $declared_dividend->amount . '</td>';
                    $content .= '</tr>';
                }
                $content .= '<tr>';
                $content .= '<td></td>';
                $content .= '<td style="text-align: center">' . $companyData['declared_dividends']->sum('amount') . '</td>';
                $content .= '</tr>';
            }
            $content .= '</table>';

            if ($companyData['proposed_dividends']) {
                foreach ($companyData['proposed_dividends'] as $proposed_dividend) {
                    $content .= '<p>The directors have recommended a ' . $proposed_dividend->dividend_type . ' of RM' . $proposed_dividend->rate . ' per ordinary share amounting to RM' . $proposed_dividend->amount . ' in respect of the financial year ended ' . Carbon::parse($companyData['company']['current_year_to'])->format('d F Y') . '</p>';
                }
            }
        } elseif ($content == '-- Content of Issues of shares and debentures --') {
            $content = '<table style="text-align: center">
                <tr>
                    <th>Date of Issue</th>
                    <th>Class of Share</th>
                    <th>No. of Shares Issued</th>
                    <th>Issued Price RM</th>
                    <th>Consideration RM</th>
                    <th>Terms of Issue</th>
                    <th>Purpose</th>
                </tr>';
            if ($companyData['share_capitals']) {
                foreach ($companyData['share_capitals'] as $share_capital) {
                    $total_shares = $share_capital->fully_paid_shares + $share_capital->partly_paid_shares;
                    $total_amount = $share_capital->fully_paid_amount + $share_capital->partly_paid_amount;
                    $content .= '<tr>';
                    $content .= '<td>' . $share_capital->effective_date->format('d.m.Y') . '</td>';
                    $content .= '<td>' . explode(" ", $share_capital->share_type)[0] . '</td>';
                    $content .= '<td>' . displayNumber($total_shares) . '</td>';
                    $content .= '<td>' . displayNumber($total_amount) . '</td>';
                    $content .= '<td></td>';
                    $content .= '<td>' . $share_capital->issuance_term . '</td>';
                    $content .= '<td>' . $share_capital->issuance_purpose . '</td>';
                    $content .= '</tr>';
                }
            }
            $content .= '</table>';
        } elseif ($content == '-- Content of Directors --') {
            $content = '<div style="margin-left: 20px">';
            foreach ($companyData['directors'] as $director) {
                $director_changes_current = $director->changes()->whereBetween('effective_date', [$companyData['company']['current_year_from'], $companyData['company']['end_date_report']])
                    ->orderBy('effective_date')
                    ->get();
                $d = [];
                foreach ($director_changes_current as $change) {
                    // Director appointed on 29/04/2024, Director resigned on 20/08/2024
                    $d[] = $change->change_nature . ' on ' . $change->effective_date->format('d.m.Y');
                }
                $director->changes_current = implode(', ', $d);
            }
            foreach ($companyData['directors'] as $director) {
                $content .= "<p>{$director->name}" . ($director->alternate ? " (alternate director to {$director->alternate->name})" : "") . ($director->changes_current ? " ({$director->changes_current})" : "") . "</p>";

            }
            $content .= '</div>';
        } elseif ($content == '-- Content of Directors\' remunerations --') {
            $content = '<table style="width: 100%">
                <tr style="text-align: center">
                    <th></th>
                    <th>RM</th>
                </tr>';
            foreach ($companyData['director_renumerations'] as $director_renumeration) {
                Log::debug("director_renumeration ->", [$director_renumeration['show_display']]);
                $content .= '<tr>';
                $content .= '<td>' . ($director_renumeration['show_display'] ? $director_renumeration['display'] : $director_renumeration['item']) . '</td>';
                $content .= '<td style="text-align: center">' . format_number($director_renumeration['this_year_amount']) . '</td>';
                $content .= '</tr>';
            }
            $content .= '</table>';
        } elseif ($content == '-- Content of Directors\' interests --') {
            $content = '<table style="width: 100%">
                <tr>
                    <td></td>
                    <td style="text-align: center" colspan="4"><b>Number of Ordinary Shares</b></td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: center">At ' . $companyData['company']['current_year_from']->format('d.m.Y') . '</td>
                    <td style="text-align: center">Bought</td>
                    <td style="text-align: center">Sold</td>
                    <td style="text-align: center">At ' . $companyData['company']['current_year_to']->format('d.m.Y') . '</td>
                </tr>';
            if (isset($companyData['shareholders_data']['Ordinary shares'])) {
                foreach ($companyData['shareholders_data']['Ordinary shares'] as $type => $share) {
                    // if ($share['total_share'] > 0) {
                    $content .= '<tr>';
                    $content .= '<td style=""><b>' . $share['name'] . '</b></td>';
                    $content .= '<td style="text-align: center">' . displayNumber($share['bf']) . '</td>';
                    $content .= '<td style="text-align: center">' . displayNumber($share['bought']) . '</td>';
                    $content .= '<td style="text-align: center">' . displayNumber($share['sold']) . '</td>';
                    $content .= '<td style="text-align: center">' . displayNumber($share['cf']) . '</td>';
                    $content .= '</tr>';
                    // }
                }
            }
            $content .= '<tr><td colspan="5" style="height: 20px"></td></tr>
                <tr>
                    <td></td>
                    <td style="text-align: center" colspan="4"><b>Number of Preference Shares</b></td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: center">At ' . $companyData['company']['current_year_from']->format('d.m.Y') . '</td>
                    <td style="text-align: center">Bought</td>
                    <td style="text-align: center">Sold</td>
                    <td style="text-align: center">At ' . $companyData['company']['current_year_to']->format('d.m.Y') . '</td>
                </tr>';
            if (isset($companyData['shareholders_data']['Preference shares'])) {
                foreach ($companyData['shareholders_data']['Preference shares'] as $type => $share) {
                    // if ($share['total_share'] > 0) {
                    $content .= '<tr>';
                    $content .= '<td style="width: 35%">' . $share['name'] . '</td>';
                    $content .= '<td style="text-align: center">' . displayNumber($share['bf']) . '</td>';
                    $content .= '<td style="text-align: center">' . displayNumber($share['bought']) . '</td>';
                    $content .= '<td style="text-align: center">' . displayNumber($share['sold']) . '</td>';
                    $content .= '<td style="text-align: center">' . displayNumber($share['cf']) . '</td>';
                    $content .= '</tr>';
                    // }
                }
            }
            $content .= '</table>';
        } elseif ($content == '-- Directors\' signature --') {
            $content = '<table style="width: 100%">
                <tr>
                    <td style="height: 70px" colspan="2"></td>
                </tr>
                <tr>';
            foreach ($companyData['directors'] as $director) {
                $lastChange = $director->changes()->whereNotNull('address_line1')->orderByDesc('effective_date')->first();
                $address = $lastChange?->full_address;
                $content .= '<td>';
                $content .= '<p class="text-bold">' . $director->name . '</p>';
                $content .= '<p>Director</p>';
                $content .= '<p>' . $address . '</p>';
                $content .= '<p>Dated: ' . Carbon::parse(Carbon::now())->format('d F Y') . '</p>';
                $content .= '</td>';
            }
            $content .= '</tr></table>';
        }
    }

    private function renderDirectorReport($companyId, $company)
    {
        $directorReportConfigs = DirectorReportConfig::where('company_id', $companyId)->where('display', 1)->orderBy('order_no')->get();
        $renderContent = '';
        $bulletFlag = false;
        foreach ($directorReportConfigs as $config) {
            $content = $config->report_content;

            $this->replaceTerms($content, $company);
            $this->adjustWords($content, $company);
            if (strcasecmp($config->template_type, 'Title') == 0) {
                $content = '<div class="section text-bold uppercase">' . $content . '</div>';
                if ($bulletFlag) {
                    $content = '</ol>' . $content;
                    $bulletFlag = false;
                }
            } elseif (strcasecmp($config->template_type, 'Paragraph') == 0) {
                $content = '<p>' . $content . '</p>';
                if ($bulletFlag) {
                    $content = '</ol>' . $content;
                    $bulletFlag = false;
                }
            } elseif (strcasecmp($config->template_type, 'Paragraph with bullet') == 0) {
                if ($bulletFlag) {
                    $content = '<li>' . $content . '</li>';
                } else {
                    $content = '<ol type="I"><li>' . $content . '</li>';
                    $bulletFlag = true;
                }
            } elseif (strcasecmp($config->template_type, 'Paragraph with indent') == 0) {
                $content = '<p>' . $content . '</p>';
                if ($bulletFlag) {
                    $content = '</ol>' . $content;
                    $bulletFlag = false;
                }
            } elseif (strcasecmp($config->template_type, 'Table') == 0) {
                $this->renderTables($content, $company);
                if ($bulletFlag) {
                    $content = '</ol>' . $content;
                    $bulletFlag = false;
                }
            }
            $renderContent .= $content;
        }
        return $renderContent;
    }

    // $ntfs_config_sig_acc_policies = NtfsConfigItem::where('company_id', $company->id)->where('section', 'sap')->orderBy('order')->get();
    // $ntfs_config_est_uncertainties = NtfsConfigItem::where('company_id', $company->id)->where('section', 'eu')->orderBy('order')->get();
    // $company_data['ntfs_config_sig_acc_policies'] = $ntfs_config_sig_acc_policies;
    // $company_data['ntfs_config_est_uncertainties'] = $ntfs_config_est_uncertainties;

    private function generalInformation($company)
    {
        $ntfs_config_gen_info = NtfsConfigItem::where('company_id', $company['company']->id)->where('section', 'gi')->where('is_active', 1)->orderBy('order')->get();
        $renderContent = '';
        foreach ($ntfs_config_gen_info as $config) {
            $content = '<li>';
            $content .= '<div class="text-bold uppercase">' . $config->title . '</div>';
            $text = $config->is_default_content ? $config->default_content : $config->content;
            if ($config->default_title == 'Basis of preparation') {
                $subContent = "<ol class='level_1'><li>
                                <p class=\"text-bold\">Going Concern</p>
                                <p>As at 31 December 2023, the current liabilities of the Company exceeded its current assets by RM3,748. This indicates the existence of an uncertainty which may cast significant doubt in the ability of the Company to continue as going concern. The validity of the going concern assumption is dependent upon the continuous financial support from the shareholders and the ability of the Company to generate sufficient cash from its operations to enable the Company to fulfill its obligations as and when they fall due.</p>
                            </li></ol>";
            }
            $this->replaceTerms($text, $company);
            $content .= '<p>' . nl2br(e($text)) . '</p>';
            $content .= $subContent ?? '';
            $content .= '</li>';
            $renderContent .= $content;
        }

        return $renderContent;
    }

    private function estimationUncertainty($company)
    {
        $ntfs_config_est_uncertainties = NtfsConfigItem::where('company_id', $company['company']->id)->where('section', 'eu')->where('is_active', 1)->orderBy('order')->get();
        $renderContent = '';
        $prevLevel = 0;
        foreach ($ntfs_config_est_uncertainties as $config) {
            $currentLevel = (int) $config->position;
            $content = '';

            // For Title:
            if (str_starts_with($config->type, 'Title')) { // Title with Decimal bullet
                if ($prevLevel < $currentLevel) {
                    if (str_contains($config->type, 'Decimal')) {
                        $content = '<ol class="level_' . $currentLevel . '">' . $content;
                    } elseif (str_contains($config->type, 'Roman')) {
                        $content = '<ol type="I">' . $content;
                    }

                } elseif ($prevLevel == $currentLevel) { // Title with Decimal bullet
                    $content .= '</li>';
                } elseif ($prevLevel > $currentLevel) {
                    $content .= '</li></ol></li>';
                }

                $content .= '<li>';
                $text = $config->content;
                $this->replaceTerms($text, $company);
                $content .= '<p class="text-bold">' . nl2br(e($text)) . '</p>';

                $prevLevel = $currentLevel;
            }
            // For Paragraph:
            elseif (str_starts_with($config->type, 'Paragraph')) {
                $text = $config->content;
                $this->replaceTerms($text, $company);
                $content .= '<p>' . nl2br(e($text)) . '</p>';
            }

            $renderContent .= $content;
        }
        for ($i = $prevLevel; $i > 0; $i--) {
            $renderContent .= '</li></ol>';
        }

        // Log::info('renderContent', [$renderContent]);
        return $renderContent;
    }

    private function significantAccountingPolices($company)
    {
        $ntfs_config_est_uncertainties = NtfsConfigItem::where('company_id', $company['company']->id)->where('section', 'sap')->where('is_active', 1)->orderBy('order')->get();
        $renderContent = '';
        $prevLevel = 0;
        foreach ($ntfs_config_est_uncertainties as $config) {
            $currentLevel = (int) $config->position;
            $content = '';

            // For Title:
            if (str_starts_with($config->type, 'Title')) { // Title with Decimal bullet
                if ($prevLevel < $currentLevel) {
                    if (str_contains($config->type, 'Decimal')) {
                        $content = '<ol class="level_' . $currentLevel . '">' . $content;
                    } elseif (str_contains($config->type, 'Roman')) {
                        $content = '<ol type="I">' . $content;
                    }

                } elseif ($prevLevel == $currentLevel) { // Title with Decimal bullet
                    $content .= '</li>';
                } elseif ($prevLevel > $currentLevel) {
                    $content .= '</li></ol></li>';
                }

                $content .= '<li>';
                $text = $config->content;
                $this->replaceTerms($text, $company);
                $content .= '<p class="text-bold">' . nl2br(e($text)) . '</p>';

                $prevLevel = $currentLevel;
            }
            // For Paragraph:
            elseif (str_starts_with($config->type, 'Paragraph')) {
                $text = $config->content;
                $this->replaceTerms($text, $company);
                $content .= '<p>' . nl2br(e($text)) . '</p>';
            }

            $renderContent .= $content;
        }
        for ($i = $prevLevel; $i > 0; $i--) {
            $renderContent .= '</li></ol>';
        }

        return $renderContent;
    }
}
