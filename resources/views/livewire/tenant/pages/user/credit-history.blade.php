<div class="grid grid-cols-1 gap-6">
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title">COSEC Order History - {{ $user->name }}</h4>
                <a href="{{ route('users.index') }}" class="btn bg-gray-500 text-white">Back to Users</a>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600">Current Balance: <span class="font-semibold text-lg">${{ number_format($user->credit ?? 0, 2) }}</span></p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-x divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Order ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Service</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Company</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Cost</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($cosecOrders as $order)
                            <tr>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                    {{ $order->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                    {{ $order->uuid }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                    {{ $order->form_name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                    {{ $order->company_name }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-red-600">
                                    -${{ number_format($order->getEffectiveCost(), 2) }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm">
                                    <div class="inline-flex items-center gap-1.5 py-1 px-3 rounded text-xs font-medium
                                        @if($order->status == 0) bg-yellow-100 text-yellow-800
                                        @elseif($order->status == 1) bg-green-100 text-green-800
                                        @elseif($order->status == 2) bg-red-100 text-red-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        @if($order->status == 0) Requested
                                        @elseif($order->status == 1) Approved
                                        @elseif($order->status == 2) Rejected
                                        @else Completed @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No COSEC orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-4 py-1">
                <nav class="flex items-center space-x-2">
                    {{ $cosecOrders->links() }}
                </nav>
            </div>
        </div>
    </div>
</div>