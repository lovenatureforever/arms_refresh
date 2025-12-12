<div>
    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Director Form -->
        <div class="lg:col-span-1">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">{{ $editingId ? 'Edit Director' : 'Add New Director' }}</h6>
                </div>
                <div class="p-6">
                    <form wire:submit="save">
                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" wire:model.live="name"
                                class="form-input w-full @error('name') border-red-500 @enderror"
                                placeholder="Enter director's full name">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Designation -->
                        <div class="mb-4">
                            <label for="designation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Designation <span class="text-red-500">*</span>
                            </label>
                            <select id="designation" wire:model.live="designation"
                                class="form-select w-full @error('designation') border-red-500 @enderror">
                                @foreach($designations as $des)
                                    <option value="{{ $des }}">{{ $des }}</option>
                                @endforeach
                            </select>
                            @error('designation')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- ID Type -->
                        <div class="mb-4">
                            <label for="idType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                ID Type <span class="text-red-500">*</span>
                            </label>
                            <select id="idType" wire:model.live="idType"
                                class="form-select w-full @error('idType') border-red-500 @enderror">
                                @foreach($idTypes as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                            @error('idType')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- ID Number -->
                        <div class="mb-4">
                            <label for="idNo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                ID Number <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="idNo" wire:model.live="idNo"
                                class="form-input w-full @error('idNo') border-red-500 @enderror"
                                placeholder="Enter ID number">
                            @error('idNo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div class="mb-4">
                            <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Gender
                            </label>
                            <select id="gender" wire:model.live="gender" class="form-select w-full">
                                <option value="">-- Select Gender --</option>
                                <option value="1">Male</option>
                                <option value="0">Female</option>
                            </select>
                        </div>

                        <!-- User Account Section -->
                        <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                            <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-300 mb-3">User Account (for login)</h4>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" id="email" wire:model.live="email"
                                    class="form-input w-full @error('email') border-red-500 @enderror"
                                    placeholder="director@example.com">
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" id="password" wire:model.live="password"
                                    class="form-input w-full @error('password') border-red-500 @enderror"
                                    placeholder="Enter password (min 6 characters)">
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Status Checkboxes -->
                        <div class="mb-4 space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" wire:model.live="isActive" class="form-checkbox">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model.live="isDefaultSignerCosec" class="form-checkbox">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Default COSEC Signer</span>
                            </label>
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-2">
                            <button type="submit" class="btn bg-primary text-white flex-1">
                                <span wire:loading.remove wire:target="save">
                                    {{ $editingId ? 'Update Director' : 'Add Director' }}
                                </span>
                                <span wire:loading wire:target="save">
                                    Saving...
                                </span>
                            </button>
                            @if($editingId)
                                <button type="button" wire:click="cancelEdit" class="btn bg-gray-500 text-white">
                                    Cancel
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Directors List -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header flex justify-between items-center">
                    <h6 class="card-title">Directors for {{ $company->name }}</h6>
                    <span class="text-sm text-gray-500">{{ count($directors) }} director(s)</span>
                </div>
                <div class="p-6">
                    @if(count($directors) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Designation</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($directors as $director)
                                        <tr class="{{ $director['is_active'] ? '' : 'bg-gray-100 dark:bg-gray-800' }}">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $director['name'] }}
                                                </div>
                                                @if($director['is_default_signer_cosec'])
                                                    <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        Default Signer
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $director['designation'] }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                <span class="text-xs text-gray-400">{{ $director['id_type'] }}:</span>
                                                {{ $director['id_no'] }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                @if($director['is_active'])
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        Inactive
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                <div class="flex gap-2">
                                                    <button wire:click="edit({{ $director['id'] }})"
                                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                        Edit
                                                    </button>
                                                    <button wire:click="toggleActive({{ $director['id'] }})"
                                                        class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                                        {{ $director['is_active'] ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                    <button wire:click="delete({{ $director['id'] }})"
                                                        wire:confirm="Are you sure you want to delete this director?"
                                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                        Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No directors</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by adding a new director.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Back to Company -->
            <div class="mt-4">
                <a href="{{ route('companies.show', ['id' => $companyId]) }}" class="btn bg-gray-500 text-white">
                    Back to Company
                </a>
            </div>
        </div>
    </div>
</div>
