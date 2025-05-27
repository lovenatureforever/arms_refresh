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
            Create / Update Company Name
        </h3>
        <button class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 dark:text-gray-200" wire:click="$dispatch('closeModal')">
            <span class="material-symbols-rounded">close</span>
        </button>
    </div>
    <form wire:submit="submit">
        <div class="px-4 py-8 overflow-y-auto">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                @if (!$isStart)
                    <div>
                        <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="changeNature">Nature of Change</label>
                        <input class="form-input" id="changeNature" type="text" wire:model="changeNature" disabled>
                    </div>
                @endif
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="companyName">Company Name</label>
                    <input class="form-input" id="companyName" type="text" wire:model="companyName">
                </div>
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="companyType">Company Type</label>
                    <select class="form-input" id="companyType" name="companyType" wire:model="companyType">
                        <option>Select company type</option>
                        <option value="Private limited liability company">Private limited liability company</option>
                        <option value="Public limited liability company">Public limited liability company</option>
                    </select>
                </div>
                <div class="col-span-2">Presentation Currency</div>
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="presentationCurrency">Report</label>
                    <input class="form-input" id="presentationCurrency" type="text" wire:model="presentationCurrency">
                </div>
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="presentationCurrencyCode">Report (Code)</label>
                    <input class="form-input" id="presentationCurrencyCode" type="text" wire:model="presentationCurrencyCode">
                </div>

                <div class="col-span-2">Functional Currency</div>
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="functionalCurrency">Report</label>
                    <input class="form-input" id="functionalCurrency" type="text" wire:model="functionalCurrency">
                </div>
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="functionalCurrencyCode">Report (Code)</label>
                    <input class="form-input" id="functionalCurrencyCode" type="text" wire:model="functionalCurrencyCode">
                </div>

                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="domicile">Domicile</label>
                    <input class="form-input" id="domicile" type="text" wire:model="domicile">
                </div>

                @if (!$isStart)
                    <div>
                        <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="effectiveDate">Effective Date</label>
                        <input class="form-input" id="effectiveDate" type="date" wire:model="effectiveDate">
                    </div>
                @endif
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="remarks">Remarks</label>
                    <input class="form-input" id="remarks" type="text" wire:model="remarks">
                </div>
            </div>
        </div>
        <div class="flex items-center justify-end gap-4 p-4 border-t dark:border-slate-700">
            <a class="transition-all border cursor-pointer btn border-slate-200 hover:bg-slate-100 dark:border-slate-700 dark:text-gray-200 hover:dark:bg-slate-700" wire:click="$dispatch('closeModal')">Close</a>
            <div wire:dirty>
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
