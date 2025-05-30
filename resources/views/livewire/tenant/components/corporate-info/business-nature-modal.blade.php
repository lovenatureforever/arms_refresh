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
            Create / Update Business Nature
        </h3>
        <button class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 dark:text-gray-200" wire:click="$dispatch('closeModal')">
            <span class="material-symbols-rounded">close</span>
        </button>
    </div>
    <form wire:submit="submit">
        <div class="px-4 py-8 overflow-y-auto">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="paragraph1">Paragraph 1</label>
                    <input class="form-input" id="paragraph1" type="text" wire:model="paragraph1">
                </div>
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="paragraph2">Paragraph 2</label>
                    <input class="form-input" id="paragraph2" type="text" wire:model="paragraph2">
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
            <button type="reset" class="transition-all border btn border-slate-200 hover:bg-slate-100 dark:border-slate-700 dark:text-gray-200 hover:dark:bg-slate-700" wire:click="$dispatch('closeModal')">Close</button>
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
