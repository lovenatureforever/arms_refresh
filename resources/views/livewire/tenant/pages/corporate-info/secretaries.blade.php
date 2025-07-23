<div class="grid grid-cols-1 gap-4">
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title">Corporate Info </h4>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 gap-5" data-fc-type="tab">
                <x-corporate-tabs :active="'corporate.secretaries'" :id="$id" />
                <div class="col-span-4 p-4 mb-2 mr-2 border border-gray-100 rounded">
                    <div role="tabpanel">
                        {{-- Detail Content --}}
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

                            <div class="flex flex-row justify-between mb-4">
                                <h1 class="text-xl">{{ $company->current_is_first_year ? 'Info as at incorporation' : 'As at prior year end' }}</h1>
                                <div>
                                    <button class="text-white btn btn-sm bg-info" type="button" wire:click="$dispatch('openModal', {component: 'tenant.components.corporate-info.secretary-modal', arguments: { companyId : {{ $id }}, id: null, isStart: true }})">Create</button>
                                </div>
                            </div>


                            <div class="relative mb-4 overflow-x-auto border rounded-lg">
                                <div class="grid grid-cols-5 text-xs font-bold text-gray-700 uppercase border-b rounded-lg rounded-b-none bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <div class="flex items-center justify-start p-4 border-r">Name</div>
                                    <div class="flex items-center justify-start p-4 border-r">Id No.</div>
                                    <div class="flex items-center justify-start p-4 border-r">Secretary No.</div>
                                    <div class="flex items-center justify-start p-4 border-r">Remarks</div>
                                    <div class="flex items-center justify-start p-4">Action</div>
                                </div>
                                @foreach ($secretaryChangesAtStart as $secretaryChange)
                                <div class="grid grid-cols-5">
                                    <div class="p-4 border-b border-r">{{ $secretaryChange->companySecretary->name }}</div>
                                    <div class="p-4 border-b border-r">{{ $secretaryChange->companySecretary->id_no }} ({{$secretaryChange->companySecretary->id_type}})</div>
                                    <div class="p-4 border-b border-r">{{ $secretaryChange->companySecretary->secretary_no }}</div>
                                    <div class="p-4 border-b border-r">{{ $secretaryChange->remarks }}</div>
                                    <div class="p-4 border-b">
                                        <button class="text-white btn btn-sm bg-info" wire:click="$dispatch('openModal', {component: 'tenant.components.corporate-info.secretary-modal', arguments: { companyId : {{ $id }}, 'id' : {{ $secretaryChange->id }}, isStart: true }})">Edit</button>
                                        <button
                                            class="text-white btn btn-sm bg-danger"
                                            type="button"
                                            wire:click="deleteSecretaryChange({{ $secretaryChange->id }})"
                                            wire:confirm="Are you sure you want to delete this?"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="flex flex-row justify-between mt-8 mb-4">
                                <h1 class="text-xl">Changes during the year</h1>
                                <div>
                                    <button class="text-white btn btn-sm bg-info" type="button" wire:click="$dispatch('openModal', {component: 'tenant.components.corporate-info.secretary-modal', arguments: { companyId : {{ $id }}, id: null, isStart: false }})">Create</button>
                                </div>
                            </div>

                            <div class="relative mb-4 overflow-x-auto border rounded-lg">
                                <div class="grid grid-cols-7 text-xs font-bold text-gray-700 uppercase border-b rounded-lg rounded-b-none bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <div class="flex items-center justify-start p-4 border-r">Name</div>
                                    <div class="flex items-center justify-start p-4 border-r">Nature Of Change</div>
                                    <div class="flex items-center justify-start p-4 border-r">Id No.</div>
                                    <div class="flex items-center justify-start p-4 border-r ">SECRETARY NO.</div>
                                    <div class="flex items-center justify-start p-4 border-r">Effective Date</div>
                                    <div class="flex items-center justify-start p-4 border-r">Remarks</div>
                                    <div class="flex items-center justify-start p-4">Action</div>
                                </div>
                                @foreach ($secretaryChangesCurrentYear as $secretaryChange)
                                <div class="grid grid-cols-7">
                                    <div class="p-4 border-b border-r">{{ $secretaryChange->companySecretary->name }}</div>
                                    <div class="p-4 border-b border-r">{{ $secretaryChange->change_nature }}</div>
                                    <div class="p-4 border-b border-r">
                                        {{ $secretaryChange->change_nature === App\Models\Tenant\CompanySecretaryChange::CHANGE_NATURE_SECRETARY_APPOINTED ? $secretaryChange->companySecretary->id_no.'('.$secretaryChange->companySecretary->id_type.')' : '' }}
                                    </div>
                                    <div class="p-4 border-b border-r">
                                        {{ $secretaryChange->change_nature === App\Models\Tenant\CompanySecretaryChange::CHANGE_NATURE_SECRETARY_APPOINTED ? $secretaryChange->companySecretary->secretary_no : '' }}
                                    </div>
                                    <div class="p-4 border-b border-r">{{ $secretaryChange->effective_date->format('Y-m-d') }}</div>
                                    <div class="p-4 border-b border-r">{{ $secretaryChange->remarks }}</div>
                                    <div class="p-4 border-b">
                                        <button class="text-white btn btn-sm bg-info" wire:click="$dispatch('openModal', {component: 'tenant.components.corporate-info.secretary-modal', arguments: { companyId : {{ $this->id }}, 'id' : {{ $secretaryChange->id }}, isStart: false }})">Edit</button>
                                        <button class="text-white btn btn-sm bg-danger" type="button" wire:click="deleteSecretaryChange({{ $secretaryChange->id }})"
                                            wire:confirm="Are you sure you want to delete this?">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="mt-8 mb-4">
                                <h1 class="text-xl text-primary">Result Summary</h1>
                                <div class="text-sm text-gray-400">as at current report date</div>
                            </div>

                            <div class="relative mb-4 overflow-x-auto border rounded-lg">
                                <div class="grid grid-cols-4 text-xs font-bold text-gray-700 uppercase border-b rounded-lg rounded-b-none bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <div class="flex items-center justify-start p-4 border-r">No.</div>
                                    <div class="flex items-center justify-start col-span-3 p-4 border-r">Secretary Info</div>
                                </div>
                                @foreach ($secretariesAtLast as $appointed)
                                <div class="grid grid-cols-4">
                                    <div class="p-4 border-r">{{ $loop->iteration }}</div>
                                    <div class="col-span-3 p-4 border-r">{{ $appointed->name }} ({{ $appointed->secretary_no }})</div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- End Detail Content --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@livewireScripts
<script src="https://unpkg.com/@wotz/livewire-sortablejs@1.0.0/dist/livewire-sortable.js"></script>

