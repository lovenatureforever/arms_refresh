<div class="mb-4 grid grid-cols-1 gap-6">
    <div class="">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <h3 class="card-title">Manage Cosec Templates</h3>
                    @if(auth()->user()->canManageCompanies())
                    <button wire:click="create" class="btn bg-primary text-white text-sm">
                        <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create Template
                    </button>
                    @endif
                </div>
            </div>
            <div class="card-body p-0 sm:p-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Name</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400 hidden md:table-cell">Description</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Signature</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Cost</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400 hidden sm:table-cell">Active</th>
                                <th class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($templates as $template)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-3 py-3 text-sm text-gray-800 dark:text-gray-200">
                                        <div class="font-medium">{{ $template->name }}</div>
                                        <!-- Show description on mobile -->
                                        <div class="text-xs text-gray-500 md:hidden mt-1">{{ Str::limit($template->description, 30) ?? '-' }}</div>
                                    </td>
                                    <td class="px-3 py-3 text-sm text-gray-800 dark:text-gray-200 hidden md:table-cell">
                                        <div class="max-w-xs truncate" title="{{ $template->description }}">{{ $template->description ?? '-' }}</div>
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap text-sm">
                                        @if($template->signature_type === 'sole_director')
                                            <span class="inline-flex items-center py-1 px-3 rounded text-xs font-semibold text-white" style="background-color: #22c55e;">Sole Director</span>
                                        @elseif($template->signature_type === 'two_directors')
                                            <span class="inline-flex items-center py-1 px-3 rounded text-xs font-semibold text-white" style="background-color: #3b82f6;">Two Directors</span>
                                        @elseif($template->signature_type === 'all_directors')
                                            <span class="inline-flex items-center py-1 px-3 rounded text-xs font-semibold text-white" style="background-color: #8b5cf6;">All Directors</span>
                                        @elseif($template->signature_type === 'default')
                                            <span class="inline-flex items-center py-1 px-3 rounded text-xs font-semibold text-white" style="background-color: #6b7280;">Default</span>
                                        @else
                                            <span class="inline-flex items-center py-1 px-3 rounded text-xs font-semibold text-white" style="background-color: #6b7280;">{{ ucfirst(str_replace('_', ' ', $template->signature_type ?? 'None')) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        <span class="font-medium">{{ number_format($template->credit_cost, 0) }} Credits</span>
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap text-sm hidden sm:table-cell">
                                        @if($template->is_active)
                                            <span class="inline-flex items-center py-1 px-2 rounded text-xs font-medium text-white" style="background-color: #22c55e;">Yes</span>
                                        @else
                                            <span class="inline-flex items-center py-1 px-2 rounded text-xs font-medium text-white" style="background-color: #ef4444;">No</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap text-sm text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <!-- View Button -->
                                            <button wire:click="showPreview({{ $template->id }})"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded border border-cyan-500 text-cyan-500 hover:bg-cyan-500 hover:text-white transition-colors"
                                                    title="View Template">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                            @if(auth()->user()->canManageCompanies())
                                            <!-- Edit Button -->
                                            <button wire:click="edit({{ $template->id }})"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded border border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white transition-colors"
                                                    title="Edit Template">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                            <!-- Delete Button -->
                                            <button wire:click="delete({{ $template->id }})"
                                                    wire:confirm="Are you sure you want to delete this template?"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded border border-red-500 text-red-500 hover:bg-red-500 hover:text-white transition-colors"
                                                    title="Delete Template">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-3 py-8 text-center text-gray-500">
                                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        No templates found. Click "Create Template" to add one.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal - Full Screen -->
    @if($showPreviewModal)
    <div class="fixed inset-0 bg-black bg-opacity-75 z-50 flex flex-col">
        <!-- Header -->
        <div class="flex justify-between items-center px-4 py-3 bg-gray-900 text-white">
            <h3 class="text-lg font-semibold">Template Preview</h3>
            <button wire:click="closePreview" class="p-2 hover:bg-gray-700 rounded-lg transition-colors" title="Close (Esc)">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <!-- Content -->
        <div class="flex-1 relative bg-white overflow-hidden">
            <div wire:loading wire:target="showPreview" class="absolute inset-0 flex items-center justify-center bg-white z-10">
                <div class="animate-spin rounded-full h-16 w-16 border-b-4 border-blue-600"></div>
            </div>
            @if($previewTemplateId)
            <iframe src="{{ route('admin.cosec.template.preview', $previewTemplateId) }}"
                    class="w-full h-full border-0">
            </iframe>
            @endif
        </div>
    </div>
    @endif
</div>
