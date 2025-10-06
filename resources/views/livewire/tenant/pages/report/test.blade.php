<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Paged Report Example</title>
    <script src="https://unpkg.com/pagedjs/dist/paged.polyfill.js"></script>
    <link href="/assets/report.css" rel="stylesheet">
    <link href="/assets/interface.css" rel="stylesheet" type="text/css">
    <style>
        /* adjust visuals for inserted clones */
        .continued-heading {
            /* match the original styles (override as needed) */
            font-weight: bold;
            /* smaller top gap since we insert at top of page */
            margin-top: 0;
            margin-bottom: 0.4em;
        }

        .continued-chapter {
            font-size: 32px;
        }

        .continued-section {
            font-size: 24px;
            font-weight: 700;
        }
    </style>
</head>

<body>
<div class="cover-page">
    <div style="width: 35%;">
        <div style="text-align: justify; padding-bottom: 96px; border-bottom: 1px solid black;">
            These financial statements and reports of the Company with Qualified /
            <strike>
                Unqualified
            </strike>
            Auditors' Report for the financial year ended 17 December 2025
            were circulated on
        </div>
        <div class="text-center">
            <div class="text-bold uppercase">
                LAST SECRETARY
            </div>
            Sec1234
            <br>
            Company Secretary
            </br>
        </div>
    </div>
    <div class="text-center m-auto" style="margin-top: 30mm; width: 70%;">
        <div class="text-title uppercase">
            New Grab Sdn Bhd
        </div>
        <div class="text-title">
            (formerly known as
            <span class="uppercase">
                New Grab Sdn Bhd)
            </span>
        </div>
        <div>
            (Incorporated in Malaysia)
        </div>
        <div class="text-title" style="margin-top: 20mm;">
            REPORTS AND FINANCIAL STATEMENTS
            <br>
            FOR THE FINANCIAL PERIOD FROM 01 JUNE 2025 TO 17 DECEMBER 2025
            </br>
        </div>
        <div class="text-bold" style="margin-top: 90mm;">
            Test & CO(AF002496)
            <br>
            Chartered Accountants
            </br>
        </div>
    </div>
</div>
<div class="report-page page-break">
    <div class="ignore">
        <div class="text-title uppercase">
            New Grab Sdn Bhd
        </div>
        <div class="text-title">
            (formerly known as
            <span class="uppercase">
                New Grab Sdn Bhd
            </span>
            )
        </div>
        <div>
            (Incorporated in Malaysia)
        </div>
    </div>
    <div class="text-title chapter-title">
        DIRECTORS’ REPORT
    </div>
    <p>
        The directors hereby submit their report together with the audited financial statements of the Company for
        the financial year ended 17 December 2025.
    </p>
    <div class="section text-bold uppercase">
        Principal activities
    </div>
    <p>
        aaaa
    </p>
    <p>
        bbbb
    </p>
    <div class="section text-bold uppercase">
        Financial results
    </div>
    <table style="width: 100%">
        <tbody>
            <tr>
                <td>
                    Profit for the financial year
                </td>
                <td>
                    RM 92,450
                </td>
            </tr>
        </tbody>
    </table>
    <p>
        In the opinion of the directors , the results of the operations of the Company during the financial period
        not been substantially affected by any item, transaction or event of a material and unusual nature.
    </p>
    <div class="section text-bold uppercase">
        Dividend
    </div>
    <p>
        Since the end of the previous financial year, the Company has declared the following dividends:
    </p>
    <table>
        <tbody>
            <tr>
                <th style="width:70%, text-align: left;">
                    During the financial year
                </th>
                <th style="text-align:center">
                    RM
                </th>
            </tr>
            <tr>
                <td>
                </td>
                <td style="text-align: center">
                    0
                </td>
            </tr>
        </tbody>
    </table>
    <div class="section text-bold uppercase">
        Reserves and provisions
    </div>
    <p>
        There were no material transfers to or from reserves or provisions during the financial period.
    </p>
    <div class="section text-bold uppercase">
        Issues of shares and debentures
    </div>
    <p>
        During the financial period, the following shares were issued:
    </p>
    <table style="text-align: center">
        <tbody>
            <tr>
                <th>
                    Date of Issue
                </th>
                <th>
                    Class of Share
                </th>
                <th>
                    No. of Shares Issued
                </th>
                <th>
                    Issued Price RM
                </th>
                <th>
                    Consideration RM
                </th>
                <th>
                    Terms of Issue
                </th>
                <th>
                    Purpose
                </th>
            </tr>
            <tr>
                <td>
                    01.09.2025
                </td>
                <td>
                    Ordinary
                </td>
                <td>
                    1,111
                </td>
                <td>
                    99,998
                </td>
                <td>
                </td>
                <td>
                    Cash
                </td>
                <td>
                    Working Capital
                </td>
            </tr>
        </tbody>
    </table>
    <p>
        The new shares issued rank pari passu in respect of the distribution of dividends and repayment of capital
        with the existing shares.
    </p>
    <p>
        There were no issues of any debentures of the Company during the financial period.
    </p>
    <div class="section text-bold uppercase">
        Share options
    </div>
    <p>
        There were no share options granted to any person to take up unissued shares in the Company during the
        financial period.
    </p>
    <div class="section text-bold uppercase">
        Directors
    </div>
    <p>
        The directors of the Company in office at any time during the financial period and since the end of the
        financial period up to the date of this report are:
    </p>
    <div style="margin-left: 20px">
        <p>
            Luis Blau (Director appointed on 01.06.2025)
        </p>
        <p>
            a Director (Changed of ID on 24.06.2025)
        </p>
        <p>
            b Director (alternate director to Luis Blau) (Changed of director address on 02.07.2025)
        </p>
    </div>
    <div class="section text-bold uppercase">
        Directors’ benefits
    </div>
    <p>
        Since the end of the previous financial year, no directors have received or become entitled to receive any
        benefit (other than benefit included in the aggregate amount of remunerations received or due and receivable
        by the directors shown in the financial statements or the fixed salary of a full-time employee of the
        Company) by reason of a contract made by the Company or a related corporation with the directors or with a
        firm of which the directors are members, or with a company in which the directors have a substantial
        financial interest.
    </p>
    <p>
        There were no arrangements during and at the end of the financial period, to which the Company is a party,
        which had the object of enabling the directors to acquire benefits by means of the acquisition of shares in
        or debentures of the Company or any other body corporate.
    </p>
    <div class="section text-bold uppercase">
        Directors’ remunerations
    </div>
    <p>
        The amounts of the remunerations of the directors or past directors of the Company comprising remunerations
        received/receivable from the Company during the financial period are as follows:
    </p>
    <table style="width: 100%">
        <tbody>
            <tr style="text-align: center">
                <th>
                </th>
                <th>
                    RM
                </th>
            </tr>
            <tr>
                <td>
                    Director's fees
                </td>
                <td style="text-align: center">
                    80
                </td>
            </tr>
            <tr>
                <td>
                    Director's other emoluments
                </td>
                <td style="text-align: center">
                    80
                </td>
            </tr>
            <tr>
                <td>
                    Director's defined contribution plans
                </td>
                <td style="text-align: center">
                    80
                </td>
            </tr>
        </tbody>
    </table>
    <p>
        None of the directors or past directors of the Company have received any other benefits otherwise than in
        cash from the Company during the financial period.
    </p>
    <p>
        No payment has been paid to or payable to any third party in respect of the services provided to the Company
        by the directors or past directors of the Company during the financial period.
    </p>
    <div class="section text-bold uppercase">
        Directors’ interests
    </div>
    <p>
        According to the register of directors’ shareholdings, the interests of directors in office at the end of
        the financial period in the ordinary shares of the Company during the financial period are as follows:
    </p>
    <table style="width: 100%">
        <tbody>
            <tr>
                <td>
                </td>
                <td colspan="4" style="text-align: center">
                    <b>
                        Number of Ordinary Shares
                    </b>
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td style="text-align: center">
                    At 01.06.2025
                </td>
                <td style="text-align: center">
                    Bought
                </td>
                <td style="text-align: center">
                    Sold
                </td>
                <td style="text-align: center">
                    At 17.12.2025
                </td>
            </tr>
            <tr>
                <td style="">
                    <b>
                        Luis Blau
                    </b>
                </td>
                <td style="text-align: center">
                    2,500
                </td>
                <td style="text-align: center">
                    1,207
                </td>
                <td style="text-align: center">
                    0
                </td>
                <td style="text-align: center">
                    3,707
                </td>
            </tr>
            <tr>
                <td style="">
                    <b>
                        Unknown holder
                    </b>
                </td>
                <td style="text-align: center">
                    7,800
                </td>
                <td style="text-align: center">
                    0
                </td>
                <td style="text-align: center">
                    0
                </td>
                <td style="text-align: center">
                    7,800
                </td>
            </tr>
            <tr>
                <td colspan="5" style="height: 20px">
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td colspan="4" style="text-align: center">
                    <b>
                        Number of Preference Shares
                    </b>
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td style="text-align: center">
                    At 01.06.2025
                </td>
                <td style="text-align: center">
                    Bought
                </td>
                <td style="text-align: center">
                    Sold
                </td>
                <td style="text-align: center">
                    At 17.12.2025
                </td>
            </tr>
            <tr>
                <td style="width: 35%">
                    Ant Company
                </td>
                <td style="text-align: center">
                    3,500
                </td>
                <td style="text-align: center">
                    0
                </td>
                <td style="text-align: center">
                    0
                </td>
                <td style="text-align: center">
                    3,500
                </td>
            </tr>
            <tr>
                <td style="width: 35%">
                    b Director
                </td>
                <td style="text-align: center">
                    1,400
                </td>
                <td style="text-align: center">
                    0
                </td>
                <td style="text-align: center">
                    0
                </td>
                <td style="text-align: center">
                    1,400
                </td>
            </tr>
            <tr>
                <td style="width: 35%">
                    Luis Blau
                </td>
                <td style="text-align: center">
                    0
                </td>
                <td style="text-align: center">
                    4,500
                </td>
                <td style="text-align: center">
                    2,500
                </td>
                <td style="text-align: center">
                    2,000
                </td>
            </tr>
        </tbody>
    </table>
    <div class="section text-bold uppercase">
        Indemnity and insurance costs
    </div>
    <p>
        No indemnity was given to or insurance effected for any directors or auditors of the Company.
    </p>
    <div class="section text-bold uppercase">
        Other statutory information
    </div>
    <p>
        Before the financial statements were made out, the directors took reasonable steps:
    </p>
    <ol type="I">
        <li>
            to ascertain that action had been taken in relation to the writing-off of bad debts and the making of
            provision for doubtful debts and satisfied themselves that all known bad debts had been written-off and
            that adequate provision had been made for doubtful debts; and
        </li>
        <li>
            to ensure that any current assets which were unlikely to be realised in the ordinary course of business
            including the value of current assets as shown in the accounting records of the Company have been
            written down to an amount which the current assets might be expected so to realise.
        </li>
    </ol>
    <p>
        At the date of this report, the directors are not aware of any circumstances:
    </p>
    <ol type="I">
        <li>
            which would render the amount written-off for bad debts or the amount of the provision for doubtful
            debts inadequate to any substantial extent; or
        </li>
        <li>
            which would render the values attributed to current assets in the financial statements misleading; or
        </li>
        <li>
            which have arisen which would render adherence to the existing method of valuation of assets or
            liabilities of the Company misleading or inappropriate; or
        </li>
        <li>
            not otherwise dealt with in this report or financial statements which would render any amount stated in
            the financial statements misleading.
        </li>
    </ol>
    <p>
        At the date of this report, there does not exist:
    </p>
    <ol type="I">
        <li>
            any charge on the assets of the Company which has arisen since the end of the financial period which
            secures the liabilities of any other person; or
        </li>
        <li>
            any contingent liability which has arisen since the end of the financial period.
        </li>
    </ol>
    <p>
        No contingent or other liability has become enforceable or is likely to become enforceable within the period
        of twelve months after the end of the financial year which, in the opinion of the directors, will or may
        affect the ability of the Company to meet its obligations when they fall due.
    </p>
    <p>
        In the opinion of the directors:
    </p>
    <ol type="I">
        <li>
            the results of the operations of the Company during the financial period were not substantially affected
            by any item, transaction or event of a material and unusual nature; and
        </li>
        <li>
            there has not arisen in the interval between the end of the financial period and the date of this report
            any item, transaction or event of a material and unusual nature likely to affect substantially the
            results of the operations of the Company for the financial period in which this report is made.
        </li>
    </ol>
    <div class="section text-bold uppercase">
        Significant events
    </div>
    <p>
        Details of significant events during the financial period are disclosed in Note 25 to the financial
        statements.
    </p>
    <div class="section text-bold uppercase">
        Auditors' remunerations
    </div>
    <p>
        The total amount paid to or receivable by the auditors as remuneration for their services for the current
        financial year as auditors of the Company is RM123.32.
    </p>
    <div class="section text-bold uppercase">
        Auditors
    </div>
    <p>
        The auditors, Messrs. Test & CO have expressed their willingness to accept re-appointment.
    </p>
    <p>
    </p>
    <p>
        Signed on behalf of the Board of Directors in accordance with a resolution of the directors.
    </p>
    <p>
    </p>
    <table style="width: 100%">
        <tbody>
            <tr>
                <td colspan="2" style="height: 70px">
                </td>
            </tr>
            <tr>
                <td>
                    <p class="text-bold">
                        Luis Blau
                    </p>
                    <p>
                        Director
                    </p>
                    <p>
                        Homerusstraat 20, Address Line 2, Address Line 3, 30760 Rotterdam, Johor
                    </p>
                    <p>
                        Dated: 28 September 2025
                    </p>
                </td>
                <td>
                    <p class="text-bold">
                        a Director
                    </p>
                    <p>
                        Director
                    </p>
                    <p>
                        Jean 1/2, Ueaddfe, 123456 Puchong Jaya, Sabah
                    </p>
                    <p>
                        Dated: 28 September 2025
                    </p>
                </td>
                <td>
                    <p class="text-bold">
                        b Director
                    </p>
                    <p>
                        Director
                    </p>
                    <p>
                        nwe line1, 2222 new town, Putrajaya
                    </p>
                    <p>
                        Dated: 28 September 2025
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div class="report-page page-break">
    <div class="ignore">
        <div class="text-title uppercase">
            New Grab Sdn Bhd
        </div>
        <div class="text-title">
            (formerly known as
            <span class="uppercase">
                New Grab Sdn Bhd
            </span>
            )
        </div>
        <div>
            (Incorporated in Malaysia)
        </div>
    </div>
    <div>
        <div class="text-title chapter">
            STATEMENT BY DIRECTORS
        </div>
        <div class="text-bold">
            Pursuant to Section 251 (2) of the Companies Act 2016
        </div>
        <p>
            In the opinion of the directors, the financial statements are drawn up in accordance with Malaysian
            Private Entities Reporting Standard and the requirements of the Companies Act 2016 in Malaysia so as to
            give a true and fair view of the financial position of the Company as of 17 December 2025 and of its
            financial performance and cash flows for the financial year then ended.
        </p>
        <p>
            Signed on behalf of the Board of Directors in accordance with a resolution of
            the directors.
        </p>
        <table style="width: 100%">
            <tbody>
                <tr>
                    <td colspan="2" style="height: 70px">
                    </td>
                </tr>
                <tr>
                    <!-- Change to report info data -->
                    <td>
                        <p>
                            <b>
                                Luis Blau
                            </b>
                        </p>
                        <p>
                            DIRECTORS
                        </p>
                        <p>
                            a
                        </p>
                        <p>
                            Dated: 02 July 2025
                        </p>
                    </td>
                    <td>
                        <p>
                            <b>
                                b Director
                            </b>
                        </p>
                        <p>
                            DIRECTORS
                        </p>
                        <p>
                            a
                        </p>
                        <p>
                            Dated: 02 July 2025
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div>
        <div class="text-title uppercase">
            STATUTORY DECLARATION
        </div>
        <div class="text-bold">
            Pursuant to Section 251 (1) (b) of the Companies Act 2016
        </div>
        <p>
            I, Luis Blau (a Director), the person primarily responsible for the financial management of New Grab Sdn
            Bhd, do solemnly and sincerely declare that the financial statements are to the best of my knowledge and
            belief, correct and I make this solemn declaration conscientiously believing the same to be true, and by
            virtue of the provisions of the Statutory Declarations Act, 1960.
        </p>
        <table style="width: 100%">
            <tbody>
                <tr>
                    <td>
                        Subscribed and solemnly declared by the
                    </td>
                    <td style="width: 50%">
                        )
                    </td>
                </tr>
                <tr>
                    <td>
                        abovenamed, Luis Blau
                    </td>
                    <td style="width: 50%">
                        )
                    </td>
                </tr>
                <tr>
                    <td>
                        at d
                    </td>
                    <td style="width: 50%">
                        )
                    </td>
                </tr>
                <tr>
                    <td>
                        on this date of 02 Jul 2025
                    </td>
                    <td style="width: 50%">
                        )
                    </td>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td style="text-align: center">
                        Luis Blau
                    </td>
                </tr>
            </tbody>
        </table>
        <p>
            Before me,
        </p>
        <table>
            <tbody>
                <tr>
                    <td style="height: 70px">
                    </td>
                    <td>
                        COMMISSIONER FOR OATHS
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="report-page page-break">
    <div class="ignore">
        <div class="text-h1">
            Test & CO
        </div>
        <span>
            (AF002496)
        </span>
        <p>
            Chartered Accountants
        </p>
        <p>
            28-2, Persiaran Puteri 1, Bandar Puteri Puchong, 47100, Puchong, Johor
        </p>
        <p>
            (Tel) 0123456789 (Fax) (Email) test@mail.com
        </p>
    </div>
    <hr style="margin-top: 30px;margin-bottom: 40px;">
    <div>
        <h3>
            INDEPENDENT AUDITORS' REPORT TO THE MEMBERS OF New Grab Sdn Bhd
        </h3>
        <h4>
            (formerly known as New Grab Sdn Bhd)
        </h4>
        <div>
            (Incorporated in Malaysia)
        </div>
    </div>
    <div>
        <h4>
            Report on the Audit of the Financial Statements
        </h4>
    </div>
    <div>
        <h4>
            Qualified Opinion
        </h4>
        <p>
            We have audited the financial statements of New Grab Sdn Bhd
            (formerly known as New Grab Sdn Bhd)
            ,
            which comprise the statement of financial position as at 31 December 2023, and the statement of
            comprehensive income, statement of changes in equity and statement of cash flows for the financial year
            then ended, and notes to the financial statements, including a summary of significant accounting
            policies
        </p>
        <p>
            In our opinion, except for the effects of the matter described in the Basis for Qualified Opinion
            section of our report, the accompanying financial statements give a true and fair view of the financial
            position of the Company as at 31 December 2023, and of its financial performance and its cash flows for
            the financial year then ended in accordance with Malaysian Private Entities Reporting Standard and the
            requirements of the Companies Act 2016 in Malaysia.
        </p>
    </div>
    <div>
        <h4>
            Basis for Qualified Opinion
        </h4>
        <p>
            We conducted our audit in accordance with approved standards on auditing in Malaysia and International
            Standards on Auditing. Our responsibilities under those standards are further described in the Auditors'
            Responsibilities for the Audit of the Financial Statements section of our report. We believe that the
            audit evidence we have obtained is sufficient and appropriate to provide a basis for our qualified
            opinion.
        </p>
        <p style="text-underline-position: below">
            Independence and Other Ethical Responsibilities
        </p>
        <p>
            We are independent of the Company in accordance with the By-Laws (on Professional Ethics, Conduct and
            Practice) of the Malaysian Institute of Accountants ("By-Laws") and the International Ethics Standards
            Board for Accountants' International Code of Ethics for Professional Accountants (including
            International Independence Standards) ("IESBA Code"), and we have fulfilled our other ethical
            responsibilities in accordance with the By-Laws and the IESBA Code.
        </p>
    </div>
    <div>
        <h4>
            Material Uncertainty Related to Going Concern
        </h4>
        <p>
            We draw attention to Note 3.1 to the financial statements which discloses the premise upon which the
            Company have prepared its financial statements by applying the going concern assumption, notwithstanding
            the financial statements and performance of the Company as stated therein, thereby indicating the
            existence of a material uncertainty which may cast significant doubt about the Company's ability to
            continue as going concern. Our opinion is not modified in respect of this matter.
        </p>
    </div>
    <div>
        <h4>
            Information Other than the Financial Statements and Auditors' Report Thereon
        </h4>
        <p>
            The directors of the Company are responsible for the other information. The other information comprises
            the Directors’ Report but does not include the financial statements of the Company and our auditors'
            report thereon
        </p>
        <p>
            Our opinion on the financial statements of the Company does not cover Directors’ Report and we do not
            express any form of assurance conclusion thereon.
        </p>
        <p>
            In connection with our audit of the financial statements of the Company, our responsibility is to read
            the Directors’ Report and, in doing so, consider whether the Directors’ Report is materially
            inconsistent with the financial statements of the Company or our knowledge obtained in the audit or
            otherwise appears to be materially misstated.
        </p>
    </div>
    <div>
        <h4>
            Responsibilities of the Directors for the Financial Statements
        </h4>
        <p>
            The directors of the Company are responsible for the preparation of financial statements of the Company
            that give a true and fair view in accordance with Malaysian Private Entities Reporting Standard and the
            requirements of the Companies Act 2016 in Malaysia. The directors are also responsible for such internal
            control as the directors determine is necessary to enable the preparation of financial statements of the
            Company that are free from material misstatement, whether due to fraud or error.
        </p>
        <p>
            In preparing the financial statements of the Company, the directors are responsible for assessing the
            Company's ability to continue as a going concern, disclosing, as applicable, matters related to going
            concern and using the going concern basis of accounting unless the directors either intend to liquidate
            the Company or to cease operations, or have no realistic alternative but to do so.
        </p>
    </div>
    <div>
        <h4>
            Auditors' Responsibilities for the Audit of the Financial Statements
        </h4>
        <p>
            Our objectives are to obtain reasonable assurance about whether the financial statements of the Company
            as a whole are free from material misstatement, whether due to fraud or error, and to issue an auditors'
            report that includes our opinion. Reasonable assurance is a high level of assurance, but is not a
            guarantee that an audit conducted in accordance with approved standards on auditing in Malaysia and
            International Standards on Auditing will always detect a material misstatement when it exists.
            Misstatements can arise from fraud or error and are considered material if, individually or in the
            aggregate, they could reasonably be expected to influence the economic decisions of users taken on the
            basis of these financial statements
        </p>
        <p>
            As part of an audit in accordance with approved standards on auditing in Malaysia and International
            Standards on Auditing, we exercise professional judgement and maintain professional scepticism
            throughout the audit. We also:
        </p>
        <ul>
            <li>
                Identify and assess the risks of material misstatement of the financial statements of the Company,
                whether due to fraud or error, design and perform audit procedures responsive to those risks, and
                obtain audit evidence that is sufficient and appropriate to provide a basis for our opinion. The
                risk of not detecting a material misstatement resulting from fraud is higher than for one resulting
                from error, as fraud may involve collusion, forgery, intentional omissions, misrepresentations, or
                the override of internal control.
            </li>
            <li>
                Obtain an understanding of internal control relevant to the audit in order to design audit
                procedures that are appropriate in the circumstances, but not for the purpose of expressing an
                opinion on the effectiveness of the Company's internal control.
            </li>
            <li>
                Evaluate the appropriateness of accounting policies used and the reasonableness of accounting
                estimates and related disclosures made by the directors.
            </li>
            <li>
                Conclude on the appropriateness of the directors’ use of the going concern basis of accounting and,
                based on the audit evidence obtained, whether a material uncertainty exists related to events or
                conditions that may cast significant doubt on the Company's ability to continue as a going concern.
                If we conclude that a material uncertainty exists, we are required to draw attention in our
                auditors' report to the related disclosures in the financial statements of the Company or, if such
                disclosures are inadequate, to modify our opinion. Our conclusions are based on the audit evidence
                obtained up to the date of our auditors' report. However, future events or conditions may cause the
                Company to cease to continue as a going concern.
            </li>
            <li>
                Evaluate the overall presentation, structure and content of the financial statements of the Company,
                including the disclosures, and whether the financial statements represent the underlying
                transactions and events in a manner that achieves fair presentation.
            </li>
        </ul>
        <p>
            We communicate with the directors regarding, among other matters, the planned scope and timing of the
            audit and significant audit findings, including any significant deficiencies in internal control that we
            identify during our audit.
        </p>
    </div>
    <div>
        <h4>
            Report on Other Legal and Regulatory Requirements
        </h4>
        <p>
            In accordance with the requirements of the Companies Act 2016 in Malaysia, we report that in our
            opinion, the accounting and other records for the matter as described in the Basis for Qualified Opinion
            section have not been properly kept by the Company in accordance with the provision of the Act.
        </p>
    </div>
    <div>
        <h4>
            Other Matters
        </h4>
        <ol type="I">
            <li>
                The financial statements of the Company for the financial year ended 31 December 2021 was audited by
                another firm of Chartered Accountants whose report thereon dated 13 June 2022 expressed an
                unmodified opinion on those financial statements.
            </li>
            <li>
                This report is made solely to the members of the Company, as a body, in accordance with Section 266
                of the Companies Act 2016 in Malaysia and for no other purpose. We do not assume responsibility to
                any other person for the content of this report.
            </li>
        </ol>
    </div>
    <div style="height: 70px">
    </div>
    <!-- Change to? -->
    <table style="width: 100%; text-align: center">
        <tbody>
            <tr>
                <td>
                    <p>
                        <b>
                            Test & CO
                        </b>
                        <br>
                        AF002496
                        <br>
                        Chartered Accountants
                        </br>
                        </br>
                    </p>
                </td>
                <td>
                    <p>
                        LEE AIK HAO
                        <br>
                        03685/09/2024 J
                        <br>
                        Chartered Accountant
                        </br>
                        </br>
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
    </hr>
</div>
<!-- SOFP -->
<div class="report-page page-break">
    <div style="width: 100%;text-align: center">
        <p>
            <b>
                New Grab Sdn Bhd
                <br>
                <b>
                    (formerly known as New Grab Sdn Bhd)
                </b>
                </br>
            </b>
        </p>
        <p>
            (Incorporated in Malaysia)
        </p>
    </div>
    <br>
    <p>
        <b>
            STATEMENT OF FINANCIAL POSITION
        </b>
    </p>
    <p>
        <b>
            AS AT 17 DECEMBER 2025
        </b>
    </p>
    <br>
    <table class="center" style="width: 80%">
        <tbody>
            <tr>
                <th>
                </th>
                <th width="10%">
                    Note
                </th>
                <th class="right" width="15%">
                    2025
                    <br>
                    RM
                    </br>
                </th>
                <th class="right" width="15%">
                    2024
                    <br>
                    RM
                    </br>
                </th>
            </tr>
            <tr class="no-line">
                <td colspan="4">
                    <b>
                        NON-CURRENT ASSETS
                    </b>
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Plant and equipment
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    10,000
                </td>
                <td class="right" width="15%">
                    1,002
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Investment property
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    20
                </td>
                <td class="right" width="15%">
                    (90,000)
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    <b>
                        TOTAL NON-CURRENT ASSETS
                    </b>
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    <b>
                        10,020
                    </b>
                </td>
                <td class="right" width="15%">
                    <b>
                        (88,998)
                    </b>
                </td>
            </tr>
            <tr class="no-line">
                <td colspan="4">
                    <b>
                        CURRENT ASSETS
                    </b>
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Inventories
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    1,500
                </td>
                <td class="right" width="15%">
                    100
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Trade receivables
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    207,360
                </td>
                <td class="right" width="15%">
                    116,310
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Other receivables
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    150
                </td>
                <td class="right" width="15%">
                    150
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Current tax assets
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    50
                </td>
                <td class="right" width="15%">
                    50
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Fixed deposits
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    100
                </td>
                <td class="right" width="15%">
                    100
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Cash and bank balances
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    50
                </td>
                <td class="right" width="15%">
                    50
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    <b>
                        TOTAL CURRENT ASSETS
                    </b>
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    <b>
                        209,210
                    </b>
                </td>
                <td class="right" width="15%">
                    <b>
                        116,760
                    </b>
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    <b>
                        TOTAL ASSETS
                    </b>
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    <b>
                        219,230
                    </b>
                </td>
                <td class="right" width="15%">
                    <b>
                        27,762
                    </b>
                </td>
            </tr>
            <tr class="no-line">
                <td colspan="4">
                    <b>
                        EQUITY
                    </b>
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Share capital
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    100
                </td>
                <td class="right" width="15%">
                    100
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Retained profits
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    16,520
                </td>
                <td class="right" width="15%">
                    8,260
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    <b>
                        TOTAL EQUITY
                    </b>
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    <b>
                        16,620
                    </b>
                </td>
                <td class="right" width="15%">
                    <b>
                        8,360
                    </b>
                </td>
            </tr>
            <tr class="no-line">
                <td colspan="4">
                    <b>
                        NON-CURRENT LIABILITIES
                    </b>
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Borrowings
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    50
                </td>
                <td class="right" width="15%">
                    50
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Finance lease payables
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    50
                </td>
                <td class="right" width="15%">
                    50
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Deferred tax liabilities
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    50
                </td>
                <td class="right" width="15%">
                    50
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    <b>
                        TOTAL NON-CURRENT LIABILITIES
                    </b>
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    <b>
                        150
                    </b>
                </td>
                <td class="right" width="15%">
                    <b>
                        150
                    </b>
                </td>
            </tr>
            <tr class="no-line">
                <td colspan="4">
                    <b>
                        CURRENT LIABILITIES
                    </b>
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Trade payables
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    100
                </td>
                <td class="right" width="15%">
                    100
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Other payables
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    50
                </td>
                <td class="right" width="15%">
                    50
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Borrowings
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    200
                </td>
                <td class="right" width="15%">
                    200
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Finance lease payables
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    200
                </td>
                <td class="right" width="15%">
                    200
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Current tax liabilities
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    50
                </td>
                <td class="right" width="15%">
                    50
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    <b>
                        TOTAL CURRENT LIABILITIES
                    </b>
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    <b>
                        600
                    </b>
                </td>
                <td class="right" width="15%">
                    <b>
                        600
                    </b>
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    <b>
                        TOTAL LIABILITIES
                    </b>
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    <b>
                        750
                    </b>
                </td>
                <td class="right" width="15%">
                    <b>
                        750
                    </b>
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    <b>
                        TOTAL EQUITY AND LIABILITIES
                    </b>
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    <b>
                        17,370
                    </b>
                </td>
                <td class="right" width="15%">
                    <b>
                        9,110
                    </b>
                </td>
            </tr>
        </tbody>
    </table>
    </br>
    </br>
</div>
<!-- SOCI -->
<div class="report-page page-break">
    <div style="width: 100%;text-align: center">
        <p>
            <b>
                New Grab Sdn Bhd
                <br>
                <b>
                    (formerly known as New Grab Sdn Bhd)
                </b>
                </br>
            </b>
        </p>
        <p>
            (Incorporated in Malaysia)
        </p>
    </div>
    <br>
    <p>
        <b>
            STATEMENT OF COMPREHENSIVE INCOME
        </b>
    </p>
    <p>
        <b>
            FOR THE FINANCIAL YEAR ENDED 17 DECEMBER 2025
        </b>
    </p>
    <br>
    <table class="center" style="width: 80%">
        <tbody>
            <tr>
                <th>
                </th>
                <th width="10%">
                    Note
                </th>
                <th class="center" width="20%">
                    2025
                    <br>
                    RM
                    </br>
                </th>
                <th class="center" width="20%">
                    01.01.2024
                    <br>
                    to
                    <br>
                    29.12.2024
                    <br>
                    RM
                    </br>
                    </br>
                    </br>
                </th>
            </tr>
            <tr>
                <td>
                    Revenue
                </td>
                <td>
                </td>
                <td class="right" width="20%">
                    100,000
                </td>
                <td class="right" width="20%">
                    120,000
                </td>
            </tr>
            <tr>
                <td>
                    Cost of sales
                </td>
                <td>
                </td>
                <td class="right" width="20%">
                    (3,650)
                </td>
                <td class="right" width="20%">
                    (570)
                </td>
            </tr>
            <tr>
                <td>
                    Gross profit
                </td>
                <td>
                </td>
                <td class="right" width="20%">
                    96,350
                </td>
                <td class="right" width="20%">
                    119,430
                </td>
            </tr>
            <tr>
                <td>
                    Other operating income
                </td>
                <td>
                </td>
                <td class="right" width="20%">
                    250
                </td>
                <td class="right" width="20%">
                    500
                </td>
            </tr>
            <tr>
                <td>
                    Administrative expenses
                </td>
                <td>
                </td>
                <td class="right" width="20%">
                    (3,600)
                </td>
                <td class="right" width="20%">
                    (3,600)
                </td>
            </tr>
            <tr>
                <td>
                    Selling and distribution expenses
                </td>
                <td>
                </td>
                <td class="right" width="20%">
                    -
                </td>
                <td class="right" width="20%">
                    -
                </td>
            </tr>
            <tr>
                <td>
                    Other operating expenses
                </td>
                <td>
                </td>
                <td class="right" width="20%">
                    (80)
                </td>
                <td class="right" width="20%">
                    (80)
                </td>
            </tr>
            <tr>
                <td>
                    Profit from operations
                </td>
                <td>
                </td>
                <td class="right" width="20%">
                    92,920
                </td>
                <td class="right" width="20%">
                    116,250
                </td>
            </tr>
            <tr>
                <td>
                    Finance costs
                </td>
                <td>
                </td>
                <td class="right" width="20%">
                    (300)
                </td>
                <td class="right" width="20%">
                    (30)
                </td>
            </tr>
            <tr>
                <td>
                    Profit before tax
                </td>
                <td>
                </td>
                <td class="right" width="20%">
                    92,620
                </td>
                <td class="right" width="20%">
                    116,220
                </td>
            </tr>
            <tr>
                <td>
                    Tax expense
                </td>
                <td>
                </td>
                <td class="right" width="20%">
                    (170)
                </td>
                <td class="right" width="20%">
                    (170)
                </td>
            </tr>
            <tr>
                <td>
                    Profit for the financial year representing total comprehensive income for the financial year
                </td>
                <td>
                </td>
                <td class="right" width="20%">
                    92,450
                </td>
                <td class="right" width="20%">
                    116,050
                </td>
            </tr>
        </tbody>
    </table>
    </br>
    </br>
</div>
<!-- SOCE  -->
<div class="report-page page-break">
    <div style="width: 100%;text-align: center">
        <p>
            <b>
                New Grab Sdn Bhd
                <br>
                <b>
                    (formerly known as New Grab Sdn Bhd)
                </b>
                </br>
            </b>
        </p>
        <p>
            (Incorporated in Malaysia)
        </p>
    </div>
    <div style="width: 100%;text-align: center">
        <p>
            <b>
                STATEMENT OF CHANGES IN EQUITY
            </b>
        </p>
        <p>
            <b>
                FOR THE FINANCIAL YEAR ENDED 17 DECEMBER 2025
            </b>
        </p>
    </div>
    <table class="center" style="width: 80%">
        <tbody>
            <tr>
                <th class="left" width="40%">
                </th>
                <th class="cell-center" width="20%">
                    Share Capital
                    <br>
                    RM
                    </br>
                </th>
                <th class="cell-center">
                    (Accumulated Losses) / Retained Profits
                    <br>
                    RM
                    </br>
                </th>
                <th class="cell-center" width="15%">
                    Total
                    <br>
                    RM
                    </br>
                </th>
            </tr>
            <tr>
                <td>
                    At 01 January 2024
                </td>
                <td class="cell-center">
                    100
                </td>
                <td class="cell-center">
                    (124,310)
                </td>
                <td class="cell-center">
                    (124,210)
                </td>
            </tr>
            <tr>
                <td>
                    Profit for the financial year
                </td>
                <td class="cell-center">
                    -
                </td>
                <td class="cell-center">
                    116,050
                </td>
                <td class="cell-center">
                    116,050
                </td>
            </tr>
            <tr>
                <td>
                    At 29 December 2024 / 01 January 2024
                </td>
                <td class="cell-center">
                    100
                </td>
                <td class="cell-center">
                    (8,260)
                </td>
                <td class="cell-center">
                    (8,160)
                </td>
            </tr>
            <tr>
                <td>
                    <b>
                        Profit for the financial year
                    </b>
                </td>
                <td class="cell-center">
                    -
                </td>
                <td class="cell-center">
                    92,450
                </td>
                <td class="cell-center">
                    92,450
                </td>
            </tr>
            <tr>
                <td>
                    <b>
                        At 17 December 2025
                    </b>
                </td>
                <td class="cell-center">
                    100
                </td>
                <td class="cell-center">
                    84,190
                </td>
                <td class="cell-center">
                    84,290
                </td>
            </tr>
        </tbody>
    </table>
</div>
<!-- SOCF -->
<div class="report-page page-break">
    <div style="width: 100%;text-align: center">
        <p>
            <b>
                New Grab Sdn Bhd
                <br>
                <b>
                    (formerly known as New Grab Sdn Bhd)
                </b>
                </br>
            </b>
        </p>
        <p>
            (Incorporated in Malaysia)
        </p>
    </div>
    <p>
        <b>
            STATEMENT OF CASH FLOWS
        </b>
    </p>
    <p>
        <b>
            FOR THE FINANCIAL YEAR ENDED 17 DECEMBER 2025
        </b>
    </p>
    <table class="center" style="width: 80%">
        <tbody>
            <tr>
                <th>
                </th>
                <th width="8%">
                    Note
                </th>
                <th class="center" width="15%">
                    2025
                    <br>
                    RM
                    </br>
                </th>
                <th class="center" width="15%">
                    01.01.2024
                    <br>
                    to
                    <br>
                    29.12.2024
                    <br>
                    RM
                    </br>
                    </br>
                    </br>
                </th>
            </tr>
            <tr class="no-line">
                <td colspan="4">
                    <b>
                        CASH FLOWS FROM OPERATING ACTIVITIES
                    </b>
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Profit before tax
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    8,430
                </td>
                <td class="right" width="15%">
                    8,430
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Adjustments for:
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Depreciation of property, plant and equipment
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    -
                </td>
                <td class="right" width="15%">
                    -
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Depreciation of investment property
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    -
                </td>
                <td class="right" width="15%">
                    -
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Gain on disposal of property, plant and equipment
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    -
                </td>
                <td class="right" width="15%">
                    -
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Interest expenses
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    -
                </td>
                <td class="right" width="15%">
                    -
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Inventories written off
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    -
                </td>
                <td class="right" width="15%">
                    -
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Quit rent and assessment
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    -
                </td>
                <td class="right" width="15%">
                    -
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    qqq
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    -
                </td>
                <td class="right" width="15%">
                    -
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    qqq
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    -
                </td>
                <td class="right" width="15%">
                    -
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    <p>
                        <b>
                            Operating profit before working capital changes
                        </b>
                    </p>
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            8,430
                        </b>
                    </p>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            8,430
                        </b>
                    </p>
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Changes in inventories
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    -
                </td>
                <td class="right" width="15%">
                    -
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Changes in receivables
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    (8,260)
                </td>
                <td class="right" width="15%">
                    -
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Changes in payables
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    -
                </td>
                <td class="right" width="15%">
                    -
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    ccc
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    -
                </td>
                <td class="right" width="15%">
                    -
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    <p>
                        <b>
                            Net change in operations
                        </b>
                    </p>
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            170
                        </b>
                    </p>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            8,430
                        </b>
                    </p>
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Interest paid
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    -
                </td>
                <td class="right" width="15%">
                    -
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Tax paid
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    -
                </td>
                <td class="right" width="15%">
                    -
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    aaa
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    -
                </td>
                <td class="right" width="15%">
                    -
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    <p>
                        <b>
                            Net change in operating activities
                        </b>
                    </p>
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            170
                        </b>
                    </p>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            8,430
                        </b>
                    </p>
                </td>
            </tr>
            <tr class="no-line">
                <td colspan="4">
                    <b>
                        CASH FLOWS FROM INVESTING ACTIVITIES
                    </b>
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Purchase of property, plant and equipment
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    -
                </td>
                <td class="right" width="15%">
                    -
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Proceeds from disposal of property, plant and equipment
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    -
                </td>
                <td class="right" width="15%">
                    -
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    <p>
                        <b>
                            Net change in investing activities
                        </b>
                    </p>
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            -
                        </b>
                    </p>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            -
                        </b>
                    </p>
                </td>
            </tr>
            <tr class="no-line">
                <td colspan="4">
                    <b>
                        CASH FLOWS FROM FINANCING ACTIVITIES
                    </b>
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Interest paid
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    -
                </td>
                <td class="right" width="15%">
                    -
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Proceeds from borrowings
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    -
                </td>
                <td class="right" width="15%">
                    -
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Repayment to directors
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    -
                </td>
                <td class="right" width="15%">
                    -
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Repayment of borrowings
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    -
                </td>
                <td class="right" width="15%">
                    -
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Repayment of finance lease
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    -
                </td>
                <td class="right" width="15%">
                    -
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    <p>
                        <b>
                            Net change in financing activities
                        </b>
                    </p>
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            -
                        </b>
                    </p>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            -
                        </b>
                    </p>
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    <p>
                        <b>
                            Net change in cash and cash equivalents
                        </b>
                    </p>
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            170
                        </b>
                    </p>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            8,430
                        </b>
                    </p>
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    <p>
                        <b>
                            Cash and cash equivalents at beginning of the year
                        </b>
                    </p>
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            100
                        </b>
                    </p>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            -
                        </b>
                    </p>
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    <p>
                        <b>
                            Cash and cash equivalents at end of the year
                        </b>
                    </p>
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            270
                        </b>
                    </p>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            8,430
                        </b>
                    </p>
                </td>
            </tr>
            <tr>
                <td class="left" colspan="4">
                    <h4>
                        <b>
                            NOTE
                        </b>
                    </h4>
                    <ol type="I">
                        <li>
                            <p>
                                <b>
                                    Cash and cash equivalents
                                </b>
                            </p>
                            <p>
                                Cash and cash equivalents included in the statement above comprise the following
                                amounts:
                            </p>
                        </li>
                    </ol>
                </td>
            </tr>
            <tr>
                <th>
                </th>
                <th width="8%">
                    Note
                </th>
                <th class="center" width="15%">
                    2025
                    <br>
                    RM
                    </br>
                </th>
                <th class="center" width="15%">
                    01.01.2024
                    <br>
                    to
                    <br>
                    29.12.2024
                    <br>
                    RM
                    </br>
                    </br>
                    </br>
                </th>
            </tr>
            <tr class="no-line">
                <td>
                    Cash and bank balances
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    100
                </td>
                <td class="right" width="15%">
                    100
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Bank overdraft
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    -
                </td>
                <td class="right" width="15%">
                    -
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    <p>
                        <b>
                        </b>
                    </p>
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            100
                        </b>
                    </p>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            100
                        </b>
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<!-- FINANCIAL NOTES / PENDING -> DIRECTOR -->
<div class="report-page page-break">
    <div style="width: 100%;">
        <p>
            <b>
                New Grab Sdn Bhd
                <br>
                <b>
                    (formerly known as New Grab Sdn Bhd)
                </b>
                <br>
                </br>
                </br>
            </b>
            (Incorporated in Malaysia)
        </p>
    </div>
    <div style="width: 100%;">
        <h3>
            NOTES TO THE FINANCIAL STATEMENTS
            <br>
            FOR THE FINANCIAL YEAR ENDED 17 DECEMBER 2025
            </br>
        </h3>
    </div>
    <ol class="level_0">
        <li>
            <div class="text-bold uppercase">
                General Information
            </div>
            <p>
                The Company is a private limited liability company , incorporated and domiciled in Malaysia.
                <br>
                <br>
                The registered office is located at aaaaaaaaaa, 222222222222, 34534 Meaford, Putrajaya.
                <br>
                <br>
                The Company is principally engaged in the business of consultancy services.
                <br>
                <br>
                The financial statements are presentd in Ringgit Malaysia (RM), which is the Company's functional
                currency. All financial information is presented in RM unless otherwise stated.
                <br>
                <br>
                The financial statements were authorised for issue by the Board of Directors on declaration date.
                </br>
                </br>
                </br>
                </br>
                </br>
                </br>
                </br>
                </br>
            </p>
        </li>
        <li>
            <div class="text-bold uppercase">
                Compliance with financial reporting standards and the Companies Act 2016
            </div>
            <p>
                The financial statements of the Company have been prepared in compliance with the Malaysian Private
                Entities Reporting Standard ("MPERS") issued by the Malaysian Accounting Standards Board ("MASB")
                and the requirements of the Companies Act 2016 in Malaysia.
            </p>
        </li>
        <li>
            <div class="text-bold uppercase">
                Basis of preparation
            </div>
            <p>
                The financial statements of the Company have been prepared on the historical cost basis except as
                otherwise stated in the financial statements.
                <br>
                <br>
                Management has used estimates and assumptions in measuring the reported amounts of assets and
                liabilities at the end of the reporting period and the reported amounts of revenues and expenses
                during the reported period. Judgements and assumptions are applied in the measurement, and hence,
                the actual results may not coincide with the reported amounts. The areas involving significant
                estimation uncertainties are disclosed in Note #NoteNo# to the financial statements.
                </br>
                </br>
            </p>
            <ol class="level_1">
                <li>
                    <p class="text-bold">
                        Going Concern
                    </p>
                    <p>
                        As at 31 December 2023, the current liabilities of the Company exceeded its current assets
                        by RM3,748. This indicates the existence of an uncertainty which may cast significant doubt
                        in the ability of the Company to continue as going concern. The validity of the going
                        concern assumption is dependent upon the continuous financial support from the shareholders
                        and the ability of the Company to generate sufficient cash from its operations to enable the
                        Company to fulfill its obligations as and when they fall due.
                    </p>
                </li>
            </ol>
        </li>
        <li>
            <p>
                <b>
                    SIGNIFICANT ACCOUNTING POLICIES
                </b>
            </p>
            <p>
                The principal accounting policies adopted are set out below:
            </p>
            <ol class="level_1">
                <li>
                    <p class="text-bold">
                        Property, Plant and Equipment
                    </p>
                    <p>
                        Operating tangible assets that are used for more than one accounting period in the
                        production and supply of goods and services, for administrative purposes are recognised as
                        property, plant and equipment when the Company obtains control of the asset. These assets
                        are measured on the cost model. The assets, including major spares, stand-by equipment and
                        servicing equipment, are classified into appropriate classes based on their nature. Any
                        subsequent replacement of a significant component in an existing asset is capitalised as a
                        new component in the asset and the old component is derecognised.
                    </p>
                    <p>
                        All property, plant and equipment are initially measured at cost. For a purchased asset,
                        cost comprises purchase price plus all directly attributable costs incurred in bringing the
                        asset to its present location and condition for management's intended use. For a
                        self-constructed asset, cost comprises all direct and indirect costs of construction
                        (including provision for restoration and cost of major inspection) but excludes internal
                        profits. For an exchange of non-monetary asset that has a commercial substance, cost is
                        measured by reference to the fair value of the asset received. For an asset transferred from
                        a customer or a grantor, cost is measured by reference to the fair value of the asset.
                    </p>
                    <p>
                        All property, plant and equipment are subsequently measured at cost less accumulated
                        depreciation and accumulated impairment losses.
                    </p>
                    <p>
                        All property, plant and equipment are depreciated by allocating the depreciable amount of a
                        significant component or of an item over the remaining useful life.
                    </p>
                    <p>
                        All property, plant and equipment are depreciated by allocating the depreciable amount of a
                        significant component or of an item over the remaining useful life. The depreciation is
                        provided on #PPE_DepreciationMethod# method so as to write off the depreciation amount of
                        the following assets based on the depreciation rates as follows:
                    </p>
                    <p>
                        At the end of each reporting period, the residual values, useful lives and depreciation
                        methods for the property, plant and equipment are reviewed for reasonableness. Any change in
                        estimate of an item is adjusted prospectively over its remaining useful life, commencing in
                        the current period.
                    </p>
                </li>
                <li>
                    <p class="text-bold">
                        Impairment of Non-Financial Assets
                    </p>
                    <p>
                        An impairment loss arises when the carrying amount of a Company's asset exceeds its
                        recoverable amount.
                    </p>
                    <p>
                        At the end of each reporting date, the Company assesses whether there is any indication that
                        a stand-alone asset or a cash-generating unit may be impaired by using external and internal
                        sources of information. If any such indication exists, the Company estimates the recoverable
                        amount of the asset or cash-generating unit.
                    </p>
                    <p>
                        If an individual asset generates independent cash inflows, it is tested for impairment as a
                        stand-alone asset. If an asset does not generate independent cash inflows, it is tested for
                        impairment together with other assets in a cash-generating unit, at the lowest level in
                        which independent cash inflows are generated and monitored for internal management purposes.
                    </p>
                    <p>
                        The recoverable amount of an asset or a cash-generating unit is the higher of its fair value
                        less costs to sell and the value in use. The Company determines the fair value less costs to
                        sell of an asset or a cash-generating unit in a hierarchy based on: (i) price in a binding
                        sale agreement; (ii) market price traded in an active market; and (iii) estimate of market
                        price using the best information available. The value in use is estimated by discounting the
                        net cash inflows (by an appropriate discount rate) of the asset or unit, using reasonable
                        and supportable management's budgets and forecasts of five years and extrapolation of cash
                        inflows for periods beyond the five-year forecast or budget.
                    </p>
                    <p>
                        For an asset measured on a cost-based model, any impairment loss is recognised in profit or
                        loss.
                    </p>
                    <p>
                        For a cash-generating unit, any impairment loss is allocated to the assets of the unit pro
                        rata based on the relative carrying amounts of the assets.
                    </p>
                    <p>
                        The Company reassesses the recoverable amount of an impaired asset or a cash-generating unit
                        if there is any indication that an impairment loss recognised previously may have reversed.
                        Any reversal of impairment loss for an asset carried at a cost-based model is recognised in
                        profit or loss, subject to the limit that the revised carrying amount does not exceed the
                        amount that would have been determined had no impairment loss been recognised previously.
                    </p>
                </li>
                <li>
                    <p class="text-bold">
                        Property development cost
                    </p>
                    <p>
                        Property development costs comprise costs associated with the acquisition of land and all
                        costs directly attributable to development activities or that can be allocated on a
                        reasonable basis to these activities, such as direct building costs, and other related
                        development expenditure, including interest expenses incurred during the period of active
                        development.
                    </p>
                    <p>
                        Property development costs not recognised as an expense are recognised as an asset and are
                        stated at the lower of cost and net realisable value.
                    </p>
                </li>
                <li>
                    <p class="text-bold">
                        Financial Instruments
                    </p>
                    <ol type="I">
                        <li>
                            <p class="text-bold">
                                Initial Recognition and Measurement
                            </p>
                            <p>
                                The Company recognises a financial asset or a financial liability (including
                                derivative instruments) in the statement of financial position when, and only when,
                                an entity in the Company becomes a party to the contractual provisions of the
                                instrument.
                            </p>
                            <p>
                                On initial recognition, all financial assets and financial liabilities are measured
                                at fair value, which is generally the transaction price, plus transaction costs if
                                the financial asset or financial liability is not measured at fair value through
                                profit or loss. For instruments measured at fair value through profit or loss,
                                transaction costs are expensed to profit or loss when incurred.
                            </p>
                        </li>
                        <li>
                            <p class="text-bold">
                                Derecognition of Financial Instruments
                            </p>
                            <p>
                                A financial asset is derecognised when, and only when, the contractual rights to
                                receive the cash flows from the financial asset expire, or when the Company
                                transfers the contractual rights to receive cash flows of the financial asset,
                                including circumstances when the Company acts only as a collecting agent of the
                                transferee, and retains no significant risks and rewards of ownership of the
                                financial asset or no continuing involvement in the control of the financial asset
                                transferred.
                            </p>
                            <p>
                                A financial liability is derecognised when, and only when, it is legally
                                extinguished, which is either when the obligation specified in the contract is
                                discharged or cancelled or expires. A substantial modification of the terms of an
                                existing financial liability is accounted for as an extinguishment of the original
                                financial liability and the recognition of a new financial liability. For this
                                purpose, the Company considers a modification as substantial if the present value of
                                the revised cash flows of the modified terms discounted at the original effective
                                interest rate is different by 10% or more when compared with the carrying amount of
                                the original liability.
                            </p>
                        </li>
                        <li>
                            <p class="text-bold">
                                Subsequent Measurement of Financial Assets
                            </p>
                            <p>
                                For the purpose of subsequent measurement, the Company classifies financial assets
                                into two categories, namely: (i) financial assets at fair value through profit or
                                loss, and (ii) financial assets at amortised cost.
                            </p>
                            <p>
                                After initial recognition, the Company measures investments in preference shares,
                                ordinary shares and derivatives that are assets at their fair values by reference to
                                the active market prices, if observable, or otherwise by a valuation technique,
                                without any deduction for transaction costs it may incur on sale or other disposal.
                            </p>
                            <p>
                                Investments in debt instruments, whether quoted or unquoted, are subsequently
                                measured at amortised cost using the effective interest method. Investments in
                                unquoted equity instruments and whose fair value cannot be reliably measured are
                                measured at cost.
                            </p>
                            <p>
                                Other than financial assets measured at fair value through profit or loss, all other
                                financial assets are subject to review for impairment in accordance with Note
                                #FinancialInstrumentNoteNo#(#ImpairmentAndUncollectibility#).
                            </p>
                        </li>
                        <li>
                            <p class="text-bold">
                                Subsequent Measurement of Financial Liabilities
                            </p>
                            <p>
                                After initial recognition, all financial liabilities are measured at amortised cost
                                using the effective interest method.
                            </p>
                        </li>
                        <li>
                            <p class="text-bold">
                                Fair Value Measurement of Financial Instruments
                            </p>
                            <p>
                                The fair value of a financial asset or a financial liability is determined by
                                reference to the quoted market price in an active market, and in the absence of an
                                observable market price, by a valuation technique using reasonable and supportable
                                assumptions.
                            </p>
                        </li>
                        <li>
                            <p class="text-bold">
                                Recognition of Gains and Losses
                            </p>
                            <p>
                                Fair value changes of financial assets and financial liabilities classified as at
                                fair value through profit or loss are recognised in profit or loss when they arise.
                            </p>
                            <p>
                                For financial assets and financial liabilities carried at amortised cost, a gain or
                                loss is recognised in profit or loss only when the financial asset or financial
                                liability is derecognised or impaired, and through the amortisation process of the
                                instrument.
                            </p>
                        </li>
                        <li>
                            <p class="text-bold">
                                Impairment and Uncollectibility of Financial Assets
                            </p>
                            <p>
                                At the end of each reporting period, the Company examines whether there is any
                                objective evidence that a financial asset or a group of financial assets is
                                impaired. Evidences of trigger loss events include: (i) significant difficulty of
                                the issuer or obligor; (ii) a breach of contract, such as a default or delinquency
                                in interest or principal payment; (iii) granting exceptional concession to a
                                customer, (iv) it is probable that a customer will enter bankruptcy or other
                                financial reorganisation, (v) the disappearance of an active market for that
                                financial asset because of financial difficulties; or (vi) any observable market
                                data indicating that there may be a measurable decrease in the estimated future cash
                                flows from a group of financial assets.
                            </p>
                            <p>
                                For a non-current loan and receivable carried at amortised cost, the revised
                                estimated cash flows are discounted at the original effective interest rate. Any
                                impairment loss is recognised in profit or loss and a corresponding amount is
                                recorded in an allowance account. Any subsequent reversal of impairment loss of the
                                financial asset is reversed in profit or loss with a corresponding adjustment to the
                                allowance account, subject to the limit that the reversal should not result in the
                                revised carrying amount of the financial asset exceeding the amount that would have
                                been determined had no impairment loss been recognised previously.
                            </p>
                            <p>
                                For short-term trade and other receivables, where the effect of discounting is
                                immaterial, impairment loss is tested for each individually significant receivable
                                wherever there is any indication of impairment. Individually significant receivables
                                for which no impairment loss is recognised are grouped together with all other
                                receivables by classes based on credit risk characteristics and aged according to
                                their past due periods. A collective allowance is estimated for a class group based
                                on the Company's experience of loss ratio in each class, taking into consideration
                                current market conditions.
                            </p>
                            <p>
                                For an unquoted equity investment measured at cost less impairment, the impairment
                                is the difference between the asset's carrying amount and the best estimate (which
                                will necessarily be an approximation) of the amount (which might be zero) that the
                                Company expects to receive for the asset if it were sold at the reporting date. The
                                Company may estimate the recoverable amount using an adjusted net asset value
                                approach.
                            </p>
                        </li>
                    </ol>
                </li>
                <li>
                    <p class="text-bold">
                        Share Capital and Distributions
                    </p>
                    <ol type="I">
                        <li>
                            <p class="text-bold">
                                Share Capital
                            </p>
                            <p>
                                Ordinary shares issued that carry no put option and no mandatory contractual
                                obligation: (i) to deliver cash or another financial asset; or (ii) to exchange
                                financial assets or financial liabilities with another entity under conditions that
                                are potentially unfavourable to the Company, are classified as equity instruments.
                            </p>
                            <p>
                                When ordinary shares and other equity instruments are issued in private placement or
                                in a rights issue to existing shareholders, they are recorded at the issue price.
                                For ordinary shares and other equity instruments issued in exchange for non-monetary
                                assets, they are measured by reference to the fair values of the assets received.
                            </p>
                            <p>
                                When ordinary shares and other equity instruments are issued as consideration
                                transferred in a business combination or as settlement of an existing financial
                                liability, they are measured at fair value at the date of the exchange transaction.
                            </p>
                            <p>
                                Transaction costs of an equity transaction are accounted for as a deduction from
                                equity, net of any related income tax effect.
                            </p>
                            <p>
                                Preference shares that carry mandatory dividend payments and mandatory redemption
                                are classified as a financial liability in their entirety. Preference shares that
                                carry mandatory dividend payments only without a redemption feature or preference
                                shares that carry mandatory redemption with discretionary dividend payments are
                                accounted for a compound financial instrument. The liability component is initially
                                measured at present value of the future cash payment discounted at a market rate of
                                interest of a similar risk class debt instrument. The subsequent measurement of the
                                liability component is at amortised cost using the effective interest method.
                            </p>
                        </li>
                        <li>
                            <p class="text-bold">
                                Distributions
                            </p>
                            <p>
                                Distributions to holders of an equity instrument are debited directly in equity, net
                                of any related income tax effect.
                            </p>
                            <p>
                                A dividend declared is recognised as a liability only after it has been
                                appropriately authorised, which is the date when the Board of Directors declares an
                                interim dividend, or in the case of a proposed final dividend, the date the
                                shareholders of the Company approve the proposed final dividend in an annual general
                                meeting of shareholders. For a distribution of non-cash assets to owners, the
                                Company measures the dividend payable at the fair value of the assets to be
                                distributed.
                            </p>
                        </li>
                    </ol>
                </li>
                <li>
                    <p class="text-bold">
                        Provisions
                    </p>
                    <p>
                        The Company recognises a liability as a provision if the outflows required to settle the
                        liability are uncertain in timing or amount.
                    </p>
                    <p>
                        A provision for warranty costs, restoration costs, restructuring costs, onerous contracts or
                        lawsuit claims is recognised when the Company has a present legal or constructive obligation
                        as a result of a past event, and of which the outflows of resources on settlement are
                        probable and a reliable estimate of the amount can be made. No provision is recognised if
                        these conditions are not met.
                    </p>
                    <p>
                        Any reimbursement attributable to a recognised provision from a counter-party (such as an
                        insurer) is not off-set against the provision but recognised separately as an asset when,
                        and only when, the reimbursement is virtually certain.
                    </p>
                    <p>
                        A provision is measured at the best estimate of the expenditure required to settle the
                        present obligation at the end of the reporting period. For a warranty provision, a
                        probability-weighted expected outcome of the resources required to settle the obligation is
                        applied, taking into account the Company's experience of similar transactions and
                        supplemented with current facts and circumstances. For a restoration provision, where a
                        single obligation is being measured, the Company uses the individual most likely outcome as
                        the best estimate of the liability by reference to current prices that contractors would
                        charge to undertake such obligations, and taking into account likely future events that may
                        affect the amount required to settle an obligation. For an onerous contract, a provision is
                        measured based on the amount by which costs to fulfil the contract exceed the benefits. For
                        a lawsuit provision, a probability-weighted expected outcome is applied in the measurement,
                        taking into account past court judgements made in similar cases and advice of legal experts.
                    </p>
                    <p>
                        A provision is measured at the present value of the expenditures expected to be required to
                        settle the obligation using a discount rate that reflects the time value of money and the
                        risk that the actual outcome might differ from the estimate made. The unwinding of the
                        discount is recognised as an interest expense.
                    </p>
                </li>
                <li>
                    <p class="text-bold">
                        Income Recognition
                    </p>
                    <p>
                        The Company measures revenue from sale of goods or service transactions at the fair value of
                        the consideration received or receivable, which is usually the invoice price, net of any
                        trade discounts and volume rebates given to the customer.
                    </p>
                    <p>
                        Revenue from a sale of goods is recognised when: (a) the Company has transferred to the
                        buyer the significant risks and rewards of the ownership of the goods; (b) the Company
                        retains neither continuing managerial involvement to the degree usually associated with
                        ownership nor effective control the goods sold; (c) the amount of revenue can be measured
                        reliably; (d) it is probable that the economic benefits associated with the transaction will
                        flow to the Company; and (e) the costs incurred or to be incurred in respect of the
                        transaction can be measured reliably.
                    </p>
                </li>
                <li>
                    <p class="text-bold">
                        Employment Benefits
                    </p>
                    <p>
                        The Company recognises a liability when an employee has provided service in exchange for
                        employee benefits to be paid in the future and an expense when the Company consumes the
                        economic benefits, arising from service provided by an employee in exchange for employee
                        benefits.
                    </p>
                    <ol type="I">
                        <li>
                            <p class="text-bold">
                                Short-Term Employee Benefits
                            </p>
                            <p>
                                Wages and salaries are usually accrued and paid on a monthly basis and are
                                recognised as expenses, unless they relate to cost of producing inventories or other
                                assets.
                            </p>
                            <p>
                                Paid absences (annual leave, maternity leave, paternity leave, sick leave etc.) are
                                accrued in each period if they are accumulating paid absences that can be carried
                                forward, or in the case of non-accumulating paid absences, recognised as and when
                                the absences occur.
                            </p>
                            <p>
                                Profit sharing and bonus payments are recognised when, and only when, the Company
                                has a present legal or constructive obligation to make such payments as a result of
                                past events and a reliable estimate of the obligation can be made.
                            </p>
                        </li>
                        <li>
                            <p class="text-bold">
                                Post-Employment Benefits - Defined Contribution Plans
                            </p>
                            <p>
                                The Company makes statutory contributions to approved provident funds and the
                                contributions made are charged to profit or loss in the period to which they relate.
                                When the contributions have been paid, the Company has no further payment
                                obligations.
                            </p>
                        </li>
                    </ol>
                </li>
                <li>
                    <p class="text-bold">
                        Cash and Cash Equivalents
                    </p>
                    <p>
                        Cash and cash equivalents consist of cash on hand, balances and deposits with banks and
                        highly liquid investments which have an insignificant risk of changes in fair value with
                        original maturities of three months or less and are used by the Company in the management of
                        their short-term commitments. For the purpose of the statement of cash flows, cash and cash
                        equivalents are presented net of bank overdrafts and pledged deposits.
                    </p>
                </li>
                <li>
                    <p class="text-bold">
                        Tax Assets and Tax Liabilities
                    </p>
                    <p>
                        A current tax for current and prior periods, to the extent unpaid, is recognised as a
                        current tax liability. If the amount already paid in respect of current and prior periods
                        exceeds the amount due for those periods, the excess is recognised as a current tax asset. A
                        current tax liability (asset) is measured at the amount the entity expects to pay (recover)
                        using tax rates and laws that have been enacted or substantially enacted by the reporting
                        date.
                    </p>
                    <p>
                        A deferred tax liability is recognised for all taxable temporary differences, except to the
                        extent that the deferred tax liability arises from: (a) the initial recognition of goodwill;
                        or (b) the initial recognition of an asset or liability in a transaction which is not a
                        business combination and at the time of the transaction, affects neither accounting profit
                        nor taxable profit (or tax loss). The exceptions for initial recognition differences include
                        items of property, plant and equipment that do not qualify for capital allowances and
                        acquired intangible assets that are not deductible for tax purposes.
                    </p>
                    <p>
                        A deferred tax asset is recognised for all deductible temporary differences to the extent
                        that it is probable that taxable profit will be available against which the deductible
                        temporary difference can be utilised, unless the deferred tax asset arises from the initial
                        recognition of an asset or liability in a transaction that is not a business combination and
                        at the time of the transaction, affect neither accounting profit nor tax taxable profit (or
                        tax loss). The exceptions for the initial recognition differences include non-taxable
                        government grants received and reinvestment allowances and investment tax allowances on
                        qualifying property, plant and equipment.
                    </p>
                    <p>
                        A deferred tax asset is recognised for the carry-forward of unused tax losses and unused tax
                        credits to the extent that it is probable that future taxable profit will be available
                        against which the unused tax losses and unused tax credits can be utilised. Unused tax
                        credits do not include unabsorbed reinvestment allowances and unabsorbed investment tax
                        allowances because the Company treats these as part of initial recognition differences.
                    </p>
                    <p>
                        Deferred taxes are measured using tax rates (and tax laws) that have been enacted or
                        substantially enacted by the end of the reporting period. The measurement of deferred taxes
                        reflect the tax consequences that would follow from the manner in which an entity in the
                        Company expects, at the end of the reporting period, to recover or settle the carrying
                        amount of its assets or liabilities. For an investment property measured at fair value, the
                        Company does not have a business model to hold the property solely for rental income, and
                        hence, the deferred liability on the fair value gain is measured based on the presumption
                        that the property is recovered through sale at the end of the reporting period.
                    </p>
                    <p>
                        At the end of each reporting period, the carrying amount of a deferred tax asset is
                        reviewed, and is reduced to the extent that it is no longer probable that sufficient taxable
                        profit will be available to allow the benefit of part or all of that deferred tax to be
                        utilised. Any such reduction will be reversed to the extent that it becomes probable that
                        sufficient taxable profit will be available.
                    </p>
                    <p>
                        A current or deferred tax is recognised as income or expense in profit or loss for the
                        period. For items recognised directly in equity, the related tax effect is also recognised
                        directly in equity.
                    </p>
                </li>
                <li>
                    <p class="text-bold">
                        Borrowing Costs
                    </p>
                    <p>
                        Borrowing costs of the Company include interest on loans, finance lease liabilities and
                        interest expense of other debt instruments calculated using the effective interest method.
                        All borrowing costs are recognised as an expense when incurred.
                    </p>
                </li>
                <li>
                    <p class="text-bold">
                        Finance and Operating Lease
                    </p>
                    <p>
                        The Company recognises a lease whenever there is an agreement, whether explicitly stated as
                        a lease or otherwise, whereby the lessor conveys to the lessee in return for a payment or
                        series of payments the right to use an asset for an agreed period of time. A lease is
                        classified as a finance lease if it transfers substantially all the risks and rewards
                        incidental to ownership. Title may or may not eventually be transferred. All other leases
                        that do not meet this criterion are classified as operating leases.
                    </p>
                    <p>
                        If an entity in the Company is a lessee, it capitalises the underlying asset and the related
                        lease liability in a finance lease. The amount recognised at the commencement date is the
                        fair value of the underlying leased asset or, if lower, the present value of the minimum
                        lease payments, each determined at the inception of the lease. The discount rate used in
                        calculating the present value of the minimum lease payments is the interest rate implicit in
                        the lease, if this is practicable to determine; if not, the lessee's incremental borrowing
                        rate is used. Any initial direct costs of the lease are added to the amount recognised as an
                        asset.
                    </p>
                    <p>
                        Minimum lease payments are apportioned between the finance charge and the reduction of the
                        outstanding liability. The finance charge is allocated to each period during the lease term
                        so as to produce a constant periodic rate of interest on the remaining balance of the
                        liability. Contingent rents are charged as expenses in the periods in which they are
                        incurred.
                    </p>
                    <p>
                        Capitalised leased assets are classified by nature and accounted for in accordance with
                        applicable Standard in MPERS. If there is no reasonable certainty that the lessee will
                        obtain ownership by the end of the lease term, the asset is depreciated over the shorter of
                        the lease term and its useful life.
                    </p>
                    <p>
                        For operating lease, a lessee in the Company does not capitalise the underlying leased asset
                        or recognise a lease liability. Instead, lease payments under an operating lease are
                        recognised as an expense on the straight-line basis over the lease term unless another
                        systematic basis is more representative of the time pattern of the user's benefit.
                    </p>
                </li>
            </ol>
        </li>
        <li>
            <p>
                <b>
                    CRITICAL JUDGEMENTS AND ESTIMATION UNCERTAINTY
                </b>
            </p>
            <ol class="level_1">
                <li>
                    <p class="text-bold">
                        Judgements and Assumptions Applied
                    </p>
                    <p>
                        In the selection of accounting policies for the Company, the areas that require significant
                        judgements and assumptions are as follows:
                    </p>
                    <ol type="I">
                        <li>
                            <p class="text-bold">
                                Classification of Investment Property
                            </p>
                            <p>
                                Certain property comprises of a portion that is held to earn rental income or
                                capital appreciation, or for both, whilst the remaining portion is held for use in
                                the production or supply of goods and services or for administrative purposes. If
                                the portion held for rental and/or capital appreciation could be sold separately (or
                                leased out separately as a finance lease), the Company accounts for that portion as
                                an investment property. If the portion held for rental and/or capital appreciation
                                could not be sold or leased out separately, it is classified as an investment
                                property only if an insignificant portion of the property is held for use in the
                                production or supply or goods and services or for administrative purposes.
                                Management uses its judgement to determine whether any ancillary services are of
                                such significance that a property does not qualify as an investment property.
                            </p>
                        </li>
                        <li>
                            <p class="text-bold">
                                Classification of Finance and Operating Lease
                            </p>
                            <p>
                                The Company classifies a lease as a finance lease or an operating lease based on the
                                criterion of the extent to which significant risks and rewards incident to ownership
                                of the underlying asset lie. As a lessee, the Company recognises a lease as a
                                finance lease if it is exposed to significant risks and rewards incident to
                                ownership of the underlying asset. In applying judgements, the Company considers
                                whether there is significant economic incentive to exercise a purchase option and
                                any optional renewal periods. A lease is classified as a finance lease if the lease
                                term is for at least 75% of the remaining economic life of the underlying asset, the
                                present value of lease payments is at least 90% of the fair value of the underlying
                                asset, or the identified asset in the lease is a specialised asset which can only be
                                used substantially by the lessee. All other leases that do not result in a
                                significant transfer of risks and rewards are classified as operating leases. The
                                Company classifies a lease of land as a finance lease if the fair value of the
                                leasehold land is 90% or more of the fair value of an equivalent freehold land or if
                                the lease period, determined at the inception of the lease, is 50 years or more.
                                Leases of land that do not meet any of these criteria are classified as operating
                                leases.
                            </p>
                        </li>
                    </ol>
                </li>
                <li>
                    <p class="text-bold">
                        Sources of Estimation Uncertainty
                    </p>
                    <p>
                        The measurement of some assets and liabilities requires management to use estimates based on
                        various observable inputs and other assumptions. The areas or items that are subject to
                        significant estimation uncertainties of the Company are as follows:
                    </p>
                    <ol type="I">
                        <li>
                            <p class="text-bold">
                                Measurement of a Provision
                            </p>
                            <p>
                                The Company uses a "best estimate" as the basis for measuring a provision.
                                Management evaluates the estimates based on the Company's historical experiences and
                                other inputs or assumptions, current developments and future events that are
                                reasonably possible under the particular circumstances. In the case when a provision
                                relates to large population of customers (such as a warranty provision), a
                                probability-weighted estimate of the outflows required to settle the obligation is
                                used. In the case of a single estimate (such as a provision for environmental
                                restoration costs), a referenced contractor's price or market price is used as the
                                best estimate. If an obligation is to be settled over time, the expected outflows
                                are discounted at a rate that takes into account the time value of money and the
                                risk that the actual outcome might differ from the estimates made. The actual
                                outcome may differ from the estimate made and this may have a significant effect on
                                the Company's financial position and results.
                            </p>
                        </li>
                        <li>
                            <p class="text-bold">
                                Determining the Value-in-Use
                            </p>
                            <p>
                                In determining the value-in-use of a stand-alone asset or cash-generating unit,
                                management uses reasonable and supportable inputs about sales, cost of sales and
                                other expenses based upon past experience, current events and reasonably possible
                                future developments. Cash flows are projected based on those inputs and discounted
                                at an appropriate discount rate(s). The actual outcome or event may not coincide
                                with the inputs or assumptions and the discount rate applied in the measurement, and
                                this may have a significant effect on the Company's financial position and results.
                            </p>
                        </li>
                        <li>
                            <p class="text-bold">
                                Impairment or Write-Down of Slow-Moving and Obsolete Inventories
                            </p>
                            <p>
                                The Company writes down its slow-moving and obsolete inventories based on assessment
                                of their fair value less costs to sell. Inventories are written down when events and
                                circumstances indicate that the carrying amounts may not recoverable. Management
                                uses its judgement to analyse past sales trend and current economic trends to
                                evaluate the adequacy of the impairment loss for slow-moving and obsolete
                                inventories. The actual impairment loss can only be confirmed in any subsequent
                                sales of those inventories and this may differ from the estimates made earlier. This
                                may affect the Company's financial position and results.
                            </p>
                        </li>
                        <li>
                            <p class="text-bold">
                                Loss Allowances of Financial Assets
                            </p>
                            <p>
                                The Company recognises impairment losses for loans and receivables using the
                                incurred loss model. Individually significant loans and receivables are tested for
                                impairment separately by estimating the cash flows expected to be recoverable. All
                                other loans and receivables are categorised into credit risk classes and tested for
                                impairment collectively, using the Company's past experience of loss statistics,
                                ageing of past due amounts and current economic trends. The actual eventual losses
                                may be different from the allowance made and this may affect the Company's financial
                                position and results.
                            </p>
                        </li>
                        <li>
                            <p class="text-bold">
                                Depreciation of Property, Plant and Equipment
                            </p>
                            <p>
                                The cost of an item of property, plant and equipment is depreciated on the
                                #PPE_DepreciationMethod# method or another systematic method that reflects the
                                consumption of the economic benefits of the asset over its useful life. Estimates
                                are applied in the selection of the depreciation method, the useful lives and the
                                residual values. The actual consumption of the economic benefits of the property,
                                plant and equipment may differ from the estimates applied and this may lead to a
                                gain or loss on an eventual disposal of an item of property, plant and equipment.
                            </p>
                        </li>
                        <li>
                            <p class="text-bold">
                                Measurement of Income Taxes
                            </p>
                            <p>
                                Significant judgement is required in determining the Company's provision for current
                                and deferred taxes because the ultimate tax liability for the Company is uncertain.
                                When the final outcome of the taxes payable is determined with the tax authorities,
                                the amounts might be different for the initial estimates of the taxes payables. Such
                                differences may impact the current and deferred taxes in the period when such
                                determination is made. The Company will adjust for the differences as over or
                                under-provision of current or deferred taxes in the current period in which those
                                differences arise.
                            </p>
                        </li>
                        <li>
                            <p class="text-bold">
                                Measurement of Revenue and Expenses in Construction Contracts
                            </p>
                            <p>
                                The Company applies the percentage of completion method to account for all of its
                                construction contracts with customer. This method requires reliable estimation of
                                future outcomes that invariably must rely on estimates stage of completion, future
                                revenues, future costs, and collectability of progress billing. Internal budgets and
                                forecasts are used in these estimates. The actual outcome will only be known when a
                                contract is completed, and this actual outcome may not coincide with the estimates
                                made.
                            </p>
                        </li>
                    </ol>
                </li>
            </ol>
        </li>
        <li>
            <p>
                <b>
                    PROPERTY, PLANT AND EQUIPMENT
                </b>
            </p>
            <table style="width: 100%">
                <tbody>
                    <tr>
                        <td>
                        </td>
                        <th class="right" width="15%">
                            2025
                            <br>
                            RM
                            </br>
                        </th>
                        <th class="right" width="15%">
                            2024
                            <br>
                            RM
                            </br>
                        </th>
                    </tr>
                    <tr>
                        <td style="text-align: left">
                            Plant and equipment
                        </td>
                        <td style="text-align: center;">
                            10,000
                        </td>
                        <td style="text-align: center;">
                            1,002
                        </td>
                    </tr>
                </tbody>
            </table>
        </li>
        <li>
            <p>
                <b>
                    TRADE RECEIVABLES
                </b>
            </p>
            <table style="width: 100%;">
                <tbody>
                    <tr style="text-align: center;">
                        <td>
                        </td>
                        <th class="right" width="15%">
                            2025
                            <br>
                            RM
                            </br>
                        </th>
                        <th class="right" width="15%">
                            2024
                            <br>
                            RM
                            </br>
                        </th>
                    </tr>
                    <tr>
                        <td style="text-align: left">
                            Trade receivables
                        </td>
                        <td style="text-align: center;">
                            207,360
                        </td>
                        <td style="text-align: center;">
                            116,310
                        </td>
                    </tr>
                </tbody>
            </table>
            <p>
                Included in the aboves are the following related party balances:
            </p>
            <table style="width: 100%; margin-top: 6px;">
                <tbody>
                    <tr style="text-align: center;">
                        <td>
                        </td>
                        <th class="right" width="15%">
                            2025
                            <br>
                            RM
                            </br>
                        </th>
                        <th class="right" width="15%">
                            2024
                            <br>
                            RM
                            </br>
                        </th>
                    </tr>
                    <tr>
                        <td style="text-align: left">
                            Other receivables
                        </td>
                        <td style="text-align: center;">
                            150
                        </td>
                        <td style="text-align: center;">
                            150
                        </td>
                    </tr>
                </tbody>
            </table>
            <p>
                The related party balances are unsecured, interest-free and repayable on demand.
            </p>
        </li>
        <li>
            <p>
                <b>
                    AMOUNT OWING BY DIRECTORS
                </b>
            </p>
            <p>
                The amount owing by directors are unsecured, interest-free and repayable on demand.
            </p>
        </li>
        <li>
            <p>
                <b>
                    CASH AND CASH EQUIVALENTS
                </b>
            </p>
            <p>
                The Company's cash management policy is to use cash and bank balances, money market instruments,
                bank overdrafts and short-term trade financing to manage cash flows to ensure sufficient liquidity
                to meet the Company's obligations.
            </p>
            <table style="width: 100%; margin-top: 6px;">
                <tbody>
                    <tr style="text-align: center;">
                        <td>
                        </td>
                        <th class="right" width="15%">
                            2025
                            <br>
                            RM
                            </br>
                        </th>
                        <th class="right" width="15%">
                            2024
                            <br>
                            RM
                            </br>
                        </th>
                    </tr>
                    <tr>
                        <td style="text-align: left">
                            Cash and cash equivalents
                        </td>
                        <td style="text-align: center;">
                            50
                        </td>
                        <td style="text-align: center;">
                            50
                        </td>
                    </tr>
                </tbody>
            </table>
        </li>
        <li>
            <p>
                <b>
                    SHARE CAPITAL
                </b>
            </p>
            <table style="width: 100%; margin-top: 6px;">
                <tbody>
                    <tr style="text-align: center;">
                        <td>
                        </td>
                        <td colspan="2">
                            <b>
                                2025
                                <br>
                                RM
                                </br>
                            </b>
                        </td>
                        <td colspan="2">
                            <b>
                                2024
                                <br>
                                RM
                                </br>
                            </b>
                        </td>
                    </tr>
                    <tr style="text-align: center">
                        <td>
                        </td>
                        <td>
                            <b>
                                Number of Shares Unit
                            </b>
                        </td>
                        <td>
                            <b>
                                Amount of Shares (RM)
                            </b>
                        </td>
                        <td>
                            <b>
                                Number of Shares Unit
                            </b>
                        </td>
                        <td>
                            <b>
                                Amount of Shares (RM)
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:40%; text-align: left">
                            <p>
                                Issued and fully paid ordinary shares:
                            </p>
                            <p>
                                At beginning and end of the financial year
                            </p>
                        </td>
                        <td style="text-align: center;">
                            0
                        </td>
                        <td style="text-align: center;">
                            99998
                        </td>
                        <td style="text-align: center;">
                        </td>
                        <td style="text-align: center;">
                        </td>
                    </tr>
                    <tr>
                        <td style="width:40%; text-align: left">
                            <p>
                                Issued and partially paid ordinary shares:
                            </p>
                            <p>
                                Issued during the financial year
                            </p>
                        </td>
                        <td style="text-align: center;">
                            0
                        </td>
                        <td style="text-align: center;">
                            5678
                        </td>
                        <td style="text-align: center;">
                        </td>
                        <td style="text-align: center;">
                        </td>
                    </tr>
                    <tr>
                        <td style="width:40%; text-align: left">
                            <p>
                                Issued and fully paid preference shares:
                            </p>
                            <p>
                                At beginning and end of the financial year
                            </p>
                        </td>
                        <td style="text-align: center;">
                            0
                        </td>
                        <td style="text-align: center;">
                            0
                        </td>
                        <td style="text-align: center;">
                        </td>
                        <td style="text-align: center;">
                        </td>
                    </tr>
                    <tr>
                        <td style="width:40%; text-align: left">
                            <p>
                                Issued and partially paid preference shares:
                            </p>
                            <p>
                                At beginning and end of the financial year
                            </p>
                        </td>
                        <td style="text-align: center;">
                            0
                        </td>
                        <td style="text-align: center;">
                            0
                        </td>
                        <td style="text-align: center;">
                        </td>
                        <td style="text-align: center;">
                        </td>
                    </tr>
                </tbody>
            </table>
        </li>
        <li>
            <p>
                <b>
                    RETAINED PROFITS
                </b>
            </p>
            <p>
                The retained profits of the Company are available for distributions by way of cash dividends or
                dividends in specie. Under the single-tier system of taxation, dividends payable to shareholders are
                deemed net of income taxes. There are no potential income tax consequences that would result from
                the payment of dividends to shareholders.
            </p>
        </li>
        <li>
            <p>
                <b>
                    BANK BORROWINGS
                </b>
            </p>
            <table style="width: 100%; margin-top: 6px;">
                <tbody>
                    <tr style="text-align: center;">
                        <td>
                        </td>
                        <th class="right" width="15%">
                            2025
                            <br>
                            RM
                            </br>
                        </th>
                        <th class="right" width="15%">
                            2024
                            <br>
                            RM
                            </br>
                        </th>
                    </tr>
                    <tr>
                        <td style="text-align: left">
                            Bank borrowings (NCL)
                        </td>
                        <td style="text-align: center;">
                            50
                        </td>
                        <td style="text-align: center;">
                            50
                        </td>
                    </tr>
                </tbody>
            </table>
        </li>
        <li>
            <p>
                <b>
                    LEASE LIABILITIES
                </b>
            </p>
            <table style="width: 100%; margin-top: 6px;">
                <tbody>
                    <tr style="text-align: center;">
                        <td>
                        </td>
                        <th class="right" width="15%">
                            2025
                            <br>
                            RM
                            </br>
                        </th>
                        <th class="right" width="15%">
                            2024
                            <br>
                            RM
                            </br>
                        </th>
                    </tr>
                    <tr>
                        <td style="text-align: left">
                            Lease liabilities (NCL)
                        </td>
                        <td style="text-align: center;">
                            50
                        </td>
                        <td style="text-align: center;">
                            50
                        </td>
                    </tr>
                </tbody>
            </table>
        </li>
        <li>
            <p>
                <b>
                    OTHER PAYABLES
                </b>
            </p>
            <table style="width: 100%; margin-top: 6px;">
                <tbody>
                    <tr style="text-align: center;">
                        <td>
                        </td>
                        <th class="right" width="15%">
                            2025
                            <br>
                            RM
                            </br>
                        </th>
                        <th class="right" width="15%">
                            2024
                            <br>
                            RM
                            </br>
                        </th>
                    </tr>
                    <tr>
                        <td style="text-align: left">
                            Other payables
                        </td>
                        <td style="text-align: center;">
                            50
                        </td>
                        <td style="text-align: center;">
                            50
                        </td>
                    </tr>
                </tbody>
            </table>
        </li>
        <li>
            <p>
                <b>
                    AMOUNT OWING TO DIRECTORS
                </b>
            </p>
            <p>
                The amount owing to directors are unsecured, interest-free and repayable on demand.
            </p>
        </li>
        <li>
            <p>
                <b>
                    REVENUE
                </b>
            </p>
            <p>
                Revenue represents the net invoiced value of services rendered less returns and discounts allowed.
            </p>
        </li>
        <li>
            <p>
                <b>
                    PROFIT BEFORE TAX
                </b>
            </p>
            <p>
                Profit before tax is arrived at:
            </p>
            <table style="width: 100%; margin-top: 6px;">
                <tbody>
                    <tr style="text-align: center;">
                        <td>
                        </td>
                        <th class="right" width="15%">
                            2025
                            <br>
                            RM
                            </br>
                        </th>
                        <th class="right" width="15%">
                            2024
                            <br>
                            RM
                            </br>
                        </th>
                    </tr>
                    <tr>
                        <td style="text-align: left">
                            Audit fee
                        </td>
                        <td class="right" width="20%">
                            92,620
                        </td>
                        <td class="right" width="20%">
                            116,220
                        </td>
                    </tr>
                </tbody>
            </table>
        </li>
        <li>
            <p>
                <b>
                    TAX EXPENSE
                </b>
            </p>
            <table style="width: 100%; margin-top: 6px;">
                <tbody>
                    <tr style="text-align: center;">
                        <td>
                        </td>
                        <th class="right" width="15%">
                            2025
                            <br>
                            RM
                            </br>
                        </th>
                        <th class="right" width="15%">
                            2024
                            <br>
                            RM
                            </br>
                        </th>
                    </tr>
                    <tr>
                        <td style="text-align: left">
                            Income tax expense - current year
                        </td>
                        <td style="text-align: center;">
                            100
                        </td>
                        <td style="text-align: center;">
                            100
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left">
                            Deferred tax expense - current year
                        </td>
                        <td style="text-align: center;">
                            20
                        </td>
                        <td style="text-align: center;">
                            20
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left">
                            Income tax expense - under/over in prior year
                        </td>
                        <td style="text-align: center;">
                            100
                        </td>
                        <td style="text-align: center;">
                            100
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left">
                            Deferred tax expense - under/over in prior year
                        </td>
                        <td style="text-align: center;">
                            (50)
                        </td>
                        <td style="text-align: center;">
                            (50)
                        </td>
                    </tr>
                </tbody>
            </table>
            <p>
                Under the amendment of Income Tax Act 1967 by the Finance Act 2019 and with effect from year of
                assessment 2020, companies with paid-up capital of RM2.5 million or less, and with annual business
                income of not more than RM50 million are subject to Small and Medium Enterprise Corporate Tax at 17%
                on chargeable income up to RM600,000 except for companies with investment holding nature or
                companies does not have gross income from business sources are subject to corporate tax at 24% on
                chargeable income.
            </p>
        </li>
        <li>
            <p>
                <b>
                    DIVIDEND PAID
                </b>
            </p>
            <table style="width: 100%; margin-top: 6px;">
                <tbody>
                    <tr>
                        <td>
                            During the financial year
                        </td>
                        <th style="text-align:center">
                            RM
                        </th>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td style="text-align: center">
                            0
                        </td>
                    </tr>
                </tbody>
            </table>
        </li>
        <li>
            <p>
                <b>
                    RELATED PARTY DISCLOSURES
                </b>
            </p>
            <ol type="I">
                <li>
                    <p>
                        <b>
                            Control Relationships
                        </b>
                    </p>
                    <p>
                        As disclosed in Note 1, the Company's parent is Ganti nama company (registered and domiciled
                        in Malaysia), which owns 90.91% of the Company's ordinary shares.
                    </p>
                </li>
                <li>
                    <p>
                        <b>
                            Key Management Personnel Compensation
                        </b>
                    </p>
                    <p>
                        The Company's director and other key management personnel compensation, including
                        compensation paid to management entities that provide key management personnel services, for
                        the financial year ended 31 December 2023 and comparative prior financial year are as
                        follows:
                    </p>
                    <table style="width: 100%; margin-top: 6px;">
                        <tbody>
                            <tr style="text-align: center;">
                                <td>
                                </td>
                                <td>
                                    2025
                                    <br>
                                    RM
                                    </br>
                                </td>
                                <td>
                                    2024
                                    <br>
                                    RM
                                    </br>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: left; width:40%">
                                    Director's fees
                                </td>
                                <td style="text-align: center;">
                                    80
                                </td>
                                <td style="text-align: center;">
                                    80
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: left; width:40%">
                                    Director's other emoluments
                                </td>
                                <td style="text-align: center;">
                                    80
                                </td>
                                <td style="text-align: center;">
                                    80
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: left; width:40%">
                                    Director's defined contribution plans
                                </td>
                                <td style="text-align: center;">
                                    80
                                </td>
                                <td style="text-align: center;">
                                    80
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </li>
            </ol>
        </li>
        <li>
            <p>
                <b>
                    SIGNIFICANT EVENTS
                </b>
            </p>
            <p>
                On 11 March 2020, the World Health Organization declared the Coronavirus (Covid-19) outbreak to be a
                pandemic, which has caused severe global social and economic disruptions and uncertainties,
                including markets where the Company operates.
            </p>
            <p>
                The Company is actively monitoring and managing its operations to respond to these changes, the
                Company does not consider it practicable to provide any quantitative estimate on the potential
                impact it may have on the Company.
            </p>
        </li>
    </ol>
</div>
<!-- STSOO -->
<div class="report-page page-break">
    <p>
        <b>
            New Grab Sdn Bhd
        </b>
    </p>
    <p>
        (Incorporated in Malaysia)
    </p>
    <br>
    <p>
        <b>
            SCHEDULE TO STATEMENT OF OPERATIONS
        </b>
    </p>
    <p>
        <b>
            FOR THE FINANCIAL YEAR ENDED 17 DECEMBER 2025
        </b>
    </p>
    <br>
    <table class="center" style="width: 80%">
        <tbody>
            <tr>
                <th>
                </th>
                <th width="8%">
                    Note
                </th>
                <th class="center" width="15%">
                    2025
                    <br>
                    RM
                    </br>
                </th>
                <th class="center" width="15%">
                    01.01.2024
                    <br>
                    to
                    <br>
                    29.12.2024
                    <br>
                    RM
                    </br>
                    </br>
                    </br>
                </th>
            </tr>
            <tr class="no-line">
                <td colspan="4">
                    <b>
                        REVENUE
                    </b>
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Sales
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    100,000
                </td>
                <td class="right" width="15%">
                    120,000
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    qwe
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    -
                </td>
                <td class="right" width="15%">
                    -
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    <p>
                        <b>
                        </b>
                    </p>
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            100,000
                        </b>
                    </p>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            120,000
                        </b>
                    </p>
                </td>
            </tr>
            <tr class="no-line">
                <td colspan="4">
                    <b>
                        COST OF SALES
                    </b>
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Opening inventories
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    100
                </td>
                <td class="right" width="15%">
                    120
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Purchases
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    5,000
                </td>
                <td class="right" width="15%">
                    500
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Repair and services
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    50
                </td>
                <td class="right" width="15%">
                    50
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Less: Closing inventories
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    (1,500)
                </td>
                <td class="right" width="15%">
                    (100)
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    <p>
                        <b>
                        </b>
                    </p>
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            3,650
                        </b>
                    </p>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            570
                        </b>
                    </p>
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    <p>
                        <b>
                            GROSS PROFIT
                        </b>
                    </p>
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            96,350
                        </b>
                    </p>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            119,430
                        </b>
                    </p>
                </td>
            </tr>
            <tr class="no-line">
                <td colspan="4">
                    <b>
                        ADD: OTHER OPERATING INCOME
                    </b>
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Gain on disposal of plant and equipment
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    50
                </td>
                <td class="right" width="15%">
                    100
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Interest income
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    50
                </td>
                <td class="right" width="15%">
                    100
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Management fees
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    100
                </td>
                <td class="right" width="15%">
                    200
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Other income
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    50
                </td>
                <td class="right" width="15%">
                    100
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    <p>
                        <b>
                        </b>
                    </p>
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            250
                        </b>
                    </p>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            500
                        </b>
                    </p>
                </td>
            </tr>
            <tr class="no-line">
                <td colspan="4">
                    <b>
                        ADMINISTRATIVE EXPENSES
                    </b>
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Advertisement
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Administration and processing fees
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Audit fee - Current year
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Audit fee - Overprovision in prior year
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Bank charges
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Commission
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Compensation
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Compound, fines and penalty
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Credit card charges
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Director's fees
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Director's other emoluments
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Director's defined contribution plans
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    EPF, SOCSO and EIS contributions
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Filing fees
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Franchise development cost
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    General expenses
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Gift and donation
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Guarantee fee
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    HRDF contribution
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Insurance and road tax
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    License fees
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Online sales processing fee
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Petrol, toll and parking
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Postage and courier
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Printing and stationery
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Professional fees
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Promotional and marketing expenses
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Processing fees
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Rental of outlets
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Salaries, allowances and commission
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Staff medical
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Staff welfare
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Secretarial fee
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Service tax
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Stamping fee
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Staff meal and refreshment
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Security subscription services
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Telephone charges
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Travelling expenses
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Tax service fee
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Upkeep of computer and software
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Upkeep of office
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Upkeep of motor vehicle
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Water and electricity
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Withholding tax
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    <p>
                        <b>
                        </b>
                    </p>
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            3,600
                        </b>
                    </p>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            3,600
                        </b>
                    </p>
                </td>
            </tr>
            <tr class="no-line">
                <td colspan="4">
                    <b>
                        OTHER OPERATING EXPENSE
                    </b>
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Depreciation of plant and equipment
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    80
                </td>
                <td class="right" width="15%">
                    80
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    <p>
                        <b>
                        </b>
                    </p>
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            80
                        </b>
                    </p>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            80
                        </b>
                    </p>
                </td>
            </tr>
            <tr class="no-line">
                <td colspan="4">
                    <b>
                        FINANCE COSTS
                    </b>
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Banker's acceptance interest
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    100
                </td>
                <td class="right" width="15%">
                    10
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Finance lease interest
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    100
                </td>
                <td class="right" width="15%">
                    10
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Term loan interest
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    100
                </td>
                <td class="right" width="15%">
                    10
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    <p>
                        <b>
                        </b>
                    </p>
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            300
                        </b>
                    </p>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            30
                        </b>
                    </p>
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    <p>
                        <b>
                            TOTAL OPERATING EXPENSES
                        </b>
                    </p>
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            3,980
                        </b>
                    </p>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            3,710
                        </b>
                    </p>
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    <p>
                        <b>
                            PROFIT BEFORE TAX
                        </b>
                    </p>
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            92,620
                        </b>
                    </p>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            116,220
                        </b>
                    </p>
                </td>
            </tr>
            <tr class="no-line">
                <td colspan="4">
                    <b>
                        LESS: INCOME TAX EXPENSE
                    </b>
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Income tax expense - current year
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    100
                </td>
                <td class="right" width="15%">
                    100
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Deferred tax expense - current year
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    20
                </td>
                <td class="right" width="15%">
                    20
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Income tax expense - under/over in prior year
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    100
                </td>
                <td class="right" width="15%">
                    100
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    Deferred tax expense - under/over in prior year
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    (50)
                </td>
                <td class="right" width="15%">
                    (50)
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    <p>
                        <b>
                        </b>
                    </p>
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            170
                        </b>
                    </p>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            170
                        </b>
                    </p>
                </td>
            </tr>
            <tr class="no-line">
                <td>
                    <p>
                        <b>
                            PROFIT FOR THE YEAR
                        </b>
                    </p>
                </td>
                <td>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            92,450
                        </b>
                    </p>
                </td>
                <td class="right" width="15%">
                    <p>
                        <b>
                            116,050
                        </b>
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
    </br>
    </br>
</div>
</body>

<script>
class PrePageContentModifier extends Paged.Handler {
    constructor(chunker, polisher, caller) {
        super(chunker, polisher, caller);
        this.lastChapter = null;
        this.lastSection = null;
        this.lastSubSection = null;
    }
    afterPageLayout(pageFragment, page) {
    const content = pageFragment.querySelector('.pagedjs_page_content > div');
    if (!content) return;
    // if (content.children[0].tagName !== "H1") {

        let insertBeforeNode = content.children[0];

        if (this.lastChapter) {
            const h1clone = document.createElement("div");
            h1clone.textContent = this.lastChapter + " (Cont’d)";
            h1clone.classList.add("continued-chapter");
            h1clone.style.marginTop = "0";
            content.insertBefore(h1clone, insertBeforeNode);
        }

        if (this.lastSection) {
            // if (!((content.children[0].tagName === "H1" && content.children[1].tagName === "H2") || (content.children[0].tagName === "H2"))) {
                const h2clone = document.createElement("div");
                h2clone.textContent = this.lastSection + " (Cont’d)";
                h2clone.classList.add("continued-section");
                h2clone.style.marginTop = "0.15em";
                content.insertBefore(h2clone, insertBeforeNode);
            // }
        }
    // }

    const foundH1 = content.querySelector(".chapter-title");
    const foundH2 = content.querySelector(".section");
    if (foundH1) this.lastChapter = foundH1.textContent.trim();
    if (foundH2) this.lastSection = foundH2.textContent.trim();
  }
}

Paged.registerHandlers(ContinuedHeadingHandler);
</script>


</html>
