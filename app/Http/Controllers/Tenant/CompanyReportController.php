<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\CompanyAddressChange;
use App\Models\Tenant\CompanyDirector;
use App\Models\Tenant\CompanyDirectorChange;
use App\Models\Tenant\CompanyShareCapitalChange;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\Tenant\DirectorReportConfig;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Tenant\CompanyReport;
use App\Models\Tenant\CompanyReportItem;
use App\Models\Tenant\CompanyReportType;
use App\Models\Tenant\CompanyReportAccount;
use App\Models\Tenant\NtfsConfigItem;
use App\Models\Tenant\CompanyDividendChange;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CompanyReportController extends Controller
{
    public function viewFinancialReport($id)
    {
        $report = CompanyReport::find($id);

        $company = $report->company;
        $current_year_end = $company->current_year_to;
        $prior_year_end = $company->last_year_to;
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
        $share_capitals = $company->sharecapitalChanges;
        $directors = $company->directors;
        $prior_ordinary_share = $company->ordinaryShareCapitalAtStart();
        $prior_preference_share = $company->preferenceShareCapitalAtStart();
        $current_ordinary_shares = CompanyShareCapitalChange::whereBetween('effective_date', [$company->current_year_from, $company->current_year_to])->where('share_type', CompanyShareCapitalChange::SHARETYPE_ORDINARY)->get();
        $current_preference_shares = CompanyShareCapitalChange::whereBetween('effective_date', [$company->current_year_from, $company->current_year_to])->where('share_type', CompanyShareCapitalChange::SHARETYPE_PREFERENCE)->get();
        $business_address = $company->bizAddressAtLast($company->end_date_report);
        $prior_business_address = $company->bizAddressAtStart();
        $prior_directors = CompanyDirectorChange::with(['companyDirector' => function($query) use ($company) {
                $query->where('company_id', $company->id);
            }])
            ->whereHas('companyDirector', function($query) use ($company) {
                $query->where('company_id', $company->id);
            }, '=', 1) // Explicit count for better performance
            ->where('effective_date', '<', $company->current_year_from)
            ->latest('effective_date')
            ->get();
        // $statement_directors = $company->statement_directors()->get();
        $statement_directors = CompanyDirector::where('company_id', $company->id)
            ->where('is_active', true)
            ->where('is_rep_statement', true)
            ->whereHas('changes', function($query) use ($company) {
                $query->where('effective_date', '<=', $company->end_date_report)
                    ->latest('effective_date');
            })
            ->orderBy('sort')
            ->get();
        $statement_info = $company->statement_infos()->first();
        $statutory_info = $company->statutory_infos()->first();
        $related_party_transactions = $company->related_party_transactions;
        $statutory_director = $company->statutory_directors()->first();

        $statement_director_names = $statement_directors->pluck('name')->toArray();
        $statutory_director_name = $statutory_director ? $statutory_director->pluck('name', 'id_no')->toArray() : [];
        foreach ($prior_directors as $prior_director) {
            if (isset($prior_director['is_rep_statement']) && filter_var($prior_director['is_rep_statement'], FILTER_VALIDATE_BOOLEAN)) {
                array_push($statement_director_names, $prior_director['name']);
            }

            if (isset($prior_director['is_rep_statutory']) && filter_var($prior_director['is_rep_statutory'], FILTER_VALIDATE_BOOLEAN)) {
                array_push($statutory_director_name, [$prior_director['name'], $prior_director['id_no']]);
            }
        }
        $statutory_director_name = collect($statutory_director_name)->flatten();
        // Log::debug("statutory_director_name ->", [$statutory_director_name]);

        # Shareholders data
        $shareholders_count = $company->shareholders()->count();
        $shareholders = $company->shareholders()->get()->groupBy('type_of_share');

        $shareholders_data = [];
        foreach ($shareholders as $share_type => $shareholder) {
            $holder_groups = $shareholder->groupBy('title');

            $holder_data = [];
            foreach ($holder_groups as $holder_title => $shares) {
                $share_brought = 0;
                $share_sold = 0;
                $share_prior_year = 0;

                foreach ($shares as $share) {
                    if ($share->nature_change == null) {
                        $share_prior_year += $share->no_of_share;
                    } else if ($share->nature_change == 'Transfer In') {
                        $share_brought += $share->no_of_share;
                    } else if ($share->nature_change == 'Transfer Out') {
                        $share_sold += $share->no_of_share;
                    }
                }

                $share_data = [
                    'name' => $holder_title,
                    'brought' => $share_brought,
                    'sold' => $share_sold,
                    'prior_year' => $share_prior_year,
                    'total_share' => $share_prior_year + $share_brought - $share_sold
                ];

                array_push($holder_data, $share_data);
            }

            $shareholders_data[$share_type] = $holder_data;
        }

        # Report data
        $sofp_type = CompanyReportType::where('company_report_id', $id)->where('name', 'SOFP')->first();
        $soci_type = CompanyReportType::where('company_report_id', $id)->where('name', 'SOCI')->first();
        $sofp = $sofp_type->company_report_items()->where('is_report', 1)->orderBy('sort')->orderBy('id')->get();
        $stsoo = CompanyReportType::where('company_report_id', $id)->where('name', 'STSOO')->first()->company_report_items()->orderBy('sort')->orderBy('id')->where('is_report', 1)->get();
        $socf = CompanyReportType::where('company_report_id', $id)->where('name', 'SOCF')->first()->company_report_items()->where('is_report', 1)->orderBy('sort')->orderBy('id')->get();
        $soci = $soci_type->company_report_items()->where('is_report', 1)->orderBy('sort')->orderBy('id')->get();
        $tax_account = CompanyReportAccount::where('name', 'TAX')->where('company_report_id', $report->id)->first();
        $tax_expenses = $stsoo->where('company_report_account_id', $tax_account->id)->where('type', 'value');
        $director_renumerations = CompanyReportAccount::where('company_report_id', $id)->where('name', 'AE')->first()->company_report_items()->whereRaw("item LIKE '%Director%' AND is_report = 1")->get();

        $sign_director = $company->cover_page_directors()->first();
        $sign_secretary = $company->cover_page_secretaries()->first();
        if ($sign_director) {
            $signed_by = ['name' => $sign_director->name, 'type' => 'Director', 'title' => $sign_director->cover_page_title];
        } elseif ($sign_secretary) {
            $signed_by = ['name' => $sign_secretary->name, 'type' => 'Secretary', 'id_no' => $sign_secretary->secretary_no, 'title' => $sign_secretary->cover_page_title];
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

        $registered_address = CompanyRegisteredAddress::where('company_id', $company->id)->latest()->first();
        $address_parts = array_filter([
            $registered_address->address_line1,
            $registered_address->address_line2,
            $registered_address->address_line3,
            $registered_address->postcode,
            $registered_address->town,
            $registered_address->state
        ]);

        $address_string = implode(', ', $address_parts);

        $company_data = [
            'tenant' => tenant(),
            'report' => $report,
            'company' => $company,
            'registered_address' => $address_string,
            'current_year_end' => $current_year_end,
            'prior_year_end' => $prior_year_end,
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
            'statement_info' => $statement_info,
            'signed_by' => $signed_by,
            'director_renumerations' => $director_renumerations,
            'related_party_transactions' => $related_party_transactions,
            'statutory_info' => $statutory_info,
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
        // return view('livewire.tenants.excel-data.index', $company_data);

        $pdf = Pdf::loadView('livewire.tenants.excel-data.index', $company_data)
                ->setOption(['dpi' => 96, 'defaultFontSize' => '11px', 'defaultFont' => 'sans-serif', 'defaultPaperSize' => 'a4']);
        $pdf->render();
        $canvas = $pdf->getCanvas();
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            if ($pageNumber > 1) {
                $text = $pageNumber - 1;
                $font = $fontMetrics->getFont('sans-serif');
                $pageWidth = $canvas->get_width();
                $pageHeight = $canvas->get_height();
                $size = 12;
                $width = $fontMetrics->getTextWidth($text, $font, $size);
                $canvas->text($pageWidth - $width - 60, $pageHeight - 50, $text, $font, $size);
            }
        });
        return $pdf->stream();

    }

    public function downloadExcel($id)
    {
        $report = CompanyReport::find($id);

        return Storage::download($report->file_path);
    }

    private function replaceTerms(&$content, $company) {
        $content = str_replace('#ToDate#', Carbon::parse($company['current_year_end']['account_closing_date'])->format('d F Y'), $content);
        $content = str_replace('#AuditorName#', $company['tenant']->firmName, $content);
        $content = str_replace('#AuditFee#', $company['company']->audit_fee, $content);
        $content = str_replace('#CompanyName#', $company['company']->company_name, $content);
        $content = str_replace('#RegisteredAddress#', $company['registered_address'], $content);
    }

    private function adjustWords(&$content, $company) {
        if (count($company['directors']) > 1) {
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

        $content = str_replace('{period}', $company['company']->current_year_period_type == 'full year' ? 'year' : 'period', $content);

        if ($company['company']->last_year_period_type == 'first year') {
            $content = str_replace('{end of the previous financial year}', 'date of incorporation', $content);
        } elseif ($company['company']->last_year_period_type == 'partial year') {
            $content = str_replace('{end of the previous financial year}', 'end of the previous financial period', $content);
        } else {
            $content = str_replace('{end of the previous financial year}', 'end of the previous financial year', $content);
        }

        if (count($company['directors']) == 1) {
            $content = str_replace('{on behalf of the Board of Directors in accordance with a resolution}', 'in accordance with a resolution', $content);
        } elseif (count($company['directors']) == 2) {
            $content = str_replace('{on behalf of the Board of Directors in accordance with a resolution}', 'by the Board of Directors in accordance with a resolution', $content);
        } else {
            $content = str_replace('{on behalf of the Board of Directors in accordance with a resolution}', 'on behalf of the Board of Directors in accordance with a resolution', $content);
        }
    }

    private function renderTables(&$content, $company) {
        if ($content == '-- Content of Principal activity --') {
            if ($company['business_nature']) {
                $content = '<p>' . $company['business_nature']['paragraph1'] . '</p>';
                $content .= '<p>' . $company['business_nature']['paragraph2'] . '</p>';
            }
        } elseif ($content == '-- Content of Financial results --') {
            $content =
            '<table style="width: 100%">
                <tr>
                    <td>Profit for the financial year</td>
                    <td>RM '.format_number($company['soci']->firstWhere('item', 'Profit for the financial year representing total comprehensive income for the financial year')?->this_year_amount).'</td>
                </tr>
            </table>';
        } elseif ($content == '-- Content of Dividends --') {
            $content =
            '<table>
                <tr>
                    <th style="width:70%, text-align: left;">During the financial year</th>
                    <th style="text-align:center">RM</th>
                </tr>';
            if ($company['declared_dividends']) {
                foreach ($company['declared_dividends'] as $declared_dividend) {
                    $content .= '<tr>';
                    $content .= '<td style="width:70%">'.$declared_dividend->dividend_type.' dividend of '.$declared_dividend->rate_unit.$declared_dividend->rate.' per '.$declared_dividend->dividend_type.' in respect of financial year end '.Carbon::parse($company['current_year_end']['account_closing_date'])->format('d F Y').'</td>';
                    $content .= '<td style="text-align:center">'.$declared_dividend->amount.'</td>';
                    $content .= '</tr>';
                }
                $content .= '<tr>';
                $content .= '<td></td>';
                $content .= '<td style="text-align: center">'.$company['declared_dividends']->sum('amount').'</td>';
                $content .= '</tr>';
            }
            $content .= '</table>';

            if ($company['proposed_dividends']) {
                foreach ($company['proposed_dividends'] as $proposed_dividend) {
                    $content .= '<p>The directors have recommended a '.$proposed_dividend->dividend_type.' of RM'.$proposed_dividend->rate.' per ordinary share amounting to RM'.$proposed_dividend->amount.' in respect of the financial year ended '.Carbon::parse($company['current_year_end']['account_closing_date'])->format('d F Y').'</p>';
                }
            }
        } elseif ($content =='-- Content of Issues of shares and debentures --') {
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
            if ($company['share_capitals']) {
                foreach ($company['share_capitals'] as $share_capital) {
                    $content .= '<tr>';
                    $content .= '<td>'.$share_capital->effective_date.'</td>';
                    $content .= '<td>'.$share_capital->share_type.'</td>';
                    $content .= '<td>'.$share_capital->total_no_share.'</td>';
                    $content .= '<td>'.$share_capital->total_amount.'</td>';
                    $content .= '<td></td>';
                    $content .= '<td>'.$share_capital->terms_of_issue.'</td>';
                    $content .= '<td>'.$share_capital->purpose_of_issue.'</td>';
                    $content .= '</tr>';
                }
            }
            $content .= '</table>';
        } elseif ($content =='-- Content of Directors --') {
            $content = '<div style="margin-left: 20px">';
            foreach ($company['directors'] as $director) {
                $content .= '<p>'.$director->name.'</p>';
                if (in_array($director->nature_change, ['Director resigned', 'Director deceased', 'Director retired'])) {
                    $content .= '<p>'.$director->name.'</p> (appointed on '.$director->effective_date.')';
                }
            }
            $content .= '</div>';
        } elseif ($content =='-- Content of Directors\' remunerations --') {
            $content = '<table style="width: 100%">
                <tr style="text-align: center">
                    <th></th>
                    <th>RM</th>
                </tr>';
            foreach ($company['director_renumerations'] as $director_renumeration) {
                Log::debug("director_renumeration ->", [$director_renumeration['show_display']]);
                $content .= '<tr>';
                $content .= '<td>'.($director_renumeration['show_display'] ? $director_renumeration['display'] : $director_renumeration['item']).'</td>';
                $content .= '<td style="text-align: center">'.format_number($director_renumeration['this_year_amount']).'</td>';
                $content .= '</tr>';
            }
            $content .= '</table>';
        } elseif ($content =='-- Content of Directors\' interests --') {
            $content = '<table style="width: 100%">
                <tr>
                    <td></td>
                    <td style="text-align: center" colspan="4"><b>Number of Ordinary Shares</b></td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: center">At '.$company['current_year_end']['account_opening_date'].'</td>
                    <td style="text-align: center">Brought</td>
                    <td style="text-align: center">Sold</td>
                    <td style="text-align: center">At '.$company['current_year_end']['account_closing_date'].'</td>
                </tr>';
            if (isset($company['shareholders_data']['Ordinary shares'])) {
                foreach ($company['shareholders_data']['Ordinary shares'] as $type => $share) {
                    if ($share['total_share'] > 0) {
                        $content .= '<tr>';
                        $content .= '<td style=""><b>'.$share['name'].'</b></td>';
                        $content .= '<td style="text-align: center">'.$share['prior_year'].'</td>';
                        $content .= '<td style="text-align: center">'.$share['brought'].'</td>';
                        $content .= '<td style="text-align: center">'.$share['sold'].'</td>';
                        $content .= '<td style="text-align: center">'.$share['total_share'].'</td>';
                        $content .= '</tr>';
                    }
                }
            }
            $content .= '<tr><td colspan="5" style="height: 20px"></td></tr>
                <tr>
                    <td></td>
                    <td style="text-align: center" colspan="4"><b>Number of Preference Shares</b></td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: center">At '.$company['current_year_end']['account_opening_date'].'</td>
                    <td style="text-align: center">Brought</td>
                    <td style="text-align: center">Sold</td>
                    <td style="text-align: center">At '.$company['current_year_end']['account_closing_date'].'</td>
                </tr>';
            if (isset($company['shareholders_data']['Preference shares'])) {
                foreach ($company['shareholders_data']['Preference shares'] as $type => $share) {
                    if ($share['total_share'] > 0) {
                        $content .= '<tr>';
                        $content .= '<td style="width: 35%">'.$share['name'].'</td>';
                        $content .= '<td style="text-align: center">'.$share['prior_year'].'</td>';
                        $content .= '<td style="text-align: center">'.$share['brought'].'</td>';
                        $content .= '<td style="text-align: center">'.$share['sold'].'</td>';
                        $content .= '<td style="text-align: center">'.$share['total_share'].'</td>';
                        $content .= '</tr>';
                    }
                }
            }
            $content .= '</table>';
        } elseif ($content == '-- Directors\' signature --') {
            $content = '<table style="width: 100%">
                <tr>
                    <td style="height: 70px" colspan="2"></td>
                </tr>
                <tr>';
            foreach ($company['directors'] as $director) {
                $content .= '<td>';
                $content .= '<p><b>'.$director->name.'</b></p>';
                $content .= '<p>DIRECTORS</p>';
                $content .= '<p>'.$director->town.', '.$director->state.'</p>';
                $content .= '<p>Dated: '.Carbon::parse(Carbon::now())->format('d F Y').'</p>';
                $content .= '</td>';
            }
            $content .= '</tr></table>';
        }
    }

    private function renderDirectorReport($companyId, $company) {
        $directorReportConfigs = DirectorReportConfig::where('company_id', $companyId)->where('display', 1)->orderBy('order_no')->get();
        $renderContent = '';
        $bulletFlag = false;
        foreach ($directorReportConfigs as $config) {
            $content = $config->report_content;

            $this->replaceTerms($content, $company);
            $this->adjustWords($content, $company);
            if (strcasecmp($config->template_type, 'Title') == 0) {
                $content = '<h4><b style="text-transform: uppercase;">'.$content.'</b></h4>';
                if ($bulletFlag) {
                    $content = '</ol>'.$content;
                    $bulletFlag = false;
                }
            } elseif (strcasecmp($config->template_type, 'Paragraph') == 0) {
                $content = '<p>'.$content.'</p>';
                if ($bulletFlag) {
                    $content = '</ol>'.$content;
                    $bulletFlag = false;
                }
            } elseif (strcasecmp($config->template_type, 'Paragraph with bullet') == 0) {
                if ($bulletFlag) {
                    $content = '<li>'.$content.'</li>';
                } else {
                    $content = '<ol type="I"><li>'.$content.'</li>';
                    $bulletFlag = true;
                }
            } elseif (strcasecmp($config->template_type, 'Paragraph with indent') == 0) {
                $content = '<p>'.$content.'</p>';
                if ($bulletFlag) {
                    $content = '</ol>'.$content;
                    $bulletFlag = false;
                }
            }
            elseif (strcasecmp($config->template_type, 'Table') == 0) {
                $this->renderTables($content, $company);
                if ($bulletFlag) {
                    $content = '</ol>'.$content;
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

    private function generalInformation($company) {
        $ntfs_config_gen_info = NtfsConfigItem::where('company_id', $company['company']->id)->where('section', 'gi')->where('is_active', 1)->orderBy('order')->get();
        $renderContent = '';
        foreach ($ntfs_config_gen_info as $config) {
            $content = '<li>';
            $content .= '<p><b style="text-transform: uppercase;">' . $config->title . '</b></p>';
            $text = $config->is_default_content ? $config->default_content : $config->content;
            if ($config->default_title == 'Basis of preparation') {
                $subContent = "<ol class='level_1'><li>
                                <p><b>Going Concern</b></p>
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

    private function estimationUncertainty($company) {
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

                }
                elseif ($prevLevel == $currentLevel) { // Title with Decimal bullet
                    $content .= '</li>';
                }
                elseif ($prevLevel > $currentLevel) {
                    $content .= '</li></ol></li>';
                }

                $content .= '<li>';
                $text = $config->content;
                $this->replaceTerms($text, $company);
                $content .= '<p><b>' . nl2br(e($text)) . '</b></p>';

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

    private function significantAccountingPolices($company) {
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

                }
                elseif ($prevLevel == $currentLevel) { // Title with Decimal bullet
                    $content .= '</li>';
                }
                elseif ($prevLevel > $currentLevel) {
                    $content .= '</li></ol></li>';
                }

                $content .= '<li>';
                $text = $config->content;
                $this->replaceTerms($text, $company);
                $content .= '<p><b>' . nl2br(e($text)) . '</b></p>';

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
