<div class="grid grid-cols-1 gap-6">
    <!-- Filters and Stats -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Reminder Notification Logs</h4>
        </div>
        <div class="p-6">
            <!-- Filters -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Company</label>
                    <select wire:model.live="selectedCompany" class="form-select">
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Log Type</label>
                    <select wire:model.live="logTypeFilter" class="form-select">
                        <option value="all">All Types</option>
                        <option value="email_sent">Email Sent</option>
                        <option value="email_failed">Email Failed</option>
                        <option value="whatsapp_sent">WhatsApp Sent</option>
                        <option value="whatsapp_failed">WhatsApp Failed</option>
                        <option value="acknowledged">Acknowledged</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Success Status</label>
                    <select wire:model.live="successFilter" class="form-select">
                        <option value="all">All</option>
                        <option value="success">Success Only</option>
                        <option value="failed">Failed Only</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date From</label>
                    <input type="date" wire:model.live="dateFrom" class="form-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date To</label>
                    <input type="date" wire:model.live="dateTo" class="form-input">
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['total'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Logs</div>
                </div>
                <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['email_sent'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Email Sent</div>
                </div>
                <div class="text-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $stats['email_failed'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Email Failed</div>
                </div>
                <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['whatsapp_sent'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">WhatsApp Sent</div>
                </div>
                <div class="text-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $stats['whatsapp_failed'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">WhatsApp Failed</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="card">
        <div>
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-x divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Date/Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Reminder</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Log Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Recipient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Error Message</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($logs as $log)
                                    <tr>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                            {{ $log->created_at->format('d M Y') }}<br>
                                            <span class="text-xs text-gray-500">{{ $log->created_at->format('H:i:s') }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                            {{ $log->reminder->reminder_title ?? 'N/A' }}
                                            <p class="text-xs text-gray-500">{{ $log->reminder->company->name ?? '' }}</p>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                            @if(str_contains($log->log_type, 'email'))
                                                <span class="badge bg-blue-500 text-white px-2 py-1 rounded"><i class="mgc_mail_line mr-1 text-sm"></i> {{ ucfirst(str_replace('_', ' ', $log->log_type)) }}</span>
                                            @elseif(str_contains($log->log_type, 'whatsapp'))
                                                <span class="badge bg-green-500 text-white px-2 py-1 rounded"><i class="mgc_phone_line mr-1 text-sm"></i> {{ ucfirst(str_replace('_', ' ', $log->log_type)) }}</span>
                                            @else
                                                <span class="badge bg-gray-500 text-white px-2 py-1 rounded">{{ ucfirst(str_replace('_', ' ', $log->log_type)) }}</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                            {{ $log->recipient_email ?? 'N/A' }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                            @if($log->success)
                                                <span class="badge bg-success text-white px-2 py-1 rounded"><i class="mgc_check_circle_line mr-1 text-sm"></i> Success</span>
                                            @else
                                                <span class="badge bg-danger text-white px-2 py-1 rounded"><i class="mgc_close_circle_line mr-1 text-sm"></i> Failed</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                            @if($log->error_message)
                                                <span class="text-red-600 text-xs">{{ Str::limit($log->error_message, 50) }}</span>
                                            @else
                                                <span class="text-gray-500">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No logs found for the selected filters.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if($logs->hasPages())
        <div class="p-4">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>
