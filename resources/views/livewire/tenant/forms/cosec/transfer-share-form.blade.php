<div>
    <h2 class="text-xl text-black text-center mb-4">Transfer Share Form</h2>
    <div class="mt-2">
        <div class="mb-6">
            <div class="mb-2">Transferee is existing shareholder?</div>
            <div>
                <label class="mr-4">
                    <input type="radio" name="transfereeExisted" wire:model.live="transfereeExisted" value="true" x-data> Yes
                </label>
                <label>
                    <input type="radio" name="transfereeExisted" wire:model.live="transfereeExisted" value="false" x-data> No
                </label>
                @error('transfereeExisted') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="mb-6">
            <div class="mb-2">Select Transferror</div>
            <div>
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
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
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
                                <input type="radio" name="transferror" wire:model="transferror" value="{{ $shareholder->id }}" />
                            </td>
                        </tr>
                        @endforeach                                           
                    </tbody>
                </table>
                @error('transferror') <span class="text-red-500">{{ $message }}</span> @enderror  
            </div>
        </div>

        <div x-data>
            <div class="mb-6" x-show="$wire.transfereeExisted == 'true'">
                <div class="mb-2">Select Transferee</div>
                <div>
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
                                    <input type="radio" name="transferror" wire:model="transferee" />
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @error('transferee') <span class="text-red-500">{{ $message }}</span> @enderror 
                </div>
            </div>

            <div class="mb-6" x-show="$wire.transfereeExisted == 'false'">
                <div class="mb-2">Register New Transferee</div>
                <div class="p-4">
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="mb-2 inline-block text-sm font-medium text-gray-800">Name (As Per IC/Passport)</label>
                            <input type="text" class="form-input" wire:model="name">
                            @error('name') <span class="text-red-500">{{ $message }}</span> @enderror  
                        </div>
                        <div>
                            
                            <label class="mb-2 inline-block text-sm font-medium text-gray-800">ID / Passport Number</label>
                            <input type="text" class="form-input" wire:model="passport">
                            @error('passport') <span class="text-red-500">{{ $message }}</span> @enderror  
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800">Residential Address</label>
                        <input type="text" class="form-input" wire:model="address">
                        @error('address') <span class="text-red-500">{{ $message }}</span> @enderror  
                    </div>
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="mb-2 inline-block text-sm font-medium text-gray-800">Email</label>
                            <input type="text" class="form-input" wire:model="email">
                            @error('email') <span class="text-red-500">{{ $message }}</span> @enderror  
                        </div>
                        <div>                            
                            <label class="mb-2 inline-block text-sm font-medium text-gray-800">Phone No.</label>
                            <input type="text" class="form-input" wire:model="phone">
                            @error('phone') <span class="text-red-500">{{ $message }}</span> @enderror  
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-6">
            <div class="mb-2">Shares To Transfer (Unit)</div>
            <input type="text" class="form-input w-2/5" wire:model="sharesToTransfer">
            @error('sharesToTransfer') <span class="text-red-500">{{ $message }}</span> @enderror                                                                          
        </div>
        <div class="mb-6">
            <div class="mb-2">Amount Consideration</div>
            <input type="text" class="form-input w-2/5" wire:model="amountConsideration">
            @error('amountConsideration') <span class="text-red-500">{{ $message }}</span> @enderror                                                                        

        </div>   
    </div>
</div>