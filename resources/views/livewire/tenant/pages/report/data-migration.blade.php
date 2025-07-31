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

    <form wire:submit="save" wire:key='{{ $this->type??"null" }}' wire:confirm="Are you sure?">
    <div class="lg:col-span-4 sticky top-[70px]">
        <div class="card">
            <div class="flex items-center justify-between card-header">
                <h6 class="card-title">Data Migration</h6>
                <div class="self-end">
                    {{-- <button class="text-white btn btn-sm bg-info" type="submit" {{ $this->type ? '' : 'disabled' }} onclick="confirm('Are you sure?!') || event.stopImmediatePropagation();">Save</button> --}}
                    <button class="text-white btn btn-sm bg-info" type="submit" {{ $this->type ? '' : 'disabled' }}>Save</button>
                    {{-- <button class="text-white btn btn-sm bg-primary" type="submit" wire:loading.attr="disabled" {{ $this->type ? '' : 'disabled' }}>
                        <div class="inline-block h-4 w-4 animate-spin rounded-full border-[3px] border-current border-t-transparent text-white" role="status" aria-label="loading" wire:loading>
                            <span class="sr-only">Loading...</span>
                        </div>
                        <span wire:loading>Saving...</span>
                        <span wire:loading.remove>Save</span>
                    </button> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <div class="card">
            <div class="card-header">
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden">
                            <div class="flex justify-between p-4 border-r">
                                <select class="w-1/4 form-input" name="type" wire:model.live="type">
                                    <option value=''>--Select--</option>
                                    @foreach ($this->company_report_types as $record)
                                        <option value="{{ $record->id }}">{{ $record->full_name }} ({{ $record->name }})</option>
                                    @endforeach
                                </select>

                                @if ($this->company_report_type?->name === 'SOCE')
                                <button
                                    class="text-white btn btn-sm bg-info"
                                    wire:click.prevent="$dispatch('openModal', {component: 'tenants.modal.data-migrations.create-col-row-modal', arguments: { company_report_id : {{ $company_report_id }} }})"
                                    wire:loading.attr="disabled"
                                >
                                    Add
                                </button>
                                @elseif ($this->company_report_type?->name === 'SOFP')
                                <label>
                                    <input type="checkbox" wire:model.live="hideEmpty" class="w-4 h-4 mr-4 bg-white border-gray-300 rounded text-slate-600 focus:ring-slate-500 focus:ring-2" />
                                    Hide empty values
                                </label>
                                @else
                                <button
                                    class="text-white btn btn-sm bg-info {{ ($this->company_report_type?->name == 'SOCI') ? 'invisible' : '' }}"
                                    wire:click.prevent="$dispatch('openModal', {component: 'tenants.modal.data-migrations.create-update-item-modal', arguments: { company_report_type_id : {{ $this->type }} }})"
                                    wire:loading.attr="disabled"
                                >
                                    Add Item
                                </button>
                                @endif

                            </div>

                            {{-- SOCE --}}
                            @if ($this->company_report_type?->name === 'SOCE')
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
                                            <input type="text" {{ $col->data_type === "number" ? "pattern=^\(?\d+(\.\d+)?\)?$" : '' }} wire:model.blur='soce_items.{{ $row->id }}.{{ $col->id }}' class="block px-3 py-2 text-right bg-white border rounded-md shadow-sm border-slate-200 placeholder-slate-400 focus:outline-none focus:border-slate-300 focus:ring-slate-300 sm:text-sm focus:ring-1 contrast-more:border-slate-400 contrast-more:placeholder-slate-300" />
                                        </td>
                                        @endforeach
                                        {{-- <td class="flex items-center px-4 py-3">
                                            <input type="checkbox" wire:model.live="skin_check_boxes.{{ $item->id }}" {{ !($check_boxes[$item->id]) ? "disabled" : "" }} class="w-4 h-4 mr-4 bg-white border-gray-300 rounded text-slate-600 focus:ring-slate-500 focus:ring-2 {{ strtolower($item->item) == 'adjustments for:' ? 'invisible' : '' }}" />
                                            <input wire:model="actual_displays.{{ $item->id }}" {{ !($skin_check_boxes[$item->id]) ? "disabled" : "" }} class="block px-3 py-2 bg-white border rounded-md shadow-sm border-slate-200 placeholder-slate-400 focus:outline-none focus:border-slate-300 focus:ring-slate-300 sm:text-sm focus:ring-1 contrast-more:border-slate-400 contrast-more:placeholder-slate-500" />
                                        </td> --}}
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @elseif ($this->company_report_type?->name === 'NTFS')
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
                                        <tr wire:key='{{ $item->id }}'>
                                            <td class="px-4 align-middle">
                                                <a class="text-blue-400 underline capitalize" href="/reports/{{ $this->id }}/ntfs/{{ $item->id }}" target="_blank">{{ $item->item }}</a>
                                            </td>
                                            <td class="px-3 text-right align-middle">
                                                <input type="text" pattern="^\(?\d+(\.\d+)?\)?$" disabled wire:model.blur='this_year_values.{{ $item->id }}' class="block px-3 py-2 text-right bg-white border rounded-md shadow-sm border-slate-200 placeholder-slate-400 focus:outline-none focus:border-slate-300 focus:ring-slate-300 sm:text-sm focus:ring-1 contrast-more:border-slate-400 contrast-more:placeholder-slate-300" />
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <input type="text" pattern="^\(?\d+(\.\d+)?\)?$" disabled wire:model.blur='last_year_values.{{ $item->id }}' class="block px-3 py-2 text-right bg-white border rounded-md shadow-sm border-slate-200 placeholder-slate-400 focus:outline-none focus:border-slate-300 focus:ring-slate-300 sm:text-sm focus:ring-1 contrast-more:border-slate-400 contrast-more:placeholder-slate-300" />
                                            </td>
                                            <td class="flex items-center px-4 py-3">
                                                <input type="checkbox" wire:model.live="skin_check_boxes.{{ $item->id }}" disabled class="w-4 h-4 mr-4 bg-white border-gray-300 rounded text-slate-600 focus:ring-slate-500 focus:ring-2 {{ strtolower($item->item) == 'adjustments for:' ? 'invisible' : '' }}" />
                                                <input wire:model="actual_displays.{{ $item->id }}" disabled class="block px-3 py-2 bg-white border rounded-md shadow-sm border-slate-200 placeholder-slate-400 focus:outline-none focus:border-slate-300 focus:ring-slate-300 sm:text-sm focus:ring-1 contrast-more:border-slate-400 contrast-more:placeholder-slate-500" />
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
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
                                            @if (!($this->company_report_type?->name === 'SOFP' && $hideEmpty && ($this_year_values[$item->id] == null || $this_year_values[$item->id] == '') && ($last_year_values[$item->id] == null || $last_year_values[$item->id] == '')))
                                            <tr wire:key='{{ $item->id }}'>
                                                <td class="px-4 align-middle">
                                                    {{-- <input type="checkbox" wire:model.live="check_boxes.{{ $item->id }}" class="w-4 h-4 mr-4 bg-white border-gray-300 rounded text-slate-600 focus:ring-slate-500 focus:ring-2 {{ $item->type != 'value' ? "invisible" : "" }} {{ strtolower($item->report_group?->description ?? $item->item) == 'adjustments for:' ? 'invisible' : '' }}" /> --}}
                                                    <span class="@if ($item->type == 'total') font-bold @endif">{{ $item->item }}</span>
                                                </td>
                                                <td class="px-3 text-right align-middle">
                                                    @if ($item->type != 'label')
                                                    <input type="text" pattern="^\(?\d+(\.\d+)?\)?$" {{ ($item->type != 'value' || !$check_boxes[$item->id]) ? 'disabled' : '' }} wire:model.blur='this_year_values.{{ $item->id }}' class="block px-3 py-2 text-right bg-white border rounded-md shadow-sm border-slate-200 placeholder-slate-400 focus:outline-none focus:border-slate-300 focus:ring-slate-300 sm:text-sm focus:ring-1 contrast-more:border-slate-400 contrast-more:placeholder-slate-300" />
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-right">
                                                    @if ($item->type != 'label')
                                                    <input type="text" pattern="^\(?\d+(\.\d+)?\)?$" {{ ($item->type != 'value' || !$check_boxes[$item->id]) ? 'disabled' : '' }} wire:model.blur='last_year_values.{{ $item->id }}' class="block px-3 py-2 text-right bg-white border rounded-md shadow-sm border-slate-200 placeholder-slate-400 focus:outline-none focus:border-slate-300 focus:ring-slate-300 sm:text-sm focus:ring-1 contrast-more:border-slate-400 contrast-more:placeholder-slate-300" />
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
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>
