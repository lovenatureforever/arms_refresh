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
    <div class="lg:col-span-4 sticky top-[70px] z-50">
        <div class="card">
            <div class="flex items-center justify-between card-header">
                <h6 class="card-title">Data Migration / NTFS Item</h6>
                <div class="self-end">
                    <button class="text-white btn btn-sm bg-info" type="submit">Save</button>
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
                            <div class="flex flex-row justify-between mt-8 mb-4">
                                <h1 class="text-xl uppercase">{{ $company_report_item->item }}</h1>
                                <div>
                                    <button
                                        class="text-white btn bg-info btn-sm"
                                        type="button"
                                        wire:click.prevent="$dispatch('openModal', {component: 'tenants.modal.data-migrations.create-ntfs-section-modal', arguments: { company_report_id : {{ $report }}, company_report_item_id : {{ $id }} }})"
                                    >New section</button>
                                </div>
                            </div>
                            <div class="grid gap-5 mb-4 grid-cols-subgrid ">
                                <div wire:sortable="updateSectionSort">
                                    @foreach ($sections->sortBy('sort') as $section)
                                        @if ($section->type == 4)
                                        <div wire:sortable.item='{{ $section->id }}' wire:key='{{ $section->id }}' class="relative overflow-x-auto border rounded-lg">
                                            <div class="grid grid-cols-5 text-xs font-bold text-center text-gray-700 border-b rounded-lg rounded-b-none bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                                <div class="flex items-center justify-center p-4 border-r"></div>
                                                <div class="flex items-center justify-center p-4 border-r">{{ $current_year_from }}<br /> RM</div>
                                                <div class="flex items-center justify-center p-4 border-r">Additions<br /> RM</div>
                                                <div class="flex items-center justify-center p-4 border-r">Disposals/Written Off<br /> RM</div>
                                                <div class="flex items-center justify-center p-4">{{ $current_year_to }}<br /> RM</div>
                                            </div>
                                            <div class="grid grid-cols-5">
                                                <div class="flex items-center justify-between col-span-5 py-4 pr-8 font-bold">
                                                    <div class="text-xl">
                                                        <button class="btn" type="button" wire:sortable.handle>
                                                            <i class="text-base mgc_transfer_4_fill"></i>
                                                        </button>
                                                        {{ $section->name }}</div>
                                                    <div class="flex items-center gap-4">
                                                        <div
                                                            class="text-green-500 cursor-pointer"
                                                            wire:click.prevent="$dispatch('openModal', {component: 'tenants.modal.data-migrations.update-ntfs-section-modal', arguments: { id : {{ $section->id }} }})"
                                                        >
                                                            Edit label
                                                        </div>
                                                        <div>/</div>
                                                        <div
                                                            class="text-red-500 cursor-pointer"
                                                            wire:click="deleteSection({{ $section->id }})"
                                                            wire:confirm="Are you sure you want to delete this?"
                                                        >
                                                            Remove section
                                                        </div>
                                                        <div>/</div>
                                                        <div
                                                            class="cursor-pointer text-cyan-500"
                                                            wire:click.prevent="$dispatch('openModal', {component: 'tenants.modal.data-migrations.create-ntfs-item-modal', arguments: { section_id : {{ $section->id }} }})"
                                                        >
                                                            Add item
                                                        </div>
                                                    </div>
                                                </div>
                                                {{--  --}}
                                                @foreach ($section->items as $item)
                                                    <div class="flex items-center px-4 py-1">
                                                        <i
                                                            class="mr-2 text-red-500 cursor-pointer mgc_delete_line"
                                                            wire:click="deleteItem({{ $item->id }})"
                                                            wire:confirm="Are you sure you want to delete this?"
                                                        ></i>
                                                        <span>{{ $item->name }}</span>
                                                    </div>
                                                    <div class="flex justify-end px-4 py-1">
                                                        <input type="text" pattern="^\(?\d+(\.\d+)?\)?$" wire:key='col_1_values.{{ $item->id }}' wire:model='col_1_values.{{ $item->id }}' class="self-end text-right w-28 form-input">
                                                    </div>
                                                    <div class="flex justify-end px-4 py-1">
                                                        <input type="text" pattern="^\(?\d+(\.\d+)?\)?$" wire:key='col_2_values.{{ $item->id }}' wire:model='col_2_values.{{ $item->id }}' class="self-end text-right w-28 form-input">
                                                    </div>
                                                    <div class="flex justify-end px-4 py-1">
                                                        <input type="text" pattern="^\(?\d+(\.\d+)?\)?$" wire:key='col_3_values.{{ $item->id }}' wire:model='col_3_values.{{ $item->id }}' class="self-end text-right w-28 form-input">
                                                    </div>
                                                    <div class="flex justify-end px-4 py-1">
                                                        <input type="text" pattern="^\(?\d+(\.\d+)?\)?$" wire:key='col_4_values.{{ $item->id }}' wire:model='col_4_values.{{ $item->id }}' class="self-end text-right w-28 form-input">
                                                    </div>
                                                @endforeach

                                                {{-- total row --}}
                                                <div class="p-4"></div>
                                                <div class="flex justify-end p-4">
                                                    <input type="text" pattern="^\(?\d+(\.\d+)?\)?$" disabled value="{{ $total_1_values[$section->id] }}" class="self-end font-bold text-right w-28 form-input">
                                                </div>
                                                <div class="flex justify-end p-4">
                                                    <input type="text" pattern="^\(?\d+(\.\d+)?\)?$" disabled value="{{ $total_2_values[$section->id] }}" class="self-end font-bold text-right w-28 form-input">
                                                </div>
                                                <div class="flex justify-end p-4">
                                                    <input type="text" pattern="^\(?\d+(\.\d+)?\)?$" disabled value="{{ $total_3_values[$section->id] }}" class="self-end font-bold text-right w-28 form-input">
                                                </div>
                                                <div class="flex justify-end p-4">
                                                    <input type="text" pattern="^\(?\d+(\.\d+)?\)?$" disabled value="{{ $total_4_values[$section->id] }}" class="self-end font-bold text-right w-28 form-input">
                                                </div>
                                                {{-- end total row --}}
                                            </div>
                                        </div>

                                        @elseif ($section->type == 2)
                                        {{-- 3rd section --}}
                                        <div wire:sortable.item='{{ $section->id }}' wire:key='{{ $section->id }}' class="relative overflow-x-auto border rounded-lg">
                                            <div class="grid grid-cols-5 text-xs font-bold text-center text-gray-700 border-b rounded-lg rounded-b-none bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                                <div class="flex items-center justify-center col-span-3 p-4 border-r"></div>
                                                <div class="flex items-center justify-center p-4 border-r">{{ $prior_year }}<br /> RM</div>
                                                <div class="flex items-center justify-center p-4">{{ $current_year }}<br /> RM</div>
                                            </div>
                                            <div class="grid grid-cols-5">
                                                <div class="flex items-center justify-between col-span-5 py-4 pr-8 font-bold">
                                                    <div class="text-xl">
                                                        <button class="btn" type="button" wire:sortable.handle>
                                                            <i class="text-base mgc_transfer_4_fill"></i>
                                                        </button>
                                                        {{ $section->name }}</div>
                                                    <div class="flex items-center gap-4">
                                                        <div
                                                            class="text-green-500 cursor-pointer"
                                                            wire:click.prevent="$dispatch('openModal', {component: 'tenants.modal.data-migrations.update-ntfs-section-modal', arguments: { id : {{ $section->id }} }})"
                                                        >
                                                            Edit label
                                                        </div>
                                                        <div>/</div>
                                                        <div
                                                            class="text-red-500 cursor-pointer"
                                                            wire:click="deleteSection({{ $section->id }})"
                                                            wire:confirm="Are you sure you want to delete this?"
                                                        >
                                                            Remove section
                                                        </div>
                                                        <div>/</div>
                                                        <div
                                                            class="cursor-pointer text-cyan-500"
                                                            wire:click.prevent="$dispatch('openModal', {component: 'tenants.modal.data-migrations.create-ntfs-item-modal', arguments: { section_id : {{ $section->id }} }})"
                                                        >
                                                            Add item
                                                        </div>
                                                    </div>

                                                </div>

                                                {{--  --}}
                                                @foreach ($section->items as $item)
                                                    <div class="flex items-center col-span-3 px-4 py-1">
                                                        <i
                                                            class="mr-2 text-red-500 cursor-pointer mgc_delete_line"
                                                            wire:click="deleteItem({{ $item->id }})"
                                                            wire:confirm="Are you sure you want to delete this?"
                                                        ></i>
                                                        <span>{{ $item->name }}</span>
                                                    </div>
                                                    <div class="flex justify-end px-4 py-1">
                                                        <input type="text" pattern="^\(?\d+(\.\d+)?\)?$" wire:key='col_1_values.{{ $item->id }}' wire:model='col_1_values.{{ $item->id }}' class="self-end text-right w-28 form-input">
                                                    </div>
                                                    <div class="flex justify-end px-4 py-1">
                                                        <input type="text" pattern="^\(?\d+(\.\d+)?\)?$" wire:key='col_2_values.{{ $item->id }}' wire:model='col_2_values.{{ $item->id }}' class="self-end text-right w-28 form-input">
                                                    </div>
                                                @endforeach

                                                {{-- total row --}}
                                                <div class="col-span-3 p-4"></div>
                                                <div class="flex justify-end p-4">
                                                    <input type="text" pattern="^\(?\d+(\.\d+)?\)?$" disabled value="{{ $total_1_values[$section->id] }}" class="self-end font-bold text-right w-28 form-input">
                                                </div>
                                                <div class="flex justify-end p-4">
                                                    <input type="text" pattern="^\(?\d+(\.\d+)?\)?$" disabled value="{{ $total_2_values[$section->id] }}" class="self-end font-bold text-right w-28 form-input">
                                                </div>
                                                {{-- end total row --}}
                                            </div>
                                        </div>

                                        @elseif ($section->type == 0)
                                        <div wire:sortable.item='{{ $section->id }}' wire:key='{{ $section->id }}' class="relative overflow-x-auto border rounded-lg">
                                            <div class="grid grid-cols-1">
                                                <div class="flex items-center justify-between col-span-5 py-4 pr-8 font-bold">
                                                    <div class="text-base">
                                                        <button class="btn" type="button" wire:sortable.handle>
                                                            <i class="text-base mgc_transfer_4_fill"></i>
                                                        </button>
                                                        {{ $section->name }}
                                                    </div>
                                                    <div class="flex items-center gap-4">
                                                        <div
                                                            class="text-green-500 cursor-pointer"
                                                            wire:click.prevent="$dispatch('openModal', {component: 'tenants.modal.data-migrations.update-ntfs-section-modal', arguments: { id : {{ $section->id }} }})"
                                                        >
                                                            Edit label
                                                        </div>
                                                        <div>/</div>
                                                        <div
                                                            class="text-red-500 cursor-pointer"
                                                            wire:click="deleteSection({{ $section->id }})"
                                                            wire:confirm="Are you sure you want to delete this?"
                                                        >
                                                            Remove section
                                                        </div>
                                                        <div>/</div>
                                                        <div
                                                            class="cursor-pointer text-cyan-500"
                                                            wire:click.prevent="$dispatch('openModal', {component: 'tenants.modal.data-migrations.create-ntfs-item-modal', arguments: { section_id : {{ $section->id }} }})"
                                                        >
                                                            Add item
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>
