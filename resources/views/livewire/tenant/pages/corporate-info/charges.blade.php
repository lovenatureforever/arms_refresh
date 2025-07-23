<div class="grid grid-cols-1 gap-4">
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title">Corporate Info </h4>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 gap-5" data-fc-type="tab">
                <x-corporate-tabs :active="'corporate.charges'" :id="$id" />
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
                                    <button class="text-white btn btn-sm bg-info" type="button" wire:click="$dispatch('openModal', {component: 'tenant.components.corporate-info.charge-modal', arguments: { companyId : {{ $id }}, id: null, isStart: true }})">Create</button>
                                </div>
                            </div>


                            <div class="relative mb-4 overflow-x-auto border rounded-lg">
                                <div class="grid grid-cols-8 text-xs font-bold text-gray-700 uppercase border-b rounded-lg rounded-b-none bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <div class="flex items-center justify-start p-4 border-r">Registered No.</div>
                                    <div class="flex items-center justify-start p-4 border-r">Register at</div>
                                    <div class="flex items-center justify-start p-4 border-r">Discharge at</div>
                                    <div class="flex items-center justify-start p-4 border-r ">Nature of Charge</div>
                                    <div class="flex items-center justify-start p-4 border-r ">Chargee</div>
                                    <div class="flex items-center justify-start p-4 border-r">Indebtedness Amount</div>
                                    <div class="flex items-center justify-start p-4 border-r">Remarks</div>
                                    <div class="flex items-center justify-start p-4">Action</div>
                                </div>
                                @foreach ($chargeChangesAtStart as $chargeChange)
                                <div class="grid border-b grid-cols-8">
                                    <div class="p-4 border-r">{{ $chargeChange->registered_number }}</div>
                                    <div class="p-4 border-r">{{ $chargeChange->registration_date?->format('Y-m-d') }}</div>
                                    <div class="p-4 border-r">{{ $chargeChange->discharge_date?->format('Y-m-d') }}</div>
                                    <div class="p-4 border-r">{{ $chargeChange->charge_nature }}</div>
                                    <div class="p-4 border-r">{{ $chargeChange->chargee_name }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($chargeChange->indebtedness_amount) }}</div>
                                    <div class="p-4 border-r">{{ $chargeChange->remarks }}</div>
                                    <div class="p-4 border-b">
                                        <button class="text-white btn btn-sm bg-info" wire:click="$dispatch('openModal', {component: 'tenant.components.corporate-info.charge-modal', arguments: { companyId : {{ $id }}, 'id' : {{ $chargeChange->id }}, isStart: true }})">Edit</button>
                                        <button
                                            class="text-white btn btn-sm bg-danger"
                                            type="button"
                                            wire:click="deleteChargeChange({{ $chargeChange->id }})"
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
                                    <button class="text-white btn btn-sm bg-info" type="button" wire:click="$dispatch('openModal', {component: 'tenant.components.corporate-info.charge-modal', arguments: { companyId : {{ $id }}, id: null, isStart: false }})">Create</button>
                                </div>
                            </div>

                            <div class="relative mb-4 overflow-x-auto border rounded-lg">
                                <div class="grid grid-cols-10 text-xs font-bold text-gray-700 uppercase border-b rounded-lg rounded-b-none bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <div class="flex items-center justify-start p-4 border-r">Nature of Change</div>
                                    <div class="flex items-center justify-start p-4 border-r">Registered No.</div>
                                    <div class="flex items-center justify-start p-4 border-r">Register at</div>
                                    <div class="flex items-center justify-start p-4 border-r">Discharge at</div>
                                    <div class="flex items-center justify-start p-4 border-r ">Nature of Charge</div>
                                    <div class="flex items-center justify-start p-4 border-r ">Chargee</div>
                                    <div class="flex items-center justify-start p-4 border-r">Indebtedness Amount</div>
                                    <div class="flex items-center justify-start p-4 border-r ">Effective Date</div>
                                    <div class="flex items-center justify-start p-4 border-r">Remarks</div>
                                    <div class="flex items-center justify-start p-4">Action</div>
                                </div>
                                @foreach ($chargeChangesCurrentYear as $chargeChange)
                                <div class="grid border-b grid-cols-10">
                                    <div class="p-4 border-r">{{ $chargeChange->change_nature }}</div>
                                    <div class="p-4 border-r">{{ $chargeChange->registered_number }}</div>
                                    <div class="p-4 border-r">{{ $chargeChange->registration_date?->format('Y-m-d') }}</div>
                                    <div class="p-4 border-r">{{ $chargeChange->discharge_date?->format('Y-m-d') }}</div>
                                    <div class="p-4 border-r">{{ $chargeChange->charge_nature }}</div>
                                    <div class="p-4 border-r">{{ $chargeChange->chargee_name }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($chargeChange->indebtedness_amount) }}</div>
                                    <div class="p-4 border-r">{{ $chargeChange->effective_date->format('Y-m-d') }}</div>
                                    <div class="p-4 border-r">{{ $chargeChange->remarks }}</div>
                                    <div class="p-4 border-b">
                                        <button class="text-white btn btn-sm bg-info" wire:click="$dispatch('openModal', {component: 'tenant.components.corporate-info.charge-modal', arguments: { companyId : {{ $this->id }}, 'id' : {{ $chargeChange->id }}, isStart: false }})">Edit</button>
                                        <button class="text-white btn btn-sm bg-danger" type="button" wire:click="deleteChargeChange({{ $chargeChange->id }})"
                                            wire:confirm="Are you sure you want to delete this?">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="mt-8 mb-4">
                                <h1 class="text-xl text-primary">Result Summary</h1>
                                <div class="text-sm text-gray-400">as at current year end</div>
                            </div>

                            <div class="relative mb-4 overflow-x-auto border rounded-lg">
                                <div class="grid grid-cols-5 text-xs font-bold text-gray-700 uppercase border-b rounded-lg rounded-b-none bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <div class="flex items-center justify-start p-4 border-r">Particular Of Assets Charge</div>
                                    <div class="flex items-center justify-start p-4 border-r">Name Of Chargee</div>
                                    <div class="flex items-center justify-start p-4 border-r">Amount Of Charge</div>
                                    <div class="flex items-center justify-start p-4 border-r">Date Created</div>
                                    <div class="flex items-center justify-start p-4 border-r">Date Discharged</div>
                                </div>
                                @foreach ($chargeResults as $charge)
                                <div class="grid grid-cols-5 border-b cursor-grab">
                                    <div class="p-4 border-r">{{ $charge->charge_nature }}</div>
                                    <div class="p-4 border-r">{{ $charge->chargee_name }}</div>
                                    <div class="p-4 border-r">{{ displayNumber($charge->indebtedness_amount) }}</div>
                                    <div class="p-4 border-r">{{ $charge->registration_date?->format('Y-m-d') }}</div>
                                    <div class="p-4 border-r">{{ $charge->discharge_date?->format('Y-m-d') }}</div>
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

