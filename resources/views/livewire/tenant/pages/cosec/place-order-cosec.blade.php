<div class="grid grid-cols-1 gap-6">
    <!-- Company Info Header -->
    @if($company)
    <div class="card">
        <div class="card-body p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">{{ $company->name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $company->registration_no }} @if($company->registration_no_old)({{ $company->registration_no_old }})@endif</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Credit Balance</p>
                    <p class="text-2xl font-bold text-primary">{{ number_format(auth()->user()->credit ?? 0, 0) }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Place Order Form -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Place Order</h3>
        </div>
        <div class="card-body p-6">
            <!-- Template Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Template</label>
                <select wire:model.live="selectedTemplateId" class="form-input w-full">
                    <option value="">-- Select a Template --</option>
                    @foreach($templates as $template)
                        <option value="{{ $template->id }}">
                            {{ $template->name }} ({{ $template->credit_cost }} credits)
                        </option>
                    @endforeach
                </select>
            </div>

            @if($selectedTemplate)
            <div wire:key="template-form-{{ $selectedTemplateId }}">
            <!-- Template Info -->
            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-blue-800 dark:text-blue-300">{{ $selectedTemplate->name }}</h4>
                        @if($selectedTemplate->description)
                            <p class="text-sm text-blue-700 dark:text-blue-400 mt-1">{{ $selectedTemplate->description }}</p>
                        @endif
                        <p class="text-sm text-blue-600 dark:text-blue-400 mt-2">
                            <strong>Cost:</strong> {{ $selectedTemplate->credit_cost }} credits |
                            <strong>Signatures Required:</strong> {{ $requiredSignatures }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Shared Form Fields -->
            @include('livewire.tenant.pages.cosec.partials.order-form-fields', [
                'customPlaceholders' => $customPlaceholders,
                'requiredSignatures' => $requiredSignatures,
                'directors' => $directors,
                'selectedDirectors' => $selectedDirectors,
            ])

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6 border-t dark:border-gray-700">
                <a href="{{ route('home') }}" class="btn bg-gray-500 text-white">
                    Cancel
                </a>
                <button type="button" wire:click="generatePreview" wire:loading.attr="disabled" class="btn bg-primary text-white">
                    <span wire:loading.remove wire:target="generatePreview">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Preview Document
                    </span>
                    <span wire:loading wire:target="generatePreview">
                        <svg class="animate-spin h-4 w-4 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Generating...
                    </span>
                </button>
            </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Inline Preview Section (appears below form when preview is generated) -->
    @if($showPreview && $previewContent)
    <div class="card" wire:key="preview-section">
        <div class="card-header bg-green-50 dark:bg-green-900/20">
            <div class="flex items-center justify-between w-full">
                <h3 class="card-title text-green-800 dark:text-green-300">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Document Preview
                </h3>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="window.printDocument()" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200" title="Print Document">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                    </button>
                    <button type="button" wire:click="closePreview" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" title="Close Preview">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-6">
            <!-- Preview Content -->
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
            <div id="printableContent" class="border dark:border-gray-700 rounded-lg p-3 sm:p-6 bg-white dark:bg-gray-900 max-w-none overflow-x-auto document-preview">
                {!! $previewContent !!}
            </div>

            <!-- Submit Actions -->
            <div class="mt-4 sm:mt-6 pt-4 sm:pt-6 border-t dark:border-gray-700">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <p>This order will cost <strong class="text-primary">{{ $selectedTemplate->credit_cost }} credits</strong></p>
                        <p class="text-xs mt-1">Your balance after order: {{ number_format((auth()->user()->credit ?? 0) - $selectedTemplate->credit_cost, 0) }} credits</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <button type="button" onclick="window.printDocument()" class="btn bg-blue-600 hover:bg-blue-700 text-white w-full sm:w-auto order-2 sm:order-1">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Print
                        </button>
                        <button type="button" wire:click="closePreview" class="btn bg-gray-500 text-white w-full sm:w-auto order-3 sm:order-2">
                            Edit Details
                        </button>
                        <button type="button" wire:click="submitOrder" wire:loading.attr="disabled" class="btn bg-green-600 hover:bg-green-700 text-white w-full sm:w-auto order-1 sm:order-3">
                            <span wire:loading.remove wire:target="submitOrder">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Confirm & Place Order
                            </span>
                            <span wire:loading wire:target="submitOrder">
                                <svg class="animate-spin h-4 w-4 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Print Script - Always available -->
    @script
    <script>
        window.printDocument = function() {
            const contentEl = document.getElementById('printableContent');
            if (!contentEl) {
                alert('No content to print. Please generate preview first.');
                return;
            }

            const content = contentEl.innerHTML;
            const printWindow = window.open('', '_blank', 'width=800,height=600');

            if (!printWindow) {
                alert('Please allow popups for this site to print.');
                return;
            }

            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Print Document</title>
                    <style>
                        body {
                            font-family: 'Times New Roman', Times, serif;
                            margin: 0;
                            padding: 20px;
                        }
                        @media print {
                            body { margin: 0; padding: 0; }
                            @page { margin: 1cm; }
                        }
                    </style>
                </head>
                <body>
                    ${content}
                </body>
                </html>
            `);
            printWindow.document.close();

            // Wait for content to load then print
            printWindow.onload = function() {
                setTimeout(function() {
                    printWindow.print();
                }, 250);
            };
        };
    </script>
    @endscript
</div>
