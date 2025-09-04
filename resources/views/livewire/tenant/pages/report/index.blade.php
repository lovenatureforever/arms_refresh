<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Report</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 13px;
        }
        .page-break {
            page-break-after: always;
        }
        .pagenum-container {
            text-align: right;
        }

        @page {
            margin: 2cm;
        }

        header {
            position: fixed;
            top: -1cm;
            left: 0;
            /* background-color: lightblue; */
            /* height: 50px; */
        }

        footer {
            position: fixed;
            bottom: -1cm;
            right: 0;
            /* background-color: lightblue; */
            /* height: 50px; */
        }

        /* footer .pagenum:before {
            content: counter(page);
        } */

        /* p {
            page-break-after: always;
        } */

        p:last-child {
            page-break-after: never;
        }

        #page1-div {
           text-align: center;
           margin-top: 100px;
        }

        .page-div {
            /* margin: 50px 100px; */
            text-align: justify;
            text-justify: inter-word;
        }

        .page-fnc-div {
           text-align: center;
        }

        .center {
            margin-left: auto;
            margin-right: auto;
        }

        .right {
            text-align: right;
            margin-right: 1em;
        }

        .left {
            text-align: left !important;
            margin-left: 1em;
        }

        .cell-center {
            text-align: center;
        }

        .no-line {
            line-height: 0px;
        }

        table { page-break-inside:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        ol.level_0 {
            counter-reset: level_0;
        }
        ol.level_0 > li {
            counter-increment: level_0;
        }
        ol.level_0 > li::marker {
            font-weight: bold;
            content: counter(level_0) ". ";
        }
        ol.level_1 {
            counter-reset: level_1;
        }
        ol.level_1 > li {
            counter-increment: level_1;
        }
        ol.level_1 > li::marker {
            font-weight: bold;
            content: counter(level_0) "." counter(level_1) " ";
        }
        ol.level_2 {
            counter-reset: level_2;
        }
        ol.level_2 > li {
            counter-increment: level_2;
        }
        ol.level_2 > li::marker {
            font-weight: bold;
            content: counter(level_0) "." counter(level_1) "." counter(level_2) " ";
        }
    </style>
</head>

<body>
    <header><b>Company No.: {{ $company->registration_no_old }} {{ $company->registration_no_old ? '('.$company->registration_no_old.')' : '' }}</b></header>
    <footer>
        <div class="pagenum-container"><span class="pagenum"></span></div>
    </footer>
    <main>
        <div>
            @if ($signed_by)
                <table style="width: 40%; padding-left: 20px;">
                    <tr>
                        <td style="text-align: justify;">These financial statements and reports of the Company with Qualified / <strike>Unqualified</strike> Auditors' Report for the financial year ended {{ Carbon\Carbon::parse($company['current_year_to'])->format('d F Y') }} were circulated on</td>
                    </tr>
                    <tr><br><br><br><br></tr>
                    <tr>
                        <td style="text-align: center;">
                            <b>{{ Str::upper($signed_by['name']) }}</b><br/>
                            {{ $signed_by['type'] == "Secretary" ? $signed_by['id_no'] : '' }}<br/>
                            {{ $signed_by['title'] }}<br/>
                        </td>
                    </tr>
                </table>
            @endif
            <div id="page1-div">
               <div><b>{{ $company->name }}</b></div>
                {{-- <p><b>Registration No. {{ $company->company_registration_no }} ({{ $company->company_registration_no_old }})</b></p> --}}
                @if ($prior_company_name)
                    <p><b>(formerly&#160;known&#160;as&#160;{{ $prior_company_name }})</b></p>
                @endif
                <div><b>(Incorporated in Malaysia)</b></div>
                <br/>
                @if ($company->current_year_to)
                    <h4><b>REPORTS AND FINANCIAL STATEMENTS<br/> FOR THE FINANCIAL PERIOD ENDED {{ Str::upper(Carbon\Carbon::parse($company->current_year_to)->format('d F Y')) }}</b></h4>
                @endif
                <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
                <div>
                <br/>
                    <b>{{ $tenant->firmName }}({{ $tenant->firmNo }})</b>
                    <br/>
                    <b>Chartered Accountants</b>
                </div>
            </div>
        </div>
        <div class="page-break"></div>
        <div class="page-div">
            <div style="margin-bottom: 10px">
                <div>
                    <b>{{ $company->name }}<br />
                        @if ($prior_company_name)
                            <b>(formerly&#160;known&#160;as&#160;{{ $prior_company_name }})</b>
                        @endif
                    </b>
                </div>
                <div>(Incorporated in Malaysia)</div>
            </div>
            <div>
                @if (count($directors) == 1)
                    <h4><b>DIRECTOR’ S REPORT</b></h4>
                @elseif (count($directors) > 1)
                    <h4><b>DIRECTORS’ REPORT</b></h4>
                @endif
                {{-- <p>The {{ count($directors) > 1 ? 'directors' : 'director' }} hereby submit {{ count($directors) > 1 ? 'their' : 'his' }} report together with the audited financial statements of the
                    @if ($current_year_end)
                        Company for the financial year ended {{ Carbon\Carbon::parse($company->current_year_to)->format('d F Y') }}
                    @endif
                </p> --}}
            </div>

            {!! $director_report !!}

        </div>
        <div class="page-break"></div>
        <div class="page-div">
            <div style="margin-bottom: 10px">
                <p>
                    <b>{{ $company->name }}<br />
                        @if ($prior_company_name)
                            <b>(formerly&#160;known&#160;as&#160;{{ $prior_company_name }})</b>
                        @endif
                    </b>
                </p>
                <p>(Incorporated in Malaysia)</p>
            </div>

            <div>
                <h3><b>STATEMENT BY {{ count($directors) > 1 ? 'DIRECTORS' : 'DIRECTOR' }} </b></h3>
                <h4>Pursuant to Section 251 (2) of the Companies Act 2016</h4>

                <p>In the opinion of the {{ count($directors) > 1 ? 'directors' : 'director' }}, the financial statements are drawn up in accordance with Malaysian Private Entities Reporting Standard and the requirements of the Companies Act 2016 in Malaysia so as to give a true and fair view of the financial position of the Company as of {{ Str::ucfirst(Carbon\Carbon::parse($company->current_year_to)->format('d F Y')) }} and of its financial performance and cash flows for the financial year then ended. </p>
                <p>
                    @if (count($directors) == 1)
                        Signed in accordance with a resolution of
                    @elseif (count($directors) == 2)
                        Signed by the Board of Directors in accordance with a resolution of
                    @else
                        Signed on behalf of the Board of Directors in accordance with a resolution of
                    @endif
                     the {{ count($directors) > 1 ? 'directors' : 'director' }}.</p>
                <table style="width: 100%">
                    <tr>
                        <td style="height: 70px" colspan="2"></td>
                    </tr>
                    <tr>
                        <!-- Change to report info data -->
                        @foreach ($statement_director_names as $director)
                            <td>
                                <p><b>{{ $director }}</b></p>
                                <p>DIRECTORS</p>
                                <p>{{ $report_setting->statement_location }}</p>
                                <p>Dated: {{ Carbon\Carbon::parse($report_setting->statement_date)->format('d F Y') }}</p>
                            </td>
                        @endforeach

                    </tr>
                </table>
            </div>
            @if ($statutory_director_name && $report_setting)
                <div>
                    <h3>STATUTORY DECLARATION</h3>
                    <h4>Pursuant to Section 251 (1) (b) of the Companies Act 2016</h4>

                    <p>I, {{ $statutory_director_name[0] }}@if (isset($statutory_director_name[1])) ({{ $statutory_director_name[1] }})@endif, the person primarily responsible for the financial management of {{ $company->name }}, do solemnly and sincerely declare that the financial statements are to the best of my knowledge and belief, correct and I make this solemn declaration conscientiously believing the same to be true, and by virtue of the provisions of the Statutory Declarations Act, 1960. </p>

                    <table style="width: 100%">
                        <tr>
                            <td>Subscribed and solemnly declared by the</td>
                            <td style="width: 50%">)</td>
                        </tr>
                        <tr>
                            <td>abovenamed, {{ $statutory_director_name[0] }}</td>
                            <td style="width: 50%">)</td>
                        </tr>
                        <tr>
                            <td>at {{ $report_setting->statutory_location }}</td>
                            <td style="width: 50%">)</td>
                        </tr>
                        <tr>
                            <td>on this date of {{ Carbon\Carbon::parse($report_setting->statutory_date)->format('d M Y') }} </td>
                            <td style="width: 50%">)</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: center">{{ $statutory_director_name[0] }}</td>
                        </tr>
                    </table>

                    <p>Before me,</p>

                    <table>
                        <tr>
                            <td style="height: 70px"></td>
                            <td>COMMISSIONER FOR OATHS</td>
                        </tr>
                    </table>
                </div>
            @endif
        </div>
        <div class="page-break"></div>
        <div class="page-div">
            <div>
                <h2>{{ $tenant->firmName }}</h2><small>({{ $tenant->firmNo }})</small>
                <p>{{ $tenant->firmTitle }}</p>
                <p>{{ $tenant->address1 . ', ' . $tenant->address2 . ', ' . $tenant->zip . ', ' . $tenant->city . ', ' . $tenant->state }}</p>
                <p>(Tel) {{ $tenant->firmContact }} (Fax) {{ $tenant->firmFax }} (Email) {{ $tenant->firmEmail }}</p>
            </div>

            <hr style="margin-top: 30px;margin-bottom: 40px;">

            <div>
                <h3>INDEPENDENT AUDITORS' REPORT TO THE {{ $shareholders_count > 1 ? 'MEMBERS' : 'MEMBER' }} OF {{ $company->name }}</h3>
                @if ($prior_company_name)
                    <h4>(formerly known as {{ $prior_company_name }})</h4>
                @endif
                <div>(Incorporated in Malaysia)</div>
            </div>

            <div>
                <h4>Report on the Audit of the Financial Statements</h4>
            </div>

            <div>
                <h4>Qualified Opinion </h4>
                <p>We have audited the financial statements of {{ $company->name }}
                    @if ($prior_company_name)
                        (formerly known as {{ $prior_company_name }})
                    @endif,
                    which comprise the statement of financial position as at 31 December 2023, and the statement of comprehensive income, statement of changes in equity and statement of cash flows for the financial {{ $company->current_year_type == 'full year' ? 'year' : 'period' }} then ended, and notes to the financial statements, including a summary of significant accounting policies
                </p>
                <p>
                    In our opinion, except for the effects of the matter described in the Basis for Qualified Opinion section of our report, the accompanying financial statements give a true and fair view of the financial position of the Company as at 31 December 2023, and of its financial performance and its cash flows for the financial {{ $company->current_year_type == 'full year' ? 'year' : 'period' }} then ended in accordance with Malaysian Private Entities Reporting Standard and the requirements of the Companies Act 2016 in Malaysia.
                </p>
            </div>

            <div>
                <h4>Basis for Qualified Opinion</h4>
                <p>We conducted our audit in accordance with approved standards on auditing in Malaysia and International Standards on Auditing. Our responsibilities under those standards are further described in the Auditors' Responsibilities for the Audit of the Financial Statements section of our report. We believe that the audit evidence we have obtained is sufficient and appropriate to provide a basis for our qualified opinion.</p>

                <p style="text-underline-position: below">Independence and Other Ethical Responsibilities</p>

                <p>We are independent of the Company in accordance with the By-Laws (on Professional Ethics, Conduct and Practice) of the Malaysian Institute of Accountants ("By-Laws") and the International Ethics Standards Board for Accountants' International Code of Ethics for Professional Accountants (including International Independence Standards) ("IESBA Code"), and we have fulfilled our other ethical responsibilities in accordance with the By-Laws and the IESBA Code.</p>
            </div>

            <div>
                <h4>Material Uncertainty Related to Going Concern</h4>
                <p>We draw attention to Note 3.1 to the financial statements which discloses the premise upon which the Company have prepared its financial statements by applying the going concern assumption, notwithstanding the financial statements and performance of the Company as stated therein, thereby indicating the existence of a material uncertainty which may cast significant doubt about the Company's ability to continue as going concern. Our opinion is not modified in respect of this matter.</p>

            </div>
            <div>
                <h4>
                    Information Other than the Financial Statements and Auditors' Report Thereon
                </h4>
                <p>The {{ count($directors) > 1 ? 'directors' : 'director' }} of the Company are responsible for the other information. The other information comprises the {{ count($directors) > 1 ? 'Directors’' : 'Director’s' }} Report but does not include the financial statements of the Company and our auditors' report thereon</p>
                <p>Our opinion on the financial statements of the Company does not cover {{ count($directors) > 1 ? 'Directors’' : 'Director’s' }} Report and we do not express any form of assurance conclusion thereon.</p>
                <p>In connection with our audit of the financial statements of the Company, our responsibility is to read the {{ count($directors) > 1 ? 'Directors’' : 'Director’s' }} Report and, in doing so, consider whether the {{ count($directors) > 1 ? 'Directors’' : 'Director’s' }} Report is materially inconsistent with the financial statements of the Company or our knowledge obtained in the audit or otherwise appears to be materially misstated.</p>

            </div>

            <div>
                <h4>Responsibilities of the {{ count($directors) > 1 ? 'Directors' : 'Director' }} for the Financial Statements </h4>
                <p>The {{ count($directors) > 1 ? 'directors' : 'director' }} of the Company are responsible for the preparation of financial statements of the Company that give a true and fair view in accordance with Malaysian Private Entities Reporting Standard and the requirements of the Companies Act 2016 in Malaysia. The {{ count($directors) > 1 ? 'directors are' : 'director is' }} also responsible for such internal control as the {{ count($directors) > 1 ? 'directors determine' : 'director determines' }} is necessary to enable the preparation of financial statements of the Company that are free from material misstatement, whether due to fraud or error. </p>
                <p>In preparing the financial statements of the Company, the {{ count($directors) > 1 ? 'directors are' : 'director is' }} responsible for assessing the Company's ability to continue as a going concern, disclosing, as applicable, matters related to going concern and using the going concern basis of accounting unless the {{ count($directors) > 1 ? 'directors' : 'director' }} either intend to liquidate the Company or to cease operations, or have no realistic alternative but to do so.</p>

            </div>

            <div>
                <h4>Auditors' Responsibilities for the Audit of the Financial Statements</h4>
                <p>Our objectives are to obtain reasonable assurance about whether the financial statements of the Company as a whole are free from material misstatement, whether due to fraud or error, and to issue an auditors' report that includes our opinion. Reasonable assurance is a high level of assurance, but is not a guarantee that an audit conducted in accordance with approved standards on auditing in Malaysia and International Standards on Auditing will always detect a material misstatement when it exists. Misstatements can arise from fraud or error and are considered material if, individually or in the aggregate, they could reasonably be expected to influence the economic decisions of users taken on the basis of these financial statements</p>
                <p>As part of an audit in accordance with approved standards on auditing in Malaysia and International Standards on Auditing, we exercise professional judgement and maintain professional scepticism throughout the audit. We also:</p>
                <ul>
                    <li>Identify and assess the risks of material misstatement of the financial statements of the Company, whether due to fraud or error, design and perform audit procedures responsive to those risks, and obtain audit evidence that is sufficient and appropriate to provide a basis for our opinion. The risk of not detecting a material misstatement resulting from fraud is higher than for one resulting from error, as fraud may involve collusion, forgery, intentional omissions, misrepresentations, or the override of internal control.</li>
                    <li>Obtain an understanding of internal control relevant to the audit in order to design audit procedures that are appropriate in the circumstances, but not for the purpose of expressing an opinion on the effectiveness of the Company's internal control. </li>
                    <li>Evaluate the appropriateness of accounting policies used and the reasonableness of accounting estimates and related disclosures made by the {{ count($directors) > 1 ? 'directors' : 'director' }}. </li>
                    <li>Conclude on the appropriateness of the {{ count($directors) > 1 ? 'directors’' : 'director’s' }} use of the going concern basis of accounting and, based on the audit evidence obtained, whether a material uncertainty exists related to events or conditions that may cast significant doubt on the Company's ability to continue as a going concern. If we conclude that a material uncertainty exists, we are required to draw attention in our auditors' report to the related disclosures in the financial statements of the Company or, if such disclosures are inadequate, to modify our opinion. Our conclusions are based on the audit evidence obtained up to the date of our auditors' report. However, future events or conditions may cause the Company to cease to continue as a going concern.</li>
                    <li>Evaluate the overall presentation, structure and content of the financial statements of the Company, including the disclosures, and whether the financial statements represent the underlying transactions and events in a manner that achieves fair presentation.</li>
                </ul>
                <p>We communicate with the {{ count($directors) > 1 ? 'directors' : 'director' }} regarding, among other matters, the planned scope and timing of the audit and significant audit findings, including any significant deficiencies in internal control that we identify during our audit. </p>
            </div>

            <div>
                <h4>Report on Other Legal and Regulatory Requirements</h4>
                <p>In accordance with the requirements of the Companies Act 2016 in Malaysia, we report that in our opinion, the accounting and other records for the matter as described in the Basis for Qualified Opinion section have not been properly kept by the Company in accordance with the provision of the Act.</p>
            </div>

            <div>
                <h4>Other Matters</h4>
                <ol type="I">
                    <li>The financial statements of the Company for the financial {{ $company->current_year_type == 'full year' ? 'year' : 'period' }} ended 31 December 2021 was audited by another firm of Chartered Accountants whose report thereon dated 13 June 2022 expressed an unmodified opinion on those financial statements. </li>
                    <li>This report is made solely to the members of the Company, as a body, in accordance with Section 266 of the Companies Act 2016 in Malaysia and for no other purpose. We do not assume responsibility to any other person for the content of this report.</li>
                </ol>
            </div>

            <div style="height: 70px">
            </div>
            <!-- Change to? -->
            <table style="width: 100%; text-align: center">
                <tr>
                    <td>
                        <p>
                            <b>{{ $tenant->firmName }}</b>
                            <br>
                            {{ $tenant->firmNo }}
                            <br>
                            Chartered Accountants
                        </p>
                    </td>
                    <td>
                        <p>
                            LEE AIK HAO<br>
                            03685/09/2024 J<br>
                            Chartered Accountant
                        </p>
                    </td>
                </tr>
            </table>
        </div>
        <div class="page-break"></div>
        <!-- SOFP -->
        <div class="page-fnc-div">
            <div style="width: 100%;text-align: center">
                <p>
                    <b>{{ $company->name }}<br />
                    @if ($prior_company_name)
                        <b>(formerly&#160;known&#160;as&#160;{{ $prior_company_name }})</b>
                    @endif
                    </b>
                </p>
                <p>(Incorporated in Malaysia)</p>
            </div>
            <br/>
            <p><b>STATEMENT OF FINANCIAL POSITION </b></p>
            <p><b>AS AT {{ Str::upper(Carbon\Carbon::parse($company['current_year_to'])->format('d F Y')) }}</b></p>
            <br/>
            <table class="center" style="width: 80%">
                <tr>
                    <th></th>
                    <th width="10%">Note</th>
                    <th width="15%" class="right">{{ Str::upper($company->current_year) }}<br/>RM</th>
                    <th width="15%" class="right">{{ Str::upper($company->last_year) }}<br/>RM</th>
                </tr>

                @foreach ($sofp as $sofp_item)
                    @if ($sofp_item->type == 'group')
                    <tr class="no-line">
                        <td colspan="4">
                            <p><b>{{ $sofp_item->show_display ? $sofp_item->display : $sofp_item->item }}</b></p>
                        </td>
                    </tr>
                    @elseif ($sofp_item->type == 'value')
                    <tr class="no-line">
                        <td>
                            <p>{{ $sofp_item->show_display ? $sofp_item->display : $sofp_item->item }}</p>
                        </td>
                        <td></td>
                        <td width="15%" class="right">
                            <p>{{ format_number($sofp_item->this_year_amount) }}</p>
                        </td>
                            <td width="15%" class="right">
                            <p>{{ format_number($sofp_item->last_year_amount) }}</p>
                        </td>
                    </tr>
                    @elseif($sofp_item->type == 'total')
                    <tr class="no-line">
                        <td>
                            <p><b>{{ $sofp_item->show_display ? $sofp_item->display : $sofp_item->item }}</b></p>
                        </td>
                        <td></td>
                        <td width="15%" class="right">
                            <p><b>{{ format_number($sofp_item->this_year_amount) }}</b></p>
                        </td>
                        <td width="15%" class="right">
                            <p><b>{{ format_number($sofp_item->last_year_amount) }}</b></p>
                        </td>
                    </tr>
                    @endif
                @endforeach
            </table>
        </div>
        <div class="page-break"></div>
        <!-- SOCI -->
        <div class="page-fnc-div">
            <div style="width: 100%;text-align: center">
                <p>
                    <b>{{ $company->name }}<br />
                    @if ($prior_company_name)
                        <b>(formerly&#160;known&#160;as&#160;{{ $prior_company_name }})</b>
                    @endif
                    </b>
                </p>
                <p>(Incorporated in Malaysia)</p>
            </div>
            <br/>
            <p><b>STATEMENT OF COMPREHENSIVE INCOME </b></p>
            <p><b>FOR THE FINANCIAL YEAR ENDED {{ Str::upper(Carbon\Carbon::parse($company->current_year_to)->format('d F Y')) }}</b></p>
            <br/>
            <table class="center" style="width: 80%">
                <tr>
                    <th></th>
                    <th width="10%">Note</th>
                    <th width="20%" class="center">{{ Str::upper($company->current_year) }}<br/>RM</th>
                    <th width="20%" class="center">{{ Str::upper(Carbon\Carbon::parse($company->last_year_from)->format('d.m.Y')) }}<br/> to<br/> {{ Str::upper(Carbon\Carbon::parse($company->last_year_to)->format('d.m.Y')) }}<br/>RM</th>
                </tr>
                @foreach ($soci as $soci_item)
                    <tr>
                        <td>{{ $soci_item->show_display ? $soci_item->display : $soci_item->item }}</td>
                        <td></td>
                        @if (collect(['Cost of sales', 'Administrative expenses', 'Other operating expenses', 'Finance costs', 'Tax expense'])->contains($soci_item->item))
                            <td width="20%" class="right">({{ format_number($soci_item->this_year_amount) }})</td>
                            <td width="20%" class="right">({{ format_number($soci_item->last_year_amount) }})</td>
                        @else
                            <td width="20%" class="right">{{ format_number($soci_item->this_year_amount) }}</td>
                            <td width="20%" class="right">{{ format_number($soci_item->last_year_amount) }}</td>
                        @endif

                    </tr>
                @endforeach
            </table>
        </div>
        <div class="page-break"></div>
        <!-- SOCE  -->

        <div class="page-fnc-div">
            <div style="width: 100%;text-align: center">
                <p>
                    <b>{{ $company->name }}<br />
                        @if ($prior_company_name)
                            <b>(formerly&#160;known&#160;as&#160;{{ $prior_company_name }})</b>
                        @endif
                        </b>
                </p>
                <p>(Incorporated in Malaysia)</p>
            </div>

            <div style="width: 100%;text-align: center">
                <p><b>STATEMENT OF CHANGES IN EQUITY</b></p>
                <p><b>FOR THE FINANCIAL YEAR ENDED {{ Str::upper(Carbon\Carbon::parse($company->current_year_to)->format('d F Y')) }}</b></p>
            </div>

            <table class="center" style="width: 80%">
                <tr>
                    <th width="40%" class="left"></th>
                    <th width="20%" class="cell-center">
                        Share Capital
                        <br>
                        RM
                    </th>
                    <th class="cell-center">
                        (Accumulated Losses) / Retained Profits
                        <br>
                        RM
                    </th>
                    <th width="15%" class="cell-center">
                        Total
                        <br>RM
                    </th>
                </tr>
                <tr>
                    <td>At {{ Carbon\Carbon::parse($company->last_year_from)->format('d F Y') }}</td>
                    <td class="cell-center">{{ format_number($last_sc_value) }}</td>
                    <td class="cell-center">({{ format_number($last_rp_value) }})</td>
                    <td class="cell-center">({{ format_number($total_last_last_year) }})</td>
                </tr>
                <tr>
                    <td>Profit for the financial year</td>
                    <td class="cell-center">-</td>
                    <td class="cell-center">{{ format_number($soci_total_profit['last_year_amount']) }}</td>
                    <td class="cell-center">{{ format_number($soci_total_profit['last_year_amount']) }}</td>
                </tr>
                <tr>
                    <td>At {{ Carbon\Carbon::parse($company->last_year_to)->format('d F Y') }} / {{ Carbon\Carbon::parse($company->last_year_from)->format('d F Y') }}</td>
                    <td class="cell-center">{{ format_number($last_sc_sum) }}</td>
                    <td class="cell-center">({{ format_number($last_rp_sum) }})</td>
                    <td class="cell-center">({{ format_number($total_last_year) }})</td>
                </tr>
                <tr>
                    <td><b>Profit for the financial year</b></td>
                    <td class="cell-center">-</td>
                    <td class="cell-center">{{ format_number($soci_total_profit['this_year_amount']) }}</td>
                    <td class="cell-center">{{ format_number($soci_total_profit['this_year_amount']) }}</td>
                </tr>
                <tr>
                    <td><b>At {{ Carbon\Carbon::parse($company->current_year_to)->format('d F Y') }}</b></td>
                    <td class="cell-center">{{ format_number($this_sc_sum) }}</td>
                    <td class="cell-center">{{ format_number($this_rp_value) }}</td>
                    <td class="cell-center">{{ format_number($total_this_year) }}</td>
                </tr>
            </table>
        </div>
        <div class="page-break"></div>

        <!-- SOCF -->
        <div class="page-fnc-div">
            <div style="width: 100%;text-align: center">
                <p>
                    <b>{{ $company->name }}<br />
                    @if ($prior_company_name)
                        <b>(formerly&#160;known&#160;as&#160;{{ $prior_company_name }})</b>
                    @endif
                    </b>
                </p>
                <p>(Incorporated in Malaysia)</p>
            </div>

            <p><b>STATEMENT OF CASH FLOWS </b></p>
            <p><b>FOR THE FINANCIAL YEAR ENDED {{ Str::upper(Carbon\Carbon::parse($company->current_year_to)->format('d F Y')) }}</b></p>

            <table class="center" style="width: 80%">
                <tr>
                    <th></th>
                    <th width="8%">Note</th>
                    <th width="15%" class="center">{{ Str::upper($company->current_year) }}<br/>RM</th>
                    <th width="15%" class="center">{{ Str::upper(Carbon\Carbon::parse($company->last_year_from)->format('d.m.Y')) }}<br/> to<br/> {{ Str::upper(Carbon\Carbon::parse($company->last_year_to)->format('d.m.Y')) }}<br/>RM</th>
                </tr>
                @foreach ($socf as $socf_item)
                    @if (str_contains(strtoupper($socf_item->item), 'CASH AND BANK BALANCES'))
                    <tr>
                        <td colspan="4" class="left">
                            <h4><b>NOTE</b></h4>
                            <ol type="I">
                                <li>
                                    <p><b>Cash and cash equivalents</b></p>
                                    <p>Cash and cash equivalents included in the statement above comprise the following amounts:</p>
                                </li>
                            </ol>
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <th width="8%">Note</th>
                        <th width="15%" class="center">{{
                            Str::upper(Carbon\Carbon::parse($company->current_year_to)->format('Y')) }}<br />RM</th>
                        <th width="15%" class="center">{{
                            Str::upper(Carbon\Carbon::parse($company->last_year_from)->format('d.m.Y')) }}<br /> to<br /> {{
                            Str::upper(Carbon\Carbon::parse($company->last_year_to)->format('d.m.Y')) }}<br />RM</th>
                    </tr>
                    @endif
                    @if ($socf_item->type == 'group')
                    <tr class="no-line">
                        <td colspan="4">
                            <p><b>{{ $socf_item->show_display ? $socf_item->display : $socf_item->item }}</b></p>
                        </td>
                    </tr>
                    @elseif ($socf_item->type == 'value')
                    <tr class="no-line">
                        <td>
                            <p>{{ $socf_item->show_display ? $socf_item->display : $socf_item->item }}</p>
                        </td>
                        <td></td>
                        <td width="15%" class="right">
                            <p>{{ format_number($socf_item->this_year_amount) }}</p>
                        </td>
                        <td width="15%" class="right">
                            <p>{{ format_number($socf_item->last_year_amount) }}</p>
                        </td>
                    </tr>
                    @elseif($socf_item->type == 'total')
                    <tr class="no-line">
                        <td>
                            <p><b>{{ $socf_item->show_display ? $socf_item->display : $socf_item->item }}</b></p>
                        </td>
                        <td></td>
                        <td width="15%" class="right">
                            <p><b>{{ format_number($socf_item->this_year_amount) }}</b></p>
                        </td>
                        <td width="15%" class="right">
                            <p><b>{{ format_number($socf_item->last_year_amount) }}</b></p>
                        </td>
                    </tr>
                    @elseif($socf_item->type == 'label')
                    <tr class="no-line">
                        <td>
                            <p>{{ $socf_item->show_display ? $socf_item->display : $socf_item->item }}</p>
                        </td>
                    </tr>
                    @endif
                @endforeach
            </table>
        </div>
        <div class="page-break"></div>
        <!-- FINANCIAL NOTES / PENDING -> DIRECTOR -->
        <div class="page-div">
            <div style="width: 100%;">
                <p>
                    <b>{{ $company->name }}<br />
                    @if ($prior_company_name)
                        <b>(formerly&#160;known&#160;as&#160;{{ $prior_company_name }})</b><br />
                    @endif
                    </b>
                    (Incorporated in Malaysia)
                </p>
            </div>

            <div style="width: 100%;">
                <h3>NOTES TO THE FINANCIAL STATEMENTS <br/>FOR THE FINANCIAL YEAR ENDED {{ Str::upper(Carbon\Carbon::parse($company->current_year_to)->format('d F Y')) }}</h3>
            </div>

            <ol class="level_0">
                {!! $ntfs_config_gen_info !!}
                <li>
                    <p><b>SIGNIFICANT ACCOUNTING POLICIES</b></p>
                    {!! $ntfs_config_sig_acc_policies !!}
                </li>
                <li>
                    <p><b>CRITICAL JUDGEMENTS AND ESTIMATION UNCERTAINTY</b></p>
                    {!! $ntfs_config_est_uncertainties !!}
                </li>
                @php
                    $ppe = $sofp->where('item', 'Property, plant and equipment');
                    $cce = $sofp->where('item', 'Cash and cash equivalents');
                    $bb = $sofp->where('item', 'Bank borrowings (NCL)');
                    $lease = $sofp->where('item', 'Lease liabilities (NCL)');
                    $other_py = $sofp->where('item', 'Other payables');
                    $trade_receiveables = $sofp->where('item', 'Trade receivables');
                    $other_receiveables = $sofp->where('item', 'Other receivables');
                    $soci_profit_before_tax = $soci->firstWhere('item', 'Profit before tax');
                @endphp
                @if ($ppe)
                    <li>
                        <p><b>PROPERTY, PLANT AND EQUIPMENT</b></p>
                        <table style="width: 100%">
                            <tr>
                                <td></td>
                                <th width="15%" class="right">{{ Str::upper(Carbon\Carbon::parse($company->current_year_to)->format('Y')) }}<br/>RM</th>
                                <th width="15%" class="right">{{ Str::upper(Carbon\Carbon::parse($company->last_year_to)->format('Y')) }}<br/>RM</th>
                            </tr>
                            @foreach ($ppe as $ppe_data)
                                <tr>
                                    <td style="text-align: left">{{ $ppe_data['show_display'] ? $ppe_data['display'] : $ppe_data['item'] }}</td>
                                    <td style="text-align: center;">{{ format_number($ppe_data['this_year_amount']) }}</td>
                                    <td style="text-align: center;">{{ format_number($ppe_data['last_year_amount']) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </li>
                @endif

                @if ($trade_receiveables || $other_receiveables)
                    <li>
                        @if ($trade_receiveables)
                            <p><b>TRADE RECEIVABLES</b></p>
                            <table style="width: 100%;">
                                <tr style="text-align: center;">
                                    <td></td>
                                    <th width="15%" class="right">{{ Str::upper(Carbon\Carbon::parse($company->current_year_to)->format('Y')) }}<br/>RM</th>
                                    <th width="15%" class="right">{{ Str::upper(Carbon\Carbon::parse($company->last_year_to)->format('Y')) }}<br/>RM</th>
                                </tr>
                                @foreach ($trade_receiveables as $tr_data)
                                    <tr>
                                        <td style="text-align: left">{{ $tr_data['item'] }}</td>
                                        <td style="text-align: center;">{{ format_number($tr_data['this_year_amount']) }}</td>
                                        <td style="text-align: center;">{{ format_number($tr_data['last_year_amount']) }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif
                        @if ($other_receiveables)
                            <p>Included in the aboves are the following related party balances: </p>
                            <table style="width: 100%; margin-top: 6px;">
                                <tr style="text-align: center;">
                                    <td></td>
                                    <th width="15%" class="right">{{ Str::upper(Carbon\Carbon::parse($company->current_year_to)->format('Y')) }}<br/>RM</th>
                                    <th width="15%" class="right">{{ Str::upper(Carbon\Carbon::parse($company->last_year_to)->format('Y')) }}<br/>RM</th>
                                </tr>
                                @foreach ($other_receiveables as $or_data)
                                    <tr>
                                        <td style="text-align: left">{{ $or_data['item'] }}</td>
                                        <td style="text-align: center;">{{ format_number($or_data['this_year_amount']) }}</td>
                                        <td style="text-align: center;">{{ format_number($or_data['last_year_amount']) }}</td>
                                    </tr>
                                @endforeach
                            </table>
                            <p>The related party balances are unsecured, interest-free and repayable on demand.</p>
                        @endif
                    </li>
                @endif
                <li>
                    <p><b>AMOUNT OWING BY DIRECTORS </b></p>
                    <p>The amount owing by directors are unsecured, interest-free and repayable on demand.</p>
                </li>
                @if ($cce)
                    <li>
                        <p><b>CASH AND CASH EQUIVALENTS</b></p>
                        <p>The Company's cash management policy is to use cash and bank balances, money market instruments, bank overdrafts and short-term trade financing to manage cash flows to ensure sufficient liquidity to meet the Company's obligations.</p>
                        <table style="width: 100%; margin-top: 6px;">
                            <tr style="text-align: center;">
                                <td></td>
                                <th width="15%" class="right">{{ Str::upper(Carbon\Carbon::parse($company->current_year_to)->format('Y')) }}<br/>RM</th>
                                <th width="15%" class="right">{{ Str::upper(Carbon\Carbon::parse($company->last_year_to)->format('Y')) }}<br/>RM</th>
                            </tr>
                            @foreach ($cce as $cce_data)
                                <tr>
                                    <td style="text-align: left">{{ $cce_data['item'] }}</td>
                                    <td style="text-align: center;">{{ format_number($cce_data['this_year_amount']) }}</td>
                                    <td style="text-align: center;">{{ format_number($cce_data['last_year_amount']) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </li>
                @endif
                <li>
                    <p><b>SHARE CAPITAL</b></p>
                    <table style="width: 100%; margin-top: 6px;">
                        <tr style="text-align: center;">
                            <td></td>
                            <td colspan="2">
                                <b>{{ Str::upper(Carbon\Carbon::parse($company->current_year_to)->format('Y')) }}<br/>RM</b>
                            </td>
                            <td colspan="2">
                                <b>{{ Str::upper(Carbon\Carbon::parse($company->last_year_to)->format('Y')) }}<br/>RM</b>
                            </td>
                        </tr>
                        <tr style="text-align: center">
                            <td></td>
                            <td><b>Number of Shares Unit</b></td>
                            <td><b>Amount of Shares (RM)</b></td>
                            <td><b>Number of Shares Unit</b></td>
                            <td><b>Amount of Shares (RM)</b></td>
                        </tr>
                        <tr>
                            <td style="width:40%; text-align: left">
                                <p>Issued and fully paid ordinary shares:</p>
                                <p>At beginning and end of the financial year</p>
                            </td>
                            <td style="text-align: center;">{{ $current_ordinary_shares->sum('fully_paid_no_share') }}</td>
                            <td style="text-align: center;">{{ $current_ordinary_shares->sum('fully_paid_amount') }}</td>
                            <td style="text-align: center;">{{ $prior_ordinary_share['ordinaryFullNoShare'] }}</td>
                            <td style="text-align: center;">{{ $prior_ordinary_share['ordinaryFullPaidAmount'] }}</td>
                        </tr>
                        <tr>
                            <td style="width:40%; text-align: left">
                                <p>Issued and partially paid ordinary shares:</p>
                                <p>Issued during the financial year</p>
                            </td>
                            <td style="text-align: center;">{{ $current_ordinary_shares->sum('partially_paid_no_share') }}</td>
                            <td style="text-align: center;">{{ $current_ordinary_shares->sum('partially_paid_amount') }}</td>
                            <td style="text-align: center;">{{ $prior_ordinary_share['ordinaryPartialNoShare'] }}</td>
                            <td style="text-align: center;">{{ $prior_ordinary_share['ordinaryPartialPaidAmount'] }}</td>
                        </tr>
                        <tr>
                            <td style="width:40%; text-align: left">
                                <p>Issued and fully paid preference shares:</p>
                                <p>At beginning and end of the financial year</p>
                            </td>
                            <td style="text-align: center;">{{ $current_preference_shares->sum('fully_paid_no_share') }}</td>
                            <td style="text-align: center;">{{ $current_preference_shares->sum('fully_paid_amount') }}</td>
                            <td style="text-align: center;">{{ $prior_preference_share['preferenceFullNoShare'] }}</td>
                            <td style="text-align: center;">{{ $prior_preference_share['preferenceFullPaidAmount'] }}</td>
                        </tr>
                        <tr>
                            <td style="width:40%; text-align: left">
                                <p>Issued and partially paid preference shares:</p>
                                <p>At beginning and end of the financial year</p>
                            </td>
                            <td style="text-align: center;">{{ $current_preference_shares->sum('partially_paid_no_share') }}</td>
                            <td style="text-align: center;">{{ $current_preference_shares->sum('partially_paid_amount') }}</td>
                            <td style="text-align: center;">{{ $prior_preference_share['preferencePartialNoShare'] }}</td>
                            <td style="text-align: center;">{{ $prior_preference_share['preferencePartialPaidAmount'] }}</td>
                        </tr>
                    </table>
                </li>
                <li>
                    <p><b>RETAINED PROFITS</b></p>
                    <p>The retained profits of the Company are available for distributions by way of cash dividends or dividends in specie. Under the single-tier system of taxation, dividends payable to shareholders are deemed net of income taxes. There are no potential income tax consequences that would result from the payment of dividends to shareholders. </p>
                </li>
                @if ($bb)
                    <li>
                        <p><b>BANK BORROWINGS </b></p>
                        <table style="width: 100%; margin-top: 6px;">
                            <tr style="text-align: center;">
                                <td></td>
                                <th width="15%" class="right">{{ Str::upper(Carbon\Carbon::parse($company->current_year_to)->format('Y')) }}<br/>RM</th>
                                <th width="15%" class="right">{{ Str::upper(Carbon\Carbon::parse($company->last_year_to)->format('Y')) }}<br/>RM</th>
                            </tr>
                            @foreach ($bb as $bb_data)
                                <tr>
                                    <td style="text-align: left">{{ $bb_data['item'] }}</td>
                                    <td style="text-align: center;">{{ format_number($bb_data['this_year_amount']) }}</td>
                                    <td style="text-align: center;">{{ format_number($bb_data['last_year_amount']) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </li>
                @endif
                @if ($lease)
                    <li>
                        <p><b>LEASE LIABILITIES</b></p>
                        <table style="width: 100%; margin-top: 6px;">
                            <tr style="text-align: center;">
                                <td></td>
                                <th width="15%" class="right">{{ Str::upper(Carbon\Carbon::parse($company->current_year_to)->format('Y')) }}<br/>RM</th>
                                <th width="15%" class="right">{{ Str::upper(Carbon\Carbon::parse($company->last_year_to)->format('Y')) }}<br/>RM</th>
                            </tr>
                            @foreach ($lease as $lease_data)
                                <tr>
                                    <td style="text-align: left">{{ $lease_data['item'] }}</td>
                                    <td style="text-align: center;">{{ format_number($lease_data['this_year_amount']) }}</td>
                                    <td style="text-align: center;">{{ format_number($lease_data['last_year_amount']) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </li>
                @endif
                @if ($other_py)
                    <li>
                        <p><b>OTHER PAYABLES</b></p>
                        <table style="width: 100%; margin-top: 6px;">
                            <tr style="text-align: center;">
                                <td></td>
                                <th width="15%" class="right">{{ Str::upper(Carbon\Carbon::parse($company->current_year_to)->format('Y')) }}<br/>RM</th>
                                <th width="15%" class="right">{{ Str::upper(Carbon\Carbon::parse($company->last_year_to)->format('Y')) }}<br/>RM</th>
                            </tr>
                            @foreach ($other_py as $other_py_data)
                                    <tr>
                                        <td style="text-align: left">{{ $other_py_data['item'] }}</td>
                                        <td style="text-align: center;">{{ format_number($other_py_data['this_year_amount']) }}</td>
                                        <td style="text-align: center;">{{ format_number($other_py_data['last_year_amount']) }}</td>
                                    </tr>
                                @endforeach
                        </table>
                    </li>
                @endif
                <li>
                    <p><b>AMOUNT OWING TO DIRECTORS</b></p>
                    <p>The amount owing to directors are unsecured, interest-free and repayable on demand.</p>
                </li>
                <li>
                    <p><b>REVENUE</b></p>
                    <p>Revenue represents the net invoiced value of services rendered less returns and discounts allowed.</p>
                </li>
                <li>
                    <p><b>PROFIT BEFORE TAX </b></p>
                    <p>Profit before tax is arrived at:</p>

                    <table style="width: 100%; margin-top: 6px;">
                        <tr style="text-align: center;">
                            <td></td>
                            <th width="15%" class="right">{{ Str::upper($company->current_year) }}<br/>RM</th>
                            <th width="15%" class="right">{{ Str::upper($company->last_year) }}<br/>RM</th>
                        </tr>
                        <tr>
                            <td style="text-align: left">Audit fee</td>
                            <td width="20%" class="right">{{ format_number($soci_profit_before_tax->this_year_amount) }}</td>
                            <td width="20%" class="right">{{ format_number($soci_profit_before_tax->last_year_amount) }}</td>
                        </tr>
                    </table>
                </li>
                @if ($tax_expenses)
                    <li>
                        <p><b>TAX EXPENSE</b></p>
                        <table style="width: 100%; margin-top: 6px;">
                            <tr style="text-align: center;">
                                <td></td>
                                <th width="15%" class="right">{{ Str::upper($company->current_year) }}<br/>RM</th>
                                <th width="15%" class="right">{{ Str::upper($company->last_year) }}<br/>RM</th>
                            </tr>
                            @foreach ($tax_expenses as $tax_data)
                                <tr>
                                    <td style="text-align: left">{{ $tax_data['item'] }}</td>
                                    <td style="text-align: center;">
                                        {{ format_number($tax_data['this_year_amount']) }}
                                    </td>
                                    <td style="text-align: center;">
                                        {{ format_number($tax_data['last_year_amount']) }}
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        <p>Under the amendment of Income Tax Act 1967 by the Finance Act 2019 and with effect from year of assessment 2020, companies with paid-up capital of RM2.5 million or less, and with annual business income of not more than RM50 million are subject to Small and Medium Enterprise Corporate Tax at 17% on chargeable income up to RM600,000 except for companies with investment holding nature or companies does not have gross income from business sources are subject to corporate tax at 24% on chargeable income.</p>
                    </li>
                @endif
                @if ($declared_dividends || $proposed_dividends)
                    <li>
                        <p><b>DIVIDEND PAID</b></p>
                        <table style="width: 100%; margin-top: 6px;">
                            <tr>
                                <td>During the financial year</td>
                                <th style="text-align:center">RM</th>
                            </tr>
                            @if ($declared_dividends)
                                @foreach ($declared_dividends as $declared_dividend)
                                    <tr>
                                        <td style="width:80%">{{ $declared_dividend->dividend_type }} dividend of {{ $declared_dividend->rate_unit . $declared_dividend->rate }} per {{ $declared_dividend->dividend_type }} in respect of financial year end {{ Carbon\Carbon::parse($company->current_year_to)->format('d F Y') }}</td>
                                        <td style="text-align:center">{{ $declared_dividend->amount }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td style="text-align: center">{{ $declared_dividends->sum('amount') }}</td>
                                </tr>
                            @endif
                        </table>
                        @if ($proposed_dividends)
                            @foreach ($proposed_dividends as $proposed_dividend)
                                <p>The directors have recommended a {{ $proposed_dividend->dividend_type }} of RM{{ $proposed_dividend->rate }} per ordinary share amounting to RM{{ $proposed_dividend->amount }} in respect of the financial year ended {{ Carbon\Carbon::parse($company->current_year_to)->format('d F Y') }}.</p>
                            @endforeach
                        @endif
                    </li>
                @endif
                <li>
                    <p><b>RELATED PARTY DISCLOSURES</b></p>
                    <ol type="I">
                        <li>
                            <p><b>Control Relationships</b></p>
                            <p>As disclosed in Note 1, the Company's parent is Ganti nama company (registered and domiciled in Malaysia), which owns 90.91% of the Company's ordinary shares.</p>
                        </li>
                        @if ($director_renumerations)
                            <li>
                                <p><b>Key Management Personnel Compensation</b></p>
                                <p>The Company's director and other key management personnel compensation, including compensation paid to management entities that provide key management personnel services, for the financial year ended 31 December 2023 and comparative prior financial year are as follows:</p>
                                <table style="width: 100%; margin-top: 6px;">
                                    <tr style="text-align: center;">
                                        <td></td>
                                        <td>
                                            {{ Str::upper(Carbon\Carbon::parse($company->current_year_to)->format('Y')) }}
                                            <br>
                                            RM
                                        </td>
                                        <td>
                                            {{ Str::upper(Carbon\Carbon::parse($company->last_year_to)->format('Y')) }}
                                            <br>RM
                                        </td>
                                    </tr>
                                    @foreach ($director_renumerations as $director_renumeration)
                                        <tr>
                                            <td style="text-align: left; width:40%">{{ $director_renumeration['show_display'] ? $director_renumeration['display'] : $director_renumeration['item'] }}</td>
                                            <td style="text-align: center;">{{ format_number($director_renumeration['this_year_amount']) }}</td>
                                            <td style="text-align: center;">{{ format_number($director_renumeration['last_year_amount']) }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </li>
                        @endif
                        @if ($related_party_transactions->isNotEmpty())
                            <li>
                                <p><b>Related Party Transaction</b></p>
                                <table style="width: 100%; margin-top: 6px;">
                                    <tr style="text-align: center;">
                                        <td></td>
                                        <td>
                                            {{ Str::upper(Carbon\Carbon::parse($company->current_year_to)->format('Y')) }}
                                            <br>
                                            RM
                                        </td>
                                        <td>
                                            {{ Str::upper(Carbon\Carbon::parse($company->last_year_to)->format('Y')) }}
                                            <br>RM
                                        </td>
                                    </tr>
                                    @foreach ($related_party_transactions as $related_party_transaction)
                                        <tr>
                                            <td colspan="3">{{ $related_party_transaction->type }}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left; width:40%">{{ $related_party_transaction->show_display ? $related_party_transaction->display : $related_party_transaction->item }}</td>
                                            <td style="text-align: center;">{{ format_number($related_party_transaction->this_year_amount) }}</td>
                                            <td style="text-align: center;">{{ format_number($related_party_transaction->last_year_amount) }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </li>
                        @endif
                    </ol>
                </li>
                <li>
                    <p><b>SIGNIFICANT EVENTS</b></p>
                    <p>On 11 March 2020, the World Health Organization declared the Coronavirus (Covid-19) outbreak to be a pandemic, which has caused severe global social and economic disruptions and uncertainties, including markets where the Company operates.</p>
                    <p>The Company is actively monitoring and managing its operations to respond to these changes, the Company does not consider it practicable to provide any quantitative estimate on the potential impact it may have on the Company.</p>
                </li>
            </ol>
        </div>
        <div class="page-break"></div>
        <!-- STSOO -->
        <div class="page-fnc-div">
            <p><b>{{ $company->name }}</b></p>
            <p>(Incorporated in Malaysia)</p>
            <br/>
            <p><b>SCHEDULE TO STATEMENT OF OPERATIONS</b></p>
            <p><b>FOR THE FINANCIAL YEAR ENDED {{ Str::upper(Carbon\Carbon::parse($company->current_year_to)->format('d F Y')) }}</b></p>
            <br/>
            <table class="center" style="width: 80%">
                <tr>
                    <th></th>
                    <th width="8%">Note</th>
                    <th width="15%" class="center">{{ Str::upper($company->current_year) }}<br/>RM</th>
                    <th width="15%" class="center">{{ Str::upper(Carbon\Carbon::parse($company->last_year_from)->format('d.m.Y')) }}<br/> to<br/> {{ Str::upper(Carbon\Carbon::parse($company->last_year_to)->format('d.m.Y')) }}<br/>RM</th>
                </tr>

                @foreach ($stsoo as $stsoo_item)
                    @if ($stsoo_item->type == 'group')
                    <tr class="no-line">
                        <td colspan="4">
                            <p><b>{{ $stsoo_item['show_display'] ? $stsoo_item['display'] : $stsoo_item['item'] }}</b></p>
                        </td>
                    </tr>
                    @elseif ($stsoo_item->type == 'value')
                    <tr class="no-line">
                        <td>
                            <p>{{ $stsoo_item['show_display'] ? $stsoo_item['display'] : $stsoo_item['item'] }}</p>
                        </td>
                        <td></td>
                        <td width="15%" class="right">
                            <p>{{ format_number($stsoo_item['this_year_amount']) }}</p>
                        </td>
                        <td width="15%" class="right">
                            <p>{{ format_number($stsoo_item['last_year_amount']) }}</p>
                        </td>
                    </tr>
                    @elseif($sofp_item->type == 'total')
                    <tr class="no-line">
                        <td>
                            <p><b>{{ $stsoo_item['show_display'] ? $stsoo_item['display'] : $stsoo_item['item'] }}</b></p>
                        </td>
                        <td></td>
                        <td width="15%" class="right">
                            <p><b>{{ format_number($stsoo_item['this_year_amount']) }}</b></p>
                        </td>
                        <td width="15%" class="right">
                            <p><b>{{ format_number($stsoo_item['last_year_amount']) }}</b></p>
                        </td>
                    </tr>
                    @endif
                @endforeach
            </table>
        </div>
    </main>
</body>

</html>
