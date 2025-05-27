<?php

namespace App\Livewire\Tenant\Components\CorporateInfo;


use App\Livewire\Tenant\Pages\CorporateInfo\BusinessAddress;
use App\Models\Tenant\CompanyBizAddressChange;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use LivewireUI\Modal\ModalComponent;

class BusinessAddressModal extends ModalComponent
{
    #[Locked]
    public $id;

    #[Locked]
    public $companyId;

    #[Locked]
    public $address;

    #[Locked]
    public $isStart;

    #[Locked]
    public $changeNature;

    #[Validate('required|min:5')]
    public $addressLine1;

    public $addressLine2;

    public $addressLine3;

    #[Validate('required|min:5')]
    public $postcode;

    #[Validate('required')]
    public $town;

    #[Validate('required')]
    public $state;

    #[Validate('required')]
    public $country;

    #[Validate('required_if:isStart,false')]
    public $effectiveDate;

    public $remarks;

    public function mount($companyId, $id = null, $isStart)
    {
        $this->country = 'Malaysia';
        if ($id) {
            $this->id = $id;
            $this->address = CompanyBizAddressChange::find($id);
            $this->changeNature = $this->address->change_nature;
            $this->addressLine1 = $this->address->address_line1;
            $this->addressLine2 = $this->address->address_line2;
            $this->addressLine3 = $this->address->address_line3;
            $this->postcode = $this->address->postcode;
            $this->town = $this->address->town;
            $this->state = $this->address->state;
            $this->country = $this->address->country;
            $this->effectiveDate = $this->address->effective_date->format('Y-m-d');
            $this->remarks = $this->address->remarks;
        }
        $this->companyId = $companyId;

        $this->isStart = $isStart;
        if (!$this->isStart) {
            $this->changeNature = 'Change of business addresses';
        }
    }

    public function render()
    {
        return view('livewire.tenant.components.corporate-info.business-address-modal');
    }

    public function submit()
    {
        $this->validate();
        if (!$this->id) {
            $this->address = CompanyBizAddressChange::create(
                $this->formData($this->all(), $this->companyId)
            );

            $this->closeModalWithEvents([
                BusinessAddress::class => 'successCreated'
            ]);
        } else {
            $this->address->update(
                $this->formData($this->all(), $this->companyId)
            );
            $this->closeModalWithEvents([
                BusinessAddress::class => 'successUpdated'
            ]);
        }
    }

    private function formData($data, $id)
    {
        return [
            'company_id' => $id,
            'change_nature' => $this->changeNature,
            'address_line1' => trim($data['addressLine1']),
            'address_line2' => trim($data['addressLine2']),
            'address_line3' => trim($data['addressLine3']),
            'postcode' => trim($data['postcode']),
            'town' => trim($data['town']),
            'state' => trim($data['state']),
            'country' => trim($data['country']),
            'effective_date' => $data['effectiveDate'],
            'remarks' => $data['remarks'] ?? '',
        ];
    }
}
