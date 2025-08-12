<div>
    <h2 class="text-xl text-black text-center mb-4">Open Bank Account</h2>
    <div class="text-gray">
        <div class="mb-4">
            <label class="mb-2 inline-block text-sm font-medium text-gray-800">Name Of Bank</label>
            <input type="text" class="form-input w-2/5" wire:model="nameOfBank" placeholder="Bank Name">
            @error('nameOfBank') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        
        <div class="mb-4">
            <label class="mb-2 inline-block text-sm font-medium text-gray-800">Branch</label>
            <input type="text" class="form-input w-2/5" wire:model="branch" placeholder="Branch">
            @error('branch') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        
        <div class="mb-4">
            <label class="mb-2 inline-block text-sm font-medium text-gray-800">Address</label>
            <input type="text" class="form-input w-2/5" wire:model="address" placeholder="Address">
            @error('address') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-6">
            <label class="mb-2 inline-block text-sm font-medium text-gray-800">Maker (Online Banking)</label>
            <div class="grid grid-cols-3 gap-6 mb-6 px-6">
                <div>
                    <label class="mb-2 inline-block text-sm font-medium text-gray-600">Name</label>
                    <input type="text" class="form-input" wire:model="makerName" placeholder="Name">
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

        <div class="mb-6">
            <label class="mb-2 inline-block text-sm font-medium text-gray-800">Checker (Online Banking)</label>
            <div class="grid grid-cols-3 gap-6 mb-6 px-6">
                <div>
                    <label class="mb-2 inline-block text-sm font-medium text-gray-600">Name</label>
                    <input type="text" class="form-input" wire:model="checkerName" placeholder="Name">
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

        <label class="mb-2 inline-block text-sm font-medium text-gray-800">
            Signing Conditions
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

        <div>
            <div class="mb-4">
                <label class="mb-2 inline-block text-sm font-medium text-gray-800">Shipping Method</label>
                <select wire:model="shippingMethod" class="form-input w-1/3">
                    <option value="0">Select an option</option>
                    <option value="1">By POS</option>
                    <option value="2">By Lalamove</option>
                </select>  
                @error('shippingMethod') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            
            <div class="px-6">
                <div class="mb-4">                
                    <label class="mb-2 inline-block text-sm font-medium text-gray-800">Shipping Address</label>
                    <input type="text" class="form-input" wire:model="shippingAddress" placeholder="Shipping Address">
                    @error('shippingAddress') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
                
                <div class="mb-4">                
                    <label class="mb-2 inline-block text-sm font-medium text-gray-800">PIC</label>
                    <input type="text" class="form-input" wire:model="shippingPic" placeholder="PIC">
                    @error('shippingPic') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">                
                    <label class="mb-2 inline-block text-sm font-medium text-gray-800">Contact No</label>
                    <input type="text" class="form-input" wire:model="shippingContactNumber" placeholder="Contact No">
                    @error('shippingContactNumber') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

    </div>
</div>