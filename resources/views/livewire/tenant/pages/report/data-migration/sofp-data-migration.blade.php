
<div>
    @if (session()->has('error'))
    <div class="p-4 border border-red-200 rounded-md bg-red-50" role="alert">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="text-xl mgc_information_line"></i>
            </div>
            <div class="ms-4">
                <h3 class="text-sm font-semibold text-red-800">
                    {{ session('error') }}
                </h3>
            </div>
        </div>
    </div>
    @endif

    @if (session()->has('success'))
    <div class="p-4 mb-5 border border-green-200 rounded-md bg-green-50" role="alert">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="text-xl mgc_information_line"></i>
            </div>
            <div class="ms-4">
                <h3 class="text-sm font-semibold text-green-800">
                    {{ session('success') }}
                </h3>
            </div>
        </div>
    </div>
    @endif

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
            <a href="{{ route('datamigration.sofp', ['id' => $id]) }}" class="px-3 py-2 font-medium text-sm rounded-t-md {{ ($activeTab ?? 'sofp') === 'sofp' ? 'bg-white border-l border-t border-r text-blue-600' : 'text-gray-500 hover:text-blue-600' }}">SOFP</a>
            <a href="{{ route('datamigration.soci', ['id' => $id]) }}" class="px-3 py-2 font-medium text-sm rounded-t-md {{ ($activeTab ?? '') === 'soci' ? 'bg-white border-l border-t border-r text-blue-600' : 'text-gray-500 hover:text-blue-600' }}">SOCI</a>
            <a href="{{ route('datamigration.soce', ['id' => $id]) }}" class="px-3 py-2 font-medium text-sm rounded-t-md {{ ($activeTab ?? '') === 'soce' ? 'bg-white border-l border-t border-r text-blue-600' : 'text-gray-500 hover:text-blue-600' }}">SOCE</a>
            <a href="{{ route('datamigration.socf', ['id' => $id]) }}" class="px-3 py-2 font-medium text-sm rounded-t-md {{ ($activeTab ?? '') === 'socf' ? 'bg-white border-l border-t border-r text-blue-600' : 'text-gray-500 hover:text-blue-600' }}">SOCF</a>
            <a href="{{ route('datamigration.stsoo', ['id' => $id]) }}" class="px-3 py-2 font-medium text-sm rounded-t-md {{ ($activeTab ?? '') === 'stsoo' ? 'bg-white border-l border-t border-r text-blue-600' : 'text-gray-500 hover:text-blue-600' }}">STSOO</a>
            <a href="{{ route('datamigration.ntfs', ['id' => $id]) }}" class="px-3 py-2 font-medium text-sm rounded-t-md {{ ($activeTab ?? '') === 'ntfs' ? 'bg-white border-l border-t border-r text-blue-600' : 'text-gray-500 hover:text-blue-600' }}">NTFS</a>
        </nav>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden">
                        <div class="flex justify-end my-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" wire:model.live="hideEmpty" class="w-4 h-4 mr-2 bg-white border-gray-300 rounded text-slate-600 focus:ring-slate-500 focus:ring-2" />
                                <span>Hide empty values</span>
                            </label>
                        </div>
                        <table class="w-full text-sm text-left text-gray-500 border border-collapse table-auto border-slate-300">
                            <thead class="text-xs text-gray-700 bg-gray-50">
                                <tr>
                                    <th class="px-4 py-4">Standard Report Wordings</th>
                                    <th class="px-4 py-4">This Year Amount</th>
                                    <th class="px-4 py-4">Last Year Amount</th>
                                    <th class="px-4 py-4">Report Display Skin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($this->company_report_items??[] as $item)
                                    @if ($item->type == "group")
                                        <tr class="bg-gray-200" wire:key='{{ $item->id }}'>
                                            <td colspan="4" class="px-3 py-3">
                                                <div class="inline-flex items-center align-middle">
                                                    <div class="flex items-center mr-5 font-bold uppercase">{{ $item->item }}</div>
                                                    <input type="checkbox" wire:model.live="skin_check_boxes.{{ $item->id }}" class="w-4 h-4 mr-4 bg-white border-gray-300 rounded text-slate-600 focus:ring-slate-500 focus:ring-2" />
                                                    <input wire:model="actual_displays.{{ $item->id }}" class="w-[400px] block px-3 py-2 uppercase bg-white border rounded-md shadow-sm border-slate-200 placeholder-slate-400 focus:outline-none focus:border-slate-300 focus:ring-slate-300 sm:text-sm focus:ring-1 contrast-more:border-slate-400 contrast-more:placeholder-slate-300" />
                                                </div>
                                                <div class="inline-flex items-center px-1 py-1 mt-1 mr-4 text-center bg-gray-100 border rounded float-end">
                                                    <i class="mgc_up_fill"></i>
                                                </div>
                                            </td>
                                        </tr>
                                    @else
                                        @if (!($hideEmpty && ($this_year_values[$item->id] == null || $this_year_values[$item->id] == '') && ($last_year_values[$item->id] == null || $last_year_values[$item->id] == '')))
                                        <tr wire:key='{{ $item->id }}'>
                                            <td class="px-4 align-middle">
                                                <span class="@if ($item->type == 'total') font-bold @endif">{{ $item->item }}</span>
                                            </td>
                                            <td class="px-3 text-right align-middle">
                                                @if ($item->type != 'label')
                                                <input type="text" {{ ($item->type != 'value' || !$check_boxes[$item->id]) ? 'disabled' : '' }} wire:model.blur='this_year_values.{{ $item->id }}' class="numberInput block px-3 py-2 text-right bg-white border rounded-md shadow-sm border-slate-200 placeholder-slate-400 focus:outline-none focus:border-slate-300 focus:ring-slate-300 sm:text-sm focus:ring-1 contrast-more:border-slate-400 contrast-more:placeholder-slate-300" />
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                @if ($item->type != 'label')
                                                <input type="text" {{ ($item->type != 'value' || !$check_boxes[$item->id]) ? 'disabled' : '' }} wire:model.blur='last_year_values.{{ $item->id }}' class="numberInput block px-3 py-2 text-right bg-white border rounded-md shadow-sm border-slate-200 placeholder-slate-400 focus:outline-none focus:border-slate-300 focus:ring-slate-300 sm:text-sm focus:ring-1 contrast-more:border-slate-400 contrast-more:placeholder-slate-300" />
                                                @endif
                                            </td>
                                            <td class="flex items-center px-4 py-3">
                                                <input type="checkbox" wire:model.live="skin_check_boxes.{{ $item->id }}" {{ !($check_boxes[$item->id]) ? "disabled" : "" }} class="w-4 h-4 mr-4 bg-white border-gray-300 rounded text-slate-600 focus:ring-slate-500 focus:ring-2 {{ strtolower($item->item) == 'adjustments for:' ? 'invisible' : '' }}" />
                                                <input wire:model="actual_displays.{{ $item->id }}" {{ !($skin_check_boxes[$item->id]) ? "disabled" : "" }} class="block px-3 py-2 bg-white border rounded-md shadow-sm border-slate-200 placeholder-slate-400 focus:outline-none focus:border-slate-300 focus:ring-slate-300 sm:text-sm focus:ring-1 contrast-more:border-slate-400 contrast-more:placeholder-slate-500" />
                                            </td>
                                        </tr>
                                        @endif
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
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
