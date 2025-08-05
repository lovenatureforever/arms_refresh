<?php

namespace App\Livewire\Tenant\Components\ReportConfig;

use Exception;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use LivewireUI\Modal\ModalComponent;
use App\Models\Tenant\NtfsConfigItem;
use App\Livewire\Tenant\Pages\ReportConfig\Ntfs\GeneralInfo;

class UpdateNtfsGeneralTitleModal extends ModalComponent
{
    #[Locked]
    public $id;

    #[Validate('required')]
    public $title;

    public $config;

    public function mount($id)
    {
        $this->id = $id;
        $this->config = NtfsConfigItem::find($id);
        $this->title = $this->config->title;
    }

    public function render()
    {
        return view('livewire.tenant.components.report-config.update-ntfs-general-title-modal');
    }

    public function submit()
    {
        $this->validate();

        $this->config->title = $this->title;
        $this->config->save();

        $this->closeModalWithEvents([
            GeneralInfo::class => 'successUpdatedTitle'
        ]);
    }
}
