<div class="border rounded-lg overflow-hidden dark:border-gray-700">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Company Name</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Registration No</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Year</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse ($companies as $company)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                        <a href="">
                            {{ $company->name }}
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $company->registration_no }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $company->current_year }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                        <div class="inline-flex items-center gap-1.5 py-1 px-3 rounded text-xs font-medium {{ $company->is_active ? 'bg-success/90' : 'bg-dark/80' }} text-white">{{ $company->is_active ? 'Active' : 'Inactive' }}</div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan=4 class="text-center">No companies found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
