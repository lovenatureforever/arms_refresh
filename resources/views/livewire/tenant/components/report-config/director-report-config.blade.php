<div>
    @if ($errors->any())
        <div class="p-4 border border-red-200 rounded-md bg-red-50" role="alert">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="text-xl mgc_information_line"></i>
                </div>
                <div class="ms-4">
                    <h3 class="text-sm font-semibold text-red-800">
                        {{ $errors }}
                    </h3>
                </div>
            </div>
        </div>
    @endif

    <div class="flex items-center justify-between border-b px-4 py-2.5 dark:border-gray-700">
        <h3 class="text-lg font-medium text-gray-800 dark:text-white">
            Create / Update Report Config
        </h3>
        <button class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 dark:text-gray-200" wire:click.prevent="closeModal()">
            <span class="material-symbols-rounded">close</span>
        </button>
    </div>
    <form wire:submit="submit">
        <div class="px-4 py-8 overflow-y-auto">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="col-span-2">
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="reportContent">Report Content</label>
                    {{-- <input class="form-input" id="reportContent" type="text" wire:model="reportContent"> --}}
                    {{-- <livewire:quill-text-editor class="form-input" wire:model="reportContent" theme="bubble" /> --}}
                    <textarea class="form-input" id="reportContent" wire:model="reportContent" rows="5"></textarea>
                </div>
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="templateType">Template Type</label>
                    <select class="form-input" id="templateType" name="templateType" wire:model="templateType">
                        <option>--Select--</option>
                        <option value="Title">Title</option>
                        <option value="Paragraph">Paragraph</option>
                        <option value="Paragraph with bullet">Paragraph with bullet</option>
                        <option value="Paragraph with indent">Paragraph with indent</option>
                    </select>
                </div>
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="display">Display</label>
                    <select class="form-input" id="display" name="display" wire:model="display">
                        <option>--Select--</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="pageBreak">Page Break</label>
                    <select class="form-input" id="pageBreak" name="pageBreak" wire:model="pageBreak">
                        <option>--Select--</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
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
