<div class="mb-4 grid grid-cols-1 gap-6">
    <div class="">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h3 class="card-title">Manage Cosec Credits</h3>
                </div>
            </div>
            <div class="card-body">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">User Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Credit</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                    @if($editId === $user->id)
                                        <div class="space-y-2">
                                            <input type="number" min="0" step="1" wire:model="credit" class="form-input w-32" placeholder="Credits" />
                                            <input type="text" wire:model="adjustmentReason" class="form-input w-full text-xs" placeholder="Reason for adjustment (optional)" />
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2">
                                            RM {{ number_format($user->credit ?? 0, 0) }}
                                            <button wire:click="showCreditHistory({{ $user->id }})" class="btn border-info text-info hover:bg-info hover:text-info group" title="View Order History">
                                                <svg class="w-4 h-4 group-hover:text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-800 dark:text-gray-200">
                                    @if($editId === $user->id)
                                        <button wire:click="save" class="btn border-success text-success hover:bg-success hover:text-white mr-2">Save</button>
                                        <button wire:click="$set('editId', null)" class="btn border-danger text-danger hover:bg-danger hover:text-white">Cancel</button>
                                    @else
                                        <button wire:click="edit({{ $user->id }})" class="btn border-primary text-primary hover:bg-primary hover:text-white">Edit</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
