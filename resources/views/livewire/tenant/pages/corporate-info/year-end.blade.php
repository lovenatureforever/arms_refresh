<div class="grid grid-cols-1 gap-4">
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title">Corporate Info </h4>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 gap-5" data-fc-type="tab">
                <x-corporate-tabs :active="'corporate.yearend'" :id="$id" />
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
                            <form wire:submit="submit">
                                <div>
                                    <div class="relative mb-4 overflow-x-auto border border-gray-100 rounded-lg">
                                        <table class="w-full text-sm text-center text-gray-500 rtl:text-right dark:text-gray-400 ">
                                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                                <tr>
                                                    <th scope="col" class="w-full p-4">
                                                        Year End
                                                    </th>
                                                    <th scope="col" class="p-4">
                                                        First Year Incorporation
                                                    </th>
                                                    <th scope="col" class="p-4">
                                                        Account Opening Date
                                                    </th>
                                                    <th scope="col" class="p-4">
                                                        Account Closing Date
                                                    </th>
                                                    <th scope="col" class="p-4">
                                                        Financial Year
                                                    </th>
                                                    <th scope="col" class="p-4">
                                                        Year Period Type
                                                    </th>
                                                    <th scope="col" class="p-4">
                                                        Report Year/Period Format
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                    <td class="p-4">
                                                        <div class="w-36">Prior year end</div>
                                                    </td>
                                                    <td class="p-4"><input class="border border-gray-200 rounded form-checkbox" id="firstYear" type="checkbox" wire:dirty.class="border-yellow-500" wire:model.live="lastIsFirstYear" {{ $lastIsFirstYear == 1 ? 'checked' : '' }} {{ $currentIsFirstYear ? 'disabled' : '' }}></td>
                                                    <td class="p-4"><input class="form-input w-36" id="accountOpenDate" type="text" wire:dirty.class="border-yellow-500" wire:model="lastYearFrom" {{ $currentIsFirstYear ? 'disabled' : '' }}></td>
                                                    <td class="p-4"><input class="form-input w-36" id="accountClosingDate" type="text" wire:dirty.class="border-yellow-500" wire:model="lastYearTo" {{ $currentIsFirstYear ? 'disabled' : '' }}></td>
                                                    <td class="p-4"><input class="form-input w-36" id="financialYear" type="year" wire:dirty.class="border-yellow-500" wire:model="lastYear" {{ $currentIsFirstYear ? 'disabled' : '' }}></td>
                                                    <td class="p-4">
                                                        <select class="form-select w-36" id="yearPeriod" wire:model.live="lastYearType" wire:dirty.class="border-yellow-500" {{ $lastIsFirstYear || $currentIsFirstYear ? 'disabled' : '' }}>
                                                            <option value="">Select</option>
                                                            <option value="partial year">Partial Year</option>
                                                            <option value="full year">Full Year</option>
                                                            <option value="first year" disabled>First Year</option>
                                                        </select>
                                                    </td>
                                                    <td class="p-4">
                                                        <select class="form-select w-36" id="periodFormat" wire:model="lastReportHeaderFormat" wire:dirty.class="border-yellow-500" {{ $lastIsFirstYear || $currentIsFirstYear ? 'disabled' : '' }}>
                                                            <option>Select</option>
                                                            <option value="year">Year (YYYY)</option>
                                                            <option value="date">Date (DD.MM.YYYY)</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr class="bg-white dark:bg-gray-800 dark:border-gray-700">
                                                    <td class="p-4">
                                                        <div class="w-36">Current year end</div>
                                                    </td>
                                                    <td class="p-4"><input class="border border-gray-200 rounded form-checkbox" id="currentFirstYear" type="checkbox" wire:dirty.class="border-yellow-500" wire:model.live="currentIsFirstYear" {{ $currentIsFirstYear == 1 ? 'checked' : '' }}></td>
                                                    <td class="p-4"><input class="form-input w-36" id="currentAccountOpenDate" type="text" wire:model="currentYearFrom" wire:dirty.class="border-yellow-500"></td>
                                                    <td class="p-4"><input class="form-input w-36" id="currentAccountClosingDate" type="text" wire:model="currentYearTo" wire:dirty.class="border-yellow-500"></td>
                                                    <td class="p-4"><input class="form-input w-36" id="currentFinancialYear" type="year" wire:model="currentYear" wire:dirty.class="border-yellow-500"></td>
                                                    <td class="p-4">
                                                        <select class="form-select w-36" id="currentYearPeriod" wire:model.live="currentYearType" wire:dirty.class="border-yellow-500" {{ $currentIsFirstYear ? 'disabled' : '' }}>
                                                            <option value="">Select</option>
                                                            <option value="partial year">Partial Year</option>
                                                            <option value="full year">Full Year</option>
                                                            <option value="first year" disabled>First Year</option>
                                                        </select>
                                                    </td>
                                                    <td class="p-4">
                                                        <select class="form-select w-36" id="currentPeriodFormat" wire:model="currentReportHeaderFormat" wire:dirty.class="border-yellow-500" {{ $currentIsFirstYear ? 'disabled' : '' }}>
                                                            <option>Select</option>
                                                            <option value="year">Year (YYYY)</option>
                                                            <option value="date">Date (DD.MM.YYYY)</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                                <h1 class="text-2xl">Result Summary - as at current report date</h1>
                                <div class="flex flex-col justify-center w-full">
                                    @if ($companyDetailAtLast)
                                        <p><span class="text-xl">{{ $companyDetailAtLast->name }}</span></p>
                                        @if ($companyDetailAtLast->id != $companyDetailAtStart->id)
                                            <p>(formerly known as <b>{{ $companyDetailAtStart->name }}</b>)</p>
                                        @endif
                                        <p>(incorporated in <span class="font-bold">{{ $companyDetailAtLast->domicile }}</span>)</p>
                                    @endif

                                    <p><b>REPORTS AND FINANCIAL STATEMENTS</b></p>
                                    <p><b>FOR THE FINANCIAL YEAR END {{ Str::upper(Carbon\Carbon::parse($currentYearTo)->format('d F Y')) }}</b></p>
                                </div>
                                <div class="flex justify-end mt-10">
                                    <button class="text-white btn bg-success w-36" type="submit" wire:loading.attr="disabled">Save</button>
                                </div>
                            </form>
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
    flatpickr('#currentAccountOpenDate', {
        dateFormat: "Y-m-d",
        defaultDate: "{{ $currentYearFrom ?? now()->format('Y-m-d') }}",
    });
    flatpickr('#currentAccountClosingDate', {
        dateFormat: "Y-m-d",
        defaultDate: "{{ $currentYearTo ?? now()->format('Y-m-d') }}",
    });
    flatpickr('#accountOpenDate', {
        dateFormat: "Y-m-d",
        defaultDate: "{{ $lastYearFrom ?? now()->format('Y-m-d') }}",
    });
    flatpickr('#accountClosingDate', {
        dateFormat: "Y-m-d",
        defaultDate: "{{ $lastYearTo ?? now()->format('Y-m-d') }}",
    });
</script>
@endscript
