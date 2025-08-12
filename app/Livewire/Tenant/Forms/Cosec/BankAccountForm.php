<?php

namespace App\Livewire\Tenant\Forms\Cosec;

use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

class BankAccountForm extends Component {

    #[Locked]
    public $id;

    #[Validate('required')]
    public $nameOfBank;

    #[Validate('required')]
    public $branch;

    #[Validate('required')]
    public $address;

    #[Validate('required')]
    public $makerName;

    #[Validate('required')]
    public $makerPassport;

    #[Validate('required')]
    public $makerDesignation;

    #[Validate('required')]
    public $checkerName;

    #[Validate('required')]
    public $checkerPassport;

    #[Validate('required')]
    public $checkerDesignation;

    #[Validate('required')]
    public $lengthOfAuthorizedPersons;

    #[Validate('required')]
    public $authorizeCondition;

    public $limitedAuthorizedCondition;

    #[Validate('required')]
    public $shippingMethod;

    #[Validate('required')]
    public $shippingAddress;

    #[Validate('required')]
    public $shippingPic;

    #[Validate('required')]
    public $shippingContactNumber;

    public function mount($id) {
        $this->id = $id;
    }

    public function render() {
        return view('livewire.tenant.forms.cosec.bank-account');
    }

    #[On('requestBankAccountData')]
    public function sendData() {
        $this->validate();
        if ($this->authorizeCondition == 'limited' && !is_numeric($this->limitedAuthorizedCondition)) {
            $this->addError('limitedAuthorizedCondition', 'Persons field must be a number.');
            return;
        }

        $this->dispatch('sendData', [
            'nameOfBank' => $this->nameOfBank,
            'branch' => $this->branch,
            'address' => $this->address,
            'makerName' => $this->makerName,
            'makerPassport' => $this->makerPassport,
            'makerDesignation' => $this->makerDesignation,
            'checkerName' => $this->checkerName,
            'checkerPassport' => $this->checkerPassport,
            'checkerDesignation' => $this->checkerDesignation,
            'lengthOfAuthorizedPersons' => $this->lengthOfAuthorizedPersons,
            'authorizeCondition' => $this->authorizeCondition,
            'limitedAuthorizedCondition' => $this->limitedAuthorizedCondition,
            'shippingMethod' => $this->shippingMethod,
            'shippingAddress' => $this->shippingAddress,
            'shippingPic' => $this->shippingPic,
            'shippingContactNumber' => $this->shippingContactNumber,
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
