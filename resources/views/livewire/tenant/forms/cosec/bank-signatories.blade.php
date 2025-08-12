<div>
    <h2 class="text-xl text-black text-center mb-4">Change of Bank Signatories</h2>
    <div class="text-gray">
        <div class="mb-4">
            <label class="mb-2 inline-block text-sm font-medium text-gray-800">Name Of Bank</label>
            <input type="text" class="form-input w-2/5" wire:model="nameOfBank" placeholder="Bank Name">
            @error('nameOfBank') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="mb-2 inline-block text-sm font-medium text-gray-800">Bank Account Number</label>
            <input type="text" class="form-input w-2/5" wire:model="numberOfBankAccount" placeholder="Bank Account No">
            @error('numberOfBankAccount') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="mb-2 inline-block text-sm font-medium text-gray-800">Branch</label>
            <input type="text" class="form-input w-2/5" wire:model="branch" placeholder="Branch">
            @error('branch') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <label class="mb-2 inline-block text-sm font-medium text-gray-800">
            New Signing Conditions
        </label>
        
        <div class="mb-6 px-6">
            <div class="mb-2">
                <label class="mb-2 inline-block text-sm font-medium text-gray-600">List of Authorised Person(s)</label>
                <select wire:model="lengthOfAuthorizedPersons" class="form-input w-1/3">
                    <option>Select an option</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>  
                @error('lengthOfAuthorizedPersons') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <label class="block mb-2 text-gray-600">
                <input type="radio" wire:model.live="authorizeCondition" value="anyone" x-data> ANY ONE of the above Authorised Person(s)
            </label>
            <label class="block mb-2 text-gray-600">
                <input type="radio" wire:model.live="authorizeCondition" value="all" x-data> ALL of the above Authorised Person(s)
            </label>            
            <label class="block mb-2 text-gray-600">
                <input type="radio" wire:model.live="authorizeCondition" value="limited" x-data> <input type="text" class="form-input w-[120px] inline-block" wire:model="limitedAuthorizedCondition" placeholder="Persons"> of the above Authorised Person(s)
            </label>
            @error('authorizeCondition') <span class="text-red-500">{{ $message }}</span> @enderror
            @error('limitedAuthorizedCondition') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

    </div>
</div>