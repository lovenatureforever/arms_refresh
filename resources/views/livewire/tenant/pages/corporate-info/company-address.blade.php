<div class="grid grid-cols-1 gap-4">
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title">Corporate Info </h4>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 gap-5" data-fc-type="tab">
                <nav class="flex-wrap rounded md:flex-col" role="tablist" aria-label="Tabs">
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-transparent" role="tab" href="{{ route('corporate.yearend', ['id' => $id]) }}">
                        Year End Info
                    </a>
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-transparent" role="tab" href="{{ route('corporate.companyname', ['id' => $id]) }}">
                        Company Name
                    </a>
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-transparent" role="tab" href="{{ route('corporate.businessnature', ['id' => $id]) }}">
                        Nature of Business
                    </a>
                    <a class="btn border border-gray-100 mr-2 mb-2 active bg-primary text-white" role="tab" href="{{ route('corporate.companyaddress', ['id' => $id]) }}">
                        Registered Address
                    </a>
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-transparent" role="tab" href="{{ route('corporate.businessaddress', ['id' => $id]) }}">
                        Business Address
                    </a>
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-transparent" role="tab" href="{{ route('corporate.sharecapital', ['id' => $id]) }}">
                        Share Capital
                    </a>
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-transparent" role="tab" href="{{ route('corporate.directors', ['id' => $id]) }}">
                        Directors
                    </a>
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-transparent" role="tab" href="{{ route('corporate.shareholders', ['id' => $id]) }}">
                        Shareholders
                    </a>
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-transparent" role="tab" href="{{ route('corporate.secretaries', ['id' => $id]) }}">
                        Secretaries
                    </a>
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-transparent" role="tab" href="{{ route('corporate.auditor', ['id' => $id]) }}">
                        Auditor
                    </a>
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-transparent" role="tab" href="{{ route('corporate.charges', ['id' => $id]) }}">
                        Charges
                    </a>
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-transparent" role="tab" href="{{ route('corporate.dividends', ['id' => $id]) }}">
                        Dividends
                    </a>
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-transparent" role="tab" href="{{ route('corporate.reportinfo', ['id' => $id]) }}">
                        Report Info
                    </a>
                </nav>
                <div class="col-span-4 p-4 mb-2 mr-2 border border-gray-100 rounded">
                    <div role="tabpanel">
                        {{-- Detail Content --}}
                        <div>
                            @if (session()->has('error'))
                            <div class="p-4 border border-red-200 rounded-md bg-red-50" role="alert">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="text-xl mgc_information_line"></i>
                                    </div>
                                    <div class="ms-4">
                                        <h3 class="text-sm font-semibold text-red-800">
                                            {{ session('error') }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if (session()->has('success'))
                            <div class="p-4 mb-5 border border-green-200 rounded-md bg-green-50" role="alert">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="text-xl mgc_information_line"></i>
                                    </div>
                                    <div class="ms-4">
                                        <h3 class="text-sm font-semibold text-green-800">
                                            {{ session('success') }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="flex flex-row justify-between mb-4">
                                <h1 class="text-xl">{{ $company->current_is_first_year ? 'Info as at incorporation' :'As at prior year end' }}</h1>
                            </div>
                            <div class="relative overflow-x-auto border rounded-lg">
                                <div class="grid grid-cols-[repeat(2,_1fr)_0.4fr] text-xs font-bold text-center text-gray-700 uppercase border-b rounded-lg rounded-b-none bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <div class="flex items-center justify-center p-4 border-r">Address</div>
                                    <div class="flex items-center justify-center p-4 border-r">Remarks</div>
                                    <div class="flex items-center justify-center p-4">Action</div>
                                </div>

                                <div class="grid grid-cols-[repeat(2,_1fr)_0.4fr]">
                                    <div class="p-4 border-r">
                                        {{ $addressAtStart->full_address }}
                                    </div>
                                    <div class="p-4 border-r">{{ $addressAtStart->remarks }}</div>
                                    <div class="flex items-center justify-center p-4">
                                        <button class="text-white btn btn-sm bg-info"
                                            type="button"
                                            wire:click="$dispatch('openModal', {component: 'tenant.components.corporate-info.company-address-modal', arguments: { companyId : {{ $id }}, 'id' : {{ $addressAtStart->id }}, isStart: true }})"
                                        >Edit</button>
                                    </div>
                                </div>

                            </div>

                            <div class="flex flex-row justify-between mt-8 mb-4">
                                <h1 class="text-xl">Changes during the year</h1>
                                <div>
                                    <button class="text-white btn btn-sm bg-info" type="button" wire:click="$dispatch('openModal', {component: 'tenant.components.corporate-info.company-address-modal', arguments: { companyId : {{ $id }}, id: null, isStart: false }})">Create</button>
                                </div>
                            </div>

                            <div class="relative mb-4 overflow-x-auto border rounded-lg">
                                <div class="grid grid-cols-5 text-xs font-bold text-center text-gray-700 uppercase border-b rounded-lg rounded-b-none bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <div class="flex items-center justify-center p-4 border-r">Address</div>
                                    <div class="flex items-center justify-center p-4 border-r ">Nature Of Change</div>
                                    <div class="flex items-center justify-center p-4 border-r">Remarks</div>
                                    <div class="flex items-center justify-center p-4 border-r">Effective Date</div>
                                    <div class="flex items-center justify-center p-4">Action</div>
                                </div>
                                @foreach ($addressChanges as $address)
                                <div class="grid grid-cols-5">
                                    <div class="p-4 border-r">
                                        {{ $address->full_address }}
                                    </div>
                                    <div class="p-4 border-r">{{ $address->change_nature }}</div>
                                    <div class="p-4 border-r">{{ $address->remarks }}</div>
                                    <div class="p-4 border-r">{{ $address->effective_date->format('Y-m-d') }}</div>
                                    <div class="flex items-center justify-center p-4">
                                        <button class="mr-1 text-white btn btn-sm bg-info"
                                            wire:click="$dispatch('openModal', {component: 'tenant.components.corporate-info.company-address-modal', arguments: { companyId : {{ $id }}, 'id' : {{ $address->id }}, isStart:  false }})"
                                        >
                                            <i class="text-xl mgc_edit_line"></i></button>
                                        <button class="text-white btn btn-sm bg-warning" type="button"
                                            wire:click="deleteAddress({{ $address->id }})" wire:confirm="Are you sure you want to delete this?">
                                            <i class="text-xl mgc_delete_line"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="mt-8 mb-4">
                                <h1 class="text-xl text-primary">Result Summary</h1>
                                <div class="text-sm text-gray-400">as at current report date</div>
                            </div>

                            <div class="relative mb-4 overflow-x-auto border rounded-lg">
                                <div class="grid grid-cols-1 text-xs font-bold text-center text-gray-700 uppercase border-b rounded-lg rounded-b-none bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <div class="flex items-center p-4 ">Address Display on Report</div>
                                </div>
                                <div class="grid grid-cols-1">
                                    @if ($addressAtLast)
                                    <div class="p-4">
                                        <p>
                                            {{ $addressAtLast->full_address }}
                                        </p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        {{-- End Detail Content --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
