<div class="grid grid-cols-1 gap-4">
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title">Corporate Info </h4>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 gap-5" data-fc-type="tab">
                <x-corporate-tabs :active="'corporate.companyname'" :id="$id" />
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

                            <div class="flex flex-row justify-between mt-8 mb-4">
                                <h1 class="text-xl">{{ $company->current_is_first_year ? 'Info as at incorporation' :'As at prior year end' }}</h1>
                            </div>
                            <div class="grid grid-cols-3 gap-5 grid-cols-subgrid">
                                <div class="card" wire:key="{{ $companyDetailAtStart->id }}">
                                    <div class="card-header">
                                        <div class="flex items-center justify-between">
                                            <h5 class="card-title">{{ $companyDetailAtStart->name }}</h5>
                                        </div>
                                    </div>
                                    <div class="flex flex-col">
                                        <div class="px-6 py-3">
                                            <h5 class="my-2">
                                                <span class="text-slate-900 dark:text-slate-200">Company Type : {{ $companyDetailAtStart->company_type }}</span>
                                            </h5>

                                            <div class="mb-3">
                                                <span><b>Presentation Currency</b></span>
                                                <span class="mr-5"><b>Report</b> : {{ $companyDetailAtStart->presentation_currency }}</span>
                                                <span><b>Report (Code)</b> : {{ $companyDetailAtStart->presentation_currency_code }}</span>
                                            </div>
                                            <div class="mb-3">
                                                <span><b>Functional Currency</b></span>
                                                <span class="mr-5"><b>Report</b> : {{ $companyDetailAtStart->functional_currency }}</span>
                                                <span><b>Report (Code)</b> : {{ $companyDetailAtStart->functional_currency_code }}</span>
                                            </div>
                                            <div>
                                                <span class="mr-5"><b>Domicile : </b>{{ $companyDetailAtStart->domicile }}</span>
                                                <span><b>Remarks : </b>{{ $companyDetailAtStart->remarks }}</span>
                                            </div>

                                            <div class="mt-5">
                                                <button class="text-white btn btn-sm bg-info" type="button" wire:click="$dispatch('openModal', {component: 'tenant.components.corporate-info.company-name-modal', arguments: { companyId : {{ $id }}, id: {{ $companyDetailAtStart->id }}, isStart: true }})">Edit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="px-6 py-3">
                                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                            <div>
                                                <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="registrationNo">Registration No: </label>
                                                <input class="form-input" id="registrationNo" type="text" wire:model="registrationNo">
                                            </div>
                                            <div>
                                                <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="registrationNoOld">Registration No Old:
                                                </label>
                                                <input class="form-input" id="registrationNoOld" type="text" wire:model="registrationNoOld">
                                            </div>
                                            <div wire:dirty class="my-auto pt-[25px]"><button class="text-white btn btn-sm bg-info" type="button" wire:click="saveRegistrationNo">Save</button></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-row justify-between mt-8 mb-4">
                                <h1 class="text-xl">Changes during the year</h1>
                                <div>
                                    <button class="text-white btn bg-info btn-sm" type="button" wire:click="$dispatch('openModal', {component: 'tenant.components.corporate-info.company-name-modal', arguments: { companyId : {{ $id }}, id: null, isStart: false }})">Create</button>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-5 mb-4 grid-cols-subgrid">
                                <div>
                                    <div class="relative overflow-x-auto border rounded-lg">
                                        <div class="grid grid-cols-10 text-xs font-bold text-center text-gray-700 uppercase border-b rounded-lg rounded-b-none bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <div class="flex items-center justify-center row-span-2 p-4 border-r">Name of Company</div>
                                            <div class="flex items-center justify-center row-span-2 p-4 border-r">Company Type</div>

                                            <div class="flex items-center justify-center col-span-2 p-4 border-b border-r">Presentation</div>
                                            <div class="flex items-center justify-center col-span-2 p-4 border-b border-r">Functional</div>
                                            <div class="flex items-center justify-center row-span-2 p-4 border-r">Domicile</div>
                                            <div class="flex items-center justify-center row-span-2 p-4 border-r">Effective Date</div>
                                            <div class="flex items-center justify-center row-span-2 p-4 border-r">Remarks</div>
                                            <div class="flex items-center justify-center row-span-2 p-4">Action</div>

                                            <div class="flex items-center justify-center p-4 border-r">Report</div>
                                            <div class="flex items-center justify-center p-4 border-r">Code</div>
                                            <div class="flex items-center justify-center p-4 border-r">Report</div>
                                            <div class="flex items-center justify-center p-4 border-r">Code</div>

                                        </div>
                                        <div class="grid grid-cols-10">
                                            @foreach ($companyDetailChanges as $companyDetail)
                                            <div class="p-4 border-t border-r">{{ $companyDetail->name }}</div>
                                            <div class="p-4 border-t border-r">{{ $companyDetail->company_type }}</div>
                                            <div class="p-4 border-t border-r">{{ $companyDetail->presentation_currency }}</div>
                                            <div class="p-4 border-t border-r">{{ $companyDetail->presentation_currency_code }}</div>
                                            <div class="p-4 border-t border-r">{{ $companyDetail->functional_currency }}</div>
                                            <div class="p-4 border-t border-r">{{ $companyDetail->functional_currency_code }}</div>
                                            <div class="p-4 border-t border-r">{{ $companyDetail->domicile }}</div>
                                            <div class="p-4 border-t border-r">{{ $companyDetail->effective_date->format('Y-m-d') }}</div>
                                            <div class="p-4 border-t border-r">{{ $companyDetail->remarks }}</div>
                                            <div class="p-4 border-t flex items-center justify-center">
                                                <button class="text-white btn btn-sm bg-info mr-1"
                                                    wire:click="$dispatch('openModal', {component: 'tenant.components.corporate-info.company-name-modal', arguments: { companyId : {{ $id }}, id: {{ $companyDetail->id }}, isStart: false }})"
                                                ><i class="mgc_edit_line text-xl"></i></button>
                                                <button class="text-white btn btn-sm bg-warning"
                                                    type="button"
                                                    wire:click="deleteCompanyName({{ $companyDetail->id }})"
                                                    wire:confirm="Are you sure you want to delete this?"
                                                ><i class="mgc_delete_line text-xl"></i></button>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 mb-4">
                                <h1 class="text-xl text-primary">Result Summary</h1>
                                <div class="text-sm text-gray-400">as at current report date</div>
                            </div>
                            <div class="grid grid-cols-3 gap-5 grid-cols-subgrid">
                                <div>
                                    <div class="relative mb-4 overflow-x-auto border rounded-lg">
                                        <div class="grid grid-cols-7 text-xs font-bold text-center text-gray-700 uppercase border-b rounded-lg rounded-b-none bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <div class="flex items-center justify-center p-4 border-r">Name of Company</div>
                                            <div class="flex items-center justify-center p-4 border-r">Company Type</div>
                                            <div class="flex items-center justify-center p-4 border-r">Presentation Currency</div>
                                            <div class="flex items-center justify-center p-4 border-r">Presentation Currency Code</div>
                                            <div class="flex items-center justify-center p-4 border-r">Functional Currency</div>
                                            <div class="flex items-center justify-center p-4 border-r">Functional Currency Code</div>
                                            <div class="flex items-center justify-center p-4">Domicile Incorporated In</div>
                                        </div>
                                        <div class="grid grid-cols-7">

                                            <div class="p-4 border-r">
                                                <div class="flex flex-col">
                                                    <p>{{ $companyDetailAtLast->name }}</p>
                                                    @if ($companyDetailAtStart != null && $companyDetailChanges->count() > 0)
                                                    <p>(Formerly known as {{ $companyDetailAtStart->name }})</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="p-4 border-r">
                                                @if ($companyDetailAtLast != null)
                                                <p>{{ $companyDetailAtLast->company_type }}</p>
                                                @else
                                                <p>{{ $companyDetailAtStart->company_type }}</p>
                                                @endif
                                            </div>
                                            <div class="p-4 border-r">
                                                @if ($companyDetailAtLast != null)
                                                <p>{{ $companyDetailAtLast->presentation_currency }}</p>
                                                @else
                                                <p>{{ $companyDetailAtStart->presentation_currency }}</p>
                                                @endif
                                            </div>
                                            <div class="p-4 border-r">
                                                @if ($companyDetailAtLast != null)
                                                <p>{{ $companyDetailAtLast->presentation_currency }}</p>
                                                @else
                                                <p>{{ $companyDetailAtStart->presentation_currency }}</p>
                                                @endif
                                            </div>
                                            <div class="p-4 border-r">
                                                @if ($companyDetailAtLast != null)
                                                <p>{{ $companyDetailAtLast->functional_currency }}</p>
                                                @else
                                                <p>{{ $companyDetailAtStart->functional_currency }}</p>
                                                @endif
                                            </div>
                                            <div class="p-4 border-r">
                                                @if ($companyDetailAtLast != null)
                                                <p>{{ $companyDetailAtLast->functional_currency_code }}</p>
                                                @else
                                                <p>{{ $companyDetailAtStart->functional_currency_code }}</p>
                                                @endif
                                            </div>
                                            <div class="p-4 ">
                                                @if ($companyDetailAtLast != null)
                                                <p>{{ $companyDetailAtLast->domicile }}</p>
                                                @else
                                                <p>{{ $companyDetailAtStart->domicile }}</p>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
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
