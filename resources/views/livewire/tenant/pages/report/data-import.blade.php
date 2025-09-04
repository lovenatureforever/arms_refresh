<div>
    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-2">
        <div class="lg:col-span-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Import Data</h6>
                </div>

                <form wire:submit="importData">
                    <div class="grid grid-cols-4 gap-6 p-6">
                        <div>
                            <p class="mb-3 text-sm font-medium underline uppercase">
                                Data Import
                            </p>
                            <input class="form-input" type="file" wire:model.live="importFile">
                            <div wire:loading wire:target="importFile">Validating file...</div>
                        </div>
                    </div>

                    <div class="px-6 pb-6" wire:loading.remove wire:target="importFile">
                        <button class="text-white rounded-lg btn bg-primary" type="submit">
                            <div class="mr-2 inline-block h-4 w-4 animate-spin rounded-full border-[3px] border-current border-t-transparent text-white" role="status" aria-label="loading" wire:loading>
                                <span class="sr-only">Loading...</span>
                            </div>
                            <span wire:loading>Importing...</span>
                            <span wire:loading.remove>Import Data</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h4 class="card-title">Report List</h4>
                </div>
                <div>
                    <div class="overflow-x-auto">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead>
                                        <tr>
                                            <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase" scope="col">Report ID</th>
                                            <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase" scope="col">Created At</th>
                                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-end" scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($company_reports as $report)
                                            <tr>
                                                <td class="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap dark:text-gray-200">{{ $report->id }}</td>
                                                <td class="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap dark:text-gray-200">{{ $report->created_at }}</td>
                                                <td class="px-6 py-4 font-medium text-gray-800 whitespace-nowrap text-end dark:text-gray-200">
                                                    @if ($report->file_path != null || $report->file_path != '')
                                                        <a class="text-white rounded-lg btn btn-sm bg-success" href="{{ route('downloadexcel', [$report->id]) }}" target="_blank">Download Excel</a>
                                                    @endif
                                                    <a class="text-white bg-orange-700 rounded-lg btn btn-sm" href="{{ route('datamigration.sofp', [$report->id]) }}" target="_blank">Data Migration</a>
                                                    <a class="text-white rounded-lg btn btn-sm bg-primary" href="{{ route('reportpdf', [$report->id]) }}" target="_blank">View Report</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
