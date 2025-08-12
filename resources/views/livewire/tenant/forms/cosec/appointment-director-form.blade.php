<div>
    <h2 class="text-xl text-black text-center mb-4">Appointment of Director Form</h2>
    <div class="text-gray">
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <label class="mb-2 inline-block text-sm font-medium text-gray-800">Name (As Per IC/Passport)</label>
                <input type="text" class="form-input" wire:model="name" placeholder="name">
                @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="mb-2 inline-block text-sm font-medium text-gray-800">ID / Passport Number</label>
                <input type="text" class="form-input" wire:model="passport" placeholder="Passport Number">
                @error('passport') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="mb-4">
            <label class="mb-2 inline-block text-sm font-medium text-gray-800">Residential Address</label>
            <input type="text" class="form-input" wire:model="address" placeholder="Address">
            @error('address') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <label class="mb-2 inline-block text-sm font-medium text-gray-800">Email</label>
                <input type="text" class="form-input" wire:model="email" placeholder="email">
                @error('email') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>                
                <label class="mb-2 inline-block text-sm font-medium text-gray-800">Phone No.</label>
                <input type="text" class="form-input" wire:model="phone" placeholder="Phone Number">
                @error('phone') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>
        <div>
            <label class="mb-2 inline-block text-sm font-medium text-gray-800">Upload IC / Passport</label>
            <div class="bg-[#D9D9D9] text-center p-8 text-white">
                Select or Draw Image /Document
            </div>
        </div>
    </div>
</div>