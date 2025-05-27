<?php

namespace App\Livewire\Tenant\Components\CorporateInfo;

use App\Livewire\Tenant\Pages\CorporateInfo\ShareCapital;
use App\Models\Tenant\CompanyShareCapitalChange;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use LivewireUI\Modal\ModalComponent;

class ShareCapitalModal extends ModalComponent
{
    #[Locked]
    public $id;

    #[Locked]
    public $companyId;

    #[Locked]
    public $shareCapital;

    #[Validate('required')]
    public $shareType;

    #[Validate('required')]
    public $allotmentType;

    #[Validate('required')]
    public $issuanceTerm;

    public $issuanceTermFreetext;

    #[Validate('required')]
    public $issuancePurpose;

    #[Validate('required')]
    public $fullyPaidShares;

    #[Validate('required')]
    public $fullyPaidAmount;

    #[Validate('required')]
    public $partiallyPaidShares;

    #[Validate('required')]
    public $partiallyPaidAmount;

    #[Validate('required')]
    public $effectiveDate;

    public $remarks;

    #[Locked]
    public $totalShares;

    #[Locked]
    public $totalAmount;

    public function mount($companyId, $id = null)
    {
        if ($id) {
            $this->id = $id;

            $this->shareCapital = CompanyShareCapitalChange::find($id);

            $this->shareType = $this->shareCapital->share_type;
            $this->allotmentType = $this->shareCapital->allotment_type;
            $this->issuanceTerm = $this->shareCapital->issuance_term;
            $this->issuanceTermFreetext = $this->shareCapital->issuance_term_freetext;
            $this->issuancePurpose = $this->shareCapital->issuance_purpose;
            $this->fullyPaidShares = displayNumber($this->shareCapital->fully_paid_shares);
            $this->fullyPaidAmount = displayNumber($this->shareCapital->fully_paid_amount);
            $this->partiallyPaidShares = displayNumber($this->shareCapital->partially_paid_shares);
            $this->partiallyPaidAmount = displayNumber($this->shareCapital->partially_paid_amount);
            $this->totalShares = displayNumber($this->shareCapital->fully_paid_shares + $this->shareCapital->partially_paid_shares);
            $this->totalAmount = displayNumber($this->shareCapital->fully_paid_amount + $this->shareCapital->partially_paid_amount);
            $this->effectiveDate = $this->shareCapital->effective_date->format('Y-m-d');
            $this->remarks = $this->shareCapital->remarks;
        }

        $this->companyId = $companyId;
    }

    public function render()
    {
        return view('livewire.tenant.components.corporate-info.share-capital-modal');
    }

    public function submit()
    {
        $this->validate();

        if (!$this->id) {
            $this->shareCapital = CompanyShareCapitalChange::create(
                $this->formData($this->all(), $this->companyId)
            );

            $this->closeModalWithEvents([
                ShareCapital::class => 'successCreated'
            ]);
        } else {
            $this->shareCapital->update(
                $this->formData($this->all(), $this->companyId)
            );

            $this->closeModalWithEvents([
                ShareCapital::class => 'successUpdated'
            ]);
        }
    }

    public function updated($name, $value)
    {
        $this->validateOnly($name);
        if ($name == 'fullyPaidAmount' || $name == 'partiallyPaidAmount') {
            $this->totalAmount = displayNumber(
                readNumber($this->fullyPaidAmount) +
                readNumber($this->partiallyPaidAmount)
            );
        }

        if ($name == 'fullyPaidShares' || $name == 'partiallyPaidShares') {
            $this->totalShares = displayNumber(
                readNumber($this->fullyPaidShares) +
                readNumber($this->partiallyPaidShares)
            );
        }
    }

    private function formData($data, $id)
    {
        return [
            'company_id' => $id,
            'share_type' => $data['shareType'],
            'allotment_type' => $data['allotmentType'],
            'issuance_term' => $data['issuanceTerm'],
            'issuance_purpose' => $data['issuancePurpose'],
            'issuance_term_freetext' => $data['issuanceTermFreetext'],
            'fully_paid_shares' => readNumber($data['fullyPaidShares']),
            'fully_paid_amount' => readNumber($data['fullyPaidAmount']),
            'partially_paid_shares' => readNumber($data['partiallyPaidShares']),
            'partially_paid_amount' => readNumber($data['partiallyPaidAmount']),
            'effective_date' => $data['effectiveDate'],
            'remarks' => $data['remarks'],
        ];
    }
}
