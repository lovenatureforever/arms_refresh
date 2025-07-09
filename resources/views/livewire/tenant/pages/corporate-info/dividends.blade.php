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
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-primary text-white active" role="tab" href="{{ route('corporate.dividends', ['id' => $id]) }}">
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
                                <h1 class="text-xl">Declared</h1>
                                <div>
                                    <button class="text-white btn btn-sm bg-info" type="button" wire:click="$dispatch('openModal', {component: 'tenant.components.corporate-info.dividend-modal', arguments: { companyId : {{ $id }}, id: null, isDeclared: true }})">Create</button>
                                </div>
                            </div>

                            <div class="relative mb-4 overflow-x-auto border rounded-lg">
                                <div class="grid grid-cols-11 text-xs font-bold text-gray-700 uppercase border-b rounded-lg rounded-b-none bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <div class=" p-4 flex justify-center items-center border-r">Declared Date</div>
                                    <div class="p-4 flex justify-center items-center border-r">Payment Date</div>
                                    <div class="p-4 flex justify-center items-center border-r">Year End</div>
                                    <div class="p-4 flex justify-center items-center border-r">Share Type</div>
                                    <div class="p-4 flex justify-center items-center border-r">Dividend Type</div>
                                    {{-- <div class="p-4 flex justify-center items-center border-r">Free Text</div> --}}
                                    <div class="p-4 flex justify-center items-center border-r">Rate Unit</div>
                                    <div class="p-4 flex justify-center items-center border-r">Rate</div>
                                    <div class="p-4 flex justify-center items-center border-r">Amount</div>
                                    <div class="p-4 flex justify-center items-center border-r">Effective Date</div>
                                    <div class="p-4 flex justify-center items-center border-r">Remark</div>
                                    <div class="flex items-center justify-start p-4">Action</div>
                                </div>
                                @foreach ($declaredDividends as $dividend)
                                <div class="grid grid-cols-11 border-b">
                                    <div class="p-4 border-r">{{ $dividend->declared_date?->format('Y-m-d') }}</div>
                                    <div class="p-4 border-r">{{ $dividend->payment_date?->format('Y-m-d') }}</div>
                                    <div class="p-4 border-r">{{ $dividend->year_end?->format('Y-m-d') }}</div>
                                    <div class="p-4 border-r">{{ $dividend->share_type }}</div>
                                    <div class="p-4 border-r">{{ $dividend->dividend_type }}</div>
                                    {{-- <div class="p-4 border-r">{{ $dividend->is_free_text }}</div> --}}
                                    <div class="p-4 border-r">{{ $dividend->rate_unit }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($dividend->rate) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($dividend->amount) }}</div>
                                    <div class="p-4 border-r">{{ $dividend->effective_date?->format('Y-m-d') }}</div>
                                    <div class="p-4 border-r">{{ $dividend->remarks }}</div>
                                    <div class="p-4">
                                        <button class="text-white btn btn-sm bg-info" wire:click="$dispatch('openModal', {component: 'tenant.components.corporate-info.dividend-modal', arguments: { companyId : {{ $id }}, 'id' : {{ $dividend->id }}, isDeclared: true }})">Edit</button>
                                        <button
                                            class="text-white btn btn-sm bg-danger"
                                            type="button"
                                            wire:click="deleteDividend({{ $dividend->id }})"
                                            wire:confirm="Are you sure you want to delete this?"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="flex flex-row justify-between mt-8 mb-4">
                                <h1 class="text-xl">Proposed/Recommended</h1>
                                <div>
                                    <button class="text-white btn btn-sm bg-info" type="button" wire:click="$dispatch('openModal', {component: 'tenant.components.corporate-info.dividend-modal', arguments: { companyId : {{ $id }}, id: null, isDeclared: false }})">Create</button>
                                </div>
                            </div>

                            <div class="relative mb-4 overflow-x-auto border rounded-lg">
                                <div class="grid grid-cols-9 text-xs font-bold text-gray-700 uppercase border-b rounded-lg rounded-b-none bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <div class="p-4 flex justify-center items-center border-r">Year End</div>
                                    <div class="p-4 flex justify-center items-center border-r">Share Type</div>
                                    <div class="p-4 flex justify-center items-center border-r">Dividend Type</div>
                                    {{-- <div class="p-4 flex justify-center items-center border-r">Free Text</div> --}}
                                    <div class="p-4 flex justify-center items-center border-r">Rate Unit</div>
                                    <div class="p-4 flex justify-center items-center border-r">Rate</div>
                                    <div class="p-4 flex justify-center items-center border-r">Amount</div>
                                    <div class="p-4 flex justify-center items-center border-r">Effective Date</div>
                                    <div class="p-4 flex justify-center items-center border-r">Remark</div>
                                    <div class="flex items-center justify-start p-4">Action</div>
                                </div>
                                @foreach ($proposedDividends as $dividend)
                                <div class="grid grid-cols-9 border-b">
                                    <div class="p-4 border-r">{{ $dividend->year_end?->format('Y-m-d') }}</div>
                                    <div class="p-4 border-r">{{ $dividend->share_type }}</div>
                                    <div class="p-4 border-r">{{ $dividend->dividend_type }}</div>
                                    {{-- <div class="p-4 border-r">{{ $dividend->is_free_text }}</div> --}}
                                    <div class="p-4 border-r">{{ $dividend->rate_unit }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($dividend->rate) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($dividend->amount) }}</div>
                                    <div class="p-4 border-r">{{ $dividend->effective_date?->format('Y-m-d') }}</div>
                                    <div class="p-4 border-r">{{ $dividend->remarks }}</div>
                                    <div class="p-4">
                                        <button class="text-white btn btn-sm bg-info" wire:click="$dispatch('openModal', {component: 'tenant.components.corporate-info.dividend-modal', arguments: { companyId : {{ $this->id }}, 'id' : {{ $dividend->id }}, isDeclared: false }})">Edit</button>
                                        <button class="text-white btn btn-sm bg-danger" type="button" wire:click="deleteDividendChange({{ $dividend->id }})"
                                            wire:confirm="Are you sure you want to delete this?">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="mt-8 mb-4">
                                <h1 class="text-xl text-primary">Result Summary</h1>
                                <div class="text-sm text-gray-400">as at current report date</div>
                            </div>
                            {{--  --}}
                            <div class="w-full overflow-x-auto">
                                <table class="min-w-max table-auto border border-gray-300">
                                    <thead>
                                    <tr>
                                        <th class="text-center border border-gray-300 px-4 py-2 font-semibold">Report Display</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="p-4">
                                        <div class="mx-2">
                                            <div id="dividend_summary">
                                            <p class="text-[10.5pt] font-bold uppercase leading-snug mb-4">Dividends</p>
                                            <p class="text-[10.5pt] text-justify leading-snug mb-4">
                                                Since the end of the previous financial period, the Company has declared the following dividends:
                                            </p>
                                            @if ($declaredDividends->count() > 0 || $proposedDividends->count() > 0)
                                            <table class="w-full text-[10.5pt] font-sans">
                                                <tbody>
                                                    @if ($declaredDividends->count() > 0)
                                                    <tr>
                                                        <td class="w-4/5"></td>
                                                        <td class="w-1/5">
                                                            <p class="text-center font-bold leading-snug mb-1">RM</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <p class="underline leading-snug mb-1">During the financial period</p>
                                                        </td>
                                                    </tr>

                                                    @foreach ($declaredDividends as $dividend)
                                                    <tr>
                                                        <td class="pr-4">
                                                            <p class="pl-4 -indent-4 leading-snug mb-1">
                                                                {{ $dividend->dividend_type === App\Models\Tenant\CompanyDividendChange::DIVIDENDTYPE_FINAL_SINGLE_TIER ? $dividend->dividend_type : numberToOrdinalWord($loop->iteration) . ' ' . $dividend->dividend_type }} of {{ $dividend->rate_unit }}{{ displayNumber($dividend->rate) }} per {{ strtolower($dividend->share_type) }} in respect of financial period ending {{ $dividend->year_end->format('d F Y') }}
                                                            </p>
                                                        </td>
                                                        <td class="align-bottom text-right font-bold">
                                                            <p class="mb-1">{{ displayNumber($dividend->amount) }}</p>
                                                        </td>
                                                    </tr>
                                                    @endforeach

                                                    @if ($declaredDividends->count() > 1)
                                                    <tr>
                                                        <td></td>
                                                        <td class="pt-2">
                                                        <div class="border-t border-gray-600"></div>
                                                        <p class="text-right font-bold mt-2">{{ displayNumber($declaredDividends->sum('amount')) }}</p>
                                                        </td>
                                                    </tr>
                                                    @endif

                                                    <tr>
                                                        <td></td>
                                                        <td>
                                                        <div class="border-t-4 border-double border-gray-600 mt-2 mb-4"></div>
                                                        </td>
                                                    </tr>
                                                    @endif

                                                    @foreach ($proposedDividends as $dividend)
                                                    <tr>
                                                        <td colspan="2">
                                                        <p class="text-justify leading-snug mb-4">
                                                            The directors have recommended an {{ strtolower($dividend->dividend_type) }} of {{ $dividend->rate_unit }}{{ displayNumber($dividend->rate) }} per ordinary share amounting to {{ $dividend->rate_unit }}{{ displayNumber($dividend->amount) }} in respect of the financial period ended {{ $dividend->year_end->format('d F Y') }}.
                                                        </p>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @endif
                                            <p class="text-[10.5pt] text-justify leading-snug mt-2">
                                                No dividends have been paid or declared by the Company since the end of the previous financial year and the directors do not recommend any dividend for the current financial period.
                                            </p>
                                            </div>
                                        </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            {{--  --}}
                        </div>

                        {{-- End Detail Content --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@livewireScripts
<script src="https://unpkg.com/@wotz/livewire-sortablejs@1.0.0/dist/livewire-sortable.js"></script>

