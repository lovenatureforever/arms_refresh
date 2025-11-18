<div class="grid grid-cols-1 gap-6">
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title">Users</h4>
                <div class="flex items-center gap-2">
                    <a class="btn bg-primary text-white" type="button" href="{{ route('users.create') }}">Create</a>
                </div>
            </div>
        </div>
        <div>
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-x divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">No.</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">User Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Username</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500 max-w-[400px]" scope="col">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Credit</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium uppercase text-gray-500" scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200">{{ $loop->iteration }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200">{{ $user->name }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200">{{ $user->email }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200">{{ $user->username }}</td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200 max-w-[400px] break-words">
                                            @php
                                                $systemRoles = $user->roles()
                                                    ->where('name', 'like', 'internal%')->get()
                                                    ->map(function($role) {
                                                        return Str::substr(\App\Models\User::USER_ROLES[$role->name], 9);
                                                    });
                                                $outerRoles = $user->roles()
                                                    ->where('name', 'like', 'outsider%')->get()
                                                    ->map(function($role) {
                                                        return Str::substr(\App\Models\User::USER_ROLES[$role->name], 9);
                                                    });
                                                $isqmRoles = $user->roles()
                                                    ->where('name', 'like', 'isqm%')->get()
                                                    ->map(function($role) {
                                                        return Str::substr(\App\Models\User::USER_ROLES[$role->name], 5);
                                                    });
                                            @endphp
                                            <span class="font-bold">Internal:</span>
                                            {{ implode(', ', $systemRoles->toArray()) }}
                                            <br>
                                            <span class="font-bold">Outsider:</span>
                                            {{ implode(', ', $outerRoles->toArray()) }}
                                            <br>
                                            <span class="font-bold">ISQM:</span>
                                            {{ implode(', ', $isqmRoles->toArray()) }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200">
                                            <div class="flex items-center gap-2">
                                                ${{ number_format($user->credit ?? 0, 2) }}
                                                <button type="button" class="btn border-info text-info hover:bg-info hover:text-info group" wire:click="showCreditHistory('{{ $user->id }}')" title="View Order History">
                                                    <svg class="w-4 h-4 group-hover:text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200"><div class="inline-flex items-center gap-1.5 py-1 px-3 rounded text-xs font-medium {{ $user->is_active ? 'bg-success/90' : 'bg-dark/80' }} text-white">{{ $user->is_active ? 'Active' : 'Inactive' }}</div></td>
                                        <td class="whitespace-nowrap px-6 py-4 text-center text-sm font-medium">
                                            <button type="button" class="btn border-primary text-primary hover:bg-primary hover:text-white mr-1" wire:click="showUser('{{ $user->id }}')">Show</button>
                                            <button type="button" class="btn border-danger text-danger hover:bg-danger hover:text-white" wire:click="deleteUser('{{ $user->id }}')">Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-4 py-1">
                        <nav class="flex items-center space-x-2">
                            {{ $users->links() }}
                        </nav>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
