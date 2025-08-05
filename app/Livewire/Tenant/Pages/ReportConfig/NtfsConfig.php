<?php

namespace App\Livewire\Tenant\Pages\ReportConfig;

use App\Models\ReportConfig;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\Tenant\DirectorReportConfig as TenantsDirectorReportConfig;

class NtfsConfig extends Component
{
    #[Locked]
    public $id;

    public $currentTab = 'general-info'; // defaultTab

    public $dump;

    public function mount($id)
    {
        $this->id = $id;
    }

    public function render()
    {
        // $reportConfigs = TenantsDirectorReportConfig::where('company_id', $this->companyId)->get();

        return view('livewire.tenant.pages.report-config.ntfs-config', [
            // 'reportConfigs' => $reportConfigs
        ]);
    }

    public function changeTab($tabName)
    {
        $this->currentTab = $tabName;
    }

    public function updateDisplay($id, $value)
    {
        $config = TenantsDirectorReportConfig::find($id);
        $config->display = $value;
        $config->save();
    }

    // #[On('successUpdatedTitle')]
    // public function successUpdatedTitle()
    // {
    //     // session()->flash('success', 'Content was created');
    //     $this->alert('success', 'Title was updated!');
    //     $this->dump = now();
    // }

    #[On('successUpdated')]
    public function successUpdated()
    {
        // session()->flash('success', 'Content was updated');
        $this->alert('success', 'Item was updated!');
        $this->render();
    }

    public function deleteItem($id)
    {
        TenantsDirectorReportConfig::find($id)->delete();
    }
}
