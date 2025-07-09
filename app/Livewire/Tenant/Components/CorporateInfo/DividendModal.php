<?php

namespace App\Livewire\Tenant\Components\CorporateInfo;


use App\Livewire\Tenant\Pages\CorporateInfo\Dividends;
use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyDividendChange;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use LivewireUI\Modal\ModalComponent;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Log;

class DividendModal extends ModalComponent
{
    #[Locked]
    public $id;

    #[Locked]
    public $companyId;

    #[Locked]
    public $dividend;

    #[Locked]
    public $isDeclared;


    #[Validate('required_if:isDeclared, true')]
    public $declaredDate;

    #[Validate('required_if:isDeclared, true')]
    public $paymentDate;

    #[Validate('required')]
    public $yearEnd;

    #[Validate('required')]
    public $shareType;

    #[Validate('required')]
    public $dividendType;

    public $isFreeText;

    #[Validate('required')]
    public $rateUnit;

    #[Validate('required')]
    public $rate;

    #[Validate('required')]
    public $amount;

    public $effectiveDate;

    public $remarks;

    #[Locked]
    public $shareTypes;

    #[Locked]
    public $dividendTypes;

    #[Locked]
    public $rateUnits;

    #[Locked]
    public $company;

    public function mount($companyId, $id = null, $isDeclared)
    {
        $this->isDeclared = $isDeclared;
        $this->companyId = $companyId;
        $this->company = Company::find($companyId);
        $this->shareTypes = CompanyDividendChange::$shareTypes;
        $this->dividendTypes = CompanyDividendChange::$dividendTypes;
        $this->rateUnits = CompanyDividendChange::$rateUnits;
        $this->rateUnit = CompanyDividendChange::RATEUNIT_RM;
        $this->isFreeText = false;
        $this->yearEnd = $this->company->current_year_to->format('Y-m-d');
        $this->shareType = CompanyDividendChange::SHARETYPE_ORDINARY;
        $this->dividendType = CompanyDividendChange::DIVIDENDTYPE_INTERIM_SINGLE_TIER;
        if ($id) {
            $this->id = $id;
            $this->dividend = CompanyDividendChange::find($id);
            if ($this->dividend) {
                $this->declaredDate = $this->dividend->declared_date?->format('Y-m-d');
                $this->paymentDate = $this->dividend->payment_date?->format('Y-m-d');
                $this->yearEnd = $this->dividend->year_end?->format('Y-m-d');
                $this->shareType = $this->dividend->share_type;
                $this->dividendType = $this->dividend->dividend_type;
                $this->isFreeText = $this->dividend->is_free_text;
                $this->rateUnit = $this->dividend->rate_unit;
                $this->rate = displayNumber($this->dividend->rate);
                $this->amount = displayNumber($this->dividend->amount);
                $this->effectiveDate = $this->dividend->effective_date?->format('Y-m-d');
                $this->remarks = $this->dividend->remarks;
            }
        }
    }

    public function render()
    {
        return view('livewire.tenant.components.corporate-info.dividend-modal');
    }

    public function submit()
    {
        $this->validate();
        $this->validateDeclaredDate();
        $this->validatePaymentDate();
        $this->validateEffectiveDate();
        // creating a charge_change record
        if (!$this->id) {
            $this->dividend = CompanyDividendChange::create([
                'company_id' => $this->companyId,
                'is_declared' => $this->isDeclared,
                'declared_date' => $this->declaredDate,
                'payment_date' => $this->paymentDate,
                'year_end' => $this->yearEnd,
                'share_type' => $this->shareType,
                'dividend_type' => $this->dividendType,
                'is_free_text' => $this->isFreeText,
                'rate_unit' => $this->rateUnit,
                'rate' => readNumber($this->rate),
                'amount' => readNumber($this->amount),
                'effective_date' => $this->effectiveDate,
                'remarks' => $this->remarks,
            ]);
            $this->closeModalWithEvents([
                Dividends::class => 'successCreated'
            ]);
        }
        // Updating an existing secretary
        else {
            $this->dividend->update([
                'is_declared' => $this->isDeclared,
                'declared_date' => $this->declaredDate,
                'payment_date' => $this->paymentDate,
                'year_end' => $this->yearEnd,
                'share_type' => $this->shareType,
                'dividend_type' => $this->dividendType,
                'is_free_text' => $this->isFreeText,
                'rate_unit' => $this->rateUnit,
                'rate' => readNumber($this->rate),
                'amount' => readNumber($this->amount),
                'effective_date' => $this->effectiveDate,
                'remarks' => $this->remarks,
            ]);
            $this->closeModalWithEvents([
                Dividends::class => 'successUpdated'
            ]);
        }
    }

    public function updatedIsFreeText($value)
    {
        if ($value === true) {
            $this->dividendType = '';
        }
    }

    private function validateEffectiveDate()
    {
        $startDate = Carbon::make($this->company->current_year_from);
        $endDate = Carbon::make($this->company->end_date_report);
        if ($this->isDeclared) {
            Carbon::parse($this->declaredDate)->isBefore($startDate) ?: $startDate = Carbon::parse($this->declaredDate);
        }
        $this->validateOnly('effectiveDate', [
            'effectiveDate' => [
                'required',
                'date',
                'after:' . $startDate->format('Y-m-d'),
                'before_or_equal:' . $endDate->format('Y-m-d'),
            ],
        ]);
    }

    private function validateDeclaredDate()
    {
        if (!$this->isDeclared) return;
        $startDate = Carbon::make($this->company->current_year_from);
        $endDate = Carbon::make($this->company->end_date_report);

        if ($this->paymentDate) {
            $endDate = Carbon::parse($this->paymentDate)->isBefore($endDate) ? Carbon::parse($this->paymentDate) : $endDate;
        }
        if ($this->effectiveDate) {
            $endDate = Carbon::parse($this->effectiveDate)->isBefore($endDate) ? Carbon::parse($this->effectiveDate) : $endDate;
        }
        $this->validateOnly('declaredDate', [
            'declaredDate' => [
                'required',
                'date',
                'after:' . $startDate->format('Y-m-d'),
                'before_or_equal:' . $endDate->format('Y-m-d'),
            ],
        ]);
    }

    private function validatePaymentDate()
    {
        if (!$this->is_declared) return;
        $startDate = Carbon::make($this->company->current_year_from);
        $endDate = Carbon::make($this->company->end_date_report);
        Carbon::parse($this->declaredDate)->isBefore($startDate) ?: $startDate = Carbon::parse($this->declaredDate);
        $this->validateOnly('paymentDate', [
            'paymentDate' => [
                'required',
                'date',
                'after:' . $startDate->format('Y-m-d'),
                'before_or_equal:' . $endDate->format('Y-m-d'),
            ],
        ]);
    }

}
