<?php

namespace App\Livewire\Tenant\Forms\Cosec;

use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

class BusinessAddressForm extends Component {

    #[Locked]
    public $id;

    #[Validate('required')]
    public $changeOrNew;

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
        return view('livewire.tenant.forms.cosec.business-address');
    }

    #[On('requestBusinessAddressData')]
    public function sendData() {
        $this->validate();
        $this->dispatch('sendData', [
            'changeOrNew' => $this->changeOrNew,
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
