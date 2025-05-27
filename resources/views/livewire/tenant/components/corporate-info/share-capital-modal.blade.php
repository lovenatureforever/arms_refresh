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
            Create / Update Share Capital
        </h3>
        <button class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 dark:text-gray-200" wire:click.prevent="closeModal()">
            <span class="material-symbols-rounded">close</span>
        </button>
    </div>
    <form wire:submit="submit">
        <div class="px-4 py-8 overflow-y-auto">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="shareType">Share Type</label>
                    <select class="form-input" id="shareType" name="shareType" wire:dirty.class="border-amber-500" wire:model="shareType">
                        <option value="">--Select--</option>
                        @foreach (App\Models\Tenant\CompanyShareCapitalChange::$sharetypes as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="allotmentType">Allotment Type</label>
                    <select class="form-input" id="allotmentType" name="allotmentType" wire:dirty.class="border-amber-500" wire:model="allotmentType">
                        <option value="">--Select--</option>
                        @foreach (App\Models\Tenant\CompanyShareCapitalChange::$allotmenttypes as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="issuanceTerm">Terms of Issue</label>
                    <select class="form-input" id="issuanceTerm" name="issuanceTerm" wire:dirty.class="border-amber-500" wire:model.live="issuanceTerm">
                        <option value="">--Select--</option>
                        @foreach (App\Models\Tenant\CompanyShareCapitalChange::$issuanceterms as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="issuancePurpose">Purpose of Issue</label>
                    <select class="form-input" id="issuancePurpose" name="issuancePurpose" wire:dirty.class="border-amber-500" wire:model="issuancePurpose">
                        <option value="">--Select--</option>
                        @foreach (App\Models\Tenant\CompanyShareCapitalChange::$issuancepurposes as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="{{ $issuanceTerm == App\Models\Tenant\CompanyShareCapitalChange::ISSUANCETERM_FREETEXT ? '' : 'hidden' }}">
                    {{-- <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="issuanceTermFreetext">No. of Share</label> --}}
                    <input class="form-input" id="issuanceTermFreetext" type="text" wire:model="issuanceTermFreetext">
                </div>
                <div class="{{ $issuanceTerm == App\Models\Tenant\CompanyShareCapitalChange::ISSUANCETERM_FREETEXT ? '' : 'hidden' }}"></div>
                <div class="col-span-2">Fully Paid</div>
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="fullyPaidShares">No. of Share</label>
                    <input class="form-input numberInput" id="fullyPaidShares" type="text" wire:dirty.class="border-amber-500" wire:model.blur="fullyPaidShares">
                </div>
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="fullyPaidAmount">Amount</label>
                    <input class="form-input numberInput" id="fullyPaidAmount" type="text" wire:dirty.class="border-amber-500" wire:model.blur="fullyPaidAmount">
                </div>
                <div class="col-span-2">Partially Paid</div>
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="partiallyPaidShares">No. of Share</label>
                    <input class="form-input numberInput" id="partiallyPaidShares" type="text" wire:dirty.class="border-amber-500" wire:model.blur="partiallyPaidShares">
                </div>
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="partiallyPaidAmount">Amount</label>
                    <input class="form-input numberInput" id="partiallyPaidAmount" type="text" wire:dirty.class="border-amber-500" wire:model.blur="partiallyPaidAmount">
                </div>
                <div class="col-span-2">Total All Shares</div>
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="totalShares">No. of Share</label>
                    <input class="form-input numberInput" id="totalShares" type="text" wire:model="totalShares" disabled>
                </div>
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="totalAmount">Amount</label>
                    <input class="form-input numberInput" id="totalAmount" type="text" wire:model="totalAmount" disabled>
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
            <button class="text-white btn bg-primary" type="submit" wire:loading.attr="disabled">
                <div class="mr-2 inline-block h-4 w-4 animate-spin rounded-full border-[3px] border-current border-t-transparent text-white" role="status" aria-label="loading" wire:loading>
                    <span class="sr-only">Loading...</span>
                </div>
                <span wire:loading>Updating...</span>
                <span wire:loading.remove>Save</span>
            </button>

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
</script>
@endscript
