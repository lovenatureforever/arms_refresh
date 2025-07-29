<?php

namespace App\Livewire\Tenant\Pages\ReportConfig;

use App\Models\ReportConfig;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\Tenant\CompanyDetailChange;
use App\Models\Tenant\DirectorReportConfig as TenantsDirectorReportConfig;

class ReportConfigs extends Component
{
    public $currentTab = 'directorReportConfig'; // defaultTab
    public $id;

    public function mount($id)
    {
        $this->id = $id;
    }
    public function changeTab($tabName)
    {
        $this->currentTab = $tabName;
    }

    public function render()
    {
        return view('livewire.tenant.pages.report-config.report-configs');
    }
}
