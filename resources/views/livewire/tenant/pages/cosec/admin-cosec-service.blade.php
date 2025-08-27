<div class="mb-4 grid grid-cols-1 gap-6">
    <div class="">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h3 class="card-title">Manage Cosec Services</h3>
                </div>
            </div>
            <div class="card-body">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Service Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Cost</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($services as $service)
                            <tr>
                                @if($editId === $service->id)
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        <input type="text" wire:model="name" class="form-input" />
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        <input type="number" wire:model="cost" class="form-input" />
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        <button wire:click="save" class="btn border-success text-success hover:bg-success hover:text-white">Save</button>
                                        <button wire:click="$set('editId', null)" class="btn border-danger text-danger hover:bg-danger hover:text-white">Cancel</button>
                                    </td>
                                @else
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $service->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $service->cost }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        <button wire:click="edit({{ $service->id }})" class="btn border-primary text-primary hover:bg-primary hover:text-white">Edit</button>
                                        <button wire:click="delete({{ $service->id }})" class="btn border-danger text-danger hover:bg-danger hover:text-white">Delete</button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
