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
            Create / Update Secretary
        </h3>
        <button class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 dark:text-gray-200" wire:click.prevent="closeModal()">
            <span class="material-symbols-rounded">close</span>
        </button>
    </div>
    <form wire:submit="submit">
        <div class="px-4 py-8 overflow-y-auto">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                @if (!$isStart)
                    <div class="col-span-2">
                        <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="changeNature">Nature of Change</label>
                        <select class="form-input" id="changeNature" name="changeNature" wire:model.live="changeNature" {{ $id ? 'disabled' : '' }}>
                            @foreach (App\Models\Tenant\CompanySecretaryChange::$changeNatures as $c)
                            <option value="{{ $c }}">{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if ($changeNature === App\Models\Tenant\CompanySecretaryChange::CHANGE_NATURE_SECRETARY_APPOINTED)
                    <div class="col-span-2">
                        <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="name">Name</label>
                        <input class="form-input" id="name" type="text" wire:dirty.class="border-amber-500" wire:model="name">
                    </div>

                    <div class="">
                        <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="idType">ID Type</label>
                        <select class="form-input" id="idType" name="idType" wire:model.live="idType" wire:dirty.class="border-amber-500">
                            <option value="">--Select--</option>
                            @foreach ($this->idTypeList as $typeList)
                                <option value="{{ $typeList }}">{{ $typeList }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="">
                        <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="idNo">ID Number</label>
                        {{-- <input class="form-input" id="idNo" type="text" wire:dirty.class="border-amber-500" wire:model="idNo"> --}}
                        <input wire:key='mykadid' class="form-input {{ $idType !== 'Mykad' ? 'hidden' : ''}}" id="idNo" type="text"
                            wire:dirty.class="border-amber-500" data-toggle="input-mask" data-mask-format="000000-00-0000"
                            placeholder="xxxxxx-xx-xxxx" inputmode="text" wire:model="idNo">
                        <input wire:key='passportid' class="form-input {{ $idType === 'Mykad' ? 'hidden' : ''}}" id="idNo" type="text"
                            wire:dirty.class="border-amber-500" wire:model="idNo">
                    </div>
                    <div>
                        <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="secretaryNo">Secretary No.</label>
                        <input class="form-input" id="secretaryNo" type="text" wire:dirty.class="border-amber-500" wire:model="secretaryNo">
                    </div>
                    <div>
                        <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="licenseNo">License No.</label>
                        <input class="form-input" id="licenseNo" type="text" wire:dirty.class="border-amber-500" wire:model="licenseNo">
                    </div>
                    <div>
                        <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="ssmNo">SSM No.</label>
                        <input class="form-input" id="ssmNo" type="text" wire:dirty.class="border-amber-500" wire:model="ssmNo">
                    </div>
                    <div>
                        <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="email">Email</label>
                        <input class="form-input" id="email" type="email" wire:dirty.class="border-amber-500" wire:model="email" placeholder="secretary@example.com">
                    </div>
                    <div>
                        <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="phone">Phone</label>
                        <input class="form-input" id="phone" type="text" wire:dirty.class="border-amber-500" wire:model="phone">
                    </div>
                    <div>
                        <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="companyName">Company Name</label>
                        <input class="form-input" id="companyName" type="text" wire:dirty.class="border-amber-500" wire:model="companyName">
                    </div>
                    <div class="col-span-2">
                        <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="secretaryAddress">Address</label>
                        <textarea class="form-input" id="secretaryAddress" rows="2" wire:dirty.class="border-amber-500" wire:model="secretaryAddress"></textarea>
                    </div>
                @else
                    <div class="col-span-2">
                        <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="selectedSecretary">Select Secretary</label>
                        <select class="form-input" id="selectedSecretary" name="selectedSecretary" wire:model.live="selectedSecretary" wire:dirty.class="border-amber-500">
                            <option value="">--Select--</option>
                            @foreach ($this->secretaries as $secretary)
                                <option value="{{ $secretary['id'] }}">{{ $secretary['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if (!$isStart)
                    <div>
                        <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="effectiveDate">Effective Date</label>
                        <input class="form-input" id="effectiveDate" type="date" wire:dirty.class="border-amber-500" wire:model="effectiveDate">
                    </div>
                @endif
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="remarks">Remarks</label>
                    <input class="form-input" id="remarks" type="text" wire:dirty.class="border-amber-500" wire:model="remarks">
                </div>
            </div>
        </div>
        <div class="flex items-center justify-end gap-4 p-4 border-t dark:border-slate-700">
            <div>
                <button class="text-white btn bg-primary" type="submit" wire:loading.attr="disabled">
                    <div class="mr-2 inline-block h-4 w-4 animate-spin rounded-full border-[3px] border-current border-t-transparent text-white" role="status" aria-label="loading" wire:loading>
                        <span class="sr-only">Loading...</span>
                    </div>
                    <span wire:loading>Updating...</span>
                    <span wire:loading.remove>Save</span>
                </button>
            </div>
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
        });

        flatpickr('#effectiveDate', {
            dateFormat: "Y-m-d",
            minDate: "{{ $company->current_year_from }}",
            defaultDate: "{{ $effectiveDate ?? now()->format('Y-m-d') }}",
            maxDate: "{{ $company->end_date_report }}"
        });
    </script>
@endscript
