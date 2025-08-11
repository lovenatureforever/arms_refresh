<?php

namespace App\Livewire\Tenant\Components\DataMigration;

use App\Models\Tenant\CompanyReportSoceCol;
use App\Models\Tenant\CompanyReportSoceRow;
use Exception;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use LivewireUI\Modal\ModalComponent;
use App\Livewire\Tenant\Pages\Report\DataMigration\SoceDataMigration;
use PhpOption\None;

class CreateColRowModal extends ModalComponent
{
    #[Locked]
    public $company_report_id;

    public $isRowOrCol = 'row';

    public $dataType = 'text';

    public $yearType = 'current';

    #[Validate('required')]
    public $name;

    public function mount($company_report_id)
    {
        $this->company_report_id = $company_report_id;
    }

    public function render()
    {
        return view('livewire.tenant.components.data-migration.create-col-row-modal');
    }

    public function submit()
    {
        $this->validate();

        if ($this->isRowOrCol == 'row') {
            $sort = null;
            if ($this->yearType == 'current') {
                $sort = '1.9';
            } else {
                $sort = '0.9';
            }
            CompanyReportSoceRow::create([
                'company_report_id' => $this->company_report_id,
                'name' => $this->name,
                'sort' => $sort,
            ]);
        } else {
            CompanyReportSoceCol::create([
                'company_report_id' => $this->company_report_id,
                'name' => $this->name,
                'data_type' => $this->dataType,
                'sort' => '8',
            ]);
        }

        $this->closeModalWithEvents([
            SoceDataMigration::class => 'successCreated'
        ]);
    }
}
