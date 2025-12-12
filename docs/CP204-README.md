# CP204 Tax Reminder System - Complete Implementation

## 🎯 Overview

The CP204 Tax Reminder System is a comprehensive solution for Malaysian companies to manage their corporate tax estimate submissions, revisions, and monthly installment payments. The system sends automated reminders via **email and WhatsApp** following a **2-reminder schedule** for each deadline.

---

## ✨ Key Features

✅ **Customizable Fiscal Year** - Supports any fiscal year period (not just Jan-Dec)
✅ **2-Reminder Schedule** - First reminder (advance notice) + Final reminder (deadline day)
✅ **Dual Notifications** - Email + WhatsApp for every reminder
✅ **Automatic Processing** - Scheduled twice daily (9 AM & 2 PM MYT)
✅ **Smart Calculations** - CP204 deadlines calculated from fiscal year dates
✅ **Comprehensive Logging** - Track all notification attempts and statuses
✅ **Priority-Based** - Urgent, High, Medium priority levels
✅ **Company-Specific** - Customizable settings per company

---

## 📋 Reminder Schedule Summary

### CP204 Initial Submission
- **First Reminder:** 15th of month before deadline
- **Final Reminder:** On deadline day (30 days before fiscal year)

### CP204A Revisions (6th, 9th, 11th month)
- **First Reminder:** 15th of month before revision month
- **Final Reminder:** 1st of revision month

### Monthly Installments
- **First Reminder:** 8th of each month (7 days before)
- **Final Reminder:** 14th of each month (1 day before)
- **Payment Due:** 15th of each month

---

## 📁 Implementation Files

### Services
- [TaxReminderService.php](../app/Services/TaxReminderService.php) - Generates reminders with 2-reminder schedule
- [TaxNotificationService.php](../app/Services/TaxNotificationService.php) - Sends email and WhatsApp notifications

### Jobs
- [ProcessTaxReminders.php](../app/Jobs/ProcessTaxReminders.php) - Scheduled job for processing reminders

### Models
- [TaxCp204Estimate.php](../app/Models/Tenant/TaxCp204Estimate.php) - CP204 estimates with 85% compliance
- [TaxReminder.php](../app/Models/Tenant/TaxReminder.php) - Reminder management
- [TaxReminderLog.php](../app/Models/Tenant/TaxReminderLog.php) - Notification history
- [CompanyTaxSetting.php](../app/Models/Tenant/CompanyTaxSetting.php) - Company settings

### Mail
- [TaxReminderNotification.php](../app/Mail/TaxReminderNotification.php) - Email mailable

### Views
- [reminder.blade.php](../resources/views/emails/tax/reminder.blade.php) - Email template

### Migrations
- [2025_11_25_100001_create_tax_cp204_estimates_table.php](../database/migrations/tenant/2025_11_25_100001_create_tax_cp204_estimates_table.php)
- [2025_11_25_100002_create_tax_reminders_table.php](../database/migrations/tenant/2025_11_25_100002_create_tax_reminders_table.php)
- [2025_11_25_100003_create_tax_reminder_logs_table.php](../database/migrations/tenant/2025_11_25_100003_create_tax_reminder_logs_table.php)
- [2025_11_25_100004_create_company_tax_settings_table.php](../database/migrations/tenant/2025_11_25_100004_create_company_tax_settings_table.php)

### Scheduler
- [console.php](../routes/console.php) - Scheduled tasks (9 AM & 2 PM MYT)

---

## 📚 Documentation

### Complete Guides

1. **[CP204-Tax-Reminder-Feature.md](./CP204-Tax-Reminder-Feature.md)**
   Original planning document with comprehensive feature specifications

2. **[CP204-Implementation-Guide.md](./CP204-Implementation-Guide.md)**
   Step-by-step implementation guide with setup instructions

3. **[CP204-Fiscal-Year-Customization.md](./CP204-Fiscal-Year-Customization.md)**
   Detailed guide on customizable fiscal year support

4. **[CP204-Fiscal-Year-Examples.md](./CP204-Fiscal-Year-Examples.md)**
   Visual examples of different fiscal year scenarios

5. **[CP204-Reminder-Schedule-Reference.md](./CP204-Reminder-Schedule-Reference.md)**
   Quick reference for reminder schedule with timelines

---

## 🚀 Quick Start

### 1. Run Migrations

```bash
php artisan migrate
```

### 2. Configure WhatsApp (Choose one provider)

Add to `.env`:

```env
# Option A: Fonnte (Recommended for Malaysia)
WHATSAPP_PROVIDER=fonnte
FONNTE_TOKEN=your_token_here

# Option B: Twilio
WHATSAPP_PROVIDER=twilio
TWILIO_ACCOUNT_SID=your_sid
TWILIO_AUTH_TOKEN=your_token
TWILIO_WHATSAPP_NUMBER=+14155238886

# Option C: WhatsApp Business API
WHATSAPP_PROVIDER=whatsapp-api
WHATSAPP_ACCESS_TOKEN=your_token
WHATSAPP_PHONE_NUMBER_ID=your_phone_id
```

### 3. Set Up Cron

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### 4. Generate Reminders for a Company

```php
use App\Services\TaxReminderService;
use App\Models\Tenant\Company;

$company = Company::find(1);
$service = app(TaxReminderService::class);
$reminders = $service->generateAllRemindersForCompany($company);
```

---

## 💡 Usage Examples

### Configure Company Tax Settings

```php
$company->taxSettings()->updateOrCreate(
    ['company_id' => $company->id],
    [
        'enable_cp204_reminders' => true,
        'enable_cp204a_reminders' => true,
        'enable_monthly_installment_reminders' => true,
        'primary_tax_contact_email' => 'tax@company.com',
        'cc_company_directors' => true,
        'additional_recipients' => [
            ['email' => 'cfo@company.com', 'phone' => '0123456789', 'name' => 'CFO'],
        ],
    ]
);
```

### Test Notifications

```php
use App\Services\TaxNotificationService;

$service = app(TaxNotificationService::class);
$results = $service->sendTestNotification('test@email.com', '0123456789');

// Returns: ['email' => true/false, 'whatsapp' => true/false]
```

### View Reminder Logs

```php
$reminder = TaxReminder::find(1);
$logs = $reminder->logs()->orderBy('created_at', 'desc')->get();

foreach ($logs as $log) {
    echo "{$log->log_type} - {$log->recipient_email} - " .
         ($log->success ? '✓' : '✗') . "\n";
}
```

---

## 📊 System Architecture

```
┌─────────────────┐
│   Company       │
│  (Fiscal Year)  │
└────────┬────────┘
         │
         ├──────────────────────┐
         │                      │
         v                      v
┌─────────────────┐    ┌─────────────────┐
│ TaxCp204Estimate│    │ CompanyTaxSetting│
└────────┬────────┘    └─────────────────┘
         │
         v
┌─────────────────┐
│  TaxReminder    │
│  (2 per event)  │
└────────┬────────┘
         │
         v
┌─────────────────────────────┐
│  ProcessTaxReminders Job    │
│  (Runs 9 AM & 2 PM daily)   │
└────────┬────────────────────┘
         │
         ├───────────────┬──────────────┐
         v               v              v
┌─────────────┐  ┌─────────────┐  ┌─────────────┐
│   Email     │  │  WhatsApp   │  │ Logging     │
└─────────────┘  └─────────────┘  └─────────────┘
```

---

## 🔄 Reminder Lifecycle

```
[Reminder Created]
      ↓
[Status: pending]
      ↓
[Wait for next_notification_at]
      ↓
[ProcessTaxReminders Job]
      ↓
[Send Email + WhatsApp]
      ↓
[Log Results]
      ↓
[Status: sent/failed]
      ↓
[User Completes Action]
      ↓
[Status: completed]
```

---

## 📈 Total Reminders Per Year

For a standard 12-month fiscal year:

| Type | Count | Description |
|------|-------|-------------|
| CP204 Initial | 2 | First + Final |
| CP204A Revisions | 6 | 2 for each of 3 revision periods |
| Monthly Installments | 24 | 2 per month for 12 months |
| **TOTAL** | **32** | **Reminders per company per year** |

---

## 🧪 Testing

### Manual Test in Tinker

```bash
php artisan tinker
```

```php
// Test reminder generation
$company = App\Models\Tenant\Company::first();
$service = app(\App\Services\TaxReminderService::class);
$reminders = $service->generateAllRemindersForCompany($company);
dd($reminders);

// Test notification
$notificationService = app(\App\Services\TaxNotificationService::class);
$results = $notificationService->sendTestNotification(
    'your@email.com',
    '0123456789'
);
dd($results);
```

---

## 📞 WhatsApp Provider Options

### Fonnte (Recommended for Malaysia)
- ✅ Easy setup
- ✅ No Facebook Business verification required
- ✅ Affordable pricing
- ✅ Local support
- 🌐 https://fonnte.com

### Twilio
- ✅ Reliable international service
- ✅ Excellent documentation
- ⚠️ Higher cost
- 🌐 https://twilio.com

### WhatsApp Business API (Official)
- ✅ Official Facebook/Meta API
- ⚠️ Requires business verification
- ⚠️ Complex setup
- 🌐 https://developers.facebook.com/docs/whatsapp

---

## ✅ Implementation Checklist

- [x] Database migrations created
- [x] Models implemented with relationships
- [x] TaxReminderService with 2-reminder schedule
- [x] TaxNotificationService with email + WhatsApp
- [x] ProcessTaxReminders job
- [x] Email mailable and template
- [x] Scheduled tasks configured
- [x] Company model CP204 helper methods
- [x] Comprehensive documentation

### Still Needed
- [ ] Run migrations in tenant databases
- [ ] Configure WhatsApp provider credentials
- [ ] Test email configuration
- [ ] Create frontend UI (optional)
- [ ] Create API controllers (optional)
- [ ] Set up production cron job

---

## 🎓 Key Concepts

### 85% Rule
From the 2nd year onwards, tax estimates must be ≥85% of the previous year's latest estimate (not actual tax).

### Basis Period
The company's accounting period (fiscal year), which can be any 12-month period, not just Jan-Dec.

### CP204 vs CP204A
- **CP204** = Initial estimate (submitted 30 days before fiscal year)
- **CP204A** = Revision (can be done in 6th, 9th, 11th month)

### Monthly Installments
Companies pay 1/12 of their estimated tax each month by the 15th.

---

## 📞 Support & Resources

### Official LHDN Resources
- MyTax Portal: https://mytax.hasil.gov.my
- LHDN Website: https://www.hasil.gov.my
- CP204 Guidelines: Official LHDN documentation

### System Documentation
- See [docs/](.) folder for complete guides
- Check [CP204-Implementation-Guide.md](./CP204-Implementation-Guide.md) for setup

---

## 🎉 Success Criteria

A successful implementation will:
- ✅ Automatically generate reminders when company fiscal year is set
- ✅ Send 2 reminders for each deadline (first + final)
- ✅ Deliver notifications via both email and WhatsApp
- ✅ Process reminders twice daily without manual intervention
- ✅ Log all notification attempts for auditing
- ✅ Handle any fiscal year period (not just Jan-Dec)
- ✅ Support company-specific settings and recipients

---

**System Version:** 1.0
**Implementation Date:** 1 December 2025
**Author:** Muhammad Daniel
**Status:** ✅ Complete - Ready for Deployment

---

## 🚦 Next Steps

1. **Deploy to Staging**
   - Run migrations
   - Configure WhatsApp provider
   - Test with sample companies

2. **User Acceptance Testing**
   - Test all reminder types
   - Verify email and WhatsApp delivery
   - Check reminder schedule accuracy

3. **Production Deployment**
   - Set up production WhatsApp credentials
   - Configure production cron job
   - Monitor first batch of reminders

4. **Optional Enhancements**
   - Build frontend dashboard for viewing reminders
   - Create API endpoints for mobile app
   - Add SMS fallback for WhatsApp failures
   - Implement reminder acknowledgment UI

Happy reminding! 🎊
