<?php

namespace App\Livewire\Tenant\Forms\Cosec;

use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;

class MakerOrCheckerForm extends Component {

    #[Locked]
    public $id;

    #[Validate('required')]
    public $nameOfBank;

    #[Validate('required')]
    public $numberOfBankAccount;

    #[Validate('required')]
    public $branch;

    #[Validate('required')]
    public $makerOrChecker;

    #[Validate('required')]
    public $changeMethod;

    #[Validate('sometimes|required_if:changeMethod, add|required_if:makerOrChecker,maker')]
    public $makerName;

    #[Validate('sometimes|required_if:changeMethod, add|required_if:makerOrChecker,maker')]
    public $makerPassport;

    #[Validate('sometimes|required_if:changeMethod, add|required_if:makerOrChecker,maker')]
    public $makerDesignation;

    #[Validate('sometimes|required_if:changeMethod, add|required_if:makerOrChecker,checker')]
    public $checkerName;

    #[Validate('sometimes|required_if:changeMethod, add|required_if:makerOrChecker,checker')]
    public $checkerPassport;

    #[Validate('sometimes|required_if:changeMethod, add|required_if:makerOrChecker,checker')]
    public $checkerDesignation;

    #[Validate('required_if:changeMethod, remove')]
    public $removedName;

    public function mount($id) {
        $this->id = $id;
    }

    public function render() {
        return view('livewire.tenant.forms.cosec.maker-checker');
    }

    #[On('requestMakerOrCheckerData')]
    public function sendData() {
        $this->validate();
        $this->dispatch('sendData', [
            'nameOfBank' => $this->nameOfBank,
            'numberOfBankAccount' => $this->numberOfBankAccount,
            'branch' => $this->branch,
            'makerOrChecker' => $this->makerOrChecker,
            'changeMethod' => $this->changeMethod,
            'makerName' => $this->makerName,
            'makerPassport' => $this->makerPassport,
            'makerDesignation' => $this->makerDesignation,
            'checkerName' => $this->checkerName,
            'checkerPassport' => $this->checkerPassport,
            'checkerDesignation' => $this->checkerDesignation,
            'removedName' => $this->removedName
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
