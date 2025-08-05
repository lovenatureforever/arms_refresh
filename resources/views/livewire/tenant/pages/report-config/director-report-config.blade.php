<div>
    <div class="mt-0">
        {{-- <h1 class="mb-4 text-2xl">Report Config</h1> --}}
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between m-4">
                    <h4 class="card-title">Report Configuration</h4>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 gap-5" data-fc-type="tab">
                    <nav class="flex-wrap rounded md:flex-col" role="tablist" aria-label="Tabs">
                        <a href="#" class="btn border border-gray-100 mr-2 mb-2 bg-primary text-white">
                            Director's Report
                        </a>
                        <a href="{{ route('reportconfig.ntfs', ['id' => $id]) }}" class="btn border border-gray-100 mr-2 mb-2 bg-transparent">
                            NTFS
                        </a>
                    </nav>
                    <div class="col-span-4 p-4 mb-2 mr-2 border border-gray-100 rounded">
                        <div role="tabpanel">
                            <div>
                                <div class="overflow-x-auto">
                                    <div class="inline-block min-w-full align-middle">
                                        <div class="overflow-auto">
                                            <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-700">
                                                <thead class="bg-gray-50 dark:bg-gray-700">
                                                    <tr>
                                                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase max-w-[400px]" scope="col">Action</th>
                                                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase max-w-[400px]" scope="col">Report Content</th>
                                                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase" scope="col">Template Type</th>
                                                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase" scope="col">Remarks</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700" wire:sortable="updateReportOrder">
                                                    @foreach ($reportConfigs->sortBy('order_no') as $key => $item)
                                                    <tr wire:sortable.item="{{ $item->id }}" wire:key="report-{{ $item->id }}">
                                                        <td class="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap dark:text-gray-200 max-w-[400px]">
                                                            <button class="btn" type="button" wire:sortable.handle>
                                                                <i class="text-base mgc_transfer_4_fill"></i>
                                                            </button>
                                                            <button
                                                                class="btn"
                                                                type="button"
                                                                title="Add"
                                                                wire:click="$dispatch('openModal', {component: 'tenant.components.report-config.director-report-config', arguments: { companyId: {{ $this->id }}, isUpdate: false, id: {{ $item->id }} }})"
                                                            >
                                                                <i class="text-base text-success mgc_add_fill"></i>
                                                            </button>
                                                            <button
                                                                class="btn"
                                                                type="button"
                                                                title="Edit"
                                                                wire:click="$dispatch('openModal', {component: 'tenant.components.report-config.director-report-config', arguments: { companyId: {{ $this->id }}, isUpdate: true, id: {{ $item->id }} }})"
                                                            >
                                                                <i class="text-base text-info mgc_edit_3_line"></i>
                                                            </button>
                                                            <button
                                                                class="btn"
                                                                type="button"
                                                                title="Page-break"
                                                                wire:click="updatePageBreak({{ $item->id }}, {{ $item->page_break == 1 ? 0 : 1 }})"
                                                            >
                                                                <i class="text-base {{ $item->page_break == 1 ? '' : 'text-gray-300' }} mgc_spacing_vertical_line"></i>
                                                            </button>
                                                            <button
                                                                class="btn"
                                                                type="button"
                                                                title="Show in page"
                                                                wire:click="updateDisplay({{ $item->id }}, {{ $item->display == 1 ? 0 : 1 }})"
                                                            >
                                                                <i class="text-base {{ $item->display == 1 ? 'mgc_eye_2_line' : 'mgc_eye_close_line text-gray-300' }}"></i>
                                                                {{-- <i class="text-base text-gray-300 mgc_eye_close_line"></i> --}}
                                                            </button>
                                                            <button
                                                                class="btn {{ $item->is_deleteable == 0 ? 'hidden' : '' }}"
                                                                type="button"
                                                                wire:click="deleteItem({{ $item->id }})"
                                                                wire:confirm="Are you sure you want to delete this?"
                                                            >
                                                                <i class="text-base text-danger mgc_delete_2_line"></i>
                                                            </button>
                                                        </td>
                                                        {{-- <td class="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap dark:text-gray-200">{!! Str::limit($item->report_content, '40') !!}</td> --}}
                                                        <td class="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap dark:text-gray-200 max-w-[400px] break-words overflow-auto"><pre class="text-wrap">{{ $item->report_content }}</pre></td>
                                                        <td class="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap dark:text-gray-200">{{ $item->template_type }}</td>
                                                        {{-- <td class="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap dark:text-gray-200">{{ $item->display == 1 ? 'Yes' : 'No' }}</td> --}}
                                                        {{-- <td class="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap dark:text-gray-200">{{ $item->page_break == 1 ? 'Yes' : 'No' }}</td> --}}
                                                        <td class="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap dark:text-gray-200">{{ $item->remarks }}</td>
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
        </div>
    </div>
</div>
