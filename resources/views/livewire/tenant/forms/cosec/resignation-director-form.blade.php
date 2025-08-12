<div>
    <h2 class="text-xl text-black text-center mb-4">Resignation of Director Form</h2>
    <label>List Of Current Directors</label>
    <div class="text-gray mt-2">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
            <thead class="text-xs uppercase bg-gray">
                <tr class="bg-gray-50 dark:bg-gray-700">
                    <th class="p-3.5 text-sm text-start text-black font-semibold min-w-[10rem]">
                        
                    </th>
                    <th class="p-3.5 text-sm text-start text-black font-semibold min-w-[10rem]">
                        Name
                    </th>
                    <th class="p-3.5 text-sm text-start text-black font-semibold min-w-[10rem]">
                        ID / Passport Number
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                @foreach ($directors as $director)
                <tr class="bg-white hover:bg-gray-50">
                    <td class="p-3.5 text-sm text-gray-700 dark:text-gray-400">
                        {{ $loop->iteration }}
                    </td>
                    <td class="p-3.5 text-sm text-gray-700 dark:text-gray-400">
                        {{ $director->name }}
                    </td>
                    <td class="p-3.5 text-sm text-gray-700 dark:text-gray-400">
                        {{ $director->id_no . " (" . $director->id_type .") " }}
                    </td>
                    <td class="p-3.5 text-sm text-gray-700 dark:text-gray-400 text-center">
                        <input type="radio" wire:model="resignedDirector" value="{{ $director->id }}" />
                    </td>
                </tr>
                @endforeach
                @error('resignedDirector') <span class="text-red-500">{{ $message }}</span> @enderror                
            </tbody>
        </table>
    </div>
</div>