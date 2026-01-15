<div class="grid grid-cols-1 gap-6">
    <!-- Filters -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Filter Companies</h4>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Year End</label>
                    <select wire:model.live="selectedYearEnd" class="form-select">
                        <option value="">Select Year End</option>
                        @foreach($yearEndOptions as $yearEnd)
                            <option value="{{ $yearEnd }}">{{ \Carbon\Carbon::parse($yearEnd)->format('d M') }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Form Type</label>
                    <select wire:model.live="selectedFormType" class="form-select">
                        <option value="cp204">CP204</option>
                        <option value="cp204a">CP204A</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Company List -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title">Companies</h4>
                <div class="flex items-center gap-2">
                    <button wire:click="confirmGeneration" class="btn bg-info text-white" type="button">
                        <i class="mgc_notification_line mr-1"></i> Generate Reminders
                    </button>
                </div>
            </div>
        </div>
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <input type="text" wire:model.live.debounce.300ms="searchName" class="form-input" placeholder="Search by Company Name...">
                </div>
                <div>
                    <input type="text" wire:model.live.debounce.300ms="searchGroup" class="form-input" placeholder="Search by Company Group...">
                </div>
                <div>
                    <input type="text" wire:model.live.debounce.300ms="searchRegNo" class="form-input" placeholder="Search by Registration No...">
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
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">
                                        <input type="checkbox"
                                               class="form-checkbox"
                                               wire:click="toggleSelectAll"
                                               @if(count(array_intersect($selectedCompanies, $companies->pluck('id')->toArray())) === $companies->count() && $companies->count() > 0)
                                                   checked
                                               @elseif(count(array_intersect($selectedCompanies, $companies->pluck('id')->toArray())) > 0)
                                                   data-indeterminate="true"
                                               @endif>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Company Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Registration No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Tax File No. C</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500" scope="col">Year End</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($companies as $company)
                                    <tr>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                            <input type="checkbox" class="form-checkbox" value="{{ $company->id }}" wire:model="selectedCompanies">
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200">{{ $company->name }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-800 dark:text-gray-200">{{ $company->registration_no }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-800 dark:text-gray-200">{{ $company->tax_file_no }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-800 dark:text-gray-200">{{ is_string($company->year_end) ? \Carbon\Carbon::parse($company->year_end)->format('d M') : $company->year_end?->format('d M') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No companies found for the selected year end.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if($companies->hasPages())
        <div class="p-4">
            {{ $companies->links() }}
        </div>
        @endif
    </div>

    <!-- Confirmation Modal -->
    @if($showConfirmModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="cancelGeneration"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-info/10 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="mgc_notification_line text-info text-xl"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                Confirm Reminder Generation
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $confirmationMessage }}
                                </p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                                    @if($selectedFormType === 'cp204')
                                        This will create CP204 initial submission reminders for all selected companies.
                                    @else
                                        This will create CP204A revision reminders for all selected companies.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 flex justify-end gap-3">
                    <button type="button" wire:click="cancelGeneration" class="btn bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancel
                    </button>
                    <button type="button" wire:click="generateReminders" class="btn bg-info text-white hover:bg-info/90">
                        Generate Reminders
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

@script
<script>
    // Set indeterminate state on checkboxes
    document.addEventListener('livewire:updated', () => {
        document.querySelectorAll('[data-indeterminate="true"]').forEach(checkbox => {
            checkbox.indeterminate = true;
        });
    });

    // Initial load
    document.querySelectorAll('[data-indeterminate="true"]').forEach(checkbox => {
        checkbox.indeterminate = true;
    });
</script>
@endscript
