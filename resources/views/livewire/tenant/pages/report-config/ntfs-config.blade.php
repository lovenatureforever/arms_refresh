<div>
    <div class="mt-0">
        {{-- <h1 class="mb-4 text-2xl">Report Config</h1> --}}
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between m-4">
                    <h4 class="card-title">Report Configuration</h4>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 gap-5" data-fc-type="tab">
                    <nav class="flex-wrap rounded md:flex-col" role="tablist" aria-label="Tabs">
                        <a href="{{ route('reportconfig.director', ['id' => $id]) }}" class="btn border border-gray-100 mr-2 mb-2 bg-transparent">
                            Director's Report
                        </a>
                        <a href="#" class="btn border border-gray-100 mr-2 mb-2 bg-primary text-white">
                            NTFS
                        </a>
                    </nav>
                    <div class="col-span-4 p-4 mb-2 mr-2 border border-gray-100 rounded">
                        <div role="tabpanel">
                            <div class="overflow-x-auto">
                                <div class="inline-block min-w-full align-middle">
                                    <div class="overflow-auto">
                                        <div class="grid grid-cols-1 gap-5" data-fc-type="tab">
                                            <nav class="flex-wrap rounded md:flex-col" role="tablist" aria-label="Tabs">
                                                <button class="btn border border-gray-100 mr-2 mb-2 active {{ $this->currentTab == 'general-info' ? 'bg-secondary text-white' : 'bg-transparent' }}" type="button" role="tab" wire:click="changeTab('general-info')">
                                                    General Info
                                                </button>
                                                <button class="btn border border-gray-100 mr-2 mb-2 {{ $this->currentTab == 'sig-acc-policies' ? 'bg-secondary text-white' : 'bg-transparent' }}" type="button" role="tab" wire:click="changeTab('sig-acc-policies')">
                                                    Significant Accounting Policies
                                                </button>
                                                <button class="btn border border-gray-100 mr-2 mb-2 {{ $this->currentTab == 'est-uncertainties' ? 'bg-secondary text-white' : 'bg-transparent' }}" type="button" role="tab" wire:click="changeTab('est-uncertainties')">
                                                    Estimation Uncertainties
                                                </button>
                                                <button class="btn border border-gray-100 mr-2 mb-2 {{ $this->currentTab == '2sig-acc-policies' ? 'bg-secondary text-white' : 'bg-transparent' }}" type="button" role="tab" wire:click="changeTab('2sig-acc-policies')">
                                                    Notes
                                                </button>
                                                <button class="btn border border-gray-100 mr-2 mb-2 {{ $this->currentTab == '3sig-acc-policies' ? 'bg-secondary text-white' : 'bg-transparent' }}" type="button" role="tab" wire:click="changeTab('3sig-acc-policies')">
                                                    Other
                                                </button>
                                            </nav>
                                            <div class="col-span-4 p-0 mb-2 mr-2">
                                                @if ($this->currentTab == 'general-info')
                                                <div role="tabpanel">
                                                    <div>
                                                        @livewire('tenant.pages.report-config.ntfs.general-info', ['id' => $this->id], key('generalInfo'))
                                                    </div>
                                                </div>
                                                @elseif ($this->currentTab == 'sig-acc-policies')
                                                <div role="tabpanel">
                                                    @livewire('tenant.pages.report-config.ntfs.sig-acc-policies', ['id' => $this->id], key('sigAccPolicies'))
                                                </div>
                                                @elseif ($this->currentTab == 'est-uncertainties')
                                                <div role="tabpanel">
                                                    @livewire('tenant.pages.report-config.ntfs.est-uncertainties', ['id' => $this->id], key('estUncertainties'))
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
