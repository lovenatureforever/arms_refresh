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
use PhpOption\None;

class CreateNtfsSectionModal extends ModalComponent
{
    #[Locked]
    public $company_report_id;

    #[Locked]
    public $company_report_item_id;

    public $type = 2;

    #[Validate('required')]
    public $name;

    public function mount($company_report_id, $company_report_item_id)
    {
        $this->company_report_id = $company_report_id;
        $this->company_report_item_id = $company_report_item_id;
    }

    public function render()
    {
        return view('livewire.tenant.components.data-migration.create-ntfs-section-modal');
    }

    public function submit()
    {
        $this->validate();

        $newSection = CompanyReportNtfsSection::create([
            'company_report_id' => $this->company_report_id,
            'name' => $this->name,
            'type' => $this->type,
            'company_report_item_id' => $this->company_report_item_id,
        ]);
        $newSection->sort = $newSection->id;
        $newSection->save();

        $this->closeModalWithEvents([
            NtfsMigration::class => 'successCreatedSection'
        ]);
    }
}
