<?php

namespace App\Livewire\Tenant\Components\CorporateInfo;

use App\Livewire\Tenant\Pages\CorporateInfo\CompanyName;
use App\Models\Tenant\CompanyDetailChange;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class CompanyNameModal extends ModalComponent
{
    #[Locked]
    public $id;

    #[Locked]
    public $companyId;

    #[Locked]
    public $companyDetail;

    #[Locked]
    public $isStart;

    #[Validate('required')]
    public $changeNature;

    public $effectiveDate;

    #[Validate('required')]
    public $companyName;

    #[Validate('required')]
    public $companyType;

    #[Validate('required')]
    public $presentationCurrency = 'Ringgit Malaysia';

    #[Validate('required')]
    public $presentationCurrencyCode = 'RM';

    #[Validate('required')]
    public $functionalCurrency = 'Ringgit Malaysia';

    #[Validate('required')]
    public $functionalCurrencyCode = 'RM';

    public $domicile = 'Malaysia';
    public $remarks;

    public function mount($companyId, $id = null, $isStart)
    {
        if ($id) {
            $this->id = $id;
            $this->companyDetail = CompanyDetailChange::find($this->id);
            $this->companyName = $this->companyDetail->name;
            $this->companyType = $this->companyDetail->company_type;
            $this->effectiveDate = $this->companyDetail->effective_date->format('Y-m-d');
            $this->presentationCurrency = $this->companyDetail->presentation_currency;
            $this->presentationCurrencyCode = $this->companyDetail->presentation_currency_code;
            $this->functionalCurrency = $this->companyDetail->functional_currency;
            $this->functionalCurrencyCode = $this->companyDetail->functional_currency_code;
            $this->domicile = $this->companyDetail->domicile;
            $this->remarks = $this->companyDetail->remarks;
        }

        $this->companyId = $companyId;
        $this->isStart = $isStart;
        $this->changeNature = "Change of company name";
    }

    public function render()
    {
        return view('livewire.tenant.components.corporate-info.company-name-modal');
    }

    public function submit()
    {
        if (!$this->isStart) {
            $this->withValidator(function ($validator) {
                $validator->after(function ($validator) {
                    if (!$this->effectiveDate) {
                        $validator->errors()->add('effectiveDate', 'Effective Date is required.');
                    }
                });
            });
        }
        $this->validate();
        if (!$this->id) { // for create
            $this->companyDetail = CompanyDetailChange::create(
                $this->formData($this->all(), $this->companyId)
            );
            $this->closeModalWithEvents([
                CompanyName::class => 'successCreated'
            ]);
        } else { // for update
            $this->companyDetail->update(
                $this->formData($this->all(), $this->companyId)
            );
            $this->closeModalWithEvents([
                CompanyName::class => 'successUpdated'
            ]);
        }
    }

    private function formData($data, $companyId)
    {

        return [
            'company_id' => $companyId,
            'nature_change' => $data['changeNature'],
            'name' => $data['companyName'],
            'company_type' => $data['companyType'],
            'presentation_currency' => $data['presentationCurrency'],
            'presentation_currency_code' => $data['presentationCurrencyCode'],
            'functional_currency' => $data['functionalCurrency'],
            'functional_currency_code' => $data['functionalCurrencyCode'],
            'domicile' => $data['domicile'],
            'effective_date' => $data['effectiveDate'],
            'remarks' => $data['remarks'],
        ];
    }
}
