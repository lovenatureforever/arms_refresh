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
                        <button class="btn border border-gray-100 mr-2 mb-2 active {{ $this->currentTab == 'directorReportConfig' ? 'bg-primary text-white' : 'bg-transparent' }}" type="button" role="tab" wire:click="changeTab('directorReportConfig')">
                            Director's Report
                        </button>
                        <button class="btn border border-gray-100 mr-2 mb-2 {{ $this->currentTab == 'ntfsConfig' ? 'bg-primary text-white' : 'bg-transparent' }}" type="button" role="tab" wire:click="changeTab('ntfsConfig')">
                            NTFS
                        </button>
                    </nav>
                    <div class="col-span-4 p-4 mb-2 mr-2 border border-gray-100 rounded">
                        @if ($this->currentTab == 'directorReportConfig')
                        <div role="tabpanel">
                            <div>
                                @livewire('tenant.pages.report-config.director-report-config', ['id' => $this->id], key('directorReportConfig'))
                            </div>
                        </div>
                        @elseif ($this->currentTab == 'ntfsConfig')
                        <div role="tabpanel">
                            @livewire('tenant.pages.report-config.ntfs-config', ['id' => $this->id], key('ntfsConfig'))
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
