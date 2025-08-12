<?php

namespace App\Livewire\Tenant\Forms\Cosec;

use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\On;

class BranchAddressForm extends Component {

    #[Locked]
    public $id;

    #[Validate('required')]
    public $addOrClose;

    #[Validate('required')]
    public $fullAddress;

    #[Validate('required')]
    public $effectiveDate;

    #[Validate('required')]
    public $needExpressFilling;

    public function mount($id) {
        $this->id = $id;
    }

    public function render()
    {
        return view('livewire.tenant.forms.cosec.branch-address');
    }

    #[On('requestBranchAddressData')]
    public function sendData() {
        $this->validate();
        $this->dispatch('sendData', [
            'addOrClose' => $this->addOrClose,
            'fullAddress' => $this->fullAddress,
            'effectiveDate' => $this->effectiveDate,
            'needExpressFilling' => $this->needExpressFilling
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
