<?php

namespace App\Livewire\Tenant\Components\DataMigration;

use Exception;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use LivewireUI\Modal\ModalComponent;
use App\Livewire\Tenant\Pages\Report\NtfsMigration;
use App\Models\Tenant\CompanyReportNtfsItem;
use App\Models\Tenant\CompanyReportNtfsSection;
use PhpOption\None;

class CreateNtfsItemModal extends ModalComponent
{
    #[Locked]
    public $section_id;

    #[Validate('required')]
    public $name;

    public function mount($section_id)
    {
        $this->section_id = $section_id;
    }

    public function render()
    {
        return view('livewire.tenant.components.data-migration.create-ntfs-item-modal');
    }

    public function submit()
    {
        $this->validate();

        CompanyReportNtfsItem::create([
            'name' => $this->name,
            'company_report_ntfs_section_id' => $this->section_id,
        ]);

        $this->closeModalWithEvents([
            NtfsMigration::class => 'successCreatedSection'
        ]);
    }
}
