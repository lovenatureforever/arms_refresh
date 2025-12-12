<div class="grid grid-cols-1 gap-6">
    <!-- Company Selector -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Tax Reminder Settings</h4>
        </div>
        <div class="p-6">
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Company</label>
                <select wire:model.live="selectedCompany" class="form-select max-w-md">
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Settings Form -->
    <form wire:submit.prevent="saveSettings">
        <!-- CP204 Reminder Settings -->
        <div class="card mb-6">
            <div class="card-header">
                <h5 class="card-title">CP204 Initial Submission Settings</h5>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center">
                    <input type="checkbox" wire:model="enable_cp204_reminders" id="enable_cp204" class="form-checkbox">
                    <label for="enable_cp204" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Enable CP204 Initial Submission Reminders</label>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Remind how many days before deadline</label>
                    <input type="number" wire:model="cp204_submission_days_before" class="form-input max-w-xs" min="1">
                    <p class="text-xs text-gray-500 mt-1">Default: 30 days before fiscal year start</p>
                </div>
            </div>
        </div>

        <!-- CP204A Revision Settings -->
        <div class="card mb-6">
            <div class="card-header">
                <h5 class="card-title">CP204A Revision Settings</h5>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center">
                    <input type="checkbox" wire:model="enable_cp204a_reminders" id="enable_cp204a" class="form-checkbox">
                    <label for="enable_cp204a" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Enable CP204A Revision Reminders (6th, 9th, 11th month)</label>
                </div>
            </div>
        </div>

        <!-- Monthly Installment Settings -->
        <div class="card mb-6">
            <div class="card-header">
                <h5 class="card-title">Monthly Installment Settings</h5>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center">
                    <input type="checkbox" wire:model="enable_monthly_installment_reminders" id="enable_installments" class="form-checkbox">
                    <label for="enable_installments" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Enable Monthly Installment Payment Reminders</label>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Remind how many days before 15th of each month</label>
                    <input type="number" wire:model="installment_reminder_days_before" class="form-input max-w-xs" min="1">
                    <p class="text-xs text-gray-500 mt-1">Default: 7 days before payment due</p>
                </div>
            </div>
        </div>

        <!-- Notification Recipients -->
        <div class="card mb-6">
            <div class="card-header">
                <h5 class="card-title">Notification Recipients</h5>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Primary Tax Contact User</label>
                    <select wire:model="primary_tax_contact_user_id" class="form-select max-w-md">
                        <option value="">Select user</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Primary Tax Contact Email</label>
                    <input type="email" wire:model="primary_tax_contact_email" class="form-input max-w-md" placeholder="tax@company.com">
                    <p class="text-xs text-gray-500 mt-1">If user is not selected, use this email</p>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" wire:model="cc_company_directors" id="cc_directors" class="form-checkbox">
                    <label for="cc_directors" class="ml-2 text-sm text-gray-700 dark:text-gray-300">CC Company Directors</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" wire:model="cc_auditors" id="cc_auditors" class="form-checkbox">
                    <label for="cc_auditors" class="ml-2 text-sm text-gray-700 dark:text-gray-300">CC Auditors</label>
                </div>
            </div>
        </div>

        <!-- Reminder Schedule -->
        <div class="card mb-6">
            <div class="card-header">
                <h5 class="card-title">Reminder Schedule</h5>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">First Reminder (days before)</label>
                        <input type="number" wire:model="first_reminder_days_before" class="form-input" min="1">
                        <p class="text-xs text-gray-500 mt-1">Default: 30 days</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Second Reminder (days before)</label>
                        <input type="number" wire:model="second_reminder_days_before" class="form-input" min="1">
                        <p class="text-xs text-gray-500 mt-1">Default: 14 days</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Final Reminder (days before)</label>
                        <input type="number" wire:model="final_reminder_days_before" class="form-input" min="1">
                        <p class="text-xs text-gray-500 mt-1">Default: 3 days</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="card">
            <div class="p-6">
                <div class="flex justify-end">
                    <button type="submit" class="btn bg-primary text-white">
                        <i class="mgc_save_line mr-1"></i> Save Settings
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
