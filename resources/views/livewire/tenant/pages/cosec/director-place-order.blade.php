<div class="mb-4 grid grid-cols-1 gap-6">
    {{-- Header --}}
    <div class="mb-2">
        <h4 class="text-xl font-semibold text-gray-800">Place New Order</h4>
        <p class="text-gray-500 mt-1">Browse templates, preview them, and place orders for your company documents.</p>
    </div>

    @if($director && $company)
        {{-- Credit Balance Info --}}
        <div class="card bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $company->name }}</p>
                            <p class="text-sm text-gray-500">{{ $company->registration_no }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500 uppercase">Your Credit Balance</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($userCredit, 0) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Templates Table --}}
        <div class="card">
            <div class="card-header py-2">
                <h3 class="card-title text-sm">Available Templates</h3>
            </div>
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-2 py-2 text-left font-medium text-gray-500 uppercase dark:text-gray-400" style="width: 35%;">Template</th>
                                <th class="px-2 py-2 text-left font-medium text-gray-500 uppercase dark:text-gray-400 hidden lg:table-cell" style="width: 25%;">Description</th>
                                <th class="px-2 py-2 text-center font-medium text-gray-500 uppercase dark:text-gray-400 hidden sm:table-cell" style="width: 10%;">Sign</th>
                                <th class="px-2 py-2 text-center font-medium text-gray-500 uppercase dark:text-gray-400" style="width: 10%;">Cost</th>
                                <th class="px-2 py-2 text-right font-medium text-gray-500 uppercase dark:text-gray-400" style="width: 20%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($templates as $template)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-2 py-2 text-gray-800 dark:text-gray-200">
                                        <div class="font-medium truncate" title="{{ $template->name }}" style="max-width: 200px;">{{ $template->name }}</div>
                                        <div class="text-gray-500 lg:hidden mt-0.5 truncate" style="max-width: 180px;">{{ Str::limit($template->description, 25) ?? '-' }}</div>
                                    </td>
                                    <td class="px-2 py-2 text-gray-600 dark:text-gray-300 hidden lg:table-cell">
                                        <div class="truncate" title="{{ $template->description }}" style="max-width: 180px;">{{ Str::limit($template->description, 40) ?? '-' }}</div>
                                    </td>
                                    <td class="px-2 py-2 whitespace-nowrap text-center hidden sm:table-cell">
                                        @if($template->signature_type === 'all_directors')
                                            <span class="inline-block py-0.5 px-1.5 rounded text-[10px] font-medium bg-blue-100 text-blue-800">All</span>
                                        @elseif($template->signature_type === 'two_directors')
                                            <span class="inline-block py-0.5 px-1.5 rounded text-[10px] font-medium bg-purple-100 text-purple-800">2</span>
                                        @else
                                            <span class="inline-block py-0.5 px-1.5 rounded text-[10px] font-medium bg-green-100 text-green-800">1</span>
                                        @endif
                                    </td>
                                    <td class="px-2 py-2 whitespace-nowrap text-center text-gray-800 dark:text-gray-200">
                                        <span class="font-medium {{ $userCredit >= $template->credit_cost ? 'text-green-600' : 'text-red-600' }}">
                                            {{ number_format($template->credit_cost, 0) }}
                                        </span>
                                    </td>
                                    <td class="px-2 py-2 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            {{-- Preview Button --}}
                                            <button wire:click="showPreview({{ $template->id }})"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded border border-cyan-500 text-cyan-500 hover:bg-cyan-500 hover:text-white transition-colors"
                                                    title="Preview Template">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                            {{-- Place Order Button --}}
                                            <button wire:click="openOrderModal({{ $template->id }})"
                                                    class="inline-flex items-center px-3 py-1.5 rounded text-white text-xs font-medium transition-colors"
                                                    style="background-color: #22c55e;"
                                                    onmouseover="this.style.backgroundColor='#16a34a'"
                                                    onmouseout="this.style.backgroundColor='#22c55e'"
                                                    title="Place Order">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                Order
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-2 py-6 text-center text-gray-500">
                                        <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        No templates available at this time.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Template Preview Modal - Full Screen --}}
        @if($showPreviewModal)
        <div class="fixed inset-0 bg-black bg-opacity-75 z-50 flex flex-col">
            <div class="flex justify-between items-center px-4 py-3 bg-gray-900 text-white">
                <h3 class="text-lg font-semibold">Template Preview</h3>
                <button wire:click="closePreview" class="p-2 hover:bg-gray-700 rounded-lg transition-colors" title="Close (Esc)">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="flex-1 relative bg-white overflow-hidden">
                <div wire:loading wire:target="showPreview" class="absolute inset-0 flex items-center justify-center bg-white z-10">
                    <div class="animate-spin rounded-full h-16 w-16 border-b-4 border-blue-600"></div>
                </div>
                @if($previewTemplateId)
                <iframe src="{{ route('director.cosec.template.preview', $previewTemplateId) }}"
                        class="w-full h-full border-0">
                </iframe>
                @endif
            </div>
        </div>
        @endif

        {{-- Order Form Modal --}}
        @if($showOrderModal && $selectedTemplate)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto p-2 sm:p-0">
            <div class="min-h-screen sm:px-4 sm:py-6 flex items-start justify-center">
                <div class="bg-white rounded-lg shadow-xl w-full h-full sm:h-auto sm:max-w-3xl flex flex-col" style="max-height: calc(100vh - 16px); sm:max-height: calc(100vh - 48px);">
                    {{-- Modal Header --}}
                    <div class="p-3 border-b flex items-center justify-between bg-blue-50 rounded-t-lg flex-shrink-0 sticky top-0 z-10">
                        <div class="min-w-0 flex-1 mr-2">
                            <h3 class="text-sm sm:text-base font-semibold text-blue-800 truncate">{{ $selectedTemplate->name }}</h3>
                            <p class="text-xs text-blue-600">Fill in the details and submit your order</p>
                        </div>
                        <button wire:click="closeOrderModal" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-4 overflow-y-auto flex-1">
                    {{-- Cost Info --}}
                    <div class="mb-4 p-3 rounded-lg {{ $userCredit >= $selectedTemplate->credit_cost ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium {{ $userCredit >= $selectedTemplate->credit_cost ? 'text-green-800' : 'text-red-800' }}">Order Cost</p>
                                <p class="text-lg font-bold {{ $userCredit >= $selectedTemplate->credit_cost ? 'text-green-600' : 'text-red-600' }}">{{ number_format($selectedTemplate->credit_cost, 0) }} credits</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-600">Balance: {{ number_format($userCredit, 0) }} credits</p>
                                @if($userCredit < $selectedTemplate->credit_cost)
                                    <p class="text-xs text-red-600">Insufficient credits</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Shared Form Fields --}}
                    @include('livewire.tenant.pages.cosec.partials.order-form-fields', [
                        'customPlaceholders' => $customPlaceholders,
                        'requiredSignatures' => $requiredSignatures,
                        'directors' => $directors,
                        'selectedDirectors' => $selectedDirectors,
                    ])
                </div>

                {{-- Modal Footer --}}
                <div class="p-3 border-t flex flex-col sm:flex-row sm:justify-between gap-2 flex-shrink-0 bg-gray-50 rounded-b-lg">
                    <button wire:click="closeOrderModal" class="px-3 py-2 sm:py-1.5 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300 w-full sm:w-auto order-3 sm:order-1">
                        Cancel
                    </button>
                    <div class="flex flex-col sm:flex-row gap-2 order-1 sm:order-2">
                        <button
                            wire:click="generateDocumentPreview"
                            wire:loading.attr="disabled"
                            wire:target="generateDocumentPreview"
                            class="px-3 py-2 sm:py-1.5 text-sm rounded text-white inline-flex items-center justify-center w-full sm:w-auto"
                            style="background-color: #2563eb;"
                        >
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Preview
                        </button>
                        @if($userCredit >= $selectedTemplate->credit_cost)
                        <button
                            wire:click="placeOrder"
                            wire:confirm="Place order? {{ number_format($selectedTemplate->credit_cost, 0) }} credits will be deducted."
                            wire:loading.attr="disabled"
                            wire:target="placeOrder"
                            class="px-3 py-2 sm:py-1.5 text-sm rounded text-white inline-flex items-center justify-center w-full sm:w-auto"
                            style="background-color: #22c55e;"
                        >
                            <span wire:loading.remove wire:target="placeOrder">
                                <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Place Order
                            </span>
                            <span wire:loading wire:target="placeOrder">Processing...</span>
                        </button>
                        @else
                        <button disabled class="px-3 py-2 sm:py-1.5 text-sm bg-gray-300 text-gray-500 rounded cursor-not-allowed w-full sm:w-auto">
                            No Credits
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            </div>
        </div>
        @endif

        {{-- Document Preview Modal --}}
        @if($showDocumentPreview && $documentPreviewContent)
        <div class="fixed inset-0 bg-black bg-opacity-75 z-[60] flex items-center justify-center p-2 sm:p-4 overflow-hidden">
            <div class="bg-white rounded-lg shadow-xl w-full h-full sm:h-auto sm:max-h-[95vh] sm:max-w-4xl flex flex-col">
                {{-- Sticky Header --}}
                <div class="p-3 sm:p-4 border-b flex items-center justify-between bg-green-50 rounded-t-lg flex-shrink-0 sticky top-0 z-10">
                    <h3 class="text-base sm:text-lg font-semibold text-green-800 inline-flex items-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="truncate">Document Preview</span>
                    </h3>
                    <button wire:click="closeDocumentPreview" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full flex-shrink-0 ml-2">
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
                        {!! $documentPreviewContent !!}
                    </div>
                </div>
                {{-- Sticky Footer --}}
                <div class="p-3 sm:p-4 border-t flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 flex-shrink-0 bg-white rounded-b-lg">
                    <button wire:click="closeDocumentPreview" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 w-full sm:w-auto order-2 sm:order-1">
                        Back to Form
                    </button>
                    @if($userCredit >= $selectedTemplate->credit_cost)
                    <button
                        wire:click="placeOrder"
                        wire:confirm="Place order for '{{ $selectedTemplate->name }}'? {{ number_format($selectedTemplate->credit_cost, 0) }} credits will be deducted from your balance."
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 inline-flex items-center justify-center w-full sm:w-auto order-1 sm:order-2"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Place Order
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endif
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
