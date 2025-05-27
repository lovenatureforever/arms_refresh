<div>
    @if ($errors->any())
    <div class="p-4 border border-red-200 rounded-md bg-red-50" role="alert">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="text-xl mgc_information_line"></i>
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
            Create / Update Registered Address
        </h3>
        <button class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 dark:text-gray-200" wire:click.prevent="closeModal()">
            <span class="material-symbols-rounded">close</span>
        </button>
    </div>
    <form wire:submit="submit">
        <div class="px-4 py-8 overflow-y-auto">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                @if (!$isStart)
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="changeNature">Nature Change</label>
                    <input class="form-input" id="changeNature" type="text" wire:model="changeNature" disabled>
                </div>
                @endif
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="country">Country</label>
                    <select class="form-input" id="country" name="country" wire:model="country">
                        <option value="Malaysia">Malaysia</option>
                        <option value="Outside Malaysia">Outside Malaysia</option>
                    </select>
                </div>
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="addressLine1">Address Line 1</label>
                    <input class="form-input" id="addressLine1" type="text" wire:model="addressLine1">
                </div>
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="addressLine2">Address Line 2</label>
                    <input class="form-input" id="addressLine2" type="text" wire:model="addressLine2">
                </div>
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="addressLine3">Address Line 3</label>
                    <input class="form-input" id="addressLine3" type="text" wire:model="addressLine3">
                </div>

                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="postcode">Postcode</label>
                    <input class="form-input" id="postcode" type="text" wire:model="postcode">
                </div>
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="town">Town</label>
                    <input class="form-input" id="town" type="text" wire:model="town">
                </div>
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="state">State</label>
                    {{-- <input class="form-input" id="state" type="text" wire:model="state"> --}}
                    <select class="form-select" id="state" wire:model="state">
                        <option value="">Select State</option>
                        @foreach (states() as $state)
                            <option value="{{ $state }}">{{ $state }}</option>
                        @endforeach
                    </select>
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
