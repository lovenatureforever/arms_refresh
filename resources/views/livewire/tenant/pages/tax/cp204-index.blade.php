<div class="grid grid-cols-1 gap-6">
    <!-- Company Selector -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Select Company</h4>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Company</label>
                    <select wire:model.live="selectedCompany" class="form-select">
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>
                @if($currentCompany)
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Fiscal Year</label>
                    <p class="text-gray-800 dark:text-gray-200 py-2">{{ $currentCompany->current_year_from?->format('d M Y') }} - {{ $currentCompany->current_year_to?->format('d M Y') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">CP204 Deadline</label>
                    <p class="text-gray-800 dark:text-gray-200 py-2">{{ $currentCompany->cp204SubmissionDeadline()?->format('d M Y') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- CP204 Estimates List -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title">CP204 Estimates</h4>
                <div class="flex items-center gap-2">
                    <button wire:click="generateReminders" class="btn bg-info text-white" type="button">
                        <i class="mgc_notification_line mr-1"></i> Generate Reminders
                    </button>
                    <button wire:click="openCreateModal" class="btn bg-primary text-white" type="button">
                        <i class="mgc_add_line mr-1"></i> Create Estimate
                    </button>
                </div>
            </div>
        </div>
        <div>
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-x divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Year</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Period</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Estimated Tax</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Monthly Installment</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">85% Compliant</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Submitted</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($estimates as $estimate)
                                    <tr>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200">{{ $estimate->basis_period_year }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                            {{ $estimate->basis_period_from->format('d M Y') }}<br>to {{ $estimate->basis_period_to->format('d M Y') }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                            @if($estimate->estimate_type === 'cp204_initial')
                                                <span class="badge bg-primary text-white px-2 py-1 rounded">CP204 Initial</span>
                                            @else
                                                <span class="badge bg-warning text-white px-2 py-1 rounded">CP204A Revision ({{ $estimate->revision_month }}th month)</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-800 dark:text-gray-200">RM {{ number_format($estimate->estimated_tax_amount, 2) }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-800 dark:text-gray-200">RM {{ number_format($estimate->monthly_installment, 2) }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                            @if($estimate->submission_status === 'draft')
                                                <span class="badge bg-secondary text-white px-2 py-1 rounded">Draft</span>
                                            @elseif($estimate->submission_status === 'submitted')
                                                <span class="badge bg-info text-white px-2 py-1 rounded">Submitted</span>
                                            @elseif($estimate->submission_status === 'approved')
                                                <span class="badge bg-success text-white px-2 py-1 rounded">Approved</span>
                                            @else
                                                <span class="badge bg-danger text-white px-2 py-1 rounded">Rejected</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                            @if($estimate->is_85_percent_compliant === null)
                                                <span class="text-gray-500">N/A</span>
                                            @elseif($estimate->is_85_percent_compliant)
                                                <span class="badge bg-success text-white px-2 py-1 rounded">Yes</span>
                                            @else
                                                <span class="badge bg-danger text-white px-2 py-1 rounded">No</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                            @if($estimate->submitted_at)
                                                {{ $estimate->submitted_at->format('d M Y') }}
                                            @else
                                                <span class="text-gray-500">Not submitted</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">No estimates found. Create one to get started.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if($estimates->hasPages())
        <div class="p-4">
            {{ $estimates->links() }}
        </div>
        @endif
    </div>

    <!-- Create Modal -->
    @if($showCreateModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0,0,0,0.5);">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Create CP204 Estimate</h3>
                        <button wire:click="closeCreateModal" class="text-gray-400 hover:text-gray-600">
                            <i class="mgc_close_line text-xl"></i>
                        </button>
                    </div>

                    <form wire:submit.prevent="createEstimate">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Estimate Type</label>
                                <select wire:model="estimate_type" class="form-select" required>
                                    <option value="cp204_initial">CP204 Initial Submission</option>
                                    <option value="cp204a_revision">CP204A Revision</option>
                                </select>
                                @error('estimate_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            @if($estimate_type === 'cp204a_revision')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Revision Month</label>
                                <select wire:model="revision_month" class="form-select" required>
                                    <option value="">Select month</option>
                                    <option value="6">6th Month</option>
                                    <option value="9">9th Month</option>
                                    <option value="11">11th Month</option>
                                </select>
                                @error('revision_month') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Estimated Tax Amount (RM)</label>
                                <input type="number" step="0.01" wire:model="estimated_tax_amount" wire:change="calculateInstallment" class="form-input" required>
                                @error('estimated_tax_amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Monthly Installment (RM)</label>
                                <input type="number" step="0.01" wire:model="monthly_installment" class="form-input" required>
                                <p class="text-sm text-gray-500 mt-1">Will auto-calculate to 1/12 of estimated tax amount</p>
                                @error('monthly_installment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Remarks (Optional)</label>
                                <textarea wire:model="remarks" class="form-input" rows="3"></textarea>
                                @error('remarks') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 mt-6">
                            <button type="button" wire:click="closeCreateModal" class="btn bg-gray-200 text-gray-700">Cancel</button>
                            <button type="submit" class="btn bg-primary text-white">Create Estimate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
