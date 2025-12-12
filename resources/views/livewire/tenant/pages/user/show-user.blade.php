<div class="grid grid-cols-1 gap-6">
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title">View / Update User</h4>
                <div class="flex items-center gap-2">
                    <a class="btn bg-warning text-white" type="button" href="{{ route('users.index') }}">Cancel</a>
                </div>
            </div>
        </div>

        <div class="p-6">
            @if (session()->has('error'))
                <div class="mb-4 rounded-md bg-danger/25 p-4 text-sm text-danger" role="alert">
                    <span class="font-bold">{{ session('error') }}</span>
                </div>
            @endif

            <form wire:submit="update">

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="name">Full Name</label>
                        <input class="form-input" id="name" type="text" wire:model="name" placeholder="Name">
                        @error('name')
                            <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="email">Email</label>
                        <input class="form-input" id="email" type="email" wire:model="email" placeholder="Email">
                        @error('email')
                            <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="username">Username</label>
                        <input class="form-input" id="username" type="text" wire:model="username" placeholder="Username">

                    </div>
                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="phoneNumber">Phone Number</label>
                        <input class="form-input" id="phoneNumber" type="text" wire:model="phoneNumber" placeholder="Name">
                        @error('phoneNumber')
                            <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
                <div class="flex flex-col gap-6">
                    {{-- <div class="mt-9">
                        <label class="mb-2 inline-block text-sm font-medium text-gray-600" for="role">Internal Role</label>
                        <div class="flex flex-row flex-wrap gap-5">
                            @foreach ($roles as $role)
                                @if (Str::contains($role, 'internal'))
                                    <div wire:key="{{ $role->id }}">
                                        <input class="text-blue form-checkbox rounded" id="{{ $role->name }}" type="checkbox" value="{{ $role->name }}" wire:model.live="internal_roles">
                                        <label class="ms-1.5" name="role" for="{{ $role->name }}">{{ Str::substr(\App\Models\User::USER_ROLES[$role->name], 9) }}</label>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div> --}}
                    <div class="mt-9">
                        <label class="mb-2 inline-block text-sm font-medium text-gray-600" for="role">Internal Role</label>
                        <div class="flex flex-row flex-wrap gap-5">
                            <div wire:key="1">
                                <input class="text-blue form-checkbox rounded" id="internal_admin" type="checkbox" value="internal_admin" wire:model.live="internal_roles">
                                <label class="ms-1.5" name="role" for="internal_admin">Admin</label>
                            </div>
                            <div wire:key="2">
                                <input class="text-blue form-checkbox rounded" id="internal_reviewer" type="checkbox" value="internal_reviewer" wire:model.live="internal_roles">
                                <label class="ms-1.5" name="role" for="internal_reviewer">Reviewer</label>
                            </div>
                            <div wire:key="3">
                                <input class="text-blue form-checkbox rounded" id="internal_executor" type="checkbox" value="internal_executor" wire:model.live="internal_roles">
                                <label class="ms-1.5" name="role" for="internal_executor">Executor</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-9">
                        <label class="mb-2 inline-block text-sm font-medium text-gray-600" for="role">Outsider Role</label>
                        <div class="flex flex-row flex-wrap gap-5">
                            @foreach ($roles as $role)
                                @if (Str::contains($role, 'outsider'))
                                    <div wire:key="{{ $role->id }}">
                                        <input class="text-blue form-radio rounded" id="{{ $role->name }}" name="outsider_role" type="radio" value="{{ $role->name }}" wire:model.live="outsider_role">
                                        <label class="ms-1.5" name="role" for="{{ $role->name }}">{{ Str::substr(\App\Models\User::USER_ROLES[$role->name], 9) }}</label>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-9">
                        <label class="mb-2 inline-block text-sm font-medium text-gray-600" for="role">ISQM Role</label>
                        <div class="flex flex-row flex-wrap gap-5">
                            @foreach ($roles as $role)
                                @if (Str::contains($role, 'isqm'))
                                    <div wire:key="{{ $role->id }}">
                                        <input class="text-blue form-checkbox rounded" id="{{ $role->name }}" type="checkbox" value="{{ $role->name }}" wire:model="isqm_roles">
                                        <label class="ms-1.5" name="role" for="{{ $role->name }}">{{ Str::substr(\App\Models\User::USER_ROLES[$role->name], 5) }}</label>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-9">
                        <label class="mb-2 inline-block text-sm font-medium text-gray-600" for="isActive">Account Status</label>
                        <div class="flex flex-row flex-wrap gap-5">
                            <div>
                                <input class="form-radio text-success" id="active" type="radio" value="1" wire:model="isActive">
                                <label class="ms-1.5" for="active">Active</label>
                            </div>
                            <div>
                                <input class="form-radio text-danger" id="deactive" type="radio" value="0" wire:model="isActive">
                                <label class="ms-1.5" for="deactive">Deactive</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-9">
                    <button class="btn mt-6 bg-primary text-white" type="submit" wire:loading.attr="disabled">
                        <div class="mr-2 inline-block h-4 w-4 animate-spin rounded-full border-[3px] border-current border-t-transparent text-white" role="status" aria-label="loading" wire:loading>
                            <span class="sr-only">Loading...</span>
                        </div>
                        <span wire:loading>Saving...</span>
                        <span wire:loading.remove>Submit</span>
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- Signature Management Section (Directors Only) --}}
    @if($user->user_type === 'director')
    <div class="card mt-6">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title">Director Signature Management</h4>
                @if($director)
                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">Linked to: {{ $director->name }}</span>
                @else
                    <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded">No director profile linked</span>
                @endif
            </div>
        </div>

        <div class="p-6">
            @if($director)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Current Signature --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Current Signature</label>
                    @if($currentSignature)
                        <div class="border rounded-lg p-4 bg-gray-50 text-center">
                            <img src="/tenancy/assets/{{ $currentSignature->signature_path }}" alt="Director Signature" class="max-h-20 mx-auto">
                        </div>
                        <button wire:click="deleteSignature" wire:confirm="Are you sure you want to delete this signature?" class="btn bg-red-500 text-white text-sm mt-4 w-full">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete Signature
                        </button>
                    @else
                        <div class="border rounded-lg p-6 bg-gray-50 text-center">
                            <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                            <p class="text-sm text-gray-500">No signature uploaded</p>
                        </div>
                    @endif
                </div>

                {{-- Upload Signature --}}
                <div>
                    <form wire:submit="uploadSignature">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $currentSignature ? 'Update Signature' : 'Upload Signature' }}</label>
                            <input type="file" wire:model="signatureFile" accept="image/png,image/jpeg,.png,.jpg,.jpeg" class="form-input w-full text-sm @error('signatureFile') border-red-500 bg-red-50 @enderror">
                            <p class="text-xs text-gray-500 mt-2">PNG or JPG, max 2MB</p>

                            {{-- Loading indicator --}}
                            <div wire:loading wire:target="signatureFile" class="mt-2">
                                <div class="flex items-center text-blue-600 text-sm">
                                    <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Uploading file...
                                </div>
                            </div>

                            @error('signatureFile')
                                <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded-lg">
                                    <p class="text-red-600 text-sm font-medium flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                    <p class="text-red-500 text-xs mt-1">Please select a PNG or JPG image file only.</p>
                                </div>
                            @enderror
                        </div>

                        @if($signatureFile)
                        <div class="mt-4" wire:loading.remove wire:target="signatureFile">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Preview</label>
                            <div class="border rounded-lg p-4 bg-gray-50 text-center">
                                <img src="{{ $signatureFile->temporaryUrl() }}" alt="Preview" class="max-h-16 mx-auto">
                            </div>
                        </div>
                        @endif

                        <button type="submit" class="btn bg-primary text-white w-full mt-4" wire:loading.attr="disabled" wire:target="uploadSignature" @if(!$signatureFile) disabled @endif>
                            <span wire:loading wire:target="uploadSignature" class="flex items-center justify-center">
                                <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Saving...
                            </span>
                            <span wire:loading.remove wire:target="uploadSignature">
                                @if($currentSignature)
                                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Update Signature
                                @else
                                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    Upload Signature
                                @endif
                            </span>
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div class="text-center py-6">
                <svg class="w-12 h-12 mx-auto text-yellow-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <p class="text-gray-600">This director user is not linked to a company director profile.</p>
                <p class="text-sm text-gray-500 mt-2">Please link this user to a director in the company settings first.</p>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
