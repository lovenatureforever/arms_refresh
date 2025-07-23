<div class="grid grid-cols-1 gap-4">
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title">Corporate Info </h4>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 gap-5" data-fc-type="tab">
                <x-corporate-tabs :active="'corporate.directors'" :id="$id" />
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
                                    <button class="text-white btn btn-sm bg-info" type="button" wire:click="$dispatch('openModal', {component: 'tenant.components.corporate-info.director-modal', arguments: { companyId : {{ $id }}, id: null, isStart: true }})">Create</button>
                                </div>
                            </div>


                            <div class="relative mb-4 overflow-x-auto border rounded-lg">
                                <div class="grid grid-cols-7 text-xs font-bold text-gray-700 uppercase border-b rounded-lg rounded-b-none bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <div class="flex items-center justify-start p-4 border-r">Name</div>
                                    <div class="flex items-center justify-start p-4 border-r ">Designation</div>
                                    <div class="flex items-center justify-start p-4 border-r ">Alternate To</div>
                                    <div class="flex items-center justify-start p-4 border-r">Id No.</div>
                                    <div class="flex items-center justify-start p-4 border-r">Address</div>
                                    <div class="flex items-center justify-start p-4 border-r">Remark</div>
                                    <div class="flex items-center justify-start p-4">Action</div>
                                </div>
                                @foreach ($directorChangesAtStart as $directorChange)
                                <div class="grid grid-cols-7">
                                    <div class="p-4 border-b border-r">{{ $directorChange->companyDirector->name }}</div>
                                    <div class="p-4 border-b border-r">{{ $directorChange->companyDirector->designation }}</div>
                                    <div class="p-4 border-b border-r">{{ $directorChange->companyDirector->alternate?->name }}</div>
                                    <div class="p-4 border-b border-r">{{ $directorChange->id_no }} ({{$directorChange->id_type}})</div>
                                    <div class="p-4 border-b border-r">
                                        {{ $directorChange->full_address }}
                                    </div>
                                    <div class="p-4 border-b border-r">{{ $directorChange->remarks }}</div>
                                    <div class="p-4 border-b">
                                        <button class="text-white btn btn-sm bg-info" wire:click="$dispatch('openModal', {component: 'tenant.components.corporate-info.director-modal', arguments: { companyId : {{ $id }}, 'id' : {{ $directorChange->id }}, isStart: true }})">Edit</button>
                                        <button
                                            class="text-white btn btn-sm bg-danger"
                                            type="button"
                                            wire:click="deleteCompanyDirector({{ $directorChange->companyDirector->id }})"
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
                                    <button class="text-white btn btn-sm bg-info" type="button" wire:click="$dispatch('openModal', {component: 'tenant.components.corporate-info.director-modal', arguments: { companyId : {{ $id }}, id: null, isStart: false }})">Create</button>
                                </div>
                            </div>

                            <div class="relative mb-4 overflow-x-auto border rounded-lg">
                                <div class="grid grid-cols-9 text-xs font-bold text-gray-700 uppercase border-b rounded-lg rounded-b-none bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <div class="flex items-center justify-start p-4 border-r">Name</div>
                                    <div class="flex items-center justify-start p-4 border-r ">Nature Of Change</div>
                                    <div class="flex items-center justify-start p-4 border-r ">Designation</div>
                                    <div class="flex items-center justify-start p-4 border-r ">Alternate To</div>
                                    <div class="flex items-center justify-start p-4 border-r">Id No.</div>
                                    <div class="flex items-center justify-start p-4 border-r">Address</div>
                                    <div class="flex items-center justify-start p-4 border-r">Remark</div>
                                    <div class="flex items-center justify-start p-4 border-r">Effective Date</div>
                                    <div class="flex items-center justify-start p-4">Action</div>
                                </div>
                                @foreach ($directorChangesCurrentYear as $directorChange)
                                <div class="grid grid-cols-9">
                                    <div class="p-4 border-b border-r">{{ $directorChange->companyDirector->name }}</div>
                                    <div class="p-4 border-b border-r">{{ $directorChange->change_nature }}</div>
                                    <div class="p-4 border-b border-r">
                                        @if ($directorChange->change_nature == App\Models\Tenant\CompanyDirectorChange::CHANGE_NATURE_DIRECTOR_APPOINTED)
                                            {{ $directorChange->companyDirector->designation }}
                                        @endif
                                    </div>
                                    <div class="p-4 border-b border-r">
                                        @if ($directorChange->change_nature == App\Models\Tenant\CompanyDirectorChange::CHANGE_NATURE_DIRECTOR_APPOINTED)
                                        {{ $directorChange->companyDirector->alternate?->name }}
                                        @endif
                                    </div>
                                    <div class="p-4 border-b border-r">
                                        @if ($directorChange->id_no && $directorChange->id_type)
                                            {{ $directorChange->id_no }} ({{$directorChange->id_type}})
                                        @endif
                                    </div>
                                    <div class="p-4 border-b border-r">
                                        {{ $directorChange->full_address }}
                                    </div>
                                    <div class="p-4 border-b border-r">{{ $directorChange->remarks }}</div>
                                    <div class="p-4 border-b border-r">{{ $directorChange->effective_date->format('Y-m-d') }}</div>
                                    <div class="p-4 border-b">
                                        <button class="text-white btn btn-sm bg-info" wire:click="$dispatch('openModal', {component: 'tenant.components.corporate-info.director-modal', arguments: { companyId : {{ $this->id }}, 'id' : {{ $directorChange->id }}, isStart: false }})">Edit</button>
                                        <button class="text-white btn btn-sm bg-danger" type="button" wire:click="deleteDirectorChange({{ $directorChange->id }})"
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
                                <div class="grid grid-cols-5 text-xs font-bold text-gray-700 uppercase border-b rounded-lg rounded-b-none bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <div class="flex items-center justify-start p-4 border-r">No.</div>
                                    <div class="flex items-center justify-start p-4 border-r">Name</div>
                                    <div class="flex items-center justify-start p-4 border-r">Id No.</div>
                                    <div class="flex items-center justify-start p-4 border-r">Alternate To.</div>
                                    <div class="flex items-center justify-start p-4 border-r">Changes Info.</div>
                                </div>
                                <div wire:sortable="updateDirectorOrder" wire:sortable.options="{ animation: 100 }">
                                    @foreach ($directorsAtLast as $director)
                                    <div class="grid grid-cols-5 border-b cursor-grab" wire:sortable.item="{{ $director->id }}" wire:key="director-{{ $director->id }}">
                                        <div class="p-4 border-r" wire:sortable.handle>
                                            <i class="mgc_move_line handle me-4"></i>
                                            {{ $loop->iteration }}</div>
                                        <div class="p-4 border-r">{{ $director->name }}</div>
                                        <div class="p-4 border-r">{{ $director->id_no }} ({{$director->id_type}})</div>
                                        <div class="p-4 border-r">{{ $director->alternate?->name }}</div>
                                        <div class="p-4 border-r">
                                            {{ $director->changes_current }}
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
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

