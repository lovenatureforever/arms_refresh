<div>
    <h2 class="text-xl text-black text-center mb-4">Change of Maker or Checker</h2>
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

        <label class="mb-2 inline-block text-sm font-medium text-gray-800">Change Maker or Checker</label>
        <div class="mb-6 px-6">
            <label class="text-gray-600 mr-4">
                <input type="radio" wire:model.live="makerOrChecker" value="maker" x-data> Maker
            </label>
            <label class="text-gray-600">
                <input type="radio" wire:model.live="makerOrChecker" value="checker" x-data> Checker
            </label> 
            @error('makerOrChecker') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <label class="mb-2 inline-block text-sm font-medium text-gray-800">Change Method</label>
        <div class=" mb-6 px-6">
            <select wire:model="changeMethod" class="form-input w-1/3">
                <option>Select an option</option>
                <option value="add">Add</option>
                <option value="remove">Remove</option>
                <option value="remain">Remain</option>
            </select> 
            @error('changeMethod') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div x-show="$wire.changeMethod == 'add'">
            <div class="mb-6" x-show="$wire.makerOrChecker == 'maker'">
                <label class="mb-2 inline-block text-sm font-medium text-gray-800">New Maker Information</label>
                <div class="grid grid-cols-3 gap-6 mb-6 px-6">
                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-600">Name</label>
                        <input type="text" class="form-input" wire:model="makerName" placeholder="name">
                        @error('makerName') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>                
                        <label class="mb-2 inline-block text-sm font-medium text-gray-600">IC No.</label>
                        <input type="text" class="form-input" wire:model="makerPassport" placeholder="IC/Passport">
                        @error('makerPassport') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>                
                        <label class="mb-2 inline-block text-sm font-medium text-gray-600">Designation</label>
                        <input type="text" class="form-input" wire:model="makerDesignation" placeholder="Designation">
                        @error('makerDesignation') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="mb-6" x-show="$wire.makerOrChecker == 'checker'">
                <label class="mb-2 inline-block text-sm font-medium text-gray-800">New Checker Information</label>
                <div class="grid grid-cols-3 gap-6 mb-6 px-6">
                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-600">Name</label>
                        <input type="text" class="form-input" wire:model="checkerName" placeholder="name">
                        @error('checkerName') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>                
                        <label class="mb-2 inline-block text-sm font-medium text-gray-600">IC No.</label>
                        <input type="text" class="form-input" wire:model="checkerPassport" placeholder="IC/Passport">
                        @error('checkerPassport') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>                
                        <label class="mb-2 inline-block text-sm font-medium text-gray-600">Designation</label>
                        <input type="text" class="form-input" wire:model="checkerDesignation" placeholder="Designation">
                        @error('checkerDesignation') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-6" x-show="$wire.changeMethod == 'remove'">
            <input type="text" class="form-input w-2/5" wire:model="removedName" placeholder="Removed Person">
        </div>

    </div>
</div>