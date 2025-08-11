<?php

namespace App\Livewire\Tenant\Components\DataMigration;

use Exception;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use LivewireUI\Modal\ModalComponent;
use App\Livewire\Tenant\Pages\Report\NtfsMigration;
use App\Models\Tenant\CompanyReportNtfsSection;

class UpdateNtfsSectionModal extends ModalComponent
{
    #[Locked]
    public $id;

    #[Validate('required')]
    public $name;

    public $section;

    public function mount($id)
    {
        $this->id = $id;
        $this->section = CompanyReportNtfsSection::find($this->id);
        $this->name = $this->section->name;
    }

    public function render()
    {
        return view('livewire.tenant.components.data-migration.update-ntfs-section-modal');
    }

    public function submit()
    {
        $this->validate();

        $this->section->update([
            'name' => $this->name,
        ]);

        $this->closeModalWithEvents([
            NtfsMigration::class => 'successUpdatedSection'
        ]);
    }
}
