<div class="mb-4 grid grid-cols-1 gap-6">
    <div class="">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h3 class="card-title">Manage Cosec Templates</h3>
                </div>
            </div>
            <div class="card-body">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Form Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Template File</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Signature Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Credit Cost</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Active</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($templates as $template)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $template->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $template->form_type }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                    @if($template->template_file)
                                        <a href="{{ asset($template->template_file) }}" class="text-blue-600 hover:underline" download>Download</a>
                                    @else
                                        <span class="text-gray-400">No file</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                    @if($template->signature_type === 'all_directors')
                                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">All Directors</span>
                                    @else
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Default Signer</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $template->credit_cost }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                    @if($template->is_active)
                                        <span class="text-green-600">Yes</span>
                                    @else
                                        <span class="text-red-600">No</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                    <button wire:click="edit({{ $template->id }})" class="btn border-primary text-primary hover:bg-primary hover:text-white mr-2">Edit</button>
                                    @if($template->template_file)
                                        <button wire:click="showPreview({{ $template->id }})" class="btn bg-info text-white hover:bg-blue-700 mr-2">
                                            Preview
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($editingId)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Template</h3>
        </div>
        <div class="card-body">
            <div class="m-4">
                <label class="mb-2 inline-block text-sm font-medium text-gray-800">Name</label>
                <input type="text" class="form-input" wire:model.live="name">
                @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="m-4">
                <label class="mb-2 inline-block text-sm font-medium text-gray-800">Template File (DOCX)</label>
                <input type="file" class="form-input" wire:model="templateFile" accept=".docx">
                <div wire:loading wire:target="templateFile">Uploading...</div>
                @error('templateFile') <span class="text-red-500">{{ $message }}</span> @enderror
                <small class="text-gray-600">Upload a DOCX template with placeholders.</small>
            </div>

            <div class="m-4">
                <label class="mb-2 inline-block text-sm font-medium text-gray-800">Credit Cost</label>
                <input type="number" class="form-input" wire:model.live="credit_cost" min="0">
                @error('credit_cost') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="m-4">
                <label class="mb-2 inline-block text-sm font-medium text-gray-800">Signature Type</label>
                <select class="form-input" wire:model.live="signature_type">
                    <option value="default">Default Signer</option>
                    <option value="all_directors">All Directors</option>
                </select>
                @error('signature_type') <span class="text-red-500">{{ $message }}</span> @enderror
                <small class="text-gray-600">Choose who should sign this template</small>
            </div>

            <div class="m-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model.live="is_active" class="form-checkbox">
                    <span class="ml-2 text-sm font-medium text-gray-800">Active</span>
                </label>
            </div>

            <div class="flex gap-2 m-4">
                <button wire:click="save" class="btn bg-success text-white" id="ckeditor-save">Save</button>
                <button wire:click="cancel" class="btn bg-gray-500 text-white">Cancel</button>
            </div>

            @php
                $template = $templates->find($editingId);
            @endphp
            @if($template && $template->template_file)
            <div class="m-4">
                <h4 class="text-lg font-medium text-gray-800 mb-2">Template Preview</h4>
                <iframe src="{{ route('admin.cosec.template.preview', $editingId) }}"
                        width="100%"
                        height="600"
                        style="border: 1px solid #ddd;">
                </iframe>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Preview Modal -->
    @if($showPreviewModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-8" wire:click="closePreview">
        <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl h-4/5 flex flex-col p-6" wire:click.stop style="aspect-ratio: 3/4;">
            <div class="flex justify-between items-center pb-4 border-b mb-4">
                <h3 class="text-xl font-semibold text-gray-900">Template Preview</h3>
                <button wire:click="closePreview" class="text-gray-500 hover:text-gray-700 p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="flex-1 relative -m-6 mt-0 rounded-b-lg overflow-hidden">
                <div wire:loading wire:target="showPreview" class="absolute inset-0 flex items-center justify-center bg-white z-10">
                    <div class="animate-spin rounded-full h-16 w-16 border-b-4 border-blue-600"></div>
                </div>
                @if($previewTemplateId)
                <iframe src="{{ route('admin.cosec.template.preview', $previewTemplateId) }}"
                        class="w-full h-full rounded-b-lg">
                </iframe>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
