<?php

namespace App\Services;

use App\Models\Tenant\Company;
use App\Models\Tenant\TaxReminder;
use App\Models\Tenant\TaxCp204Estimate;
use App\Models\Tenant\CompanyTaxSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaxReminderService
{
    /**
     * Generate all CP204-related reminders for a company's basis period
     *
     * Reminder Schedule:
     * - CP204 Initial: 2 reminders (mid-month before deadline, then on deadline day)
     * - CP204A Revisions: 2 reminders per revision (mid-month before, then 1st of revision month)
     * - Monthly Installments: 2 reminders (7 days before, then 1 day before)
     */
    public function generateAllRemindersForCompany(Company $company): array
    {
        $reminders = [];

        DB::beginTransaction();
        try {
            // 1. CP204 Initial Submission Reminders (2 reminders)
            $reminders['cp204_initial'] = $this->createCp204SubmissionReminders($company);

            // 2. CP204A Revision Reminders (2 reminders for each revision month)
            $reminders['cp204a_revisions'] = $this->createCp204aRevisionReminders($company);

            // 3. Monthly Installment Reminders (2 reminders per month)
            $reminders['monthly_installments'] = $this->createMonthlyInstallmentReminders($company);

            DB::commit();

            Log::info("Generated reminders for company {$company->id}", $reminders);

            return $reminders;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to generate reminders for company {$company->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create CP204 initial submission reminders (2 reminders)
     *
     * Reminder 1: Middle of the month before the deadline
     * Reminder 2: On the deadline day
     */
    protected function createCp204SubmissionReminders(Company $company): array
    {
        $settings = $company->taxSettings ?? new CompanyTaxSetting();

        if (!($settings->enable_cp204_reminders ?? true)) {
            return [];
        }

        $deadlineDate = $company->cp204SubmissionDeadline();
        if (!$deadlineDate) {
            return [];
        }

        $reminders = [];

        // Calculate the month before deadline
        $monthBefore = $deadlineDate->copy()->subMonth();

        // First reminder: 15th of the month before deadline
        $firstReminderDate = $monthBefore->day(15);

        // Second reminder: On the deadline day
        $secondReminderDate = $deadlineDate->copy();

        // Create First Reminder
        $reminders['first'] = TaxReminder::create([
            'company_id' => $company->id,
            'reminder_category' => 'cp204',
            'reminder_type' => 'cp204_initial_submission_first',
            'basis_period_year' => $company->current_year,
            'basis_period_from' => $company->current_year_from,
            'basis_period_to' => $company->current_year_to,
            'action_due_date' => $deadlineDate,
            'reminder_trigger_date' => $firstReminderDate,
            'final_reminder_date' => $secondReminderDate,
            'status' => 'pending',
            'primary_recipient_user_id' => $settings->primary_tax_contact_user_id,
            'primary_recipient_email' => $settings->primary_tax_contact_email,
            'cc_recipients' => $this->getCcRecipients($company, $settings),
            'reminder_title' => "CP204 Submission Due - First Reminder",
            'reminder_message' => "This is your first reminder that CP204 tax estimate must be submitted by {$deadlineDate->format('d M Y')} (30 days before your fiscal year begins on {$company->current_year_from->format('d M Y')}).",
            'reminder_priority' => 'high',
            'next_notification_at' => $firstReminderDate,
            'metadata' => [
                'reminder_sequence' => 1,
                'total_reminders' => 2,
                'fiscal_year_from' => $company->current_year_from->format('Y-m-d'),
                'fiscal_year_to' => $company->current_year_to->format('Y-m-d'),
            ],
        ]);

        // Create Second Reminder (Final)
        $reminders['second'] = TaxReminder::create([
            'company_id' => $company->id,
            'reminder_category' => 'cp204',
            'reminder_type' => 'cp204_initial_submission_final',
            'basis_period_year' => $company->current_year,
            'basis_period_from' => $company->current_year_from,
            'basis_period_to' => $company->current_year_to,
            'action_due_date' => $deadlineDate,
            'reminder_trigger_date' => $secondReminderDate,
            'final_reminder_date' => $secondReminderDate,
            'status' => 'pending',
            'primary_recipient_user_id' => $settings->primary_tax_contact_user_id,
            'primary_recipient_email' => $settings->primary_tax_contact_email,
            'cc_recipients' => $this->getCcRecipients($company, $settings),
            'reminder_title' => "⚠️ CP204 Submission Due TODAY - Final Reminder",
            'reminder_message' => "URGENT: CP204 tax estimate submission deadline is TODAY ({$deadlineDate->format('d M Y')}). Please ensure submission is completed before end of day.",
            'reminder_priority' => 'urgent',
            'next_notification_at' => $secondReminderDate,
            'metadata' => [
                'reminder_sequence' => 2,
                'total_reminders' => 2,
                'is_final_reminder' => true,
                'fiscal_year_from' => $company->current_year_from->format('Y-m-d'),
                'fiscal_year_to' => $company->current_year_to->format('Y-m-d'),
            ],
        ]);

        return $reminders;
    }

    /**
     * Create CP204A revision reminders (2 reminders for each revision month: 6th, 9th, 11th)
     *
     * For each revision month:
     * Reminder 1: 15th of the month before revision month
     * Reminder 2: 1st day of the revision month
     */
    protected function createCp204aRevisionReminders(Company $company): array
    {
        $settings = $company->taxSettings ?? new CompanyTaxSetting();

        if (!($settings->enable_cp204a_reminders ?? true)) {
            return [];
        }

        $revisionDates = $company->cp204aRevisionDates();
        $allReminders = [];

        foreach ($revisionDates as $month => $revisionDate) {
            $monthNumber = (int) explode('_', $month)[0]; // Extract 6, 9, or 11

            // Calculate reminder dates
            $monthBefore = $revisionDate->copy()->subMonth();
            $firstReminderDate = $monthBefore->day(15); // 15th of month before
            $secondReminderDate = $revisionDate->copy()->day(1); // 1st of revision month

            // Create First Reminder
            $allReminders["{$month}_first"] = TaxReminder::create([
                'company_id' => $company->id,
                'reminder_category' => 'cp204a',
                'reminder_type' => "cp204a_revision_{$month}_first",
                'basis_period_year' => $company->current_year,
                'basis_period_from' => $company->current_year_from,
                'basis_period_to' => $company->current_year_to,
                'action_due_date' => $revisionDate,
                'reminder_trigger_date' => $firstReminderDate,
                'final_reminder_date' => $secondReminderDate,
                'status' => 'pending',
                'primary_recipient_user_id' => $settings->primary_tax_contact_user_id,
                'primary_recipient_email' => $settings->primary_tax_contact_email,
                'cc_recipients' => $this->getCcRecipients($company, $settings),
                'reminder_title' => "CP204A Revision Available - {$monthNumber}th Month (First Reminder)",
                'reminder_message' => "You may revise your CP204 tax estimate in the {$monthNumber}th month of your fiscal year. Revision can be done by {$revisionDate->format('d M Y')}.",
                'reminder_priority' => 'medium',
                'next_notification_at' => $firstReminderDate,
                'metadata' => [
                    'revision_month' => $monthNumber,
                    'is_optional' => true,
                    'reminder_sequence' => 1,
                    'total_reminders' => 2,
                ],
            ]);

            // Create Second Reminder (on 1st of revision month)
            $allReminders["{$month}_second"] = TaxReminder::create([
                'company_id' => $company->id,
                'reminder_category' => 'cp204a',
                'reminder_type' => "cp204a_revision_{$month}_final",
                'basis_period_year' => $company->current_year,
                'basis_period_from' => $company->current_year_from,
                'basis_period_to' => $company->current_year_to,
                'action_due_date' => $revisionDate,
                'reminder_trigger_date' => $secondReminderDate,
                'final_reminder_date' => $secondReminderDate,
                'status' => 'pending',
                'primary_recipient_user_id' => $settings->primary_tax_contact_user_id,
                'primary_recipient_email' => $settings->primary_tax_contact_email,
                'cc_recipients' => $this->getCcRecipients($company, $settings),
                'reminder_title' => "CP204A Revision Month - {$monthNumber}th Month (Final Reminder)",
                'reminder_message' => "This is the {$monthNumber}th month of your fiscal year. You may now revise your CP204 tax estimate. This is optional but recommended if your business circumstances have changed.",
                'reminder_priority' => 'medium',
                'next_notification_at' => $secondReminderDate,
                'metadata' => [
                    'revision_month' => $monthNumber,
                    'is_optional' => true,
                    'reminder_sequence' => 2,
                    'total_reminders' => 2,
                    'is_final_reminder' => true,
                ],
            ]);
        }

        return $allReminders;
    }

    /**
     * Create monthly installment payment reminders (2 reminders per month)
     *
     * Reminder 1: 7 days before the 15th (8th of each month)
     * Reminder 2: 1 day before the 15th (14th of each month)
     */
    protected function createMonthlyInstallmentReminders(Company $company): array
    {
        $settings = $company->taxSettings ?? new CompanyTaxSetting();

        if (!($settings->enable_monthly_installment_reminders ?? true)) {
            return [];
        }

        $allReminders = [];
        $basisFrom = Carbon::parse($company->current_year_from);
        $basisTo = Carbon::parse($company->current_year_to);

        // Generate reminders for each month within basis period
        $currentMonth = $basisFrom->copy()->startOfMonth();

        while ($currentMonth->lte($basisTo)) {
            $paymentDueDate = $currentMonth->copy()->day(15); // 15th of each month

            // Skip if payment date is before basis period starts
            if ($paymentDueDate->gte($basisFrom)) {
                // First reminder: 7 days before (8th of the month)
                $firstReminderDate = $currentMonth->copy()->day(8);

                // Second reminder: 1 day before (14th of the month)
                $secondReminderDate = $currentMonth->copy()->day(14);

                $monthKey = $currentMonth->format('Y-m');

                // Create First Reminder
                $allReminders["{$monthKey}_first"] = TaxReminder::create([
                    'company_id' => $company->id,
                    'reminder_category' => 'monthly_installment',
                    'reminder_type' => 'monthly_installment_payment_first',
                    'basis_period_year' => $company->current_year,
                    'basis_period_from' => $company->current_year_from,
                    'basis_period_to' => $company->current_year_to,
                    'action_due_date' => $paymentDueDate,
                    'reminder_trigger_date' => $firstReminderDate,
                    'final_reminder_date' => $secondReminderDate,
                    'status' => 'pending',
                    'primary_recipient_user_id' => $settings->primary_tax_contact_user_id,
                    'primary_recipient_email' => $settings->primary_tax_contact_email,
                    'cc_recipients' => $this->getCcRecipients($company, $settings),
                    'reminder_title' => "Monthly Tax Installment Due - {$currentMonth->format('F Y')} (First Reminder)",
                    'reminder_message' => "Monthly tax installment payment is due on {$paymentDueDate->format('d M Y')} (7 days from now).",
                    'reminder_priority' => 'high',
                    'next_notification_at' => $firstReminderDate,
                    'metadata' => [
                        'payment_month' => $currentMonth->format('Y-m'),
                        'payment_day' => 15,
                        'reminder_sequence' => 1,
                        'total_reminders' => 2,
                    ],
                ]);

                // Create Second Reminder
                $allReminders["{$monthKey}_second"] = TaxReminder::create([
                    'company_id' => $company->id,
                    'reminder_category' => 'monthly_installment',
                    'reminder_type' => 'monthly_installment_payment_final',
                    'basis_period_year' => $company->current_year,
                    'basis_period_from' => $company->current_year_from,
                    'basis_period_to' => $company->current_year_to,
                    'action_due_date' => $paymentDueDate,
                    'reminder_trigger_date' => $secondReminderDate,
                    'final_reminder_date' => $secondReminderDate,
                    'status' => 'pending',
                    'primary_recipient_user_id' => $settings->primary_tax_contact_user_id,
                    'primary_recipient_email' => $settings->primary_tax_contact_email,
                    'cc_recipients' => $this->getCcRecipients($company, $settings),
                    'reminder_title' => "⚠️ Monthly Tax Installment Due TOMORROW - {$currentMonth->format('F Y')}",
                    'reminder_message' => "REMINDER: Monthly tax installment payment is due TOMORROW on {$paymentDueDate->format('d M Y')}. Please ensure payment is processed.",
                    'reminder_priority' => 'urgent',
                    'next_notification_at' => $secondReminderDate,
                    'metadata' => [
                        'payment_month' => $currentMonth->format('Y-m'),
                        'payment_day' => 15,
                        'reminder_sequence' => 2,
                        'total_reminders' => 2,
                        'is_final_reminder' => true,
                    ],
                ]);
            }

            $currentMonth->addMonth();
        }

        return $allReminders;
    }

    /**
     * Get CC recipients based on company settings
     */
    protected function getCcRecipients(Company $company, CompanyTaxSetting $settings): array
    {
        $recipients = [];

        // Add company directors if enabled
        if ($settings->cc_company_directors ?? true) {
            foreach ($company->directors as $director) {
                if ($director->is_active) {
                    $latestChange = $director->changes()->latest('effective_date')->first();
                    if ($latestChange && !empty($latestChange->email)) {
                        $recipients[] = [
                            'email' => $latestChange->email,
                            'name' => $director->name ?? 'Director',
                            'role' => 'director',
                        ];
                    }
                }
            }
        }

        // Add additional recipients
        if (!empty($settings->additional_recipients)) {
            foreach ($settings->additional_recipients as $recipient) {
                $recipients[] = $recipient;
            }
        }

        return $recipients;
    }

    /**
     * Link tax estimate to related reminders
     */
    public function linkEstimateToReminders(TaxCp204Estimate $estimate): void
    {
        TaxReminder::where('company_id', $estimate->company_id)
                   ->where('basis_period_year', $estimate->basis_period_year)
                   ->whereNull('tax_estimate_id')
                   ->update(['tax_estimate_id' => $estimate->id]);
    }

    /**
     * Mark reminder as completed
     */
    public function markReminderCompleted(TaxReminder $reminder, $userId = null): void
    {
        $reminder->update([
            'status' => 'completed',
            'completed_at' => now(),
            'acknowledged_by' => $userId,
        ]);

        $reminder->logs()->create([
            'log_type' => 'acknowledged',
            'user_action' => 'completed',
            'action_at' => now(),
            'action_by' => $userId,
            'action_notes' => 'Reminder marked as completed',
            'created_at' => now(),
        ]);
    }

    /**
     * Process overdue reminders
     */
    public function processOverdueReminders(): int
    {
        $count = TaxReminder::overdue()->update([
            'status' => 'overdue',
        ]);

        Log::info("Marked {$count} reminders as overdue");

        return $count;
    }
}
