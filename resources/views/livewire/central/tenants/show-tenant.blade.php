<div class="grid grid-cols-1 gap-6">
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title">View / Update Tenant</h4>
                <div class="flex items-center gap-2">
                    <a class="btn bg-warning text-white" type="button" href="{{ route('index.tenant') }}">Cancel</a>
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
                <p class="mb-4 text-xl text-slate-700 underline dark:text-slate-400">Tenant Detail</p>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="col-span-2">
                        {{-- <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="companyName">Company Name</label>
                        <input class="form-input" id="companyName" type="text" wire:model="companyName" placeholder="Company SDN BHD...."> --}}

                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="domainName">Domain Name</label>
                        <div class="flex">
                            <input class="form-input w-2/5 ltr:rounded-r-none rtl:rounded-l-none" type="text" wire:model="domainName" placeholder="Domain Name" disabled />
                            <div class="inline-flex w-3/5 items-center rounded-e border border-s-0 border-gray-200 bg-gray-50 px-4 text-gray-500 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                .{{ config('app.domain') }}
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="firmName">Firm Name</label>
                        <input class="form-input" id="firmName" type="text" wire:dirty.class="border-amber-500" wire:model="firmName" placeholder="Company SDN BHD...">
                    </div>

                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="firmTitle">Firm Title</label>
                        <input class="form-input" id="firmTitle" type="text" wire:dirty.class="border-amber-500" wire:model="firmTitle" placeholder="Associate...">
                    </div>

                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="firmNo">Firm No</label>
                        <input class="form-input" id="firmNo" type="text" wire:dirty.class="border-amber-500" wire:model="firmNo" placeholder="abc-123-xxx">
                    </div>

                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="firmEmail">Firm Email</label>
                        <input class="form-input" id="firmEmail" type="text" wire:dirty.class="border-amber-500" wire:model="firmEmail" placeholder="abc@example.com">
                    </div>

                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="firmContact">Firm Contact Number</label>
                        <input class="form-input" id="firmContact" type="text" wire:dirty.class="border-amber-500" wire:model="firmContact" placeholder="0123456789">
                    </div>

                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="firmFax">Firm Fax</label>
                        <input class="form-input" id="firmFax" type="text" wire:dirty.class="border-amber-500" wire:model="firmFax" placeholder="60123456789">
                    </div>

                    <div class="lg:col-span-2">
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="inputAddress">Address</label>
                        <input class="form-input" id="inputAddress" type="text" wire:dirty.class="border-amber-500" wire:model="address1" placeholder="1234 Main St">
                    </div>

                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="inputAddress2">Address 2</label>
                        <input class="form-input" id="inputAddress2" type="text" wire:dirty.class="border-amber-500" wire:model="address2" placeholder="Apartment, studio, or floor">
                    </div>

                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="inputCity">City</label>
                        <input class="form-input" id="inputCity" type="text" wire:dirty.class="border-amber-500" wire:model="city">
                    </div>
                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="inputState">State</label>
                        <select class="form-select" id="inputState" wire:dirty.class="border-amber-500" wire:model="state">
                            <option>Choose</option>
                            @foreach (states() as $state)
                                <option value="{{ $state }}">{{ $state }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="inputZip">Zip</label>
                        <input class="form-input" id="inputZip" type="text" wire:dirty.class="border-amber-500" wire:model="zip">
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
