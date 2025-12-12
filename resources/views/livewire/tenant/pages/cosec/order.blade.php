<div class="mb-4 grid grid-cols-1 gap-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Orders</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-gray-200">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <p class="text-sm text-yellow-600 dark:text-yellow-400">Pending</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <p class="text-sm text-green-600 dark:text-green-400">Approved</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['approved'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <p class="text-sm text-red-600 dark:text-red-400">Rejected</p>
            <p class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between flex-wrap gap-2">
                <h3 class="card-title">COSEC Orders</h3>
            </div>
        </div>

        <!-- Filters -->
        <div class="card-body border-b dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                    <input type="text" wire:model.live.debounce.300ms="searchQuery" class="form-input w-full text-sm" placeholder="Search by Order ID, Form, User...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select wire:model.live="filterStatus" class="form-select w-full text-sm">
                        <option value="">All Status</option>
                        <option value="0">Pending</option>
                        <option value="1">Approved</option>
                        <option value="2">Rejected</option>
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
                <div class="flex items-end">
                    <button wire:click="clearFilters" class="btn bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm w-full">
                        Clear
                    </button>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Order ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Company</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400 hidden md:table-cell">Requested By</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Resolution</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Cost</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400 hidden sm:table-cell">Date</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200">
                                <span class="font-mono text-xs">{{ Str::limit($order->uuid, 12) }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200">
                                <div class="font-medium">{{ $order->company_name }}</div>
                                <div class="text-xs text-gray-500">{{ $order->company_no }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200 hidden md:table-cell">
                                {{ $order->user }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200">
                                {{ $order->form_name }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right font-medium text-gray-800 dark:text-gray-200">
                                RM {{ number_format($order->getEffectiveCost(), 0) }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($order->status == 0)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                        Pending
                                    </span>
                                @elseif($order->status == 1)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                        Approved
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                        Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500 hidden sm:table-cell">
                                {{ $order->created_at->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('cosec.view', $order->id) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded border border-cyan-500 text-cyan-500 hover:bg-cyan-500 hover:text-white transition-colors"
                                       title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    @if($order->status == 1)
                                    <a href="{{ route('cosec.print-pdf', $order->id) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded border border-red-500 text-red-500 hover:bg-red-500 hover:text-white transition-colors"
                                       title="Download PDF">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('cosec.print-word', $order->id) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded border border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white transition-colors"
                                       title="Download Word">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                No orders found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="card-footer">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
