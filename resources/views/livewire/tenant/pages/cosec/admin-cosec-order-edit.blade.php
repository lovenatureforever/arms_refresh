<div>
    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h4 class="text-xl font-semibold text-gray-800">Process Order #{{ $order->id }}</h4>
            <p class="text-gray-500 mt-1">Fill in the document details and approve the order.</p>
        </div>
        <a href="{{ route('admin.cosec.index') }}" class="btn bg-gray-200 text-gray-700 inline-flex items-center self-start">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Orders
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Order Details Sidebar --}}
        <div class="lg:col-span-1 space-y-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Order Details</h5>
                </div>
                <div class="card-body p-6">
                    <dl class="space-y-4">
                        {{-- Status --}}
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status</dt>
                            <dd class="mt-1">
                                @php $statusBadge = (int) $order->status; @endphp
                                @if($statusBadge === \App\Models\Tenant\CosecOrder::STATUS_APPROVED)
                                    <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                @elseif($statusBadge === \App\Models\Tenant\CosecOrder::STATUS_REJECTED)
                                    <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @endif
                            </dd>
                        </div>

                        {{-- Company --}}
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Company</dt>
                            <dd class="mt-1">
                                <p class="text-sm font-medium text-gray-900">{{ $order->company_name }}</p>
                                <p class="text-sm text-gray-500">{{ $order->company_no }}</p>
                            </dd>
                        </div>

                        {{-- Requester --}}
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Requested By</dt>
                            <dd class="mt-1 text-sm font-medium text-gray-900">{{ $order->user }}</dd>
                        </div>

                        {{-- Template --}}
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Template</dt>
                            <dd class="mt-1 text-sm font-medium text-gray-900">{{ $order->form_name }}</dd>
                        </div>

                        {{-- Cost --}}
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Credits (Paid)</dt>
                            <dd class="mt-1 text-lg font-bold text-green-600">{{ number_format($order->getEffectiveCost(), 0) }}</dd>
                        </div>

                        {{-- Date --}}
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Order Date</dt>
                            <dd class="mt-1 text-sm text-gray-700">{{ $order->created_at->format('d M Y, h:i A') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Actions</h5>
                </div>
                <div class="card-body p-6">
                    <div class="flex flex-col gap-3">
                        {{-- Always show Preview button --}}
                        <button wire:click="generatePreview" type="button" style="display: block; width: 100%; padding: 10px 16px; background-color: #2563eb; color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; text-align: center;">
                            Preview Document
                        </button>

                        @if((int)$order->status !== 1)
                        <button
                            wire:click="approve"
                            wire:confirm="Approve this order? The director will be able to download the PDF."
                            type="button"
                            style="display: block; width: 100%; padding: 10px 16px; background-color: #16a34a; color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; text-align: center;"
                        >
                            Approve Order
                        </button>
                        <button
                            wire:click="reject"
                            wire:confirm="Reject this order? This action cannot be undone."
                            type="button"
                            style="display: block; width: 100%; padding: 10px 16px; background-color: #dc2626; color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; text-align: center;"
                        >
                            Reject Order
                        </button>
                        @else
                        <a href="{{ route('admin.cosec.report', $order->id) }}" style="display: block; width: 100%; padding: 10px 16px; background-color: #0891b2; color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; text-align: center; text-decoration: none;">
                            View Report
                        </a>
                        <button wire:click="printPdf" type="button" style="display: block; width: 100%; padding: 10px 16px; background-color: #4b5563; color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; text-align: center;">
                            Download PDF
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Document Form --}}
        <div class="lg:col-span-2 space-y-4">
            {{-- Template Info --}}
            @if($order->template)
            <div class="card">
                <div class="card-body p-4 bg-blue-50 rounded-lg">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h4 class="font-semibold text-blue-800">{{ $order->template->name }}</h4>
                            @if($order->template->description)
                                <p class="text-sm text-blue-700 mt-1">{{ $order->template->description }}</p>
                            @endif
                            <p class="text-sm text-blue-600 mt-2">
                                <strong>Signatures Required:</strong> {{ $requiredSignatures }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Document Form Fields (Shared Partial) --}}
            @if(count($customPlaceholders) > 0 || $requiredSignatures > 0)
            <div class="card">
                <div class="card-header flex items-center justify-between">
                    <h5 class="card-title">Document Form</h5>
                    <button wire:click="saveDraft" class="btn btn-sm bg-gray-200 text-gray-700 inline-flex items-center">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        Save Draft
                    </button>
                </div>
                <div class="card-body p-6">
                    @include('livewire.tenant.pages.cosec.partials.order-form-fields', [
                        'customPlaceholders' => $customPlaceholders,
                        'requiredSignatures' => $requiredSignatures,
                        'directors' => $directors,
                        'selectedDirectors' => $selectedDirectors,
                    ])
                </div>
            </div>
            @endif

            {{-- Custom Credit Cost --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Pricing</h5>
                </div>
                <div class="card-body p-6">
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Custom Credit Cost (optional)</label>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                            <div class="flex items-center">
                                <input type="number"
                                       class="form-input w-28"
                                       wire:model="customCreditCost"
                                       min="0"
                                       placeholder="{{ $order->template ? $order->template->credit_cost : '0' }}">
                                <span class="text-gray-500 ml-2">credits</span>
                            </div>
                            <span class="text-sm text-gray-500">
                                Template default: {{ $order->template ? number_format($order->template->credit_cost, 0) : 'N/A' }} credits
                            </span>
                        </div>
                        @error('customCreditCost') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Preview Modal --}}
    @if($showPreview && $previewContent)
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-2 sm:p-4 overflow-hidden">
        <div class="bg-white rounded-lg shadow-xl w-full h-full sm:h-auto sm:max-h-[95vh] sm:max-w-4xl flex flex-col">
            {{-- Sticky Header --}}
            <div class="p-3 sm:p-4 border-b flex items-center justify-between bg-green-50 rounded-t-lg flex-shrink-0 sticky top-0 z-10">
                <h3 class="text-base sm:text-lg font-semibold text-green-800 inline-flex items-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="truncate">Document Preview</span>
                </h3>
                <button wire:click="closePreview" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full flex-shrink-0 ml-2">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            {{-- Scrollable Content --}}
            <div class="p-3 sm:p-6 overflow-y-auto flex-1 min-h-0">
                <style>
                    /* Reset prose image styles and preserve template's own CSS */
                    .document-preview img {
                        max-width: 150px !important;
                        max-height: 50px !important;
                        margin: 0 !important;
                    }
                    /* Ensure signature-area styling matches PDF output */
                    .document-preview .signature-area {
                        height: 60px;
                        margin-bottom: 10px;
                        display: flex;
                        align-items: flex-end;
                        justify-content: center;
                    }
                    .document-preview .signature-area img {
                        max-height: 50px !important;
                        max-width: 150px !important;
                        object-fit: contain;
                    }
                    /* Preserve grid layout for signatures */
                    /* Responsive grid for signatures */
                    .document-preview .signatures-grid {
                        display: grid !important;
                        grid-template-columns: 1fr !important;
                        gap: 20px !important;
                    }
                    .document-preview .certification-signatures {
                        display: grid !important;
                        grid-template-columns: 1fr !important;
                        gap: 20px !important;
                    }
                    @media (min-width: 640px) {
                        .document-preview .signatures-grid {
                            grid-template-columns: 1fr 1fr !important;
                            gap: 40px !important;
                        }
                        .document-preview .certification-signatures {
                            grid-template-columns: 1fr 1fr !important;
                            gap: 40px !important;
                        }
                    }
                    .document-preview .signature-block {
                        text-align: center;
                    }
                    /* Make document content overflow properly on mobile */
                    .document-preview {
                        overflow-x: auto;
                        -webkit-overflow-scrolling: touch;
                    }
                </style>
                <div class="border rounded-lg p-3 sm:p-6 bg-white max-w-none document-preview overflow-x-auto">
                    {!! $previewContent !!}
                </div>
            </div>
            {{-- Sticky Footer --}}
            <div class="p-3 sm:p-4 border-t flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 flex-shrink-0 bg-white rounded-b-lg">
                <button wire:click="closePreview" class="btn bg-gray-200 text-gray-700 w-full sm:w-auto order-2 sm:order-1">
                    Close
                </button>
                <button
                    wire:click="approve"
                    wire:confirm="Approve this order? The director will be able to download the PDF."
                    class="btn bg-green-600 text-white inline-flex items-center justify-center w-full sm:w-auto order-1 sm:order-2"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Approve & Complete
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
