<div class="grid grid-cols-1 gap-6">
    <!-- Header -->
    <div class="card">
        <div class="card-header flex justify-between items-center">
            <h4 class="card-title">Create E-Confirmation Request</h4>
            <a href="{{ route('econfirmation.index') }}" class="btn bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                <i class="mgc_arrow_left_line mr-1"></i> Back to Requests
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Company Info -->
        <div class="lg:col-span-1">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Company Info</h5>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Company <span class="text-red-500">*</span></label>
                        <select wire:model.live="selectedCompany" class="form-select">
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if($selectedCompanyData)
                        <div class="border-t dark:border-gray-700 pt-4 mt-4">
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Registration No:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $selectedCompanyData->registration_no ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Year End:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ $selectedCompanyData->current_year_to?->format('d M Y') ?? '-' }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Directors:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ $selectedCompanyData->directors->count() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Request Form -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Request Details</h5>
                </div>
                <div class="p-6">
                    @if($banks->isEmpty())
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <i class="mgc_bank_line text-4xl mb-2"></i>
                            <p>No banks found.</p>
                            <a href="{{ route('econfirmation.banks') }}" class="text-primary hover:underline mt-2 inline-block">
                                Add banks in Bank Registry
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Bank Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bank <span class="text-red-500">*</span></label>
                                <select wire:model.live="selectedBank" class="form-select">
                                    <option value="">-- Select a Bank --</option>
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->bank_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Branch Selection -->
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Branch <span class="text-red-500">*</span></label>
                                @if($selectedBranchData)
                                    <div class="flex items-center gap-2 p-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                                        <span class="flex-1 text-sm text-green-800 dark:text-green-200">
                                            {{ $selectedBranchData->branch_name ?: 'Main Branch' }}
                                        </span>
                                        <button wire:click="clearBranch" class="text-green-600 hover:text-green-800" title="Clear">
                                            <i class="mgc_close_line"></i>
                                        </button>
                                    </div>
                                @else
                                    <input type="text" wire:model.live.debounce.300ms="branchSearch" class="form-input" placeholder="Type to search branches..." @if(!$selectedBank) disabled @endif>

                                    @if(count($branches) > 0)
                                        <div class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                                            @foreach($branches as $branch)
                                                <div wire:click="selectBranch({{ $branch->id }})" class="p-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer border-b dark:border-gray-700 last:border-b-0">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $branch->branch_name ?: 'Main Branch' }}
                                                    </div>
                                                    @if($branch->full_address)
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $branch->full_address }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @endif
                                @error('selectedBranch') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Account No -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Account No <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="accountNo" class="form-input" placeholder="Enter account number">
                                @error('accountNo') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Charge Code -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Charge Code @if(!$chargeCodeUnavailable)<span class="text-red-500">*</span>@endif</label>
                                <input type="text" wire:model="chargeCode" class="form-input" placeholder="Enter charge code" @if($chargeCodeUnavailable) disabled @endif>
                                <div class="flex items-center mt-2">
                                    <input type="checkbox" wire:model.live="chargeCodeUnavailable" id="chargeCodeUnavailable" class="form-checkbox h-4 w-4 text-primary rounded">
                                    <label for="chargeCodeUnavailable" class="ml-2 text-sm text-gray-600 dark:text-gray-400">Charge Code Unavailable</label>
                                </div>
                                @error('chargeCode') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Approver -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Approver <span class="text-red-500">*</span></label>
                                <select wire:model="selectedApprover" class="form-select">
                                    <option value="">-- Select Approver --</option>
                                    @foreach($approvers as $auditor)
                                        <option value="{{ $auditor->user_id }}">{{ $auditor->user->name }} ({{ $auditor->title }})</option>
                                    @endforeach
                                </select>
                                @error('selectedApprover') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Validity Days -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Validity Period (Days) <span class="text-red-500">*</span></label>
                                <input type="number" wire:model="validityDays" class="form-input" min="7" max="30">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Directors will have this many days to sign.</p>
                                @error('validityDays') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Authorization Letter -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Authorization Letter <span class="text-red-500">*</span></label>
                                <input type="file" wire:model="authorizationLetter" class="form-input" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Upload authorization letter (PDF, DOC, DOCX, JPG, PNG)</p>
                                @error('authorizationLetter') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Client Consent Acknowledged -->
                            <div class="md:col-span-2">
                                <div class="flex items-start">
                                    <input type="checkbox" wire:model="clientConsentAcknowledged" id="clientConsentAcknowledged" class="form-checkbox h-4 w-4 text-primary rounded mt-1">
                                    <label for="clientConsentAcknowledged" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                        I have included the client's consent to submit confirmation requests via eConfirm.my and client's authorisation in my confirmation request letter. <span class="text-red-500">*</span>
                                    </label>
                                </div>
                                @error('clientConsentAcknowledged') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-6 pt-4 border-t dark:border-gray-700">
                            <button wire:click="createRequest" wire:loading.attr="disabled" class="btn bg-primary text-white">
                                <span wire:loading.remove wire:target="createRequest">
                                    <i class="mgc_add_line mr-1"></i> Create Request
                                </span>
                                <span wire:loading wire:target="createRequest">
                                    Creating...
                                </span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
