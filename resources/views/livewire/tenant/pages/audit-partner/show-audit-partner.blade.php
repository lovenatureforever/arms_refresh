<div class="grid grid-cols-1 gap-6">
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title">Update Audit Partner</h4>
                <div class="flex items-center gap-2">
                    <a class="btn bg-warning text-white" type="button" href="{{ route('auditpartners.index') }}">Cancel</a>
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
                <p class="my-4 text-xl text-slate-700 underline dark:text-slate-400">Audit Partner Detail</p>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <h2 class="col-span-2">A. Auditor Info</h2>
                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="name">Auditor Name</label>
                        <input class="form-input" id="name" type="text" wire:model="name">
                        @error('name')
                            <div class="pristine-error text-help" id="name" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="auditorLicense">Auditor No</label>
                        <input class="form-input" id="auditorLicense" type="text" wire:model="auditorLicense" disabled>
                    </div>
                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="title">Auditor Title</label>
                        <input class="form-input" id="title" type="text" wire:model="title">
                        @error('title')
                            <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div></div>

                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="isActive">Auditor Status</label>
                        <div class="grid grid-cols-3 gap-3">
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
                    <h2 class="mt-9 col-span-2 flex align-middle items-center">
                        B. Auditor License Number
                        <div class="ms-5">
                            <button class="btn bg-primary text-white" wire:click.prevent="addLicense({{ $i }})">
                                <i class="mgc_add_fill"></i>
                            </button>
                        </div>
                    </h2>

                    <div class="col-span-2">
                        {{-- <input type="hidden" wire:model="ids.0">
                        <div class="grid grid-cols-4 items-end gap-4" wire:key="license-0">
                            <div>
                                <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="licenseNumber">License Number</label>
                                <input class="form-input" id="licenseNumber" type="text" wire:model="licenseNumbers.0">
                            </div>
                            <div>
                                <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="effectiveDate">Effective Date</label>
                                <input class="form-input" id="effectiveDate" type="date" wire:model="effectiveDates.0">
                            </div>
                            <div>
                                <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="expiredDate">Expiry Date</label>
                                <input class="form-input" id="expiredDate" type="date" wire:model="expiryDates.0">
                            </div>
                            <div>
                                <button class="btn bg-primary text-white" wire:click.prevent="addLicense({{ $i }})">+</button>
                            </div>
                        </div> --}}

                        @foreach ($inputs as $key => $value)
                            <input type="hidden" wire:model="ids.{{ $value }}">
                            <div class="mt-4 grid grid-cols-4 items-end gap-4" wire:key="license-{{ $key }}">
                                <div>
                                    <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="licenseNumber">License Number</label>
                                    <input class="form-input" id="licenseNumber" type="text" wire:model="licenseNumbers.{{ $value }}">
                                </div>
                                <div>
                                    <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="effectiveDate">Effective Date</label>
                                    <input class="form-input" id="effectiveDate" type="date" wire:model="effectiveDates.{{ $value }}">
                                </div>
                                <div>
                                    <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="expiredDate">Expiry Date</label>
                                    <input class="form-input" id="expiredDate" type="date" wire:model="expiryDates.{{ $value }}">
                                </div>
                                <div>
                                    <button class="btn bg-danger text-white" wire:click.prevent="removeLicense({{ $key }})">-</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <h2 class="col-span-2">C. User Profile</h2>
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
                        @error('username')
                            <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="phoneNumber">Contact No</label>
                        <input class="form-input" id="phoneNumber" type="text" wire:model="phoneNumber" placeholder="Phone Number">
                        @error('phoneNumber')
                            <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div></div>

                </div>

                <div class="my-9">
                    <h2 class="col-span-2">D. Role</h2>
                    <div class="mt-9">
                        <label class="mb-2 inline-block text-sm font-medium text-gray-600" for="role">Internal Role</label>
                        <div class="flex flex-row flex-wrap gap-5">
                            @foreach ($roles as $role)
                                @if (Str::contains($role, 'internal'))
                                    <div wire:key="{{ $role->id }}" class="{{ Str::contains($role, '2nd_reviewer') ? 'opacity-75' : 'opacity-100' }}">
                                        <input class="text-blue form-checkbox rounded" id="{{ $role->name }}" type="checkbox" value="{{ $role->name }}" wire:model="internal_roles" {{ Str::contains($role, '2nd_reviewer') ? 'disabled' : '' }}>
                                        <label class="ms-1.5" name="role" for="{{ $role->name }}">{{ Str::substr($role->name, '9') }}</label>
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
                </div>

                <div>
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
