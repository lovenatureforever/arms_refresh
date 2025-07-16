<div class="grid grid-cols-1 gap-4">
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title">Corporate Info </h4>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 gap-5" data-fc-type="tab">
                <nav class="flex-wrap rounded md:flex-col" role="tablist" aria-label="Tabs">
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-transparent" role="tab" href="{{ route('corporate.yearend', ['id' => $id]) }}">
                        Year End Info
                    </a>
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-transparent" role="tab" href="{{ route('corporate.companyname', ['id' => $id]) }}">
                        Company Name
                    </a>
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-transparent" role="tab" href="{{ route('corporate.businessnature', ['id' => $id]) }}">
                        Nature of Business
                    </a>
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-transparent" role="tab" href="{{ route('corporate.companyaddress', ['id' => $id]) }}">
                        Registered Address
                    </a>
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-transparent" role="tab" href="{{ route('corporate.businessaddress', ['id' => $id]) }}">
                        Business Address
                    </a>
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-transparent" role="tab" href="{{ route('corporate.sharecapital', ['id' => $id]) }}">
                        Share Capital
                    </a>
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-transparent" role="tab" href="{{ route('corporate.directors', ['id' => $id]) }}">
                        Directors
                    </a>
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-transparent" role="tab" href="{{ route('corporate.shareholders', ['id' => $id]) }}">
                        Shareholders
                    </a>
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-transparent" role="tab" href="{{ route('corporate.secretaries', ['id' => $id]) }}">
                        Secretaries
                    </a>
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-transparent" role="tab" href="{{ route('corporate.auditor', ['id' => $id]) }}">
                        Auditor
                    </a>
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-transparent" role="tab" href="{{ route('corporate.charges', ['id' => $id]) }}">
                        Charges
                    </a>
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-transparent" role="tab" href="{{ route('corporate.dividends', ['id' => $id]) }}">
                        Dividends
                    </a>
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-primary text-white active" role="tab" href="{{ route('corporate.reportinfo', ['id' => $id]) }}">
                        Report Info
                    </a>
                </nav>
                <div class="col-span-4 p-4 mb-2 mr-2 border border-gray-100 rounded">
                    <div role="tabpanel">
                        {{-- Detail Content --}}
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

                        <div class="flex flex-row justify-between mb-4">
                            <h1 class="text-xl">Director Representative</h1>
                        </div>
                        <div>
                            <div class="relative overflow-x-auto border rounded-lg">
                                <div class="grid grid-cols-5 text-xs font-bold text-center text-gray-700 uppercase border-b rounded-lg rounded-b-none bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <div class="flex items-center justify-center p-4 border-r">Director Name (Id No.)</div>
                                    <div class="flex items-center justify-center p-4 border-r">Alternate Director Name (Id No.)</div>
                                    <div class="flex items-center justify-center p-4 border-r">Alternate Director Signing</div>
                                    <div class="flex items-center justify-center p-4 border-r">Representative of Statutory Declaration</div>
                                    <div class="flex items-center justify-center p-4">Representative of Statement By Directors</div>
                                </div>
                                @foreach ($directors as $index => $director)
                                <div class="grid grid-cols-5 border-b" wire:key="director-{{ $director->id }}">
                                    <div class="p-4 border-r">{{ $director->name }} ({{ $director->id_no }})</div>
                                    <div class="p-4 border-r">
                                        @if ($director->alternate)
                                        {{ $director->alternate->name }} ({{ $director->alternate->id_no }})
                                        @endif
                                    </div>
                                    <div class="justify-center p-4 border-r">
                                        @if ($director->alternate)
                                        <label class="p-1 btn custom-btn-color" style="width:40px">
                                            <input type="checkbox" class="form-checkbox rounded" wire:model="isAlternateSignings.{{ $director->id }}">
                                        </label>
                                        @endif
                                    </div>
                                    <div class="justify-center p-4 border-r">
                                        <label class="p-1 btn custom-btn-color" style="width:40px">
                                            <input type="radio" class="form-radio" name="selectedDirectorForStatutory" value="{{ $director->id }}" wire:model="selectedDirectorForStatutory">
                                        </label>
                                    </div>
                                    <div class="justify-center p-4 border-r">
                                        <label class="p-1 btn custom-btn-color" style="width:40px">
                                            <input type="checkbox" class="form-checkbox rounded" wire:model="isRepresentatives.{{ $director->id }}">
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="flex flex-row mt-8 mb-4">
                                <div class="max-w-[450px] ml-5">
                                    <div class="py-4">
                                        <label for="statutory_declaration_sign" class="text-gray-700">Statutory declaration sign by officer: </label>
                                        <input type="checkbox" class="form-checkbox rounded ml-3" name="statutory_declaration_sign" id="statutory_declaration_sign" wire:model.live="isDeclarationOfficer">
                                    </div>

                                    <div class="flex items-center py-2">
                                        <label for="officer_name" class="text-nowrap w-[120px]">Officer Name: </label>
                                        <input type="text" class="form-input rounded ml-3" name="officer_name" id="officer_name" wire:model="officerName" {{ $isDeclarationOfficer ? '' : 'disabled' }}>
                                    </div>

                                    <div class="flex items-center py-2">
                                        <label for="id_type" class="text-nowrap w-[120px]">ID Type: </label>
                                        <select class="form-input rounded ml-3" name="id_type" id="id_type" wire:model="officerIdType" {{ $isDeclarationOfficer ? '' : 'disabled' }}>
                                            <option value="">--Select--</option>
                                            <option value="MyKad">MyKad</option>
                                            <option value="Passport">Passport</option>
                                        </select>
                                    </div>

                                    <div class="flex items-center py-2">
                                        <label for="id_number" class="text-nowrap w-[120px]">ID Number: </label>
                                        <input type="text" class="form-input rounded ml-3" name="id_number" id="id_number" wire:model="officerId" {{ $isDeclarationOfficer ? '' : 'disabled' }}>
                                    </div>
                                </div>

                                <div class="max-w-[450px] ml-12">
                                    <div class="py-4">
                                        <label for="isMiaMember" class="text-gray-700">Representative of Statutory Declaration is MIA member? </label>
                                        <input type="checkbox" class="form-checkbox rounded ml-3" name="isMiaMember" id="isMiaMember" wire:model.live="isDeclarationMia" {{ $isDeclarationOfficer ? '' : 'disabled' }}>
                                    </div>

                                    <div class="py-2">
                                        <label for="rep_statutory" class="text-gray-800 text-sm font-medium inline-block mb-2">Representative of Statutory Declaration: </label>
                                        <input type="text" class="form-input rounded" name="rep_statutory" id="rep_statutory" wire:model="repStatutory" disabled>
                                    </div>

                                    <div class="py-2">
                                        <label for="mia_number" class="text-gray-800 text-sm font-medium inline-block mb-2">MIA Number: </label>
                                        <input type="text" class="form-input rounded" name="mia_number" id="mia_number" wire:model="officerMiaNo" {{ $isDeclarationMia ? '' : 'disabled' }}>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="flex flex-row justify-between mt-8 mb-4">
                            <h1 class="text-xl">Other Report Info Setup</h1>
                        </div>
                        <div>
                            <div class="relative overflow-x-auto border rounded-lg">
                                <div class="grid grid-cols-4 text-xs font-bold text-center text-gray-700 uppercase border-b rounded-lg rounded-b-none bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <div class="flex items-center justify-center p-4 border-r">Report Type</div>
                                    <div class="flex items-center justify-center p-4 border-r">Location</div>
                                    <div class="flex items-center justify-center p-4 border-r">Date</div>
                                    <div class="flex items-center justify-center p-4">Date same as Director's report</div>
                                </div>

                                <form wire:submit="otherSubmit">
                                    <div class="grid grid-cols-4 border-b">
                                        <div class="p-4 border-r">Director's report</div>
                                        <div class="p-4 border-r">
                                            <input class="form-input w-36"
                                                id="director_report_location"
                                                type="text"
                                                wire:model="directorReportLocation"
                                                wire:dirty.class="border-yellow-500"
                                                {{-- wire:blur="directorReport" --}}
                                            >
                                        </div>
                                        <div class="p-4 border-r">
                                            <input class="form-input w-36"
                                                id="directorReportDate"
                                                type="text"
                                                wire:model="directorReportDate"
                                                wire:dirty.class="border-yellow-500"
                                                {{-- wire:blur="directorReport" --}}
                                            >
                                        </div>
                                        <div class="p-4">&nbsp;</div>
                                    </div>
                                    <div class="grid grid-cols-4 border-b">
                                        <div class="p-4 border-r">Statement by directors</div>
                                        <div class="p-4 border-r">
                                            <input class="form-input w-36"
                                                id="statement_location"
                                                type="text"
                                                wire:model="statementLocation"
                                                wire:dirty.class="border-yellow-500"
                                                {{-- wire:blur="statementReport" --}}
                                            >
                                        </div>
                                        <div class="p-4 border-r">
                                            <input class="form-input w-36"
                                                id="statementDate"
                                                type="text"
                                                wire:model="statementDate"
                                                wire:dirty.class="border-yellow-500"
                                                {{-- wire:blur="statementReport" --}}
                                            >
                                        </div>
                                        <div class="p-4">
                                            <input type="checkbox" class="form-checkbox rounded"
                                                id="statement_is_same"
                                                wire:model="statementAsReportDate"
                                                {{-- wire:blur="statementReport" --}}
                                            >
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-4 border-b">
                                        <div class="p-4 border-r">Statutory declaration</div>
                                        <div class="p-4 border-r">
                                            <input class="form-input w-36"
                                                id="statutory_location"
                                                type="text"
                                                wire:model="statutoryLocation"
                                                wire:dirty.class="border-yellow-500"
                                                {{-- wire:blur="statutoryReport" --}}
                                            >
                                        </div>
                                        <div class="p-4 border-r">
                                            <input class="form-input w-36"
                                                id="statutoryDate"
                                                type="text"
                                                wire:model="statutoryDate"
                                                wire:dirty.class="border-yellow-500"
                                                {{-- wire:blur="statutoryReport" --}}
                                            >
                                        </div>
                                        <div class="p-4">
                                            <input type="checkbox" class="form-checkbox rounded"
                                                id="statutory_is_same"
                                                wire:model="statutoryAsReportDate"
                                                {{-- wire:blur="statutoryReport" --}}
                                            >
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-4 border-b">
                                        <div class="p-4 border-r">Auditor's report</div>
                                        <div class="p-4 border-r">
                                            <input class="form-input w-36"
                                                id="auditor_location"
                                                type="text"
                                                wire:model="auditorReportLocation"
                                                wire:dirty.class="border-yellow-500"
                                                {{-- wire:blur="auditorReport" --}}
                                            >
                                        </div>
                                        <div class="p-4 border-r">
                                            <input class="form-input w-36"
                                                id="auditorReportDate"
                                                type="text"
                                                wire:model="auditorReportDate"
                                                wire:dirty.class="border-yellow-500"
                                                {{-- wire:blur="auditorReport" --}}
                                            >
                                        </div>
                                        <div class="p-4">
                                            <input type="checkbox" class="form-checkbox rounded"
                                                id="auditor_is_same"
                                                wire:model="auditorReportAsReportDate"
                                                {{-- wire:blur="auditorReport" --}}
                                            >
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-4 border-b">
                                        <div class="p-4 border-r">Circulation of financial report to member</div>
                                        <div class="p-4 border-r">
                                        </div>
                                        <div class="p-4 border-r">
                                            <input class="form-input w-36" id="circulationDate" type="text" wire:model="circulationDate" wire:dirty.class="border-yellow-500">
                                        </div>
                                        <div class="p-4">
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="relative overflow-x-auto border rounded-lg mt-5">
                                <div class="grid grid-cols-4 text-xs font-bold text-center text-gray-700 uppercase border-b rounded-lg rounded-b-none bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <div class="flex items-center justify-center p-4 border-r">Declaration location</div>
                                    <div class="flex items-center justify-center p-4 border-r">Foreign Country Act</div>
                                    <div class="flex items-center justify-center p-4 border-r">Appointed Authority</div>
                                    <div class="flex items-center justify-center p-4">Auditor Remuneration</div>
                                </div>

                                <div class="grid grid-cols-4 border-b">
                                    <div class="p-4 border-r">
                                        <select class="form-input rounded ml-3" name="id_type" id="id_type" wire:model.live="declarationCountry">
                                            <option value="">--Select--</option>
                                            <option value="Malaysia">Malaysia</option>
                                            <option value="Outside Malaysia">Outside Malaysia</option>
                                        </select>
                                    </div>
                                    <div class="p-4 border-r">
                                        <input type="text" class="form-input" wire:model="foreignAct" {{ $declarationCommissioner === "Malaysia" ? 'disabled' : '' }}>
                                    </div>
                                    <div class="p-4 border-r">
                                        <input type="text" class="form-input" wire:model="declarationCommissioner" {{ $declarationCommissioner === "Malaysia" ? 'disabled' : '' }}>
                                    </div>
                                    <div class="p-4">
                                        <input type="text" class="form-input numberInput" wire:model="auditorRemuneration">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-row justify-between mt-8 mb-4">
                            <h1 class="text-xl">Cover Page Endorsement</h1>
                        </div>
                        <div class="flex">
                            <div class="relative overflow-x-auto border rounded-lg">
                                <div class="grid grid-cols-2 text-xs font-bold text-center text-gray-700 uppercase border-b rounded-lg rounded-b-none bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <div class="flex items-center justify-center p-4 border-r">Sign by</div>
                                    <div class="flex items-center justify-center p-4"></div>
                                </div>

                                <div class="grid grid-cols-2 border-b">
                                    <div class="p-4 border-r">
                                        <select class="form-input" id="signByChange" name="signByChange" wire:model.live="coverSignPosition">
                                            <option value="">--Select--</option>
                                            <option value="Secretary">Secretary</option>
                                            <option value="Director">Director</option>
                                        </select>
                                    </div>
                                    <div class="p-4">
                                        <select class="form-input" id="signIdChange" name="signIdChange" wire:model.live="coverSignName">
                                            <option value="">--Select--</option>

                                        </select>
                                        {{-- @if ($this->signByChange == 'Secretary')
                                            <select class="form-input" id="signIdChange" name="signIdChange" wire:model.live="signIdChange">
                                                <option value="">--Select--</option>
                                                @foreach ($this->secretaryList as $nameList)
                                                    <option value="{{ $nameList['id'] }}">{{ $nameList['name'] }} ({{ $nameList['id_no'] }})</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <select class="form-input" id="signIdChange" name="signIdChange" wire:model.live="signIdChange" {{ $this->signByChange == null || $this->signByChange == '' ? 'disabled' : '' }}>
                                                <option value="">--Select--</option>
                                                @foreach ($this->directorList as $nameList)
                                                    <option value="{{ $nameList['id'] }}">{{ $nameList['name'] }}</option>
                                                @endforeach
                                            </select>
                                        @endif --}}
                                        <br/>
                                        @if ($coverSignPosition === 'Secretary')
                                            <input class="form-input mb-3" id="coverSignSecretaryNo" type="text" wire:dirty.class="border-amber-500" wire:model.live="coverSignSecretaryNo">
                                        @endif
                                        <input class="form-input" id="signTitleChange" type="text" wire:dirty.class="border-amber-500" wire:model.live="coverSignaturePosition" {{ $coverSignName == null || $coverSignPosition == '' ? 'disabled' : '' }}>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-4 mt-5 sticky end-12 bottom-0 w-full bg-white">
                            <div class="flex justify-end gap-3">
                                <button type="button" class="btn bg-danger text-white w-36" wire:click="resetForm">
                                    Cancel
                                </button>
                                <button type="button" class="btn bg-success text-white w-36" wire:click="save">
                                    Save
                                </button>
                            </div>
                        </div>
                        {{-- End Detail Content --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    document.querySelectorAll('.numberInput').forEach(function (el) {
        new Cleave(el, {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });
    });

    flatpickr('#circulationDate', {
        dateFormat: "Y-m-d",
        defaultDate: "{{ $circulationDate ?? now()->format('Y-m-d') }}",
    });
    flatpickr('#auditorReportDate', {
        dateFormat: "Y-m-d",
        defaultDate: "{{ $auditorReportDate ?? now()->format('Y-m-d') }}",
    });
    flatpickr('#statutoryDate', {
        dateFormat: "Y-m-d",
        defaultDate: "{{ $statutoryDate ?? now()->format('Y-m-d') }}",
    });
    flatpickr('#statementDate', {
        dateFormat: "Y-m-d",
        defaultDate: "{{ $statementDate ?? now()->format('Y-m-d') }}",
    });
    flatpickr('#directorReportDate', {
        dateFormat: "Y-m-d",
        defaultDate: "{{ $directorReportDate ?? now()->format('Y-m-d') }}",
    });
</script>
@endscript
