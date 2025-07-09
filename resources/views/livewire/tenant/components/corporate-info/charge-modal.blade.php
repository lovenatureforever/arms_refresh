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
            Create / Update Charge
        </h3>
        <button class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 dark:text-gray-200" wire:click.prevent="closeModal()">
            <span class="material-symbols-rounded">close</span>
        </button>
    </div>
    <form wire:submit="submit">
        <div class="px-4 py-8 overflow-y-auto">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                @if (!$isStart)
                    <div class="col-span-2">
                        <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="changeNature">Nature of Change</label>
                        <select class="form-input" id="changeNature" name="changeNature" wire:model.live="changeNature" {{ $id ? 'disabled' : '' }}>
                            @foreach (App\Models\Tenant\CompanyChargeChange::CHANGE_NATURES as $c)
                            <option value="{{ $c }}">{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif


                @if ($changeNature === App\Models\Tenant\CompanyChargeChange::CHANGE_NATURE_CREATE)
                <div class="col-span-2">
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="registeredNumber">Registered Number</label>
                    <input class="form-input" id="registeredNumber" type="text" wire:dirty.class="border-amber-500" wire:model="registeredNumber">
                </div>
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="registrationDate">Date Of Registration</label>
                    <input class="form-input" id="registrationDate" type="date" wire:dirty.class="border-amber-500" wire:model="registrationDate">
                </div>
                @else
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="registeredNumber">Registered Number</label>
                    <select class="form-input" id="registeredNumber" name="registeredNumber" wire:model="registeredNumber">
                        <option value="">Select Registered Number</option>
                        @foreach ($createdCharges as $c)
                        <option value="{{ $c->id . '-' . $c->registered_number }}">{{ $c->registered_number }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="dischargeDate">Date Of Discharge</label>
                    <input class="form-input" id="dischargeDate" type="date" wire:dirty.class="border-amber-500" wire:model="dischargeDate">
                </div>

                @if ($changeNature === App\Models\Tenant\CompanyChargeChange::CHANGE_NATURE_CREATE)
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="chargeNature">Nature Of Charge</label>
                    <input class="form-input" id="chargeNature" type="text" wire:dirty.class="border-amber-500" wire:model="chargeNature">
                </div>
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="chargeeName">Name Of Chargee</label>
                    <input class="form-input" id="chargeeName" type="text" wire:dirty.class="border-amber-500" wire:model="chargeeName">
                </div>
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="indebtednessAmount">Indebtedness Amount</label>
                    <input class="form-input numberInput" id="indebtednessAmount" type="text" wire:dirty.class="border-amber-500" wire:model="indebtednessAmount">
                </div>
                @endif

                @if (!$isStart)
                    <div>
                        <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="effectiveDate">Effective Date</label>
                        <input class="form-input" id="effectiveDate" type="date" wire:dirty.class="border-amber-500" wire:model="effectiveDate">
                    </div>
                @endif
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
            maxDate: "{{ $company->current_year_to }}"
        });
        flatpickr('#registrationDate', {
            dateFormat: "Y-m-d",
            minDate: "{{ $company->current_year_from }}",
            defaultDate: "{{ $registrationDate ?? now()->format('Y-m-d') }}",
            maxDate: "{{ $company->current_year_to }}"
        });
        flatpickr('#dischargeDate', {
            dateFormat: "Y-m-d",
            minDate: "{{ $company->current_year_from }}",
            defaultDate: "{{ $dischargeDate ?? now()->format('Y-m-d') }}",
            maxDate: "{{ $company->current_year_to }}"
        });
    </script>
@endscript
