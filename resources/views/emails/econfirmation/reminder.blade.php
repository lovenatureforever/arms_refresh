<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Confirmation Reminder</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header.urgent {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .header-badge {
            display: inline-block;
            background-color: rgba(255, 255, 255, 0.2);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            margin-top: 10px;
        }
        .content {
            padding: 30px 20px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .message-box {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .message-box.urgent {
            border-left-color: #f5576c;
            background-color: #fff5f5;
        }
        .info-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 12px;
            border-bottom: 1px solid #e9ecef;
        }
        .info-table td:first-child {
            font-weight: 600;
            width: 140px;
            color: #6c757d;
        }
        .expiry-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }
        .expiry-box.urgent {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .expiry-box .label {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 5px;
        }
        .expiry-box .date {
            font-size: 24px;
            font-weight: 700;
        }
        .expiry-box .countdown {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 5px;
        }
        .action-button {
            display: inline-block;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 14px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }
        .action-button.urgent {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .warning-box {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .warning-box.urgent {
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        .warning-box h3 {
            margin-top: 0;
            color: #856404;
            font-size: 16px;
        }
        .warning-box.urgent h3 {
            color: #721c24;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 13px;
            color: #6c757d;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            margin: 5px 0;
        }
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #dee2e6, transparent);
            margin: 20px 0;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 0;
                border-radius: 0;
            }
            .content {
                padding: 20px 15px;
            }
            .header h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header {{ $isUrgent ? 'urgent' : '' }}">
            <h1>
                @if($isUrgent)
                    Urgent: Signature Required Tomorrow
                @else
                    Reminder: Signature Required
                @endif
            </h1>
            <div class="header-badge">
                @if($isUrgent)
                    Final Reminder
                @else
                    {{ $daysUntilExpiry }} Days Remaining
                @endif
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Dear {{ $directorName }},
            </div>

            <!-- Message -->
            <div class="message-box {{ $isUrgent ? 'urgent' : '' }}">
                @if($isUrgent)
                    <strong>This is your final reminder.</strong> The bank confirmation signing link for <strong>{{ $companyName }}</strong>
                    will expire <strong>tomorrow</strong>. Please sign immediately to avoid delays in the audit process.
                @else
                    This is a friendly reminder that you have pending bank confirmation(s) to sign for <strong>{{ $companyName }}</strong>.
                    The signing link will expire in <strong>{{ $daysUntilExpiry }} days</strong>.
                @endif
            </div>

            <!-- Warning Box -->
            <div class="warning-box {{ $isUrgent ? 'urgent' : '' }}">
                <h3>
                    @if($isUrgent)
                        Action Required Immediately
                    @else
                        Please Complete Soon
                    @endif
                </h3>
                <p>
                    @if($isUrgent)
                        If the link expires, a new request will need to be generated which may delay the audit completion.
                    @else
                        Completing this promptly will help ensure a smooth audit process for {{ $companyName }}.
                    @endif
                </p>
            </div>

            <!-- Expiry Box -->
            <div class="expiry-box {{ $isUrgent ? 'urgent' : '' }}">
                <div class="label">Link Expires On</div>
                <div class="date">{{ $expiresAt }}</div>
                <div class="countdown">
                    @if($daysUntilExpiry > 0)
                        {{ $daysUntilExpiry }} {{ Str::plural('day', $daysUntilExpiry) }} remaining
                    @elseif($daysUntilExpiry === 0)
                        Expires TODAY
                    @else
                        Expired
                    @endif
                </div>
            </div>

            <!-- Company Information -->
            <table class="info-table">
                <tr>
                    <td>Company</td>
                    <td>{{ $companyName }}</td>
                </tr>
                <tr>
                    <td>Year End Date</td>
                    <td>{{ $yearEndDate }}</td>
                </tr>
                <tr>
                    <td>Bank(s) to Sign</td>
                    <td>{{ $bankCount }} {{ Str::plural('bank', $bankCount) }}</td>
                </tr>
            </table>

            <div class="divider"></div>

            <!-- Action Button -->
            <center>
                <a href="{{ $signingUrl }}" class="action-button {{ $isUrgent ? 'urgent' : '' }}">
                    Sign Now
                </a>
            </center>

            <p style="text-align: center; font-size: 13px; color: #6c757d; margin-top: 10px;">
                Or copy this link to your browser:<br>
                <a href="{{ $signingUrl }}" style="color: #667eea; word-break: break-all;">{{ $signingUrl }}</a>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>This is an automated reminder from ARMS E-Confirmation System</strong></p>
            <p>If you have already signed the confirmation(s), please disregard this email.</p>
            <p style="margin-top: 15px; font-size: 11px;">
                This email was sent to you as a director of {{ $companyName }}.
            </p>
        </div>
    </div>
</body>
</html>
