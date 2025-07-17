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
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-primary text-white active" role="tab" href="{{ route('corporate.auditor', ['id' => $id]) }}">
                        Auditor
                    </a>
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-transparent" role="tab" href="{{ route('corporate.charges', ['id' => $id]) }}">
                        Charges
                    </a>
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-transparent" role="tab" href="{{ route('corporate.dividends', ['id' => $id]) }}">
                        Dividends
                    </a>
                    <a class="btn border border-gray-100 mr-2 mb-2 bg-transparent" role="tab" href="{{ route('corporate.reportinfo', ['id' => $id]) }}">
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
                        <!-- Auditor Change Section -->
                        <div class="flex flex-row justify-between mb-4">
                            <h3 class="text-xl">Current year auditor</h3>
                        </div>
                        <table class="w-[500px]">
                            <tbody>
                                <tr class="">
                                    <td class="p-2 text-sm font-medium text-gray-800">Change of auditor?</td>
                                    <td class="p-2 flex items-center space-x-4">
                                        <label class="inline-flex items-center space-x-2">
                                            <input type="radio" name="SameOrNotSameFirm" value="1" class="form-radio" id="rd_notsame_firm" wire:model.live="auditFirmChanged" />
                                            <span>Yes</span>
                                        </label>
                                        <label class="inline-flex items-center space-x-2">
                                            <input type="radio" name="SameOrNotSameFirm" value="0" class="form-radio" id="rd_same_firm" wire:model.live="auditFirmChanged" />
                                            <span>No</span>
                                        </label>
                                    </td>
                                </tr>
                                <tr class="">
                                    <td class="p-2 text-sm font-medium text-gray-800">Previous Audit Firm Name</td>
                                    <td class="p-2">
                                        <input type="text" class="form-input" name="priorAuditFirm" id="priorAuditFirm" wire:model="priorAuditFirm" {{ $auditFirmChanged == '1' ? '' : 'disabled' }} />
                                    </td>
                                </tr>
                                <tr class="">
                                    <td class="p-2 text-sm font-medium text-gray-800">Prior year report date</td>
                                    <td class="p-2">
                                        <input class="form-input" type="text" placeholder="YYYY-MM-DD" id="priorReportDate" wire:model="priorReportDate" {{ $auditFirmChanged == '1' ? '' : 'disabled' }} />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-2 text-sm font-medium text-gray-800">Prior year report opinion</td>
                                    <td class="p-2">
                                        <select class="form-input" wire:model="priorReportOpinion" {{ $auditFirmChanged == '1' ? '' : 'disabled' }}>
                                            <option value="">-- Select --</option>
                                            <option value="unmodified">Unmodified</option>
                                            <option value="modified" selected>Modified</option>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- Letterhead Settings -->
                        <div class="flex flex-row justify-between mt-8 mb-4">
                            <h3 class="text-xl">Letterhead settings on Independent Auditors' Report (IAR)</h3>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <table class="table-auto w-full">
                                <tbody>
                                    <tr>
                                        <td class="p-2 text-sm font-medium text-gray-800">Letterhead on:</td>
                                        <td class="p-2 space-x-4">
                                            <label class="inline-flex items-center space-x-2 text-gray-800">
                                                <input type="radio" class="form-radio" name="letterhead" value="0" id="letterheadFirstpage" wire:model="isLetterheadRepeat" />
                                                <span>First Page</span>
                                            </label>
                                            <label class="inline-flex items-center space-x-2 text-gray-800">
                                                <input type="radio" class="form-radio" name="letterhead" value="1" id="letterheadEverypage" wire:model="isLetterheadRepeat" />
                                                <span>Every Page</span>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-2 text-sm font-medium text-gray-800">Use default letterhead:</td>
                                        <td class="p-2 space-y-2">
                                            <label class="inline-flex items-center space-x-2">
                                                <input type="radio" class="form-radio" name="DefaultLetterHead" value="1" id="is_default_yes" wire:model.live="isDefaultLetterhead" />
                                                <span>Yes</span>
                                            </label>
                                            <label for="cb_is_address_uppercase" class="ml-4 {{ $isDefaultLetterhead == '1' ? '' : 'opacity-70' }}">
                                                <input type="checkbox" class="form-checkbox rounded" id="cb_is_address_uppercase" wire:model="isFirmAddressUppercase" {{ $isDefaultLetterhead == '1' ? '' : 'disabled' }} />
                                                <span>Address in uppercase</span>
                                            </label>
                                            <br/>
                                            <label class="inline-flex items-center space-x-2">
                                                <input type="radio" class="form-radio" name="DefaultLetterHead" value="0" id="is_default_no" wire:model.live="isDefaultLetterhead" />
                                                <span>No <small>(Leave letterhead blank)</small></span>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-2 text-sm font-medium text-gray-800">Height to leave blank:</td>
                                        <td class="p-2">
                                            <div class="flex">
                                                <select id="blank_spacing" class="form-input ltr:rounded-r-none rtl:rounded-l-none w-24" wire:model="blankHeaderSpacing" {{ $isDefaultLetterhead == '1' ? 'disabled' : '' }}>
                                                    <option value="">-</option>
                                                    <option value="3.0">3.0</option>
                                                    <option value="3.5">3.5</option>
                                                    <option value="4.0">4.0</option>
                                                    <option value="4.5">4.5</option>
                                                    <option value="5.0">5.0</option>
                                                </select>
                                                <div class="inline-flex items-center px-4 rounded-e border border-s-0 border-gray-200 bg-gray-50 text-gray-500 dark:bg-gray-700 dark:border-gray-700 dark:text-gray-400">
                                                    CM
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-2 text-sm font-medium text-gray-800">Audit license no. position:</td>
                                        <td class="p-2 space-x-4">
                                            <label class="inline-flex items-center space-x-2 text-gray-800">
                                                <input type="radio" class="form-radio" name="AuditLicenseNoPosition" value="0" id="after_firm_name" wire:model="withBreakline" />
                                                <span>After firm name</span>
                                            </label>
                                            <label class="inline-flex items-center space-x-2 text-gray-800">
                                                <input type="radio" class="form-radio" name="AuditLicenseNoPosition" value="1" id="break_to_new_line" wire:model="withBreakline" />
                                                <span>Break to new line</span>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-2 text-sm font-medium text-gray-800">Audit Firm Description:</td>
                                        <td class="p-2 space-x-4">
                                            {{ $auditFirmDescription }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-5 border rounded-lg overflow-hidden dark:border-gray-700 w-[800px]">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-500">Firm Info</th>
                                        <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-500 border-r"></th>
                                        <th scope="col" class="px-6 py-3 text-center text-sm font-medium text-gray-500 w-[190px]">Show in Letterhead</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 border-r">Audit Firm Name</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 border-r">{{ tenant()->firmName }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <input type="checkbox" class="form-checkbox rounded" id="isShowFirmName" wire:model="isShowFirmName" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 border-r">Audit Firm Title</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 border-r">{{ tenant()->firmTitle }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <input type="checkbox" class="form-checkbox rounded" id="isShowFirmTitle" wire:model="isShowFirmTitle" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 border-r">Audit license no.</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 border-r">{{ tenant()->firmNo }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <input type="checkbox" class="form-checkbox rounded" id="isShowFirmLicense" wire:model="isShowFirmLicense" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 border-r">Address</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 border-r text-wrap">{{ $firmAddress }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <input type="checkbox" class="form-checkbox rounded" id="isShowFirmAddress" wire:model="isShowFirmAddress" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 border-r">Contact no.</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 border-r">{{ tenant()->firmContact }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <input type="checkbox" class="form-checkbox rounded" id="isShowFirmContact" wire:model="isShowFirmContact" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 border-r">Fax no.</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 border-r">{{ tenant()->firmFax }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <input type="checkbox" class="form-checkbox rounded" id="isShowFirmFax" wire:model="isShowFirmFax" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 border-r">Email address</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 border-r">{{ tenant()->firmEmail }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <input type="checkbox" class="form-checkbox rounded" id="isShowFirmEmail" wire:model="isShowFirmEmail" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="border rounded-lg overflow-hidden dark:border-gray-700 w-[600px] mt-6">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-500" colspan="3">Firm Info</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 border-r">Signing Auditor name</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200" colspan="2">{{ $auditorModel->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 border-r">Auditor title</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200" colspan="2">{{ $auditorModel->title }}</td>
                                    </tr>
                                    @foreach ($auditorModel->licenses as $license)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 border-r">
                                                @if ($loop->iteration == 1)
                                                    Auditor license no.
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 border-r text-wrap"><b>{{ $license->license_no }}</b><br> Effective Period: {{ $license->effective_date->format('Y-m-d') }} - {{ $license->expiry_date->format('Y-m-d') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <input type="radio" class="form-radio" name="selectedLicense" value="{{ $license->id }}" wire:model="selectedAuditorLicenseId" />
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="flex flex-row justify-between mt-8 mb-4">
                            <h3 class="text-xl">Audit Firm Address</h3>
                        </div>
                        <div class="border rounded-lg overflow-hidden dark:border-gray-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Display in Report</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Branch Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Address line 1</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Address line 2</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Address line 3</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Postcode</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Town</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">State</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($addresses as $address)
                                    <tr>
                                        <td class="px-6 py-4 text-center text-sm font-medium text-gray-800 dark:text-gray-200">
                                            <input type="radio" class="form-radio" name="selectedFirmAddress" id="selectedFirmAddress_{{$address->id}}" type="radio" wire:model="selectedFirmAddressId" value="{{ $address->id }}" />
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200">{{ $address->branch }}</td>
                                        <td class="px-6 py-4 text-wrap text-sm text-gray-800 dark:text-gray-200">{{ $address->address_line1 }}</td>
                                        <td class="px-6 py-4 text-wrap text-sm text-gray-800 dark:text-gray-200">{{ $address->address_line2 }}</td>
                                        <td class="px-6 py-4 text-wrap text-sm text-gray-800 dark:text-gray-200">{{ $address->address_line3 }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200">{{ $address->postcode }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200">{{ $address->town }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200">{{ $address->state }}</td>
                                    @endforeach
                                </tbody>
                            </table>
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
