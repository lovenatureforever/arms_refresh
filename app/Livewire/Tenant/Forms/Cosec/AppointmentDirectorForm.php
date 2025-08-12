<?php

namespace App\Livewire\Tenant\Forms\Cosec;

use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Attributes\Validate;

class AppointmentDirectorForm extends Component {

    #[Validate('required')]
    public $name;

    #[Validate('required')]
    public $passport;

    #[Validate('required')]
    public $address;

    #[Validate('required|email')]
    public $email;

    #[Validate('required')]
    public $phone;

    public function render()
    {
        return view('livewire.tenant.forms.cosec.appointment-director-form');
    }

    #[On('requestAppointmentDirectorData')]
    public function sendData() {
        $this->validate();
        $this->dispatch('sendData', [
            'name' => $this->name,
            'passport' => $this->passport,
            'address' => $this->address,
            'email' => $this->email,
            'phone' => $this->phone,
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
