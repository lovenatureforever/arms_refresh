<?php

namespace App\Livewire\Tenant\Components\ReportConfig;

use Exception;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use LivewireUI\Modal\ModalComponent;
use App\Models\Tenant\NtfsConfigItem;
use App\Livewire\Tenant\Pages\ReportConfig\Ntfs\GeneralInfo;

class UpdateNtfsGeneralContentModal extends ModalComponent
{
    #[Locked]
    public $id;

    #[Validate('required')]
    public $content;

    public $config;

    public function mount($id)
    {
        $this->id = $id;
        $this->config = NtfsConfigItem::find($id);
        $this->content = $this->config->content;
    }

    public function render()
    {
        return view('livewire.tenant.components.report-config.update-ntfs-general-content-modal');
    }

    public function submit()
    {
        $this->validate();

        $this->config->content = $this->content;
        $this->config->save();

        $this->closeModalWithEvents([
            GeneralInfo::class => 'successUpdatedContent'
        ]);
    }
}
