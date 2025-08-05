<div>
    <table class="min-w-full border border-gray-200 divide-y divide-gray-200 table-fixed dark:divide-gray-700 dark:border-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase max-w-[400px]" scope="col">Action</th>
                <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase max-w-[500px]" scope="col">Report Content</th>
                <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase max-w-[100px]" scope="col">Indentation Level</th>
                <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase max-w-[200px]" scope="col">Template Type</th>
                <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase max-w-[200px]" scope="col">Remark</th>
                {{-- <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase" scope="col">MBRS Mapping</th> --}}
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700" wire:sortable="updateOrder">
            @foreach ($items->sortBy('order') as $key => $item)
            <tr wire:sortable.item="{{ $item->id }}" wire:key="sap-{{ $item->id }}">
                <td class="py-4 text-sm font-medium text-gray-800  dark:text-gray-200 max-w-[400px]">
                    <button class="btn" type="button" wire:sortable.handle>
                        <i class="text-base mgc_transfer_4_fill"></i>
                    </button>
                    <button
                        class="btn"
                        type="button"
                        title="Add"
                        wire:click="$dispatch('openModal', {component: 'tenant.components.report-config.est-uncertainties-modal', arguments: { companyId: {{ $this->id }}, isUpdate: false, id: {{ $item->id }} }})"
                    >
                        <i class="text-base text-success mgc_add_fill"></i>
                    </button>
                    <button
                        class="btn {{ $item->is_default_content ? 'hidden' : '' }}"
                        type="button"
                        title="Edit"
                        wire:click="$dispatch('openModal', {component: 'tenant.components.report-config.est-uncertainties-modal', arguments: { companyId: {{ $this->id }}, isUpdate: true, id: {{ $item->id }} }})"
                    >
                        <i class="text-base text-info mgc_edit_3_line"></i>
                    </button>
                    <button
                        class="btn"
                        type="button"
                        title="Show in page"
                        wire:click="updateDisplay({{ $item->id }}, {{ $item->is_active ? 0 : 1 }})"
                    >
                        <i class="text-base {{ $item->is_active ? 'mgc_eye_2_line' : 'mgc_eye_close_line text-gray-300' }}"></i>
                        {{-- <i class="text-base text-gray-300 mgc_eye_close_line"></i> --}}
                    </button>
                    <button
                        class="btn {{ $item->is_default_content ? 'hidden' : '' }}"
                        type="button"
                        wire:click="deleteItem({{ $item->id }})"
                        wire:confirm="Are you sure you want to delete this?"
                    >
                        <i class="text-base text-danger mgc_delete_2_line"></i>
                    </button>
                </td>
                <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200 max-w-[500px] break-words overflow-auto"><pre class="text-wrap">{{ $item->content }}</pre></td>
                <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200 max-w-[100px]">{{ $item->position }}</td>
                <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200 max-w-[200px]">{{ $item->type }}</td>
                <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200 max-w-[200px]">{{ $item->remark }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
