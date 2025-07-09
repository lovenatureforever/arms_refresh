<?php

namespace App\Livewire\Tenant\Components\CorporateInfo;


use App\Livewire\Tenant\Pages\CorporateInfo\Charges;
use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyChargeChange;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use LivewireUI\Modal\ModalComponent;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Log;

class ChargeModal extends ModalComponent
{
    #[Locked]
    public $id;

    #[Locked]
    public $companyId;

    #[Locked]
    public $chargeChange;

    #[Locked]
    public $selectedChargeChangeId;

    #[Locked]
    public $isStart;

    public $changeNature;

    #[Validate('required')]
    public $registeredNumber;

    #[Validate('required_if:changeNature, Create')]
    public $registrationDate;

    #[Validate('required_if:changeNature, Discharge')]
    public $dischargeDate;

    #[Validate('required_if:changeNature, Create')]
    public $chargeNature;

    #[Validate('required_if:changeNature, Create')]
    public $chargeeName;

    #[Validate('required_if:changeNature, Create')]
    public $indebtednessAmount;

    public $effectiveDate;

    public $remarks;

    #[Locked]
    public $createdCharges;

    #[Locked]
    public $company;

    public function mount($companyId, $id = null, $isStart)
    {
        $this->company = Company::find($companyId);
        $this->changeNature = CompanyChargeChange::CHANGE_NATURE_CREATE;
        if ($id) {
            $this->id = $id;
            $this->chargeChange = CompanyChargeChange::find($id);
            $this->changeNature = $this->chargeChange->change_nature;
            $this->registeredNumber = $this->chargeChange->registered_number;
            if ($this->changeNature === CompanyChargeChange::CHANGE_NATURE_DISCHARGE)
            {
                $this->registeredNumber = $this->chargeChange->creating_charge_id . '-' . $this->registeredNumber;
            }
            $this->registrationDate = $this->chargeChange->registration_date?->format('Y-m-d');
            $this->dischargeDate = $this->chargeChange->discharge_date?->format('Y-m-d');
            $this->chargeNature = $this->chargeChange->charge_nature;
            $this->chargeeName = $this->chargeChange->chargee_name;
            $this->indebtednessAmount = displayNumber($this->chargeChange->indebtedness_amount);
            $this->effectiveDate = $this->chargeChange->effective_date->format('Y-m-d');
            $this->remarks = $this->chargeChange->remarks;

            $this->selectedChargeChangeId = $this->chargeChange->creating_charge_id;
        }
        $this->companyId = $companyId;
        $this->isStart = $isStart;
        if ($this->isStart) {
            $this->effectiveDate = "2000-01-01";
        }
    }

    public function render()
    {
        $this->createdCharges = CompanyChargeChange::where('company_id', $this->companyId)
            ->where('change_nature', CompanyChargeChange::CHANGE_NATURE_CREATE)
            ->get();
        return view('livewire.tenant.components.corporate-info.charge-modal');
    }

    public function submit()
    {
        $this->validate();
        $this->validateEffectiveDate();
        // creating a charge_change record
        if (!$this->id) {
            if ($this->changeNature === CompanyChargeChange::CHANGE_NATURE_CREATE) {
                $this->chargeChange = CompanyChargeChange::create([
                    'company_id' => $this->companyId,
                    'registered_number' => $this->registeredNumber,
                    'registration_date' => $this->registrationDate,
                    'discharge_date' => $this->dischargeDate,
                    'change_nature' => $this->changeNature,
                    'charge_nature' => $this->chargeNature,
                    'chargee_name' => $this->chargeeName,
                    'indebtedness_amount' => readNumber($this->indebtednessAmount),
                    'effective_date' => $this->effectiveDate,
                    'remarks' => $this->remarks,
                ]);
            }
            else {
                $this->chargeChange = CompanyChargeChange::create([
                    'company_id' => $this->companyId,
                    'change_nature' => $this->changeNature,
                    'registered_number' => explode('-', $this->registeredNumber)[1],
                    'creating_charge_id' => explode('-', $this->registeredNumber)[0],
                    'discharge_date' => $this->dischargeDate,
                    'effective_date' => $this->effectiveDate,
                    'remarks' => $this->remarks,
                ]);
            }
            $this->closeModalWithEvents([
                Charges::class => 'successCreated'
            ]);
        }
        // Updating an existing secretary
        else {
            if ($this->changeNature === CompanyChargeChange::CHANGE_NATURE_CREATE) {
                $this->chargeChange->update([
                    'company_id' => $this->companyId,
                    'registered_number' => $this->registeredNumber,
                    'registration_date' => $this->registrationDate,
                    'discharge_date' => $this->dischargeDate,
                    'charge_nature' => $this->chargeNature,
                    'chargee_name' => $this->chargeeName,
                    'indebtedness_amount' => readNumber($this->indebtednessAmount),
                    'effective_date' => $this->effectiveDate,
                    'remarks' => $this->remarks,
                ]);
            }
            else {
                $this->chargeChange->update([
                    'company_id' => $this->companyId,
                    'registered_number' => explode('-', $this->registeredNumber)[1],
                    'creating_charge_id' => explode('-', $this->registeredNumber)[0],
                    'discharge_date' => $this->dischargeDate,
                    'effective_date' => $this->effectiveDate,
                    'remarks' => $this->remarks,
                ]);
            }
            $this->closeModalWithEvents([
                Charges::class => 'successUpdated'
            ]);
        }
    }

    public function updatedRegisteredNumber($value)
    {
        if ($this->changeNature === CompanyChargeChange::CHANGE_NATURE_DISCHARGE)
        {
            $this->selectedChargeChangeId = explode('-', $value)[0];
        }
    }

    private function validateEffectiveDate()
    {
        if ($this->isStart) return;
        $createdDate = null;
        $start = Carbon::make($this->company->current_year_from);
        if ($this->changeNature !== CompanyChargeChange::CHANGE_NATURE_CREATE) {
            $appointedChange = CompanyChargeChange::find($this->selectedChargeChangeId);

            $createdDate = $appointedChange ? $appointedChange->effective_date : null;
            if ($createdDate) {
                $appointed = Carbon::make($createdDate);
                $start = $appointed->gt($start) ? $appointed : $start;
            }
        }
        $this->validateOnly('effectiveDate', [
            'effectiveDate' => [
                'required',
                'date',
                'after:' . $start->format('Y-m-d'),
                'before_or_equal:' . $this->company->current_year_to->format('Y-m-d'),
            ],
        ]);
    }

}
