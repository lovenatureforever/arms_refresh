<div class="grid grid-cols-1 gap-6">
    <!-- Company Selector and Stats -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Tax Reminders</h4>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Company</label>
                    <select wire:model.live="selectedCompany" class="form-select">
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select wire:model.live="statusFilter" class="form-select">
                        <option value="all">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="sent">Sent</option>
                        <option value="overdue">Overdue</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                    <select wire:model.live="categoryFilter" class="form-select">
                        <option value="all">All Categories</option>
                        <option value="cp204">CP204</option>
                        <option value="cp204a">CP204A</option>
                        <option value="monthly_installment">Monthly Installment</option>
                    </select>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['total'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total</div>
                </div>
                <div class="text-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                    <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['pending'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Pending</div>
                </div>
                <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['sent'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Sent</div>
                </div>
                <div class="text-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $stats['overdue'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Overdue</div>
                </div>
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-900/20 rounded-lg">
                    <div class="text-2xl font-bold text-gray-600 dark:text-gray-400">{{ $stats['completed'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Completed</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reminders List -->
    <div class="card">
        <div>
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-x divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Due Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Next Notification</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Priority</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium uppercase text-gray-500" scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($reminders as $reminder)
                                    <tr>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200">
                                            {{ $reminder->reminder_title }}
                                            @if($reminder->reminder_message)
                                                <p class="text-xs text-gray-500 mt-1">{{ Str::limit($reminder->reminder_message, 50) }}</p>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                            @if($reminder->reminder_category === 'cp204')
                                                <span class="badge bg-primary text-white px-2 py-1 rounded">CP204</span>
                                            @elseif($reminder->reminder_category === 'cp204a')
                                                <span class="badge bg-warning text-white px-2 py-1 rounded">CP204A</span>
                                            @else
                                                <span class="badge bg-info text-white px-2 py-1 rounded">Installment</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                            {{ $reminder->action_due_date->format('d M Y') }}
                                            @if($reminder->action_due_date->isPast() && $reminder->status !== 'completed')
                                                <span class="text-red-500 text-xs">(Overdue)</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                            @if($reminder->next_notification_at)
                                                {{ $reminder->next_notification_at->format('d M Y H:i') }}
                                            @else
                                                <span class="text-gray-500">-</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                            @if($reminder->reminder_priority === 'urgent')
                                                <span class="badge bg-danger text-white px-2 py-1 rounded">Urgent</span>
                                            @elseif($reminder->reminder_priority === 'high')
                                                <span class="badge bg-warning text-white px-2 py-1 rounded">High</span>
                                            @else
                                                <span class="badge bg-info text-white px-2 py-1 rounded">{{ ucfirst($reminder->reminder_priority) }}</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                            @if($reminder->status === 'pending')
                                                <span class="badge bg-secondary text-white px-2 py-1 rounded">Pending</span>
                                            @elseif($reminder->status === 'sent')
                                                <span class="badge bg-info text-white px-2 py-1 rounded">Sent</span>
                                            @elseif($reminder->status === 'overdue')
                                                <span class="badge bg-danger text-white px-2 py-1 rounded">Overdue</span>
                                            @elseif($reminder->status === 'completed')
                                                <span class="badge bg-success text-white px-2 py-1 rounded">Completed</span>
                                            @else
                                                <span class="badge bg-gray-500 text-white px-2 py-1 rounded">{{ ucfirst($reminder->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-center text-sm">
                                            @if($reminder->status !== 'completed')
                                            <div class="flex justify-center gap-2">
                                                <button wire:click="acknowledgeReminder({{ $reminder->id }})" class="btn btn-sm bg-info text-white px-3 py-2 rounded" title="Acknowledge">
                                                    <i class="mgc_check_line text-base"></i>
                                                </button>
                                                <button wire:click="completeReminder({{ $reminder->id }})" class="btn btn-sm bg-success text-white px-3 py-2 rounded" title="Mark Complete">
                                                    <i class="mgc_check_circle_line text-base"></i>
                                                </button>
                                            </div>
                                            @else
                                                <span class="text-green-600"><i class="mgc_check_circle_line text-base"></i> Done</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No reminders found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if($reminders->hasPages())
        <div class="p-4">
            {{ $reminders->links() }}
        </div>
        @endif
    </div>
</div>
