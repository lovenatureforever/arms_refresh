<div class="grid grid-cols-1 gap-6">
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title">Edit Company</h4>
                <div class="flex items-center gap-2">
                    <a class="btn bg-warning text-white" type="button" href="{{ route('companies.show', $companyId) }}">Cancel</a>
                </div>
            </div>
        </div>

        <div class="p-6">
            @if (session()->has('success'))
            <div class="mb-4 rounded-md bg-success/25 p-4 text-sm text-success" role="alert">
                <span class="font-bold">{{ session('success') }}</span>
            </div>
            @endif

            @if (session()->has('error'))
            <div class="mb-4 rounded-md bg-danger/25 p-4 text-sm text-danger" role="alert">
                <span class="font-bold">{{ session('error') }}</span>
            </div>
            @endif

            <form wire:submit="update">
                <p class="my-4 text-xl text-slate-700 underline dark:text-slate-400">Company Detail</p>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="col-span-2">
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="companyName">Company Name</label>
                        <input class="form-input" id="companyName" type="text" wire:model="name">
                        @error('name')
                        <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-span-2">
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="companyGroup">Company Group</label>
                        <input class="form-input" id="companyGroup" type="text" wire:model="companyGroup">
                        @error('companyGroup')
                        <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-span-2">
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="registrationNo">Company Registration No</label>
                        <input class="form-input" id="registrationNo" type="text" wire:model="registrationNo">
                        @error('registrationNo')
                        <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-span-2">
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="registrationNoOld">Company Registration No (old)</label>
                        <input class="form-input" id="registrationNoOld" type="text" wire:model="registrationNoOld">
                        @error('registrationNoOld')
                        <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="taxFileNo">Tax File No. C</label>
                        <input class="form-input" id="taxFileNo" type="text" wire:model="taxFileNo">
                        @error('taxFileNo')
                        <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="employerFileNo">Employer File No. E</label>
                        <input class="form-input" id="employerFileNo" type="text" wire:model="employerFileNo">
                        @error('employerFileNo')
                        <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="sstRegistrationNo">SST Registration No.</label>
                        <input class="form-input" id="sstRegistrationNo" type="text" wire:model="sstRegistrationNo">
                        @error('sstRegistrationNo')
                        <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="yearEnd">Year End</label>
                        <input class="form-input" id="yearEnd" type="text" wire:model="yearEnd">
                        @error('yearEnd')
                        <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div>
                    <button class="btn mt-6 bg-primary text-white" type="submit" wire:loading.attr="disabled">
                        <div class="mr-2 inline-block h-4 w-4 animate-spin rounded-full border-[3px] border-current border-t-transparent text-white" role="status" aria-label="loading" wire:loading>
                            <span class="sr-only">Loading...</span>
                        </div>
                        <span wire:loading>Updating...</span>
                        <span wire:loading.remove>Update</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@script
<script>
    flatpickr('#yearEnd', {
        dateFormat: "Y-m-d",
    });
</script>
@endscript
