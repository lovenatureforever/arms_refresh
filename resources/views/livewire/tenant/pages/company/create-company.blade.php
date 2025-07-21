<div class="grid grid-cols-1 gap-6">
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h4 class="card-title">Create Company</h4>
                <div class="flex items-center gap-2">
                    <a class="btn bg-warning text-white" type="button" href="{{ route('home') }}">Cancel</a>
                </div>
            </div>
        </div>

        <div class="p-6">
            @if (session()->has('error'))
            <div class="mb-4 rounded-md bg-danger/25 p-4 text-sm text-danger" role="alert">
                <span class="font-bold">{{ session('error') }}</span>
            </div>
            @endif

            <form wire:submit="create">
                <p class="my-4 text-xl text-slate-700 underline dark:text-slate-400">Company Detail</p>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="col-span-2">
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="companyName">Company Name</label>
                        <input class="form-input" id="companyName" type="text" wire:model="name">
                        @error('name')
                        <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-span-2">
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="registrationNo">Company Registration No</label>
                        <input class="form-input" id="registrationNo" type="text" wire:model="registrationNo">
                        @error('registrationNo')
                        <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-span-2">
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="registrationNoOld">Company Registration No (old)</label>
                        <input class="form-input" id="registrationNoOld" type="text" wire:model="registrationNoOld">
                        @error('registrationNoOld')
                        <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-span-2 grid grid-cols-3 gap-4">
                        <div>
                            <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="currentIsFirstYear">Current Year is First Year ?</label>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <input class="form-radio text-success" id="currentAuditYes" type="radio" value="1" wire:model.live="currentIsFirstYear">
                                    <label class="ms-1.5" for="currentAuditYes">Yes</label>
                                </div>
                                <div>
                                    <input class="form-radio text-danger" id="currentAuditNo " type="radio" value="0" wire:model.live="currentIsFirstYear">
                                    <label class="ms-1.5" for="currentAuditNo">No</label>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="currentYearPeriodType">Current Year Period Type</label>
                            <select class="form-select" id="currentYearPeriodType" wire:model.live="currentYearType" {{ $currentIsFirstYear ? 'disabled' : '' }}>
                                <option>Select</option>
                                <option value="partial year">Partial Year</option>
                                <option value="full year">Full Year</option>
                                <option value="first year" disabled>First Year</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="currentYearReportDisplay">Current Year Report Header Display Format</label>
                            <select class="form-select" id="currentYearReportDisplay" wire:model="currentReportHeaderFormat" {{ $currentIsFirstYear ? 'disabled' : '' }}>
                                <option>Select</option>
                                <option value="year">Year (YYYY)</option>
                                <option value="date">Date (DD.MM.YYYY)</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-span-2 grid grid-cols-3 gap-4">
                        <div>
                            <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="currentFinancialYear">Current Financial Year</label>
                            <input class="form-input" id="currentFinancialYear" type="text" wire:model="currentYear" placeholder="YYYY">
                            @error('currentYear')
                            <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="currentAuditPeriodFrom">Current Year Account Opening Date</label>
                            <input class="form-input" id="currentAuditPeriodFrom" name="date" type="text" wire:model="currentYearFrom">
                            @error('currentYearFrom')
                            <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="currentAuditPeriodTo">Current Year Account Closing Date</label>
                            <input class="form-input" id="currentAuditPeriodTo" name="date" type="text" wire:model="currentYearTo">
                            @error('currentYearTo')
                            <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-span-2 grid grid-cols-3 gap-4">
                        <div>
                            <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="lastIsFirstYear">Last Audit is First Year ?</label>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <input class="form-radio text-success" id="lastAuditYes" type="radio" value="1" wire:model.live="lastIsFirstYear" {{ $currentIsFirstYear ? 'disabled' : '' }}>
                                    <label class="ms-1.5" for="lastAuditYes">Yes</label>
                                </div>
                                <div>
                                    <input class="form-radio text-danger" id="lastAuditNo" type="radio" value="0" wire:model.live="lastIsFirstYear" {{ $currentIsFirstYear ? 'disabled' : '' }}>
                                    <label class="ms-1.5" for="lastAuditNo">No</label>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="lastYearPeriodType">Last Year Period Type</label>
                            <select class="form-select" id="lastYearPeriodType" wire:model.live="lastYearType" {{ $currentIsFirstYear || $lastIsFirstYear ? 'disabled' : '' }}>
                                <option>Select</option>
                                <option value="partial year">Partial Year</option>
                                <option value="full year">Full Year</option>
                                <option value="first year" disabled>First Year</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="lastYearReportDisplay">Last Year Report Header Display Format</label>
                            <select class="form-select" id="lastYearReportDisplay" wire:model="lastReportHeaderFormat" {{ $currentIsFirstYear || $lastIsFirstYear ? 'disabled' : '' }}>
                                <option>Select</option>
                                <option value="year">Year (YYYY)</option>
                                <option value="date">Date (DD.MM.YYYY)</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-span-2 grid grid-cols-3 gap-4">
                        <div>
                            <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="lastFinancialYear">Last Financial Year</label>
                            <input class="form-input" id="lastFinancialYear" type="text" wire:model="lastYear" placeholder="YYYY" {{ $currentIsFirstYear ? 'disabled' : '' }}>
                            @error('lastYear')
                            <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="lastAuditPeriodFrom">Last Year Account Opening Date</label>
                            <input class="form-input" id="lastAuditPeriodFrom" name="date" type="text" wire:model="lastYearFrom" {{ $currentIsFirstYear ? 'disabled' : '' }}>
                            @error('lastYearFrom')
                            <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="lastAuditPeriodTo">Last Year Account Closing Date</label>
                            <input class="form-input" id="lastAuditPeriodTo" name="date" type="text" wire:model="lastYearTo" {{ $currentIsFirstYear ? 'disabled' : '' }}>
                            @error('lastYearTo')
                            <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="auditFee">Audit Fee</label>
                        <input class="form-input numberInput" id="auditFee" type="text" wire:model="auditFee">
                        @error('auditFee')
                        <div class="pristine-error text-help" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- <div class="col-span-2 grid grid-cols-3 gap-4">
                        <div>
                            <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="partner">2nd Reviewer (Partner)</label>
                            <select class="form-input" id="partner" wire:model="partner" multiple>
                                @foreach ($partners as $partner)
                                <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="reviewer">Reviewer</label>
                            <select class="form-input" id="reviewer" wire:model="reviewer" multiple>
                                @foreach ($reviewers as $reviewer)
                                <option value="{{ $reviewer->id }}">{{ $reviewer->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 inline-block text-sm font-medium text-gray-800" for="executor">Executor</label>
                            <select class="form-input" id="executor" wire:model="executor" multiple>
                                @foreach ($executors as $executor)
                                <option value="{{ $executor->id }}">{{ $executor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}
                </div>

                <div>
                    <button class="btn mt-6 bg-primary text-white" type="submit" wire:loading.attr="disabled">
                        <div class="mr-2 inline-block h-4 w-4 animate-spin rounded-full border-[3px] border-current border-t-transparent text-white" role="status" aria-label="loading" wire:loading>
                            <span class="sr-only">Loading...</span>
                        </div>
                        <span wire:loading>Saving...</span>
                        <span wire:loading.remove>Submit</span>
                    </button>
                </div>
            </form>

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
    flatpickr('#currentAuditPeriodFrom', {
        dateFormat: "Y-m-d",
        defaultDate: "{{ $currentYearFrom ?? now()->format('Y-m-d') }}",
    });
    flatpickr('#currentAuditPeriodTo', {
        dateFormat: "Y-m-d",
        defaultDate: "{{ $currentYearTo ?? now()->format('Y-m-d') }}",
    });
    flatpickr('#lastAuditPeriodFrom', {
        dateFormat: "Y-m-d",
        defaultDate: "{{ $lastYearFrom ?? now()->format('Y-m-d') }}",
    });
    flatpickr('#lastAuditPeriodTo', {
        dateFormat: "Y-m-d",
        defaultDate: "{{ $lastYearTo ?? now()->format('Y-m-d') }}",
    });
</script>
@endscript
