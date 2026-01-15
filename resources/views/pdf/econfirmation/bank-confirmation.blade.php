<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Bank Confirmation - {{ $company->name }}</title>
    <style>
        @page {
            margin: 15mm 15mm 15mm 15mm;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #000;
        }

        .page-break {
            page-break-after: always;
        }

        .letterhead {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #000;
        }

        .firm-name {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .firm-details {
            font-size: 8pt;
            color: #333;
        }

        .date-right {
            text-align: right;
            margin-bottom: 15px;
        }

        .bank-address {
            margin-bottom: 15px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            margin: 20px 0;
            font-size: 11pt;
        }

        .content {
            text-align: justify;
            margin-bottom: 15px;
        }

        .content p {
            margin-bottom: 10px;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            width: 200px;
            display: inline-block;
            margin-bottom: 5px;
        }

        .signature-section {
            margin-top: 30px;
        }

        .customer-box {
            border: 1px solid #000;
            padding: 15px;
            margin: 20px 0;
        }

        .customer-signature {
            margin-top: 40px;
        }

        .dotted-line {
            border-bottom: 1px dotted #000;
            width: 250px;
            height: 20px;
            display: inline-block;
        }

        .indent {
            margin-left: 20px;
        }

        ol.alpha {
            list-style-type: lower-alpha;
        }

        ol.alpha li {
            margin-bottom: 8px;
        }

        .underline {
            text-decoration: underline;
        }

        .bold {
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        .mt-20 {
            margin-top: 20px;
        }

        .mt-40 {
            margin-top: 40px;
        }

        .mb-10 {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <!-- PAGE 1: Standard Request for Information -->

    <!-- Audit Firm Letterhead -->
    <div class="letterhead">
        <div class="firm-name">{{ $tenant->firmName ?? 'AUDIT FIRM NAME' }}</div>
        <div class="firm-details">
            {{ $tenant->firmNo ?? '' }}<br>
            Chartered Accountants
        </div>
    </div>

    <!-- Date -->
    <div class="date-right">
        Date: {{ now()->format('d F Y') }}
    </div>

    <!-- Bank Address -->
    <div class="bank-address">
        {{ $bank->bank_name }}<br>
        @if($bankBranch->branch_name)
            {{ $bankBranch->branch_name }}<br>
        @endif
        @if($bankBranch->full_address)
            {!! nl2br(e($bankBranch->full_address)) !!}
        @endif
    </div>

    <p>Dear Sir,</p>

    <!-- Title -->
    <div class="title">STANDARD REQUEST FOR INFORMATION FOR AUDIT PURPOSES</div>

    <!-- Customer Info Box -->
    <div class="customer-box">
        <table width="100%">
            <tr>
                <td width="30%"><strong>Customer Name:</strong></td>
                <td>{{ $company->name }}</td>
            </tr>
            <tr>
                <td><strong>Registration No:</strong></td>
                <td>{{ $company->registration_no ?? 'N/A' }}</td>
            </tr>
            @if($accountNo)
            <tr>
                <td><strong>Account No:</strong></td>
                <td>{{ $accountNo }}</td>
            </tr>
            @endif
        </table>
    </div>

    <!-- Content -->
    <div class="content">
        <p>
            In accordance with your above-named customer's instructions given hereon please send us, as auditors of your customer for the purpose of our audit, the following information relating to their affairs at your bank as at the close of business on <strong class="underline">{{ $yearEndDate->format('d F Y') }}</strong> and, in the case of closure of accounts, during the period up to the date of your reply.
        </p>

        <p>
            It is understood that any replies given by you will be treated by us with strict confidence. Neither the request from us, as auditors of your customer, nor the bank response will create any contractual relationship with us.
        </p>
    </div>

    <!-- Closing -->
    <div class="mt-40">
        <p>Yours faithfully,</p>
        <br><br><br>
        <p>for <strong>{{ $tenant->firmName ?? 'AUDIT FIRM NAME' }}</strong></p>
    </div>

    <!-- Page Break -->
    <div class="page-break"></div>

    <!-- PAGE 2: Agreement for Online Bank Confirmation -->

    <!-- Date -->
    <div class="date-right">
        Date: {{ now()->format('d F Y') }}
    </div>

    <!-- Bank Address -->
    <div class="bank-address">
        {{ $bank->bank_name }}<br>
        @if($bankBranch->branch_name)
            {{ $bankBranch->branch_name }}<br>
        @endif
        @if($bankBranch->full_address)
            {!! nl2br(e($bankBranch->full_address)) !!}
        @endif
    </div>

    <p>Dear Sir,</p>

    <!-- Title -->
    <div class="title">AGREEMENT TO OBTAIN BANK CONFIRMATION THROUGH THE ONLINE BANK CONFIRMATION PLATFORM</div>

    <!-- Content -->
    <div class="content">
        <p>
            I/We hereby authorise <strong>{{ $tenant->firmName ?? 'AUDIT FIRM NAME' }}</strong> to obtain the bank confirmation through the online bank confirmation platform.
        </p>

        <p>I/We am/are fully aware and give my/our consent that:</p>

        <ol class="alpha">
            <li>there will be a fee of RM100 per confirmation for using the online bank confirmation platform and the fee is to be paid to Extol Corporation Sdn. Bhd;</li>
            <li>this RM100 per confirmation fee is in addition to the fee I/we currently pay to the bank concerned for providing the bank confirmation service; and</li>
            <li>my/our financial information will be available in the Platform for a period of 6 months from the confirmation date in the online bank confirmation platform.</li>
        </ol>

        <p>I/We give my/our consent for you to disclose to <strong>{{ $tenant->firmName ?? 'AUDIT FIRM NAME' }}</strong> the information requested.</p>
    </div>

    <!-- Customer Signature Section -->
    <div class="customer-signature">
        <p class="dotted-line"></p>
        <p><strong>({{ $company->name }})</strong></p>
        <p>(Company Stamp)</p>

        <p class="mt-20">Signed in accordance with the mandate<br>for the conduct of the customer's bank account</p>

        <!-- Directors Signatures -->
        <div class="mt-40">
            <table width="100%">
                @foreach($directors->chunk(2) as $chunk)
                <tr>
                    @foreach($chunk as $director)
                    <td width="50%" style="padding: 10px; vertical-align: top;">
                        @php
                            $signature = $signatures->firstWhere('director_id', $director->id);
                        @endphp

                        @if($signature && $signature->signature_path_used)
                            <img src="{{ Storage::disk('public')->path($signature->signature_path_used) }}"
                                 style="max-width: 150px; max-height: 50px;">
                        @else
                            <p class="dotted-line"></p>
                        @endif
                        <p><strong>[Authorised Signature]</strong></p>
                        <p>{{ $director->name }}</p>
                        <p>Director</p>
                        @if($signature && $signature->signed_at)
                            <p style="font-size: 8pt;">Date: {{ $signature->signed_at->format('d/m/Y') }}</p>
                        @else
                            <p>Date: _______________</p>
                        @endif
                    </td>
                    @endforeach
                    @if($chunk->count() == 1)
                    <td width="50%"></td>
                    @endif
                </tr>
                @endforeach
            </table>
        </div>
    </div>

</body>
</html>
