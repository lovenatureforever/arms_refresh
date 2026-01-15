<div x-data="{ showInstructions: false }">
    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex flex-col justify-center items-center">
            <h4 class="text-xl font-semibold text-gray-800">
                {{ $isEditing ? 'Edit Template' : 'Create New Template' }}
            </h4>
            <p class="text-gray-500 mt-1">Design your document template with placeholders for dynamic content.</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <button @click="showInstructions = !showInstructions" class="btn btn-sm bg-blue-100 text-blue-700 inline-flex items-center">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Help
            </button>
            <a href="{{ route('admin.cosec.templates') }}" class="btn btn-sm bg-gray-200 text-gray-700 inline-flex items-center">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Cancel
            </a>
            <button wire:click="save" class="btn btn-sm bg-success text-white inline-flex items-center">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ $isEditing ? 'Update Template' : 'Save Template' }}
            </button>
        </div>
    </div>

    <div>
        {{-- Instructions Panel (Collapsible) --}}
        <div x-show="showInstructions" x-collapse class="mb-4">
            <div class="card">
                <div class="card-body p-4 bg-blue-50">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="flex-1">
                            <h3 class="font-semibold text-blue-800 mb-2">How to Create Templates</h3>
                            <div class="text-sm text-blue-700 space-y-2">
                                <p><strong>1. Template Settings:</strong> Enter a name, description, credit cost, and select the signature type.</p>
                                <p><strong>2. Use Placeholders:</strong> Insert placeholders using the format <code class="bg-blue-100 px-1 rounded">\{\{placeholder_name\}\}</code>. These will be replaced with actual data when generating documents.</p>
                                <p><strong>3. Quick Insert:</strong> Click on any placeholder button in the panel below to copy it to clipboard.</p>
                                <p><strong>4. Signature Type:</strong></p>
                                <ul class="list-disc list-inside ml-4">
                                    <li><strong>Single Director:</strong> Uses <code class="bg-blue-100 px-1 rounded">\{\{director_signature_1\}\}</code> for single signatory</li>
                                    <li><strong>Two Directors:</strong> Uses <code class="bg-blue-100 px-1 rounded">\{\{director_signature_1\}\}</code> and <code class="bg-blue-100 px-1 rounded">\{\{director_signature_2\}\}</code></li>
                                    <li><strong>All Directors:</strong> Dynamically adapts to all directors - add placeholders _1, _2, _3, etc.</li>
                                </ul>
                            </div>
                        </div>
                        <button @click="showInstructions = false" class="text-blue-500 hover:text-blue-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Template Settings --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title">Template Settings</h5>
            </div>
            <div class="card-body p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Template Name <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Board Resolution">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <input type="text" wire:model="description" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Brief description">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Credit Cost <span class="text-red-500">*</span></label>
                        <input type="number" wire:model="credit_cost" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" min="0" step="1">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Signature Type</label>
                        <select wire:model="signature_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="sole_director">Single Director (1 signature)</option>
                            <option value="two_directors">Two Directors (2 signatures)</option>
                            <option value="all_directors">All Directors</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <label class="inline-flex items-center">
                            <input type="checkbox" wire:model="is_active" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Insert Placeholders Panel --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title flex items-center">
                    <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    Quick Insert Placeholders
                    <span class="ml-2 text-xs text-gray-400 font-normal">(Click to copy)</span>
                </h5>
            </div>
            <div class="card-body p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Company Placeholders -->
                    <div>
                        <p class="text-xs font-medium text-green-700 mb-2 uppercase">Company (Auto-filled)</p>
                        <div class="flex flex-wrap gap-1">
                            <button type="button" onclick="copyPlaceholder('company_name')" class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded hover:bg-green-200" title="Click to copy">company_name</button>
                            <button type="button" onclick="copyPlaceholder('company_no')" class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded hover:bg-green-200" title="Click to copy">company_no</button>
                            <button type="button" onclick="copyPlaceholder('company_old_no')" class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded hover:bg-green-200" title="Click to copy">company_old_no</button>
                            <button type="button" onclick="copyPlaceholder('company_address')" class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded hover:bg-green-200" title="Click to copy">company_address</button>
                        </div>
                    </div>

                    <!-- Director Placeholders -->
                    <div>
                        <p class="text-xs font-medium text-blue-700 mb-2 uppercase">Directors (Auto-filled)</p>
                        <div class="flex flex-wrap gap-1">
                            <button type="button" onclick="copyPlaceholder('director_name_1')" class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200" title="Click to copy">director_name_1</button>
                            <button type="button" onclick="copyPlaceholder('director_signature_1')" class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200" title="Click to copy">director_signature_1</button>
                            <button type="button" onclick="copyPlaceholder('director_name_2')" class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200" title="Click to copy">director_name_2</button>
                            <button type="button" onclick="copyPlaceholder('director_signature_2')" class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200" title="Click to copy">director_signature_2</button>
                        </div>
                    </div>

                    <!-- Secretary Placeholders -->
                    <div>
                        <p class="text-xs font-medium text-purple-700 mb-2 uppercase">Secretary (Auto-filled)</p>
                        <div class="flex flex-wrap gap-1">
                            <button type="button" onclick="copyPlaceholder('secretary_name')" class="px-2 py-1 text-xs bg-purple-100 text-purple-700 rounded hover:bg-purple-200" title="Click to copy">secretary_name</button>
                            <button type="button" onclick="copyPlaceholder('secretary_license')" class="px-2 py-1 text-xs bg-purple-100 text-purple-700 rounded hover:bg-purple-200" title="Click to copy">secretary_license</button>
                            <button type="button" onclick="copyPlaceholder('secretary_ssm_no')" class="px-2 py-1 text-xs bg-purple-100 text-purple-700 rounded hover:bg-purple-200" title="Click to copy">secretary_ssm_no</button>
                            <button type="button" onclick="copyPlaceholder('secretary_signature')" class="px-2 py-1 text-xs bg-purple-100 text-purple-700 rounded hover:bg-purple-200" title="Click to copy">secretary_signature</button>
                        </div>
                    </div>

                    <!-- Custom/User Input Placeholders -->
                    <div>
                        <p class="text-xs font-medium text-orange-700 mb-2 uppercase">Common Fields (User Input)</p>
                        <div class="flex flex-wrap gap-1">
                            <button type="button" onclick="copyPlaceholder('resolution_date')" class="px-2 py-1 text-xs bg-orange-100 text-orange-700 rounded hover:bg-orange-200" title="Click to copy">resolution_date</button>
                            <button type="button" onclick="copyPlaceholder('meeting_date')" class="px-2 py-1 text-xs bg-orange-100 text-orange-700 rounded hover:bg-orange-200" title="Click to copy">meeting_date</button>
                            <button type="button" onclick="copyPlaceholder('effective_date')" class="px-2 py-1 text-xs bg-orange-100 text-orange-700 rounded hover:bg-orange-200" title="Click to copy">effective_date</button>
                            <button type="button" onclick="copyPlaceholder('description')" class="px-2 py-1 text-xs bg-orange-100 text-orange-700 rounded hover:bg-orange-200" title="Click to copy">description</button>
                        </div>
                    </div>
                </div>

                <div class="mt-3 p-2 bg-gray-50 rounded text-xs text-gray-600">
                    <strong>Tip:</strong> Create custom placeholders by using the format <code class="bg-gray-200 px-1 rounded">@{{your_placeholder_name}}</code> in your HTML.
                    Use underscores for multi-word names (e.g., <code class="bg-gray-200 px-1 rounded">@{{new_director_name}}</code>).
                </div>
            </div>
        </div>

        {{-- HTML & CSS Editor --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
            {{-- HTML Editor --}}
            <div class="card overflow-hidden">
                <div class="card-header bg-gray-800 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 17.56l-7.02 4.04 1.78-7.92L.5 8.24l8.02-.68L12 .5l3.48 7.06 8.02.68-6.26 5.44 1.78 7.92z"/>
                            </svg>
                            <span class="font-semibold">HTML Content</span>
                        </div>
                        <span class="text-xs text-gray-400">Use @{{placeholder}} for dynamic content</span>
                    </div>
                </div>
                <div class="p-0">
                    <textarea
                        id="htmlEditor"
                        wire:model.live="htmlContent"
                        class="w-full h-96 p-4 font-mono text-sm border-0 focus:outline-none focus:ring-0 resize-none"
                        style="background-color: #1e1e1e; color: #d4d4d4;"
                        placeholder="<!-- Sample Template - Add director_signature_1, _2, _3... for multiple signatures -->"
                    ></textarea>
                </div>
            </div>

            {{-- CSS Editor --}}
            <div class="card overflow-hidden">
                <div class="card-header bg-gray-800 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 17.56l-7.02 4.04 1.78-7.92L.5 8.24l8.02-.68L12 .5l3.48 7.06 8.02.68-6.26 5.44 1.78 7.92z"/>
                            </svg>
                            <span class="font-semibold">CSS Styles</span>
                        </div>
                        <span class="text-xs text-gray-400">Optional styling</span>
                    </div>
                </div>
                <div class="p-0">
                    <textarea
                        wire:model.live="cssContent"
                        class="w-full h-96 p-4 font-mono text-sm border-0 focus:outline-none focus:ring-0 resize-none"
                        style="background-color: #1e1e1e; color: #d4d4d4;"
                        placeholder="/* Sample CSS Styles */"
                    ></textarea>
                </div>
            </div>
        </div>

        {{-- Live Preview --}}
        <div class="card overflow-hidden">
            <div class="card-header bg-gray-50">
                <div class="flex items-center justify-between">
                    <h5 class="card-title flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Live Preview
                    </h5>
                    <span class="text-xs text-gray-500">Placeholders will show as @{{name}} - they'll be replaced with real data</span>
                </div>
            </div>
            <div class="card-body p-6 bg-white min-h-64 border-2 border-dashed border-gray-200">
                @if($htmlContent)
                    @if($cssContent)
                        <style>{!! $cssContent !!}</style>
                    @endif
                    <div class="preview-content">
                        {!! $htmlContent !!}
                    </div>
                @else
                    <div class="text-center text-gray-400 py-16">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p>Start typing HTML to see the preview</p>
                        <p class="text-sm mt-2">Use the placeholder buttons above to quickly insert dynamic fields</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Copy Notification Toast -->
    <div id="copyToast" class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg transform translate-y-20 opacity-0 transition-all duration-300 z-50">
        Copied to clipboard!
    </div>

    <script>
        function copyPlaceholder(name) {
            const placeholder = `\{\{${name}\}\}`;
            navigator.clipboard.writeText(placeholder).then(() => {
                // Show toast
                const toast = document.getElementById('copyToast');
                toast.textContent = 'Copied: ' + placeholder;
                toast.classList.remove('translate-y-20', 'opacity-0');

                // Hide after 2 seconds
                setTimeout(() => {
                    toast.classList.add('translate-y-20', 'opacity-0');
                }, 2000);
            });
        }
    </script>
</div>
