<div class="grid grid-cols-1 gap-6">
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title">Audit Partner</h4>
                <div class="flex items-center gap-2">
                    <a class="btn bg-primary text-white" type="button" href="{{ route('auditpartners.create') }}">Create</a>
                </div>
            </div>
        </div>
        <div>
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">No.</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">User Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">License No.</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500 max-w-[400px]" scope="col">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Status</th>
                                    <th class="px-6 py-3 text-end text-xs font-medium uppercase text-gray-500" scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($auditors as $auditor)
                                    <tr>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200">{{ $loop->iteration }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200">{{ $auditor->user->name }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200">{{ $auditor->user->username }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200">{{ $auditor->selected_license->license_no }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200">{{ $auditor->title }}</td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200 max-w-[400px] break-words">
                                            @php
                                                $systemRoles = $auditor->user->roles()
                                                    ->where('name', 'like', 'internal%')->get()
                                                    ->map(function($role) {
                                                        return Str::substr(\App\Models\User::USER_ROLES[$role->name], 9);
                                                    });
                                                $isqmRoles = $auditor->user->roles()
                                                    ->where('name', 'like', 'isqm%')->get()
                                                    ->map(function($role) {
                                                        return Str::substr(\App\Models\User::USER_ROLES[$role->name], 5);
                                                    });
                                            @endphp
                                            <span class="font-bold">Internal:</span>
                                            {{ implode(', ', $systemRoles->toArray()) }}
                                            <br>
                                            <span class="font-bold">ISQM:</span>
                                            {{ implode(', ', $isqmRoles->toArray()) }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200">
                                            <div class="inline-flex items-center gap-1.5 py-1 px-3 rounded text-xs font-medium {{ $auditor->user->is_active ? 'bg-success/90' : 'bg-dark/80' }} text-white">{{ $auditor->user->is_active ? 'Active' : 'Inactive' }}</div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-end text-sm font-medium">
                                            <button type="button" class="btn border-primary text-primary hover:bg-primary hover:text-white" wire:click="showAuditor('{{ $auditor->id }}')">Show</button>
                                            <button type="button" class="btn border-danger text-danger hover:bg-danger hover:text-white" wire:click="confirmDelete('{{ $auditor->id }}')">Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-4 py-1">
                        <nav class="flex items-center space-x-2">
                            {{ $auditors->links() }}
                        </nav>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
