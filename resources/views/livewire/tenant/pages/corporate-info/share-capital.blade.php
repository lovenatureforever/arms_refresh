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
                    <a class="btn border border-gray-100 mr-2 mb-2 active bg-primary text-white" role="tab" href="{{ route('corporate.sharecapital', ['id' => $id]) }}">
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

                            @if ($errors->any())
                                <div class="p-4 border border-red-200 rounded-md bg-red-50" role="alert">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="text-xl text-red-800 mgc_information_line"></i>
                                        </div>
                                        <div class="ms-4">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li class="text-sm font-semibold text-red-800">{{ $error }}</li>
                                                @endforeach
                                            </ul>
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
                            </div>
                            <div class="overflow-x-auto mb-9">
                                <div class="inline-block min-w-full align-middle">
                                    <div class="overflow-hidden">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead>
                                                <col />
                                                <colgroup span="2"></colgroup>
                                                <colgroup span="2"></colgroup>
                                                <colgroup span="2"></colgroup>
                                                <tr>
                                                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase" rowspan="2">
                                                        Share Type</th>
                                                    <th class="px-6 py-3 text-xs font-medium text-center text-gray-500 uppercase" colspan="2"
                                                        scope="colgroup">Fully Paid</th>
                                                    <th class="px-6 py-3 text-xs font-medium text-center text-gray-500 uppercase" colspan="2"
                                                        scope="colgroup">Partially Paid</th>
                                                    <th class="px-6 py-3 text-xs font-medium text-center text-gray-500 uppercase" colspan="2"
                                                        scope="colgroup">Total All Shares</th>
                                                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase" rowspan="2">
                                                        Action</th>
                                                </tr>
                                                <tr>
                                                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase" scope="col">No.
                                                        Of Share</th>
                                                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase" scope="col">
                                                        Amount</th>
                                                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase" scope="col">No.
                                                        Of Share</th>
                                                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase" scope="col">
                                                        Amount</th>
                                                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase" scope="col">No.
                                                        Of Share</th>
                                                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase" scope="col">
                                                        Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                    <td
                                                        class="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap dark:text-gray-200">
                                                        Ordinary Shares</td>
                                                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200">
                                                        <input class="form-input numberInput" id="ordinaryFullNoShare"
                                                            name="ordinaryFullNoShare" type="text" wire:model.blur="ordinaryFullNoShare"
                                                            >
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200"><input
                                                            class="form-input numberInput" id="ordinaryFullPaidAmount"
                                                            name="ordinaryFullPaidAmount" type="text" wire:model.blur="ordinaryFullPaidAmount"
                                                            ></td>
                                                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200"><input
                                                            class="form-input numberInput" id="ordinaryPartialNoShare"
                                                            name="ordinaryPartialNoShare" type="text" wire:model.blur="ordinaryPartialNoShare"
                                                            ></td>
                                                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200"><input
                                                            class="form-input numberInput" id="ordinaryPartialPaidAmount"
                                                            name="ordinaryPartialPaidAmount" type="text"
                                                            wire:model.blur="ordinaryPartialPaidAmount" ></td>
                                                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200">
                                                        {{ displayNumber($ordinaryTotalShare) }}</td>
                                                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200">
                                                        {{ displayNumber($ordinaryTotalAmount) }}</td>
                                                    <td>
                                                        <button class="text-white btn btn-xs bg-primary" type="button"
                                                            wire:click="ordinarySave">Save</button>
                                                    </td>
                                                </tr>
                                                <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                    <td
                                                        class="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap dark:text-gray-200">
                                                        Preference Shares</td>
                                                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200"><input
                                                            class="form-input numberInput" id="preferenceFullNoShare"
                                                            name="preferenceFullNoShare" type="text" wire:model.blur="preferenceFullNoShare"
                                                            ></td>
                                                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200"><input
                                                            class="form-input numberInput" id="preferenceFullPaidAmount"
                                                            name="preferenceFullPaidAmount" type="text"
                                                            wire:model.blur="preferenceFullPaidAmount" ></td>
                                                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200"><input
                                                            class="form-input numberInput" id="preferencePartialNoShare"
                                                            name="preferencePartialNoShare" type="text"
                                                            wire:model.blur="preferencePartialNoShare" ></td>
                                                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200"><input
                                                            class="form-input numberInput" id="preferencePartialPaidAmount"
                                                            name="preferencePartialPaidAmount" type="text"
                                                            wire:model.blur="preferencePartialPaidAmount" ></td>
                                                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200">
                                                        {{ displayNumber($preferenceTotalShare) }}</td>
                                                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200">
                                                        {{ displayNumber($preferenceTotalAmount) }}</td>
                                                    <td>
                                                        <button class="text-white btn btn-xs bg-primary" type="button"
                                                            wire:click="preferenceSave">Save</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-row justify-between mb-4">
                                <h1 class="text-xl">Changes during the year</h1>
                                <div>
                                    <button class="text-white btn bg-info" type="button"
                                        wire:click="$dispatch('openModal', {component: 'tenant.components.corporate-info.share-capital-modal', arguments: { companyId : {{ $id }} }})">
                                        New
                                    </button>
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <div class="inline-block min-w-full align-middle">
                                    <div class="overflow-hidden">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead>
                                                <colgroup></colgroup>
                                                <colgroup span="2"></colgroup>
                                                <colgroup span="2"></colgroup>
                                                <colgroup span="2"></colgroup>
                                                <tr>
                                                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase" rowspan="2">
                                                        Share Type</th>
                                                    <th class="px-6 py-3 text-xs font-medium text-center text-gray-500 uppercase" colspan="2"
                                                        scope="colgroup">Fully Paid</th>
                                                    <th class="px-6 py-3 text-xs font-medium text-center text-gray-500 uppercase" colspan="2"
                                                        scope="colgroup">Partially Paid</th>
                                                    <th class="px-6 py-3 text-xs font-medium text-center text-gray-500 uppercase" colspan="2"
                                                        scope="colgroup">Total All Shares</th>
                                                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase" rowspan="2">
                                                        Effective Date
                                                    </th>
                                                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase" rowspan="2">
                                                    Action</th>
                                                </tr>
                                                <tr>
                                                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase" scope="col">No.
                                                        Of Share</th>
                                                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase" scope="col">
                                                        Amount</th>
                                                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase" scope="col">No.
                                                        Of Share</th>
                                                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase" scope="col">
                                                        Amount</th>
                                                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase" scope="col">No.
                                                        Of Share</th>
                                                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase" scope="col">
                                                        Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($sharecapitalChangesCurrentYear as $share)
                                                    <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800"
                                                        wire:key="{{ $share->id }}">
                                                        <td class="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap dark:text-gray-200">
                                                            {{ $share->share_type }}
                                                        </td>
                                                        <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200">
                                                            {{ displayNumber($share->fully_paid_shares) }}
                                                        </td>
                                                        <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200">
                                                            {{ displayNumber($share->fully_paid_amount) }}
                                                        </td>
                                                        <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200">
                                                            {{ displayNumber($share->partially_paid_shares) }}
                                                        </td>
                                                        <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200">
                                                            {{ displayNumber($share->partially_paid_amount) }}
                                                        </td>
                                                        <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200">
                                                            {{ displayNumber($share->fully_paid_shares + $share->partially_paid_shares) }}
                                                        </td>
                                                        <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200">
                                                            {{ displayNumber($share->fully_paid_amount + $share->partially_paid_amount) }}
                                                        </td>
                                                        <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-gray-200">
                                                            {{ $share->effective_date->format('Y-m-d') }}
                                                        </td>
                                                        <td>
                                                            <button class="text-white btn btn-xs bg-primary" type="button"
                                                                wire:click="$dispatch('openModal', {component: 'tenant.components.corporate-info.share-capital-modal', arguments: { companyId : {{ $id }}, 'id' : {{ $share->id }} }})"
                                                            >
                                                                Edit
                                                            </button>
                                                            <button class="text-white btn btn-xs bg-warning" type="button"
                                                                wire:click="deleteShare({{ $share->id }})"
                                                                wire:confirm="Are you sure you want to delete this?"
                                                            >
                                                                Delete
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 mb-4">
                                <h1 class="text-xl text-primary">Result Summary</h1>
                                <div class="text-sm text-gray-400">as at current report date</div>
                            </div>

                            <div class="relative mb-4 overflow-x-auto border rounded-lg">
                                <div
                                    class="grid grid-cols-9 text-xs font-bold text-gray-700 uppercase border-b rounded-lg rounded-b-none bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <div class="flex items-center justify-center row-span-2 p-4 border-r">Share Type</div>
                                    <div class="flex items-center justify-center col-span-4 p-4 border-b border-r">Share Capital Movement (Unit)
                                    </div>
                                    <div class="flex items-center justify-center col-span-4 p-4 border-b border-l">Share Capital Movement (RM)
                                    </div>

                                    <div class="flex items-center justify-center p-4 border-r">B/F</div>
                                    <div class="flex items-center justify-center p-4 border-r">Addition</div>
                                    <div class="flex items-center justify-center p-4 border-r">Reduction</div>
                                    <div class="flex items-center justify-center p-4 border-r">C/F</div>

                                    <div class="flex items-center justify-center p-4 border-l border-r">B/F</div>
                                    <div class="flex items-center justify-center p-4 border-r">Addition</div>
                                    <div class="flex items-center justify-center p-4 border-r">Reduction</div>
                                    <div class="flex items-center justify-center p-4">C/F</div>
                                </div>

                                <div class="grid grid-cols-9 p-1 pl-3 underline border-b">
                                    Ordinary shares
                                </div>
                                <div class="grid grid-cols-9 border-b">
                                    <div class="flex items-center justify-center p-4 border-r">Fully Paid Ordinary Shares</div>
                                    <div class="p-4 border-r">{{ displayNumber($startFullOrdinaryShares) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($additionFullOrdinaryShares) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($reductionFullOrdinaryShares) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($sumFullOrdinaryShares) }}</div>
                                    <div class="p-4 border-l border-r">{{ displayNumber($startFullOrdinaryAmount) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($additionFullOrdinaryAmount) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($reductionFullOrdinaryAmount) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($sumFullOrdinaryAmount) }}</div>
                                </div>
                                <div class="grid grid-cols-9 border-b">
                                    <div class="flex items-center justify-center p-4 border-r">Partially Paid Ordinary Shares</div>
                                    <div class="p-4 border-r">{{ displayNumber($startPartiallyOrdinaryShares) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($additionPartiallyOrdinaryShares) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($reductionPartiallyOrdinaryShares) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($sumPartiallyOrdinaryShares) }}</div>
                                    <div class="p-4 border-l border-r">{{ displayNumber($startPartiallyOrdinaryAmount) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($additionPartiallyOrdinaryAmount) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($reductionPartiallyOrdinaryAmount) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($sumPartiallyOrdinaryAmount) }}</div>
                                </div>
                                <div class="grid grid-cols-9">
                                    <div class="flex items-center justify-end p-4 border-r">Total:</div>
                                    <div class="p-4 border-r">{{ displayNumber($startTotalOrdinaryShares) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($additionTotalOrdinaryShares) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($reductionTotalOrdinaryShares) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($sumTotalOrdinaryShares) }}</div>
                                    <div class="p-4 border-l border-r">{{ displayNumber(readNumber($startTotalOrdinaryAmount)) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($additionTotalOrdinaryAmount) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($reductionTotalOrdinaryAmount) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($sumTotalOrdinaryAmount) }}</div>
                                </div>
                                <div class="grid grid-cols-9 p-1 pl-3 underline border-t border-b">
                                    Preference shares
                                </div>
                                <div class="grid grid-cols-9 border-b">
                                    <div class="flex items-center justify-center p-4 border-r">Fully Paid Preference Shares</div>
                                    <div class="p-4 border-r">{{ displayNumber($startFullPreferenceShares) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($additionFullPreferenceShares) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($reductionFullPreferenceShares) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($sumFullPreferenceShares) }}</div>
                                    <div class="p-4 border-l border-r">{{ displayNumber($startFullPreferenceAmount) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($additionFullPreferenceAmount) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($reductionFullPreferenceAmount) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($sumFullPreferenceAmount) }}</div>
                                </div>
                                <div class="grid grid-cols-9 border-b">
                                    <div class="flex items-center justify-center p-4 border-r">Partially Paid Preference Shares</div>
                                    <div class="p-4 border-r">{{ displayNumber($startPartiallyPreferenceShares) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($additionPartiallyPreferenceShares) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($reductionPartiallyPreferenceShares) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($sumPartiallyPreferenceShares) }}</div>
                                    <div class="p-4 border-l border-r">{{ displayNumber($startPartiallyPreferenceAmount) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($additionPartiallyPreferenceAmount) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($reductionPartiallyPreferenceAmount) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($sumPartiallyPreferenceAmount) }}</div>
                                </div>
                                <div class="grid grid-cols-9">
                                    <div class="flex items-center justify-end p-4 border-r">Total:</div>
                                    <div class="p-4 border-r">{{ displayNumber( $startTotalPreferenceShares) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($additionTotalPreferenceShares) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($reductionTotalPreferenceShares) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($sumTotalPreferenceShares) }}</div>
                                    <div class="p-4 border-l border-r">{{ displayNumber($startTotalPreferenceAmount) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($additionTotalPreferenceAmount) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($reductionTotalPreferenceAmount) }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($sumTotalPreferenceAmount) }}</div>
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
