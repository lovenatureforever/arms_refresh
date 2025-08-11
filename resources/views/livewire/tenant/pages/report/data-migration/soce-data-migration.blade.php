
<div>
    <form wire:submit="save" wire:confirm="Are you sure?">
    <div class="lg:col-span-4 sticky top-[70px]">
        <div class="card">
            <div class="flex items-center justify-between card-header">
                <h6 class="card-title">Data Migration</h6>
                <div class="self-end">
                    <button class="text-white btn btn-sm bg-info" type="submit">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 border-b border-gray-200">
        <nav class="flex space-x-4" aria-label="Tabs">
            <a href="{{ route('datamigration.sofp', ['id' => $id]) }}" class="px-3 py-2 font-medium text-sm rounded-t-md {{ ($activeTab ?? '') === 'sofp' ? 'bg-white border-l border-t border-r text-blue-600' : 'text-gray-500 hover:text-blue-600' }}">SOFP</a>
            <a href="{{ route('datamigration.soci', ['id' => $id]) }}" class="px-3 py-2 font-medium text-sm rounded-t-md {{ ($activeTab ?? '') === 'soci' ? 'bg-white border-l border-t border-r text-blue-600' : 'text-gray-500 hover:text-blue-600' }}">SOCI</a>
            <a href="{{ route('datamigration.soce', ['id' => $id]) }}" class="px-3 py-2 font-medium text-sm rounded-t-md {{ ($activeTab ?? 'soce') === 'soce' ? 'bg-white border-l border-t border-r text-blue-600' : 'text-gray-500 hover:text-blue-600' }}">SOCE</a>
            <a href="{{ route('datamigration.socf', ['id' => $id]) }}" class="px-3 py-2 font-medium text-sm rounded-t-md {{ ($activeTab ?? '') === 'socf' ? 'bg-white border-l border-t border-r text-blue-600' : 'text-gray-500 hover:text-blue-600' }}">SOCF</a>
            <a href="{{ route('datamigration.stsoo', ['id' => $id]) }}" class="px-3 py-2 font-medium text-sm rounded-t-md {{ ($activeTab ?? '') === 'stsoo' ? 'bg-white border-l border-t border-r text-blue-600' : 'text-gray-500 hover:text-blue-600' }}">STSOO</a>
            <a href="{{ route('datamigration.ntfs', ['id' => $id]) }}" class="px-3 py-2 font-medium text-sm rounded-t-md {{ ($activeTab ?? '') === 'ntfs' ? 'bg-white border-l border-t border-r text-blue-600' : 'text-gray-500 hover:text-blue-600' }}">NTFS</a>
        </nav>
    </div>

    <div class="">
        <div class="card">
            <div class="card-header">
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden">
                            <div class="flex justify-end mb-2">
                                <button
                                    class="text-white btn btn-sm bg-info"
                                    wire:click.prevent="$dispatch('openModal', {component: 'tenants.modal.data-migrations.create-col-row-modal', arguments: { company_report_id : {{ $id }} }})"
                                    wire:loading.attr="disabled"
                                >
                                    Add
                                </button>
                            </div>
                            <table class="w-full text-sm text-left text-gray-500 border border-collapse table-auto border-slate-300">
                                <thead class="text-xs text-gray-700 bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-4" style="min-width: 150px"></th>
                                        @foreach ($this->soce_cols??[] as $item)
                                        <th wire:key='{{ $item->id }}' class="px-4 py-4">{{ $item->name }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($this->soce_rows??[] as $row)
                                    <tr wire:key='{{ $row->id }}'>
                                        <td class="px-4 text-right align-middle">
                                            <span class="">{{ $row->name }}</span>
                                        </td>
                                        @foreach ($this->soce_cols??[] as $col)
                                        <td wire:key='{{ $col->id }}' class="px-4 py-3 text-right align-middle">
                                            <input type="text" {{ $col->data_type === "number" ? "pattern=^\(?\d{1,3}(,\d{3})*(\.\d+)?\)?$" : '' }} wire:model.blur='soce_items.{{ $row->id }}.{{ $col->id }}' class="numberInput block px-3 py-2 text-right bg-white border rounded-md shadow-sm border-slate-200 placeholder-slate-400 focus:outline-none focus:border-slate-300 focus:ring-slate-300 sm:text-sm focus:ring-1 contrast-more:border-slate-400 contrast-more:placeholder-slate-300" />
                                        </td>
                                        @endforeach
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
    </form>
</div>

@script
<script>
    document.querySelectorAll('.numberInput').forEach(function (el) {
        new Cleave(el, {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });
    });
</script>
@endscript
