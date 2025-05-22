<div class="grid grid-cols-1 gap-6">
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title">Letterhead settings on Independent Auditors' Report (IAR)</h4>
                <button class="btn bg-primary text-white" type="button" wire:click="save">
                    Save
                </button>
            </div>
        </div>

        <div class="p-6">
            <div class="min-w-full inline-block align-middle">
                <div class="overflow-hidden">
                    <div class="flex items-center gap-2">
                        <div class="text-sm font-medium text-gray-800 dark:text-gray-200 w-60">
                            Letterhead on:
                        </div>
                        <div class="">
                            <input type="radio" class="form-radio text-success" id="letterheadFirstpage" name="letterhead" value="0" wire:model="isLetterheadRepeat">
                            <label class="ms-1.5" for="letterheadFirstpage">First page</label>
                        </div>
                        <div>
                            <input type="radio" class="form-radio text-success" id="letterheadEverypage" name="letterhead" value="1" wire:model="isLetterheadRepeat">
                            <label class="ms-1.5" for="letterheadEverypage">Every page</label>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 mt-5">
                        <div class="text-sm font-medium text-gray-800 dark:text-gray-200 w-60">
                            Use default letterhead:
                        </div>
                        <div class="">
                            <input type="checkbox" id="isDefaultLetterhead" class="form-switch text-primary" wire:model.live="isDefaultLetterhead">
                        </div>
                        <div class="ml-5 {{ $isDefaultLetterhead ? '' : 'opacity-70' }}">
                            (
                            <input type="checkbox" class="form-checkbox rounded text-primary" id="isFirmAddressUppercase" wire:model.live="isFirmAddressUppercase"  {{ $isDefaultLetterhead ? '' : 'disabled' }}>
                            <label class="ms-1.5" for="isFirmAddressUppercase">Address in uppercase</label>
                            )
                        </div>
                    </div>
                    <div class="flex items-center gap-2 mt-5">
                        <div class="text-sm font-medium text-gray-800 dark:text-gray-200 w-60">
                            Height to leave blank:
                        </div>
                        <div class="flex items-center gap-2">
                            <select class="form-select" id="blankHeaderSpacing" wire:model="blankHeaderSpacing">
                                <option value="">Select</option>
                                <option value="3.0">3.0</option>
                                <option value="3.5">3.5</option>
                                <option value="4.0">4.0</option>
                                <option value="4.5">4.5</option>
                                <option value="5.0">5.0</option>
                            </select>
                            <label for="blankHeaderSpacing">CM</label>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 mt-5">
                        <div class="text-sm font-medium text-gray-800 dark:text-gray-200 w-60">
                            Audit license no. position:
                        </div>
                        <div class="">
                            <input type="radio" class="form-radio text-success" id="withBreaklineNo" name="withBreakline" value="0" wire:model="withBreakline">
                            <label class="ms-1.5" for="withBreaklineNo">After firm name</label>
                        </div>
                        <div>
                            <input type="radio" class="form-radio text-success" id="withBreaklineYes" name="withBreakline" value="1" wire:model="withBreakline">
                            <label class="ms-1.5" for="withBreaklineYes">Break to new line</label>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 mt-5">
                        <div class="text-sm font-medium text-gray-800 dark:text-gray-200 w-60">
                            Audit Firm Description:
                        </div>
                        <div class="flex-grow">
                            <input class="form-input" id="auditFirmDescription" type="text" wire:dirty.class="border-amber-500" wire:model="auditFirmDescription">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title">Audit Firm Info</h4>
            </div>
        </div>

        <div class="p-6">
            @if (session()->has('error'))
                <div class="mb-4 rounded-md bg-danger/25 p-4 text-sm text-danger" role="alert">
                    <span class="font-bold">{{ session('error') }}</span>
                </div>
            @endif

            <form wire:submit="update">
                <div class="overflow-x-auto">
                    <div class="min-w-full inline-block align-middle">
                        <div class="border rounded-lg shadow-lg overflow-hidden dark:border-gray-700 dark:shadow-gray-900">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 w-40 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Firm Info</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400"></th>
                                        <th scope="col" class="px-6 py-3 w-40 text-xs font-medium text-gray-500 uppercase dark:text-gray-400 text-center">Show in Letterhead</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">Firm Name</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                            <input class="form-input" id="firmName" type="text" wire:dirty.class="border-amber-500" wire:model="firmName">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <input type="checkbox" class="form-checkbox rounded text-primary" id="isShowFirmName" wire:model="isShowFirmName">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">Firm Title</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                            <input class="form-input" id="firmTitle" type="text" wire:dirty.class="border-amber-500" wire:model="firmTitle">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <input type="checkbox" class="form-checkbox rounded text-primary" id="isShowFirmTitle" wire:model="isShowFirmTitle">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">Firm No.</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                            <input class="form-input" id="firmNo" type="text" wire:dirty.class="border-amber-500" wire:model="firmNo">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <input type="checkbox" class="form-checkbox rounded text-primary" id="isShowFirmNo" wire:model="isShowFirmNo">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">Firm Address</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                            <input class="form-input" id="firmAddress" type="text" wire:dirty.class="border-amber-500" wire:model="firmAddress" disabled>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <input type="checkbox" class="form-checkbox rounded text-primary" id="isShowFirmAddress" wire:model="isShowFirmAddress">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">Contact No.</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                            <input class="form-input" id="contactNo" type="text" wire:dirty.class="border-amber-500" wire:model="contactNo">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <input type="checkbox" class="form-checkbox rounded text-primary" id="isShowFirmContact" wire:model="isShowFirmContact">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">Fax No.</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                            <input class="form-input" id="faxNo" type="text" wire:dirty.class="border-amber-500" wire:model="faxNo">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <input type="checkbox" class="form-checkbox rounded text-primary" id="isShowFirmFax" wire:model="isShowFirmFax">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">Email</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                            <input class="form-input" id="email" type="text" wire:dirty.class="border-amber-500" wire:model="email">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <input type="checkbox" class="form-checkbox rounded text-primary" id="isShowFirmEmail" wire:model="isShowFirmEmail">
                                        </td>
                                    </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title">Audit Firm Address</h4>
                <div class="flex items-center gap-2">
                    <button class="btn btn-sm bg-primary text-white" type="button" wire:click="$dispatch('openModal', { component: 'tenant.components.audit-firm-address-modal', arguments: { id: null } })">
                        {{-- wire:click="$dispatch('openModal', {component: 'tenants.modal.business-address.create-update-business-address-modal', arguments: { id: null }})" --}}
                        New Address
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <div class="min-w-full inline-block align-middle">
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Set Default</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Branch</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Address Line 1</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Address Line 2</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Address Line 3</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Postcode</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Town</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">State</th>
                                <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($addresses as $address)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                    <input class="form-radio text-success" name="selectedAddress" id="selectedAddress_{{$address->id}}" type="radio" wire:model="selectedAddressId" value="{{ $address->id }}">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">HQ</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ $address->address_line1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">{{ $address->address_line2 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $address->address_line3 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $address->postcode }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $address->town }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $address->state }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                    <a href="javascript:void(0);" class="me-0.5" wire:click="$dispatch('openModal', { component: 'tenant.components.audit-firm-address-modal', arguments: { id: {{ $address->id }} } })"> <i class="mgc_edit_line text-lg text-blue-500"></i> </a>
                                    <a href="javascript:void(0);" class="ms-0.5" wire:confirm="Are you sure you want to delete this?" wire:click="deleteAddress({{ $address->id }})"> <i class="mgc_delete_line text-xl text-red-500"></i> </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
