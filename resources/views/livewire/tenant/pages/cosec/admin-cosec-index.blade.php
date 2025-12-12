<div>
    {{-- Header --}}
    <div class="mb-6">
        <h4 class="text-xl font-semibold text-gray-800">COSEC Orders</h4>
        <p class="text-gray-500 mt-1">Manage and process COSEC document orders from directors.</p>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h5 class="card-title">All Orders</h5>
                <span class="text-sm text-gray-500">{{ $orders->count() }} orders</span>
            </div>
        </div>
        <div class="card-body">
            @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Requester</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Template</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cost</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Approved Date</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">#{{ $order->id }}</p>
                                    <p class="text-xs text-gray-500">{{ $order->created_at->format('d M Y') }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-sm text-gray-900">{{ $order->company_name }}</p>
                                <p class="text-xs text-gray-500">{{ $order->company_no }}</p>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $order->user }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $order->form_name }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm font-medium text-gray-900">{{ number_format($order->getEffectiveCost(), 0) }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if($order->status == \App\Models\Tenant\CosecOrder::STATUS_PENDING)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @elseif($order->status == \App\Models\Tenant\CosecOrder::STATUS_APPROVED)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Approved
                                    </span>
                                @elseif($order->status == \App\Models\Tenant\CosecOrder::STATUS_REJECTED)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Rejected
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Unknown
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($order->approved_at)
                                    <p class="text-sm text-gray-900">{{ $order->approved_at->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $order->approved_at->format('h:i A') }}</p>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Edit/Fill Form - show for pending orders --}}
                                    @if($order->status == \App\Models\Tenant\CosecOrder::STATUS_PENDING)
                                        <a href="{{ route('admin.cosec.order.edit', $order->id) }}"
                                           class="btn bg-primary text-white text-xs px-3 py-1">
                                            Fill Form
                                        </a>
                                    @endif

                                    {{-- View/Print for approved orders (no edit allowed) --}}
                                    @if($order->status == \App\Models\Tenant\CosecOrder::STATUS_APPROVED)
                                        <a href="{{ route('admin.cosec.report', $order->id) }}"
                                           class="btn bg-info text-white text-xs px-3 py-1">
                                            View
                                        </a>
                                        <button wire:click="printPdf({{ $order->id }})"
                                                class="btn bg-secondary text-white text-xs px-3 py-1">
                                            PDF
                                        </button>
                                    @endif

                                    {{-- Edit button only for rejected orders (to allow re-processing) --}}
                                    @if($order->status == \App\Models\Tenant\CosecOrder::STATUS_REJECTED)
                                        <a href="{{ route('admin.cosec.order.edit', $order->id) }}"
                                           class="btn bg-gray-200 text-gray-700 text-xs px-3 py-1">
                                            Edit
                                        </a>
                                    @endif
                                </div>
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
                <p class="text-gray-500">No orders yet.</p>
            </div>
            @endif
        </div>
    </div>
</div>
