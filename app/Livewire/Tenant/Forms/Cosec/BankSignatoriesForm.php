<?php

namespace App\Livewire\Tenant\Forms\Cosec;

use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\On;

class BankSignatoriesForm extends Component {

    #[Locked]
    public $id;

    #[Validate('required')]
    public $nameOfBank;

    #[Validate('required')]
    public $numberOfBankAccount;

    #[Validate('required')]
    public $branch;

    #[Validate('required')]
    public $lengthOfAuthorizedPersons;

    #[Validate('required')]
    public $authorizeCondition;

    public $limitedAuthorizedCondition;

    protected $listeners = ['requestBankSignatoriesData' => 'sendData'];

    public function mount($id) {
        $this->id = $id;
    }

    public function render() {
        return view('livewire.tenant.forms.cosec.bank-signatories');
    }

    #[On('requestBankSignatoriesData')]
    public function sendData() {
        $this->validate();
        if ($this->authorizeCondition == 'limited' && !is_numeric($this->limitedAuthorizedCondition)) {
            $this->addError('limitedAuthorizedCondition', 'Persons field must be a number.');
            return;
        }

        $this->dispatch('sendData', [
            'nameOfBank' => $this->nameOfBank,
            'numberOfBankAccount' => $this->numberOfBankAccount,
            'branch' => $this->branch,
            'lengthOfAuthorizedPersons' => $this->lengthOfAuthorizedPersons,
            'authorizeCondition' => $this->authorizeCondition,
            'limitedAuthorizedCondition' => $this->limitedAuthorizedCondition
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
