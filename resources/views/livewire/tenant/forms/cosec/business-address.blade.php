<div>
    <h2 class="text-xl text-black text-center mb-4">Change or New Business Address</h2>
    <div class="text-gray mt-2">
        <div class="mb-4">
            <label class="mb-2 inline-block text-sm font-medium text-gray-800">Change/New business address:</label>
            <div class="mb-6 px-4">
                <label class="mr-4">
                    <input type="radio" wire:model.live="changeOrNew" value="change" x-data> Change
                </label>
                <label>
                    <input type="radio" wire:model.live="changeOrNew" value="new" x-data> New
                </label>
                @error('changeOrNew')<span class="text-red-500">{{ $message }}</span>@enderror
            </div>

            <div class="mb-6">
                <label class="mb-2 inline-block text-sm font-medium text-gray-800">Full Business Address:</label>
                <input type="text" class="form-input w-2/5" wire:model="fullAddress">
                @error('fullAddress')<span class="text-red-500">{{ $message }}</span>@enderror    
            </div>

            <div class="mb-6">
                <label class="mb-2 inline-block text-sm font-medium text-gray-800">Effective Date of Change:</label>
                <input type="text" class="form-input datepicker w-2/5" wire:model="effectiveDate">
                @error('effectiveDate')<span class="text-red-500">{{ $message }}</span>@enderror    
            </div>

            <div class="mb-6">
                <label class="mb-2 inline-block text-sm font-medium text-gray-800">Need Express Filling:</label>
                <div class="mb-2">
                    <label class="mr-4">
                        <input type="radio" wire:model.live="needExpressFilling" value="true" x-data> Yes
                    </label>
                    <label>
                        <input type="radio" wire:model.live="needExpressFilling" value="false" x-data> No
                    </label>
                </div>
                @error('needExpressFilling')<span class="text-red-500">{{ $message }}</span>@enderror
                <div>
                    Normal Filling: 1 month, Express Filling: 3 working days. Express Filling Fee: RM100
                </div>   
            </div>
        </div>
    </div>
</div>