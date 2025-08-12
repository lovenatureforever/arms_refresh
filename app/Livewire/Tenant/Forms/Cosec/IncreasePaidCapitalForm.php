<?php

namespace App\Livewire\Tenant\Forms\Cosec;

use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;

use App\Models\Tenant\CompanyShareholder;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\On;

class IncreasePaidCapitalForm extends Component {
    #[Locked]
    public $id;

    #[Validate('required')]
    public $increasedCapitalBy;

    #[Validate('required|numeric')]
    public $unitOfShareToIncrease;

    #[Validate('required|numeric')]
    public $pricePerShare;

    #[Validate('required|numeric')]
    public $totalAmountToIncrease;

    public $allottees = [];

    public function mount($id) {
        $this->id = $id;
        $this->addMoreAllotee();
    }

    public function addMoreAllotee()
    {
        $this->allottees[] = [
            'allotteeExisted' => '',
            'shareholder' => '',
            'name' => '',
            'passport' => '',
            'address' => '',
            'email' => '',
            'phone' => '',
            'unitShareToAllot' => '',
            'amountPaid' => '',
        ];
    }

    public function render()
    {
        $shareholders = CompanyShareholder::where('company_id', $this->id)->where('is_active', true)->get();
        return view('livewire.tenant.forms.cosec.increase-paid-capital', [
            'shareholders' => $shareholders
        ]);
    }

    #[On('requestIncreasePaidCapitalData')]
    public function sendData() {
        $this->resetErrorBag();
        $this->validate();

        if (count($this->allottees) == 0) {
            $this->addError('allottees', 'At least one allottee is required.');
            return;
        }

        foreach ($this->allottees as $index => $allottee) {
            if (empty($allottee['allotteeExisted'])) {
                $this->addError("allottees.$index.allotteeExisted", 'Please select if allottee existed or not.');
                return;
            }

            $allottee['allotteeExisted'] = $allottee['allotteeExisted'] == 'true';
            if ($allottee['allotteeExisted']) {
                $this->validate(["allottees.$index.shareholder" => 'required']);
                $shareholder = CompanyShareholder::find($allottee['shareholder']);
                $this->allottees[$index]['name'] = $shareholder->title;
            } else {
                $this->validate([
                    "allottees.$index.name" => 'required',
                    "allottees.$index.passport" => 'required',
                    "allottees.$index.address" => 'required',
                    "allottees.$index.email" => 'required|email',
                    "allottees.$index.phone" => 'required',
                    "allottees.$index.unitShareToAllot" => 'required|numeric',
                ]);
            }
        }

        $this->dispatch('sendData', [
            'increasedCapitalBy' => $this->increasedCapitalBy,
            'unitOfShareToIncrease' => $this->unitOfShareToIncrease,
            'pricePerShare' => $this->pricePerShare,
            'totalAmountToIncrease' => $this->totalAmountToIncrease,
            'allottees' => $this->allottees,
        ]);

        LivewireAlert::withOptions([
            "position" => "top-end",
            "icon" => "success",
            "title" => 'Added to cart successful!',
            "showConfirmButton" => false,
            "timer" => 1500
        ])->show();
    }

}
