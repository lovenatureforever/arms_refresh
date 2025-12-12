<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $reminderTitle }}</title>
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
        .header.high {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            color: #333;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .reminder-badge {
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
        .due-date-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }
        .due-date-box.urgent {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .due-date-box .label {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 5px;
        }
        .due-date-box .date {
            font-size: 24px;
            font-weight: 700;
        }
        .due-date-box .countdown {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 5px;
        }
        .action-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 14px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }
        .instructions {
            background-color: #e7f3ff;
            border: 1px solid #b3d9ff;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .instructions h3 {
            margin-top: 0;
            color: #0056b3;
            font-size: 16px;
        }
        .instructions ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .instructions li {
            margin: 8px 0;
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
        <div class="header {{ $priority }}">
            <h1>{{ $reminderTitle }}</h1>
            @if($isFinalReminder)
                <div class="reminder-badge">Final Reminder ({{ $reminderSequence }}/{{ $totalReminders }})</div>
            @else
                <div class="reminder-badge">Reminder {{ $reminderSequence }} of {{ $totalReminders }}</div>
            @endif
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Hello {{ $recipientName }},
            </div>

            <!-- Message -->
            <div class="message-box {{ $priority === 'urgent' ? 'urgent' : '' }}">
                {{ $reminderMessage }}
            </div>

            <!-- Due Date Box -->
            <div class="due-date-box {{ $priority === 'urgent' ? 'urgent' : '' }}">
                <div class="label">Due Date</div>
                <div class="date">{{ $dueDate }}</div>
                @if($daysUntilDue !== null)
                    <div class="countdown">
                        @if($daysUntilDue > 0)
                            {{ $daysUntilDue }} {{ Str::plural('day', $daysUntilDue) }} remaining
                        @elseif($daysUntilDue === 0)
                            Due TODAY
                        @else
                            Overdue by {{ abs($daysUntilDue) }} {{ Str::plural('day', abs($daysUntilDue)) }}
                        @endif
                    </div>
                @endif
            </div>

            <!-- Company Information -->
            <table class="info-table">
                <tr>
                    <td>Company</td>
                    <td>{{ $companyName }}</td>
                </tr>
                <tr>
                    <td>Fiscal Year</td>
                    <td>{{ $fiscalYearPeriod }}</td>
                </tr>
                <tr>
                    <td>Reminder Type</td>
                    <td>{{ ucwords(str_replace('_', ' ', $reminderCategory)) }}</td>
                </tr>
            </table>

            <!-- Specific Instructions -->
            <div class="instructions">
                @if(str_contains($reminderType, 'cp204_initial'))
                    <h3>📋 CP204 Initial Submission Instructions</h3>
                    <ul>
                        <li>Submit CP204 tax estimate form to LHDN</li>
                        <li>Must be filed at least 30 days before your fiscal year begins</li>
                        <li>Can be submitted via e-Filing at <a href="https://mytax.hasil.gov.my">MyTax Portal</a></li>
                        <li>For 2nd year onwards: Estimate must be ≥85% of previous year's latest estimate</li>
                    </ul>
                @elseif(str_contains($reminderType, 'cp204a_revision'))
                    <h3>📝 CP204A Revision Period</h3>
                    <ul>
                        <li>This is an <strong>optional</strong> revision period</li>
                        <li>You may revise your tax estimate if business circumstances have changed</li>
                        <li>Submit CP204A form if you wish to revise</li>
                        <li>If no changes needed, no action is required</li>
                    </ul>
                @elseif(str_contains($reminderType, 'monthly_installment'))
                    <h3>💰 Monthly Tax Installment Payment</h3>
                    <ul>
                        <li>Monthly installment payment is due by the <strong>15th of each month</strong></li>
                        <li>Make payment via FPX, GIRO, or over the counter</li>
                        <li>Late payment may incur penalties and interest</li>
                        <li>Payment reference: Your company's tax reference number</li>
                    </ul>
                @endif
            </div>

            <div class="divider"></div>

            <!-- Action Button -->
            <center>
                <a href="https://mytax.hasil.gov.my" class="action-button">
                    Go to MyTax Portal
                </a>
            </center>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>This is an automated reminder from ARMS Tax Management System</strong></p>
            <p>If you have already completed this action, please ignore this reminder or update the status in the system.</p>
            <p style="margin-top: 15px; font-size: 11px;">
                This email was sent to {{ $recipient['email'] ?? 'you' }} as {{ $recipient['role'] ?? 'a recipient' }} for {{ $companyName }}.
            </p>
        </div>
    </div>
</body>
</html>
