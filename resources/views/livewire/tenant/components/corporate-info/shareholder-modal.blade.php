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
            Create / Update Shareholder
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
                        <select class="form-input" id="changeNature" name="changeNature" wire:model="changeNature">
                            @foreach (App\Models\Tenant\CompanyShareholderChange::$changeNatures as $c)
                            <option value="{{ $c }}">{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                @if ($id == null)
                <div class="col-span-2 flex flex-row gap-2">
                    <div class="form-check">
                        <input type="checkbox" class="form-checkbox rounded text-primary" id="isNewShareholder" wire:model.live="isNewShareholder">
                        {{-- <input type="radio" class="form-radio text-primary" name="isNewShareholder" id="formRadio01" value="select" wire:model.live="creationMode"> --}}
                        <label class="ms-1.5" for="isNewShareholder">
                            Create New Shareholder
                        </label>
                    </div>
                </div>
                @endif

                {{-- @if ($isNewShareholder) --}}
                <div class="col-span-2 {{ !$isNewShareholder ? '' : 'hidden'}}">
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="selectedShareholder">Select Shareholder</label>
                    <select class="form-input" id="selectedShareholder" name="selectedShareholder" wire:model.live="selectedShareholder" wire:dirty.class="border-amber-500">
                        <option value="">--Select--</option>
                        @foreach ($this->shareholders as $shareholder)
                            <option value="{{ $shareholder['id'] }}">{{ $shareholder['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- @else --}}
                <div class="col-span-2 {{ $isNewShareholder ? '' : 'hidden'}}">
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="name">Name</label>
                    <input class="form-input" id="name" type="text" wire:dirty.class="border-amber-500" wire:model="name">
                </div>
                <div class="col-span-2 {{ $isNewShareholder ? '' : 'hidden'}}">
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="type">Type of Member</label>
                    <select class="form-input" id="type" name="type" wire:model.live="type" wire:dirty.class="border-amber-500">
                        <option value="">--Select--</option>
                        <option value="Individual">Individual</option>
                        <option value="Company">Company</option>
                    </select>
                </div>
                <div class="{{ $isNewShareholder ? '' : 'hidden'}}">
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="idType">ID Type</label>
                    <select class="form-input" id="idType" name="idType" wire:model.live="idType" wire:dirty.class="border-amber-500">
                        <option value="">--Select--</option>
                        @foreach ($this->idTypeList as $typeList)
                            <option value="{{ $typeList }}">{{ $typeList }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="{{ $isNewShareholder ? '' : 'hidden'}}">
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="idNo">ID Number</label>
                    {{-- <input class="form-input" id="idNo" type="text" wire:dirty.class="border-amber-500" wire:model="idNo"> --}}
                    <input wire:key='mykadid' class="form-input {{ $idType !== 'Mykad' ? 'hidden' : ''}}" id="idNo" type="text"
                        wire:dirty.class="border-amber-500" data-toggle="input-mask" data-mask-format="000000-00-0000"
                        placeholder="xxxxxx-xx-xxxx" inputmode="text" wire:model="idNo">
                    <input wire:key='passportid' class="form-input {{ $idType === 'Mykad' ? 'hidden' : ''}}" id="idNo" type="text"
                        wire:dirty.class="border-amber-500" wire:model="idNo">
                </div>
                @if ($this->type == 'Company')
                    <div class="{{ $isNewShareholder ? '' : 'hidden'}}">
                        <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="companyDomicile">Company Domicile</label>
                        <input class="form-input" id="companyDomicile" type="text" wire:dirty.class="border-amber-500" wire:model="companyDomicile">
                    </div>
                    <div class="{{ $isNewShareholder ? '' : 'hidden'}}">
                        <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="relatedDirectors">Common Director</label>
                        <select class="form-input" id="relatedDirectors" name="relatedDirectors" multiple wire:model="relatedDirectors">
                            @foreach ($this->directors as $director)
                                <option value="{{ $director['id'] }}">{{ $director['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                {{-- @endif --}}

                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="shareType">Type of Share</label>
                    <select class="form-input" id="shareType" name="shareType" wire:dirty.class="border-amber-500" wire:model="shareType">
                        <option value="Ordinary shares">Ordinary Shares</option>
                        <option value="Preference shares">Preference Shares</option>
                    </select>
                </div>
                <div>
                    <label class="inline-block mb-2 text-sm font-medium text-gray-800" for="shares">No. of Shares</label>
                    <input class="form-input numberInput" id="shares" type="text" wire:dirty.class="border-amber-500" wire:model="shares">
                </div>
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
        document.querySelectorAll('.numberInput').forEach(function (el) {
            new Cleave(el, {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand'
            });
        });

        flatpickr('#effectiveDate', {
            dateFormat: "Y-m-d",
            minDate: "{{ $company->current_year_from }}",
            defaultDate: "{{ $effectiveDate ?? now()->format('Y-m-d') }}",
            maxDate: "{{ $company->current_year_to }}"
        });
    </script>
@endscript
