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
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-transparent" role="tab" href="{{ route('corporate.companyaddress', ['id' => $id]) }}">
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
                    <a class="btn border border-gray-100 mr-2 mb-2 active bg-primary text-white" role="tab" href="{{ route('corporate.shareholders', ['id' => $id]) }}">
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
                                <h1 class="text-xl">{{ $company->current_is_first_year ? 'Info as at incorporation' : 'As at prior year end' }}</h1>
                                <div>
                                    <button class="text-white btn btn-sm bg-info" type="button" wire:click="$dispatch('openModal', {component: 'tenant.components.corporate-info.shareholder-modal', arguments: { companyId : {{ $id }}, id: null, isStart: true }})">Create</button>
                                </div>
                            </div>


                            <div class="relative mb-4 overflow-x-auto border rounded-lg">
                                <div class="grid grid-cols-7 text-xs font-bold text-gray-700 uppercase border-b rounded-lg rounded-b-none bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <div class="flex items-center justify-start p-4 border-r">Name</div>
                                    <div class="flex items-center justify-start p-4 border-r">Id No.</div>
                                    <div class="flex items-center justify-start p-4 border-r ">Member Type</div>
                                    <div class="flex items-center justify-start p-4 border-r">Share Type</div>
                                    <div class="flex items-center justify-start p-4 border-r">No. Of Shares</div>
                                    <div class="flex items-center justify-start p-4 border-r">Remarks</div>
                                    <div class="flex items-center justify-start p-4">Action</div>
                                </div>
                                @foreach ($shareholderChangesAtStart as $shareholderChange)
                                <div class="grid grid-cols-7">
                                    <div class="p-4 border-b border-r">{{ $shareholderChange->companyShareholder->name }}</div>
                                    <div class="p-4 border-b border-r">{{ $shareholderChange->companyShareholder->id_no }} ({{$shareholderChange->companyShareholder->id_type}})</div>
                                    <div class="p-4 border-b border-r">{{ $shareholderChange->companyShareholder->type }}</div>
                                    <div class="p-4 border-b border-r">{{ $shareholderChange->share_type }}</div>
                                    <div class="p-4 border-b border-r">{{ displayNumber($shareholderChange->shares) }}</div>
                                    <div class="p-4 border-b border-r">{{ $shareholderChange->remarks }}</div>
                                    <div class="p-4 border-b">
                                        <button class="text-white btn btn-sm bg-info" wire:click="$dispatch('openModal', {component: 'tenant.components.corporate-info.shareholder-modal', arguments: { companyId : {{ $id }}, 'id' : {{ $shareholderChange->id }}, isStart: true }})">Edit</button>
                                        <button
                                            class="text-white btn btn-sm bg-danger"
                                            type="button"
                                            wire:click="deleteCompanyShareholder({{ $shareholderChange->companyShareholder->id }})"
                                            wire:confirm="Are you sure you want to delete this?"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="flex flex-row justify-between mt-8 mb-4">
                                <h1 class="text-xl">Changes during the year</h1>
                                <div>
                                    <button class="text-white btn btn-sm bg-info" type="button" wire:click="$dispatch('openModal', {component: 'tenant.components.corporate-info.shareholder-modal', arguments: { companyId : {{ $id }}, id: null, isStart: false }})">Create</button>
                                </div>
                            </div>

                            <div class="relative mb-4 overflow-x-auto border rounded-lg">
                                <div class="grid grid-cols-9 text-xs font-bold text-gray-700 uppercase border-b rounded-lg rounded-b-none bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <div class="flex items-center justify-start p-4 border-r">Nature Of Change</div>
                                    <div class="flex items-center justify-start p-4 border-r">Name</div>
                                    <div class="flex items-center justify-start p-4 border-r">Id No.</div>
                                    <div class="flex items-center justify-start p-4 border-r ">Member Type</div>
                                    <div class="flex items-center justify-start p-4 border-r">Share Type</div>
                                    <div class="flex items-center justify-start p-4 border-r">No. Of Shares</div>
                                    <div class="flex items-center justify-start p-4 border-r">Effective Date</div>
                                    <div class="flex items-center justify-start p-4 border-r">Remarks</div>
                                    <div class="flex items-center justify-start p-4">Action</div>
                                </div>
                                @foreach ($shareholderChangesCurrentYear as $shareholderChange)
                                <div class="grid grid-cols-9">
                                    <div class="p-4 border-b border-r">{{ $shareholderChange->change_nature }}</div>
                                    <div class="p-4 border-b border-r">{{ $shareholderChange->companyShareholder->name }}</div>
                                    <div class="p-4 border-b border-r">{{ $shareholderChange->companyShareholder->id_no }} ({{$shareholderChange->companyShareholder->id_type}})</div>
                                    <div class="p-4 border-b border-r">{{ $shareholderChange->companyShareholder->type }}</div>
                                    <div class="p-4 border-b border-r">{{ $shareholderChange->share_type }}</div>
                                    <div class="p-4 border-b border-r">{{ displayNumber($shareholderChange->shares) }}</div>
                                    <div class="p-4 border-b border-r">{{ $shareholderChange->effective_date->format('Y-m-d') }}</div>
                                    <div class="p-4 border-b border-r">{{ $shareholderChange->remarks }}</div>
                                    <div class="p-4 border-b">
                                        <button class="text-white btn btn-sm bg-info" wire:click="$dispatch('openModal', {component: 'tenant.components.corporate-info.shareholder-modal', arguments: { companyId : {{ $this->id }}, 'id' : {{ $shareholderChange->id }}, isStart: false }})">Edit</button>
                                        <button class="text-white btn btn-sm bg-danger" type="button" wire:click="deleteShareholderChange({{ $shareholderChange->id }})"
                                            wire:confirm="Are you sure you want to delete this?">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="mt-8 mb-4">
                                <h1 class="text-xl text-primary">Result Summary</h1>
                                <div class="text-sm text-gray-400">as at current year end</div>
                            </div>

                            <div class="relative mb-4 overflow-x-auto border rounded-lg">
                                <div class="grid grid-cols-8 text-xs font-bold text-gray-700 uppercase border-b rounded-lg rounded-b-none bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <div class="flex items-center justify-center p-4 border-r row-span-2">Name</div>
                                    <div class="flex items-center justify-center p-4 border-r border-b col-span-4">No. Of Shares</div>
                                    <div class="flex items-center justify-start p-4 border-r row-span-2">% Of Shares</div>
                                    <div class="flex items-center justify-start p-4 border-r row-span-2">Is Director</div>
                                    <div class="flex items-center justify-start p-4 border-r row-span-2">Is Holding Company</div>
                                    <div class="flex items-center justify-start p-4 border-r">B/F</div>
                                    <div class="flex items-center justify-start p-4 border-r">Bought</div>
                                    <div class="flex items-center justify-start p-4 border-r">Sold</div>
                                    <div class="flex items-center justify-start p-4 border-r">C/F</div>
                                </div>

                                <div class="p-1 grid grid-cols-8 border-b col-span-8">
                                    <span class="font-bold underline italic">Ordinary shares</span>
                                </div>

                                @foreach ($resultSummary['ordinaryShareChanges'] as $i)
                                    <div class="grid grid-cols-8 border-b">
                                        <div class="p-2 border-r">{{ $i['name'] }}</div>
                                        <div class="p-2 border-r">{{ displayNumber($i['bf']) }}</div>
                                        <div class="p-2 border-r">{{ displayNumber($i['bought']) }}</div>
                                        <div class="p-2 border-r">{{ displayNumber($i['sold']) }}</div>
                                        <div class="p-2 border-r">{{ displayNumber($i['cf']) }}</div>
                                        <div class="p-2 border-r">{{ $i['percentage'] }}</div>
                                        <div class="p-2 border-r justify-center">
                                            <input class="form-switch" type="checkbox" role="switch" disabled {{ $i['isDirector'] ? 'checked' : ''}}>
                                        </div>
                                        <div class="p-2 border-r">
                                            <input class="form-switch" type="checkbox" role="switch" disabled {{ $i['isHoldingCompany'] ? 'checked' : ''}}>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="grid grid-cols-8 border-b">
                                    <div class="p-2 border-r text-right"><span class="text-black font-bold">Total Ordinary shares:</span></div>
                                    <div class="p-2 border-r">{{ displayNumber($resultSummary['totalOrdinaryShares']['bf']) }}</div>
                                    <div class="p-2 border-r">{{ displayNumber($resultSummary['totalOrdinaryShares']['bought']) }}</div>
                                    <div class="p-2 border-r">{{ displayNumber($resultSummary['totalOrdinaryShares']['sold']) }}</div>
                                    <div class="p-2 border-r">{{ displayNumber($resultSummary['totalOrdinaryShares']['cf']) }}</div>
                                    <div class="p-2 border-r">100%</div>
                                    <div class="p-2 border-r"></div>
                                    <div class="p-2 border-r"></div>
                                </div>
                                <div class="p-1 grid grid-cols-8 border-b col-span-8">
                                    <span class="font-bold underline italic">Preference shares</span>
                                </div>
                                @foreach ($resultSummary['preferenceShareChanges'] as $i)
                                    <div class="grid grid-cols-8 border-b">
                                        <div class="p-2 border-r">{{ $i['name'] }}</div>
                                        <div class="p-2 border-r">{{ displayNumber($i['bf']) }}</div>
                                        <div class="p-2 border-r">{{ displayNumber($i['bought']) }}</div>
                                        <div class="p-2 border-r">{{ displayNumber($i['sold']) }}</div>
                                        <div class="p-2 border-r">{{ displayNumber($i['cf']) }}</div>
                                        <div class="p-2 border-r">{{ $i['percentage'] }}</div>
                                        <div class="p-2 border-r">
                                            <input class="form-switch" type="checkbox" role="switch" disabled {{ $i['isDirector'] ? 'checked' : ''}}>
                                        </div>
                                        <div class="p-2 border-r">
                                            <input class="form-switch" type="checkbox" role="switch" disabled {{ $i['isHoldingCompany'] ? 'checked' : ''}}>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="grid grid-cols-8 border-b">
                                    <div class="p-2 border-r text-right"><span class="text-black font-bold">Total Preference shares:</span></div>
                                    <div class="p-2 border-r">{{ displayNumber($resultSummary['totalPreferenceShares']['bf']) }}</div>
                                    <div class="p-2 border-r">{{ displayNumber($resultSummary['totalPreferenceShares']['bought']) }}</div>
                                    <div class="p-2 border-r">{{ displayNumber($resultSummary['totalPreferenceShares']['sold']) }}</div>
                                    <div class="p-2 border-r">{{ displayNumber($resultSummary['totalPreferenceShares']['cf']) }}</div>
                                    <div class="p-2 border-r">100%</div>
                                    <div class="p-2 border-r"></div>
                                    <div class="p-2 border-r"></div>
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

@script
<script>
    document.querySelectorAll('.numberInput').forEach(function (el) {
        new Cleave(el, {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });
    });
</script>
@endscript

@livewireScripts
<script src="https://unpkg.com/@wotz/livewire-sortablejs@1.0.0/dist/livewire-sortable.js"></script>

