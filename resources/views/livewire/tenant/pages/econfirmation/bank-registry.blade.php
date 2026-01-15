<div class="grid grid-cols-1 gap-6">
    <!-- Header -->
    <div class="card">
        <div class="card-header flex justify-between items-center">
            <h4 class="card-title">Bank Registry</h4>
            <button wire:click="openBankModal()" class="btn bg-primary text-white">
                <i class="mgc_add_line mr-1"></i> Add Bank
            </button>
        </div>
        <div class="p-6">
            <div class="mb-4">
                <input type="text" wire:model.live.debounce.300ms="search" class="form-input max-w-md" placeholder="Search banks...">
            </div>
        </div>
    </div>

    <!-- Banks List -->
    <div class="card">
        <div class="p-6">
            @if($banks->isEmpty())
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    <i class="mgc_bank_line text-4xl mb-2"></i>
                    <p>No banks found. Click "Add Bank" to create one.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($banks as $bank)
                        <div class="border dark:border-gray-700 rounded-lg overflow-hidden">
                            <!-- Bank Header -->
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 cursor-pointer" wire:click="toggleBankExpand({{ $bank->id }})">
                                <div class="flex items-center gap-3">
                                    <i class="mgc_down_line transition-transform {{ in_array($bank->id, $expandedBanks) ? 'rotate-180' : '' }}"></i>
                                    <div>
                                        <h5 class="font-medium text-gray-900 dark:text-white">{{ $bank->bank_name }}</h5>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $bank->bank_code }}</span>
                                    </div>
                                    @if(!$bank->is_active)
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Inactive</span>
                                    @endif
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                        {{ $bank->branches->count() }} {{ Str::plural('branch', $bank->branches->count()) }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2" wire:click.stop>
                                    <button wire:click="openBranchModal({{ $bank->id }})" class="btn btn-sm bg-success text-white" title="Add Branch">
                                        <i class="mgc_add_line"></i>
                                    </button>
                                    <button wire:click="openBankModal({{ $bank->id }})" class="btn btn-sm bg-info text-white" title="Edit Bank">
                                        <i class="mgc_edit_line"></i>
                                    </button>
                                    <button wire:click="toggleBankStatus({{ $bank->id }})" class="btn btn-sm {{ $bank->is_active ? 'bg-warning' : 'bg-success' }} text-white" title="{{ $bank->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="mgc_{{ $bank->is_active ? 'eye_close_line' : 'eye_line' }}"></i>
                                    </button>
                                    <button wire:click="deleteBank({{ $bank->id }})" wire:confirm="Are you sure you want to delete this bank and all its branches?" class="btn btn-sm bg-danger text-white" title="Delete">
                                        <i class="mgc_delete_line"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Branches List -->
                            @if(in_array($bank->id, $expandedBanks))
                                <div class="border-t dark:border-gray-700">
                                    @if($bank->branches->isEmpty())
                                        <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                                            <p class="text-sm">No branches yet. Click the + button to add one.</p>
                                        </div>
                                    @else
                                        <table class="min-w-full">
                                            <thead class="bg-gray-100 dark:bg-gray-700">
                                                <tr>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Branch Name</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Address</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                                @foreach($bank->branches as $branch)
                                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                                            {{ $branch->branch_name ?: 'Main Branch' }}
                                                        </td>
                                                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                                            {{ $branch->full_address ?: '-' }}
                                                        </td>
                                                        <td class="px-4 py-3">
                                                            @if($branch->is_active)
                                                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Active</span>
                                                            @else
                                                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Inactive</span>
                                                            @endif
                                                        </td>
                                                        <td class="px-4 py-3 text-right">
                                                            <div class="flex items-center justify-end gap-2">
                                                                <button wire:click="openBranchModal({{ $bank->id }}, {{ $branch->id }})" class="btn btn-sm bg-info text-white" title="Edit">
                                                                    <i class="mgc_edit_line"></i>
                                                                </button>
                                                                <button wire:click="toggleBranchStatus({{ $branch->id }})" class="btn btn-sm {{ $branch->is_active ? 'bg-warning' : 'bg-success' }} text-white" title="{{ $branch->is_active ? 'Deactivate' : 'Activate' }}">
                                                                    <i class="mgc_{{ $branch->is_active ? 'eye_close_line' : 'eye_line' }}"></i>
                                                                </button>
                                                                <button wire:click="deleteBranch({{ $branch->id }})" wire:confirm="Are you sure you want to delete this branch?" class="btn btn-sm bg-danger text-white" title="Delete">
                                                                    <i class="mgc_delete_line"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $banks->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Bank Modal -->
    @if($showBankModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 py-6">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeBankModal"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl w-80 p-5 z-10">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                        {{ $editingBankId ? 'Edit Bank' : 'Add New Bank' }}
                    </h3>
                    <form wire:submit.prevent="saveBank">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bank Name *</label>
                                <input type="text" wire:model.live="bankName" class="form-input w-full" placeholder="e.g., Maybank">
                                @error('bankName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bank Code *</label>
                                <input type="text" wire:model="bankCode" class="form-input w-full" placeholder="e.g., maybank">
                                <p class="text-xs text-gray-500 mt-1">Unique identifier (lowercase, no spaces)</p>
                                @error('bankCode') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" wire:model="bankIsActive" id="bankIsActive" class="form-checkbox">
                                <label for="bankIsActive" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</label>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" wire:click="closeBankModal" class="btn bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">Cancel</button>
                            <button type="submit" class="btn bg-primary text-white">Save Bank</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Branch Modal -->
    @if($showBranchModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 py-6">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeBranchModal"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl w-96 p-5 z-10 max-h-[85vh] overflow-y-auto">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                        {{ $editingBranchId ? 'Edit Branch' : 'Add New Branch' }}
                    </h3>
                    <form wire:submit.prevent="saveBranch">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Branch Name</label>
                                <input type="text" wire:model="branchName" class="form-input w-full" placeholder="e.g., Kuala Lumpur Main Branch">
                                <p class="text-xs text-gray-500 mt-1">Leave empty for main/headquarters branch</p>
                            </div>

                            <hr class="dark:border-gray-700">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Address</h4>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Street Address</label>
                                <input type="text" wire:model="branchStreet" class="form-input w-full" placeholder="e.g., 100 Jalan Tun Perak">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Street Address 2</label>
                                <input type="text" wire:model="branchStreet2" class="form-input w-full" placeholder="e.g., Bukit Bintang">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Street Address 3</label>
                                <input type="text" wire:model="branchStreet3" class="form-input w-full" placeholder="Optional">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">City</label>
                                    <input type="text" wire:model="branchCity" class="form-input w-full" placeholder="e.g., Kuala Lumpur">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">State</label>
                                    <input type="text" wire:model="branchState" class="form-input w-full" placeholder="e.g., Wilayah Persekutuan">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Postcode</label>
                                    <input type="text" wire:model="branchPostcode" class="form-input w-full" placeholder="e.g., 50050">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Country</label>
                                    <input type="text" wire:model="branchCountry" class="form-input w-full" placeholder="Malaysia">
                                </div>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" wire:model="branchIsActive" id="branchIsActive" class="form-checkbox">
                                <label for="branchIsActive" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</label>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" wire:click="closeBranchModal" class="btn bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">Cancel</button>
                            <button type="submit" class="btn bg-primary text-white">Save Branch</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
