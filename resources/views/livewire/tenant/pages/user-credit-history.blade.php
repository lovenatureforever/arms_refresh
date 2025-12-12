<div class="mb-4 grid grid-cols-1 gap-6">
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between flex-wrap gap-2">
                <h3 class="card-title">My Credit History</h3>
                <a href="{{ route('profile') }}" class="btn bg-gray-500 text-white text-sm">
                    <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Profile
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="card-body border-b dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                    <p class="text-sm text-blue-600 dark:text-blue-400">Current Balance</p>
                    <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">RM {{ number_format($currentBalance, 0) }}</p>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                    <p class="text-sm text-green-600 dark:text-green-400">Total Credits</p>
                    <p class="text-2xl font-bold text-green-700 dark:text-green-300">+ RM {{ number_format($totalCredits, 0) }}</p>
                </div>
                <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4">
                    <p class="text-sm text-red-600 dark:text-red-400">Total Debits</p>
                    <p class="text-2xl font-bold text-red-700 dark:text-red-300">- RM {{ number_format($totalDebits, 0) }}</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card-body border-b dark:border-gray-700">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type</label>
                    <select wire:model.live="filterType" class="form-select w-full text-sm">
                        <option value="">All Types</option>
                        <option value="credit">Credit (Added)</option>
                        <option value="debit">Debit (Deducted)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date From</label>
                    <input type="date" wire:model.live="filterDateFrom" class="form-input w-full text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date To</label>
                    <input type="date" wire:model.live="filterDateTo" class="form-input w-full text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">&nbsp;</label>
                    <button wire:click="clearFilters" class="btn bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm w-full">
                        Clear Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Description</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Amount</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Balance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($transactions as $transaction)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200">
                                    {{ $transaction->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($transaction->type === 'credit')
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Credit
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                            Debit
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200">
                                    <div>{{ $transaction->description }}</div>
                                    @if($transaction->reference_type)
                                        <div class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $transaction->reference_type)) }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-right font-medium {{ $transaction->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->type === 'credit' ? '+' : '-' }} RM {{ number_format($transaction->amount, 0) }}
                                </td>
                                <td class="px-4 py-3 text-sm text-right text-gray-800 dark:text-gray-200">
                                    RM {{ number_format($transaction->balance_after, 0) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    No transactions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
            <div class="card-footer">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
