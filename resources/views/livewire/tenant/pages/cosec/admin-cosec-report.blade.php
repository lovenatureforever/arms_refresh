<div class="mb-4">
    <div class="card">
        <div class="text-black w-full lg:w-2/3 mx-auto p-6">
            {{-- Back Button --}}
            <div class="mb-4">
                <a href="{{ route('admin.cosec.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Orders
                </a>
            </div>

            <div class="flex items-center justify-between border-b mb-6">
                <h2 class="text-xl font-semibold py-4">
                    {{ $order->form_name }}
                </h2>

                <div class="flex gap-2">
                    @if ((int)$order->status === \App\Models\Tenant\CosecOrder::STATUS_PENDING)
                        <button wire:click="approve" wire:confirm="Approve this order?" class="btn bg-green-600 text-white">
                            Approve
                        </button>
                        <button wire:click="deny" wire:confirm="Deny this order?" class="btn bg-red-600 text-white">
                            Deny
                        </button>
                    @elseif ((int)$order->status === \App\Models\Tenant\CosecOrder::STATUS_APPROVED)
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                    @elseif ((int)$order->status === \App\Models\Tenant\CosecOrder::STATUS_REJECTED)
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                    @endif
                </div>
            </div>

            {{-- Check if order has document_content --}}
            @if (!empty($order->document_content))
                {{-- Render saved document content --}}
                <div class="prose max-w-none">
                    {!! $order->document_content !!}
                </div>
            @else
                {{-- No document content yet - show message --}}
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                    <p class="text-yellow-700">This order has not been processed yet.</p>
                    <p class="text-yellow-600 text-sm mt-2">Please go to the <a href="{{ route('admin.cosec.order.edit', $order->id) }}" class="text-blue-600 underline">Order Edit page</a> to fill in the document details and approve.</p>
                </div>
            @endif
        </div>
    </div>
</div>
