<div class="grid grid-cols-1 gap-6">
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title">View / Update User</h4>
                <div class="flex items-center gap-2">
                    <a class="btn bg-warning text-white" type="button" href="{{ route('index.user') }}">Cancel</a>
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
                <p class="my-4 text-xl text-slate-700 underline dark:text-slate-400">User Detail</p>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="col-span-2">
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="email">Email</label>
                        <input class="form-input" id="email" type="email" wire:dirty.class="border-amber-500" wire:model="email" placeholder="email@example.com">
                        @error('email')
                            <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="name">Name</label>
                        <input class="form-input" id="name" type="text" wire:dirty.class="border-amber-500" wire:model="name" placeholder="Name">
                        @error('name')
                            <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="username">Username</label>
                        <input class="form-input" id="username" type="text" wire:dirty.class="border-amber-500" wire:model="username" placeholder="Username">
                        @error('username')
                            <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="phoneNumber">Contact No</label>
                        <input class="form-input" id="phoneNumber" type="text" wire:dirty.class="border-amber-500" wire:model="phoneNumber" placeholder="601X-XXXXXXXX">
                        @error('phoneNumber')
                            <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div></div>

                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="role">Internal Role</label>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach ($roles as $role)
                                @if (Str::contains($role, 'internal'))
                                    <div wire:key="{{ $role->id }}">
                                        <input class="text-blue form-checkbox rounded" id="{{ $role->name }}" type="checkbox" value="{{ $role->name }}" wire:dirty.class="border-amber-500" wire:model="role">
                                        <label class="ms-1.5" name="role" for="{{ $role->name }}">{{ Str::ucfirst(Str::substr($role->name, '9')) }}</label>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="role">Outsider Role</label>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach ($roles as $role)
                                @if (Str::contains($role, 'outsider'))
                                    <div wire:key="{{ $role->id }}">
                                        <input class="text-blue form-checkbox rounded" id="{{ $role->name }}" type="checkbox" value="{{ $role->name }}" wire:dirty.class="border-amber-500" wire:model="role">
                                        <label class="ms-1.5" name="role" for="{{ $role->name }}">{{ Str::ucfirst(Str::substr($role->name, '9')) }}</label>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="accountStatus">Account Status</label>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <input class="form-radio text-success" id="active" type="radio" value="1" wire:model="accountStatus">
                                <label class="ms-1.5" for="active">Active</label>
                            </div>
                            <div>
                                <input class="form-radio text-danger" id="deactive" type="radio" value="0" wire:model="accountStatus">
                                <label class="ms-1.5" for="deactive">Deactive</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div wire:dirty>
                    <button class="btn mt-6 bg-primary text-white" type="submit" wire:loading.attr="disabled">
                        <div class="mr-2 inline-block h-4 w-4 animate-spin rounded-full border-[3px] border-current border-t-transparent text-white" role="status" aria-label="loading" wire:loading>
                            <span class="sr-only">Loading...</span>
                        </div>
                        <span wire:loading>Updating...</span>
                        <span wire:loading.remove>Save Update</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
