<div>
    <div class="flex items-center justify-between border-b px-4 py-2.5 dark:border-gray-700">
        <h3 class="text-lg font-medium text-gray-800 dark:text-white">
            {{ $id ? 'Edit Address' : 'Add New Address' }}
        </h3>
        <button class="inline-flex h-8 w-8 flex-shrink-0 items-center justify-center dark:text-gray-200" type="button" wire:click="$dispatch('closeModal')">
            <span class="material-symbols-rounded">close</span>
        </button>
    </div>
    <form wire:submit="submit">
        <div class="overflow-y-auto px-4 py-8">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="firmBranch">Firm Branch</label>
                    <input class="form-input" type="text" id="firmBranch" wire:model="firmBranch">
                </div>
                <div>
                    <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="address1">Address Line 1</label>
                    <input class="form-input" id="address1" type="text" wire:model="address1">
                </div>
                <div>
                    <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="address2">Address Line 2</label>
                    <input class="form-input" id="address2" type="text" wire:model="address2">
                </div>
                <div>
                    <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="address3">Address Line 3</label>
                    <input class="form-input" id="address3" type="text" wire:model="address3">
                </div>
                <div>
                    <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="postcode">Postcode</label>
                    <input class="form-input" id="postcode" type="text" wire:model="postcode">
                </div>

                <div>
                    <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="town">Town</label>
                    <input class="form-input" id="town" type="text" wire:model="town">
                </div>
                <div>
                    <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="state">State</label>
                    <select class="form-select" id="state" wire:model="state">
                        <option value="">Select State</option>
                        @foreach (states() as $state)
                            <option value="{{ $state }}">{{ $state }}</option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>
        <div class="flex items-center justify-end gap-4 border-t p-4 dark:border-slate-700">
            <div>
                <button class="btn bg-primary text-white" type="submit" wire:loading.attr="disabled">
                    <span>Save</span>
                </button>
            </div>
        </div>
    </form>
</div>
