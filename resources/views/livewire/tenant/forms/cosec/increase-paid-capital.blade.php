<div>
    <h2 class="text-xl text-black text-center mb-4">Increase of Paid Up Capital</h2>
    <div class="text-gray mt-2">
        <div class="mb-4">
            <label class="mb-2 inline-block text-sm font-medium text-gray-800">Increase Of Share Capital By:</label>
            <select wire:model="increasedCapitalBy" class="form-input w-1/3">
                <option>Select an option</option>
                <option value="1">By Cash</option>
                <option value="2">By Otherwise</option>
            </select>
            @error('increasedCapitalBy') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        
        <div class="mb-4">
            <label class="mb-2 inline-block text-sm font-medium text-gray-800">Unit of share to increase:</label>
            <input type="text" class="form-input w-2/5" wire:model="unitOfShareToIncrease">
            @error('unitOfShareToIncrease') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="mb-2 inline-block text-sm font-medium text-gray-800">Price per share:</label>
            <input type="text" class="form-input w-2/5" wire:model="pricePerShare">
            @error('pricePerShare') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="mb-2 inline-block text-sm font-medium text-gray-800">Total amount to increase:</label>
            <input type="text" class="form-input w-2/5" wire:model="totalAmountToIncrease"> 
            @error('totalAmountToIncrease') <span class="text-red-500">{{ $message }}</span> @enderror   
        </div>
        
        <div class="mb-4">
            <div class="mb-4 text-sm font-medium text-gray-800">Allottes' Information:</div>
            
            @foreach($allottees as $index => $allottee)
            <div class="mb-4 px-2 text-sm font-medium text-gray-800">
                Allotte {{ $loop->iteration }}
            </div>

            <div class="px-6 mb-8">
                <div class="mb-4">
                    <div class="mb-2">Allotee is existing shareholder?</div>
                    <div>
                        <label class="mr-4">
                            <input type="radio" wire:model.live="allottees.{{ $index }}.allotteeExisted" value="true" x-data> Yes
                        </label>
                        <label>
                            <input type="radio" wire:model.live="allottees.{{ $index }}.allotteeExisted" value="false" x-data> No
                        </label>
                        @error("allottees.$index.allotteeExisted") <span class="text-red-500">{{ $message }}</span> @enderror   
                    </div>
                </div>

                <div x-data="{ allotteeExisted: @entangle('allottees.' . $index . '.allotteeExisted') }">
                    <div x-show="allotteeExisted == 'false'">
                        <div class="mb-2">
                            Register New Allottee
                        </div>
                        <div class="grid grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="mb-2 inline-block text-sm font-medium text-gray-800">Name / Company Name</label>
                                <input type="text" class="form-input" wire:model="allottees.{{ $index }}.name">
                                @error("allottees.$index.name") <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="mb-2 inline-block text-sm font-medium text-gray-800">ID / Passport Number</label>
                                <input type="text" class="form-input" wire:model="allottees.{{ $index }}.passport">
                                @error("allottees.$index.passport") <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="mb-2 inline-block text-sm font-medium text-gray-800">Residential Address</label>
                            <input type="text" class="form-input" wire:model="allottees.{{ $index }}.address">
                            @error("allottees.$index.address") <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="mb-2 inline-block text-sm font-medium text-gray-800">Email</label>
                                <input type="text" class="form-input" wire:model="allottees.{{ $index }}.email">
                                @error("allottees.$index.email") <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="mb-2 inline-block text-sm font-medium text-gray-800">Phone Number</label>
                                <input type="text" class="form-input" wire:model="allottees.{{ $index }}.phone">
                                @error("allottees.$index.phone") <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div x-show="allotteeExisted == 'true'">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr class="bg-gray-50 dark:bg-gray-700">
                                    <th class="p-3.5 text-sm text-start text-black font-semibold min-w-[10rem]">
                                        
                                    </th>
                                    <th class="p-3.5 text-sm text-start text-black font-semibold min-w-[10rem]">
                                        Name
                                    </th>
                                    <th class="p-3.5 text-sm text-start text-black font-semibold min-w-[10rem]">
                                        ID / Passport Number
                                    </th>
                                    <th class="p-3.5 text-sm text-start text-black font-semibold min-w-[10rem]">
                                        Current No Of Share
                                    </th>
                                    <th class="p-3.5 text-sm text-start text-black font-semibold min-w-[10rem]">
                                        
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shareholders as $shareholder)
                                <tr>
                                    <td class="p-3.5 text-sm text-gray-700 dark:text-gray-400">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="p-3.5 text-sm text-gray-700 dark:text-gray-400">
                                        {{ $shareholder->title }}
                                    </td>
                                    <td class="p-3.5 text-sm text-gray-700 dark:text-gray-400">
                                        {{ $shareholder-> id_no . " (" . $shareholder->id_type .") " }}
                                    </td>
                                    <td class="p-3.5 text-sm text-gray-700 dark:text-gray-400">
                                        {{ $shareholder->no_of_share }}
                                    </td>
                                    <td class="p-3.5 text-sm text-gray-700 dark:text-gray-400 text-center">
                                        <input type="radio" wire:model="allottees.{{ $index }}.shareholder" value="{{ $shareholder->id }}" />
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @error("allottees.$index.shareholder") <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="mb-2 inline-block text-sm font-medium text-gray-800">Unit share to allot:</label>
                    <input type="text" class="form-input w-2/5" wire:model="allottees.{{ $index }}.unitShareToAllot">    
                    @error("allottees.$index.unitShareToAllot") <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="mb-2 inline-block text-sm font-medium text-gray-800">Amount paid:</label>
                    <input type="text" class="form-input w-2/5" wire:model="allottees.{{ $index }}.amountPaid">
                    @error("allottees.$index.amountPaid") <span class="text-red-500">{{ $message }}</span> @enderror    
                </div>
            </div>
            @endforeach
            @error('allottees') <span class="text-red-500">{{ $message }}</span> @enderror   

            <button wire:click="addMoreAllotee" class="btn bg-success text-white mb-8">
                Add more Allotee
            </button>

        </div>
    </div>
</div>