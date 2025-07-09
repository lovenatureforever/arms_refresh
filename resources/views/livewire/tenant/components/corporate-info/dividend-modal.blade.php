<div>
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

    <div class="flex items-center justify-between border-b px-4 py-2.5 dark:border-gray-700">
        <h3 class="text-lg font-medium text-gray-800 dark:text-white">
            Create / Update Dividend
        </h3>
        <button class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 dark:text-gray-200" wire:click.prevent="closeModal()">
            <span class="material-symbols-rounded">close</span>
        </button>
    </div>
    <form wire:submit="submit">
        <div class="px-4 py-8 overflow-y-auto">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                @if ($isDeclared)
                    <div>
                        <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="declaredDate">Declared Date</label>
                        <input class="form-input" id="declaredDate" type="date" wire:dirty.class="border-amber-500" wire:model="declaredDate">
                    </div>
                    <div>
                        <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="paymentDate">Payment Date</label>
                        <input class="form-input" id="paymentDate" type="date" wire:dirty.class="border-amber-500" wire:model="paymentDate">
                    </div>
                @endif

                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="yearEnd">Year End</label>
                    <input class="form-input" id="yearEnd" type="date" wire:dirty.class="border-amber-500" wire:model="yearEnd">
                </div>
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="shareType">Share Type</label>
                    <select class="form-input" id="shareType" name="shareType" wire:model="shareType">
                        <option value="">Select Share Type</option>
                        @foreach ($shareTypes as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2 flex flex-row gap-2">
                    <div class="form-check">
                        <input type="checkbox" class="form-checkbox rounded text-primary" id="isFreeText" wire:model.live="isFreeText">
                        <label class="ms-1.5" for="isFreeText">
                            Free Text
                        </label>
                    </div>
                </div>

                @if (!$isFreeText)
                    <div>
                        <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="dividendType">Dividend Type</label>
                        <select class="form-input" id="dividendType" name="dividendType" wire:model="dividendType">
                            <option value="">Select Dividend Type</option>
                            @foreach ($dividendTypes as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div>
                        <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="dividendType">Dividend Type</label>
                        <input class="form-input" id="dividendType" type="text" wire:dirty.class="border-amber-500" wire:model="dividendType">
                    </div>
                @endif

                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="rateUnit">Rate Unit</label>
                    <select class="form-input" id="rateUnit" name="rateUnit" wire:model="rateUnit">
                        <option value="">Select Rate Unit</option>
                        @foreach ($rateUnits as $unit)
                        <option value="{{ $unit }}">{{ $unit }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="rate">Rate</label>
                    <input class="form-input numberInput" id="rate" type="text" wire:dirty.class="border-amber-500" wire:model="rate">
                </div>
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="amount">Amount</label>
                    <input class="form-input numberInput" id="amount" type="text" wire:dirty.class="border-amber-500" wire:model="amount">
                </div>

                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="effectiveDate">Effective Date</label>
                    <input class="form-input" id="effectiveDate" type="date" wire:dirty.class="border-amber-500" wire:model="effectiveDate">
                </div>
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="remarks">Remarks</label>
                    <input class="form-input" id="remarks" type="text" wire:dirty.class="border-amber-500" wire:model="remarks">
                </div>
            </div>
        </div>
        <div class="flex items-center justify-end gap-4 p-4 border-t dark:border-slate-700">
            <div>
                <button class="text-white btn bg-primary" type="submit" wire:loading.attr="disabled">
                    <div class="mr-2 inline-block h-4 w-4 animate-spin rounded-full border-[3px] border-current border-t-transparent text-white" role="status" aria-label="loading" wire:loading>
                        <span class="sr-only">Loading...</span>
                    </div>
                    <span wire:loading>Updating...</span>
                    <span wire:loading.remove>Save</span>
                </button>
            </div>
        </div>
    </form>
</div>
@script
    <script>
        document.querySelectorAll('.numberInput').forEach(function (el) {
            new Cleave(el, {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand'
            });
        });

        flatpickr('#effectiveDate', {
            dateFormat: "Y-m-d",
            minDate: "{{ $company->current_year_from }}",
            defaultDate: "{{ $effectiveDate ?? now()->format('Y-m-d') }}",
            maxDate: "{{ $company->end_date_report }}"
        });
        flatpickr('#declaredDate', {
            dateFormat: "Y-m-d",
            defaultDate: "{{ $declaredDate ?? now()->format('Y-m-d') }}",
        });
        flatpickr('#paymentDate', {
            dateFormat: "Y-m-d",
            defaultDate: "{{ $paymentDate ?? now()->format('Y-m-d') }}",
        });
        flatpickr('#yearEnd', {
            dateFormat: "Y-m-d",
            defaultDate: "{{ $yearEnd ?? now()->format('Y-m-d') }}",
        });
    </script>
@endscript
