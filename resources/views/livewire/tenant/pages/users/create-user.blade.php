<div class="grid grid-cols-1 gap-6">
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title">Create User</h4>
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

            <form wire:submit="create">

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

                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="password">Password</label>
                        <input class="form-input" id="password" type="password" wire:model="password" placeholder="Password">
                        @error('password')
                            <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="confirmPassword">Confirm Password</label>
                        <input class="form-input" id="confirmPassword" type="password" wire:model="password_confirmation" placeholder="Re-type Password">
                        @error('password_confirmation')
                            <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="flex flex-col gap-6">
                    <div class="mt-9">
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
</div>
