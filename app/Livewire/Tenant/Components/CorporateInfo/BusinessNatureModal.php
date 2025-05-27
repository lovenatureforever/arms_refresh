<?php

namespace App\Livewire\Tenant\Components\CorporateInfo;

use App\Livewire\Tenant\Pages\CorporateInfo\BusinessNature;
use App\Models\Tenant\CompanyBusinessChange;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class BusinessNatureModal extends ModalComponent
{
    #[Locked]
    public $id;

    #[Locked]
    public $companyId;

    #[Locked]
    public $business;

    #[Locked]
    public $isStart;

    #[Validate('required')]
    public $paragraph1;

    public $paragraph2;

    #[Validate('required_if:isStart,false', message: 'Effective Date is required')]
    public $effectiveDate;

    public $remarks;

    public function mount($companyId, $id = null, $isStart)
    {
        if ($id) {
            $this->id = $id;
            $this->business = CompanyBusinessChange::find($id);
            $this->paragraph1 = $this->business->paragraph1;
            $this->paragraph2 = $this->business->paragraph2;
            $this->effectiveDate = $this->business->effective_date->format('Y-m-d');
            $this->remarks = $this->business->remarks;
        }

        $this->companyId = $companyId;
        $this->isStart = $isStart;
    }

    public function render()
    {
        return view('livewire.tenant.components.corporate-info.business-nature-modal');
    }

    public function submit()
    {
        $this->validate();
        if (!$this->id) {
            $this->business = CompanyBusinessChange::create(
                $this->formData($this->all(), $this->companyId)
            );

            $this->closeModalWithEvents([
                BusinessNature::class => 'successCreated'
            ]);
        } else {
            $this->business->update(
                $this->formData($this->all(), $this->companyId)
            );

            $this->closeModalWithEvents([
                BusinessNature::class => 'successUpdated'
            ]);
        }
    }

    private function formData($data, $companyId)
    {

        return [
            'company_id' => $companyId,
            'paragraph1' => $data['paragraph1'],
            'paragraph2' => $data['paragraph2'],
            'remarks' => $data['remarks'],
            'effective_date' => $data['effectiveDate'],
        ];
    }
}
