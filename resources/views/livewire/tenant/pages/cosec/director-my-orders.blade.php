<div>
    {{-- Header --}}
    <div class="mb-6">
        <h4 class="text-xl font-semibold text-gray-800">My Orders</h4>
        <p class="text-gray-500 mt-1">View your COSEC orders and download completed documents.</p>
    </div>

    @if($director && $companyId)
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h3 class="card-title">Order History</h3>
                    <a href="{{ route('director.cosec.place-order') }}" class="btn bg-primary text-white">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Place New Order
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Template</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cost</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    #{{ $order->id }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $order->form_name }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ number_format($order->cost ?? 0, 0) }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500">
                                    {{ $order->created_at->format('d M Y') }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($order->status == \App\Models\Tenant\CosecOrder::STATUS_PENDING)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @elseif($order->status == \App\Models\Tenant\CosecOrder::STATUS_APPROVED)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Completed
                                        </span>
                                    @elseif($order->status == \App\Models\Tenant\CosecOrder::STATUS_REJECTED)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Rejected
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Processing
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($order->status == \App\Models\Tenant\CosecOrder::STATUS_APPROVED)
                                        <button
                                            wire:click="downloadPdf({{ $order->id }})"
                                            class="btn bg-primary text-white text-sm"
                                        >
                                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                            Download PDF
                                        </button>
                                    @elseif($order->status == \App\Models\Tenant\CosecOrder::STATUS_PENDING)
                                        <span class="text-sm text-gray-400">Awaiting admin processing</span>
                                    @elseif($order->status == \App\Models\Tenant\CosecOrder::STATUS_REJECTED)
                                        <span class="text-sm text-red-400">Order rejected</span>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-500 mb-4">You haven't placed any orders yet.</p>
                    <a href="{{ route('director.cosec.place-order') }}" class="btn bg-primary text-white">
                        Place Your First Order
                    </a>
                </div>
                @endif
            </div>
        </div>
    @else
        {{-- Not Linked Warning --}}
        <div class="card">
            <div class="card-body p-8 text-center">
                <svg class="w-16 h-16 mx-auto text-yellow-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <h5 class="text-lg font-semibold text-gray-800 mb-2">Account Not Linked</h5>
                <p class="text-gray-500">Your account is not linked to a company director profile.</p>
                <p class="text-sm text-gray-400 mt-2">Please contact your administrator to link your account.</p>
            </div>
        </div>
    @endif
</div>
