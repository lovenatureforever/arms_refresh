<div>
    @if ($errors->any())
    <div class="p-4 border border-red-200 rounded-md bg-red-50" role="alert">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="text-xl text-red-800 mgc_information_line"></i>
            </div>
            <div class="ms-4">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li class="text-sm font-semibold text-red-800">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <div class="flex items-center justify-between border-b px-4 py-2.5 dark:border-gray-700">
        <h3 class="text-lg font-medium text-gray-800 dark:text-white">
            Create / Update Director
        </h3>
        <button class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 dark:text-gray-200" wire:click.prevent="closeModal()">
            <span class="material-symbols-rounded">close</span>
        </button>
    </div>
    <form wire:submit="submit">
        <div class="px-4 py-8 overflow-y-auto">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                @if (!$isStart)
                    <div class="">
                        <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="changeNature">Nature of Change</label>
                        <select class="form-input" id="changeNature" name="changeNature" wire:model.live="changeNature" {{ $id ? 'disabled' : '' }}>
                            @foreach (App\Models\Tenant\CompanyDirectorChange::$changeNatures as $c)
                            <option value="{{ $c }}">{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="">
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="name">Name</label>
                    @if ($changeNature == "Director appointed")
                        <input class="form-input" id="input-name" type="text" wire:model="name">
                    @else
                        <select class="form-input" id="select-name" wire:model="selectedDirector" {{ $id ? 'disabled' : '' }}>
                            <option value="">Select director</option>
                            @foreach ($directors as $director)
                            <option value="{{ $director->id }}">{{ $director->name }}</option>
                            @endforeach
                        </select>
                    @endif

                </div>

                @if (
                    $changeNature != App\Models\Tenant\CompanyDirectorChange::CHANGE_NATURE_DIRECTOR_RESIGNED
                    && $changeNature != App\Models\Tenant\CompanyDirectorChange::CHANGE_NATURE_DIRECTOR_DECEASED
                    && $changeNature != App\Models\Tenant\CompanyDirectorChange::CHANGE_NATURE_DIRECTOR_RETIRED
                )
                    @if ($changeNature != App\Models\Tenant\CompanyDirectorChange::CHANGE_NATURE_CHANGED_OF_ID && $changeNature != App\Models\Tenant\CompanyDirectorChange::CHANGE_NATURE_CHANGED_OF_ADDRESS)
                        <div>
                            <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="designation">Designation</label>
                            <select class="form-input" id="designation" name="designation" wire:model.live="designation">
                                @foreach (App\Models\Tenant\CompanyDirector::$designations as $des)
                                <option value="{{ $des }}">{{ $des }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if ($changeNature == App\Models\Tenant\CompanyDirectorChange::CHANGE_NATURE_DIRECTOR_APPOINTED && $designation == App\Models\Tenant\CompanyDirector::DESIGNATION_ALTERNATEDIRECTOR)
                        <div>
                            <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="alternateTo">Alternate To</label>
                            <select class="form-input" id="alternateTo" name="alternateTo" wire:model="alternateTo">
                                <option value="">Select Director</option>
                                @foreach ($directors as $director)
                                <option value="{{ $director->id }}">{{ $director->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if ($changeNature == App\Models\Tenant\CompanyDirectorChange::CHANGE_NATURE_CHANGED_OF_ID || $changeNature == App\Models\Tenant\CompanyDirectorChange::CHANGE_NATURE_DIRECTOR_APPOINTED)
                        <div>
                            <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="idType">ID Type</label>
                            <select class="form-input" id="idType" name="idType" wire:model.live="idType">
                                <option value="Mykad">Mykad</option>
                                <option value="Passport">Passport</option>
                            </select>
                        </div>
                        <div>
                            <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="idNo">ID No</label>
                            <input wire:key='mykadid' class="form-input {{ $idType != 'Mykad' ? 'hidden' : ''}}" id="idNo" type="text" data-toggle="input-mask" data-mask-format="000000-00-0000" placeholder="xxxxxx-xx-xxxx" inputmode="text" wire:model="idNo">
                            <input wire:key='passportid' class="form-input {{ $idType == 'Mykad' ? 'hidden' : ''}}" id="idNo" type="text" wire:model="idNo">
                        </div>
                    @endif

                    @if ($changeNature == App\Models\Tenant\CompanyDirectorChange::CHANGE_NATURE_DIRECTOR_APPOINTED)
                        <div>
                            <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="gender">Gender</label>
                            <select class="form-input" id="gender" name="gender" wire:model="gender">
                                <option value="">Select Gender</option>
                                <option value="1">Male</option>
                                <option value="0">Female</option>
                            </select>
                        </div>
                    @endif

                    @if ($changeNature == App\Models\Tenant\CompanyDirectorChange::CHANGE_NATURE_DIRECTOR_APPOINTED || $id)
                        <div>
                            <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="email">Email @if(!$id)<span class="text-red-500">*</span>@endif</label>
                            <input class="form-input {{ $id ? 'bg-gray-100' : '' }}" id="email" type="email" wire:model="email" placeholder="director@example.com" {{ $id ? 'disabled' : '' }}>
                            <p class="text-xs text-gray-500 mt-1">{{ $id ? 'Email cannot be changed' : 'Required for COSEC login access' }}</p>
                        </div>
                        <div>
                            <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="password">Password</label>
                            <input class="form-input" id="password" type="password" wire:model="password" placeholder="{{ $id ? 'Leave blank to keep current' : 'Leave blank for default' }}">
                            <p class="text-xs text-gray-500 mt-1">{{ $id ? 'Only fill to change password' : 'Default: password123' }}</p>
                        </div>
                    @endif

                    @if ($changeNature == App\Models\Tenant\CompanyDirectorChange::CHANGE_NATURE_CHANGED_OF_ADDRESS || $changeNature == App\Models\Tenant\CompanyDirectorChange::CHANGE_NATURE_DIRECTOR_APPOINTED)
                        <div>
                            <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="addressLine1">Address Line 1</label>
                            <input class="form-input" id="addressLine1" type="text" wire:model="addressLine1">
                        </div>
                        <div>
                            <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="addressLine2">Address Line 2</label>
                            <input class="form-input" id="addressLine2" type="text" wire:model="addressLine2">
                        </div>
                        <div>
                            <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="addressLine3">Address Line 3</label>
                            <input class="form-input" id="addressLine3" type="text" wire:model="addressLine3">
                        </div>

                        <div>
                            <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="postcode">Postcode</label>
                            <input class="form-input" id="postcode" type="text" wire:model="postcode">
                        </div>
                        <div>
                            <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="town">Town</label>
                            <input class="form-input" id="town" type="text" wire:model="town">
                        </div>
                        <div>
                            <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="state">State</label>
                            <select class="form-select" id="state" wire:model="state">
                                <option value="">Select State</option>
                                @foreach (states() as $state)
                                    <option value="{{ $state }}">{{ $state }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="country">Country</label>
                            <select class="form-input" id="country" name="county" wire:model="country">
                                <option value="Malaysia">Malaysia</option>
                                <option value="Outside Malaysia">Outside Malaysia</option>
                            </select>
                        </div>
                    @endif
                @endif

                @if (!$isStart)
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="effectiveDate">Effective Date</label>
                    <input class="form-input" id="effectiveDate" type="date" wire:model="effectiveDate">
                </div>
                @endif
                <div class="col-span-2">
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="remarks">Remarks</label>
                    <input class="form-input" id="remarks" type="text" wire:model="remarks">
                </div>
            </div>
        </div>
        <div class="flex items-center justify-end gap-4 p-4 border-t dark:border-slate-700">
            <button class="text-white btn bg-primary" type="submit">
                <span>Save</span>
            </button>
        </div>
    </form>
</div>
@script
    <script>
        document.querySelectorAll('[data-toggle="input-mask"]').forEach(t => {
            var a = t.getAttribute("data-mask-format").toString().replaceAll("0", "9");
            t.setAttribute("data-mask-format", a);
            const e = new Inputmask(a);
            e.mask(t)
        }
        );

        flatpickr('#effectiveDate', {
            dateFormat: "Y-m-d",
            minDate: "{{ $company->current_year_from }}",
            defaultDate: "{{ $effectiveDate ?? now()->format('Y-m-d') }}",
            maxDate: "{{ $company->current_year_to }}"
        });
    </script>
@endscript
