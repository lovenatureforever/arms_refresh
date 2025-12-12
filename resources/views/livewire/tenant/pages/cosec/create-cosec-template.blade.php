<div class="min-h-screen bg-gray-100">
    <!-- Header Bar -->
    <div class="bg-white shadow-sm border-b sticky top-0 z-10">
        <div class="px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.cosec.templates') }}" class="text-gray-600 hover:text-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-xl font-semibold text-gray-800">
                    {{ $isEditing ? 'Edit Template' : 'Create New Template' }}
                </h1>
            </div>
            <div class="flex items-center gap-2">
                <button wire:click="cancel" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                    Cancel
                </button>
                <button wire:click="save" class="px-4 py-2 text-white rounded hover:opacity-90" style="background-color: #22c55e;">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ $isEditing ? 'Update Template' : 'Save Template' }}
                </button>
            </div>
        </div>
    </div>

    <div class="p-4">
        <!-- Template Settings -->
        <div class="bg-white rounded-lg shadow p-4 mb-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Template Settings</h3>
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
                    <input type="number" wire:model="credit_cost" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" min="0" step="0.01">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Signature Type</label>
                    <select wire:model="signature_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="default">Default Signer</option>
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

        <!-- HTML & CSS Editor -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <!-- HTML Editor -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-4 py-3 border-b bg-gray-800 text-white flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 17.56l-7.02 4.04 1.78-7.92L.5 8.24l8.02-.68L12 .5l3.48 7.06 8.02.68-6.26 5.44 1.78 7.92z"/>
                        </svg>
                        <span class="font-semibold">HTML</span>
                    </div>
                    <span class="text-xs text-gray-400">Write your HTML template code</span>
                </div>
                <div class="p-0">
                    <textarea
                        wire:model.live="htmlContent"
                        class="w-full h-96 p-4 font-mono text-sm border-0 focus:outline-none focus:ring-0 resize-none"
                        style="background-color: #1e1e1e; color: #d4d4d4;"
                        placeholder="<!DOCTYPE html>
<html>
<head>
    <title>Template</title>
</head>
<body>
    <!-- Your HTML content here -->
    <h1>Template Title</h1>
    <p>Template content...</p>
</body>
</html>"
                    ></textarea>
                </div>
            </div>

            <!-- CSS Editor -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-4 py-3 border-b bg-gray-800 text-white flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 17.56l-7.02 4.04 1.78-7.92L.5 8.24l8.02-.68L12 .5l3.48 7.06 8.02.68-6.26 5.44 1.78 7.92z"/>
                        </svg>
                        <span class="font-semibold">CSS</span>
                    </div>
                    <span class="text-xs text-gray-400">Add custom styles (optional)</span>
                </div>
                <div class="p-0">
                    <textarea
                        wire:model.live="cssContent"
                        class="w-full h-96 p-4 font-mono text-sm border-0 focus:outline-none focus:ring-0 resize-none"
                        style="background-color: #1e1e1e; color: #d4d4d4;"
                        placeholder="/* Your CSS styles here */

body {
    font-family: Arial, sans-serif;
    margin: 20px;
}

h1 {
    color: #333;
    font-size: 24px;
}

p {
    line-height: 1.6;
}"
                    ></textarea>
                </div>
            </div>
        </div>

        <!-- Live Preview -->
        <div class="bg-white rounded-lg shadow overflow-hidden mt-4">
            <div class="px-4 py-3 border-b bg-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <span class="font-semibold text-gray-800">Live Preview</span>
                </div>
            </div>
            <div class="p-4 bg-white min-h-64 border-2 border-dashed border-gray-200">
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
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
