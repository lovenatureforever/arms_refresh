<?php

namespace App\Livewire\Tenant\Forms\Cosec;

use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;

use App\Models\Tenant\CompanyShareholder;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\On;

class TransferShareForm extends Component{

    #[Locked]
    public $id;

    #[Validate('required')]
    public $transfereeExisted;

    #[Validate('required|numeric')]
    public $transferror;

    #[Validate('required_if:transfereeExisted, true')]
    public $transferee;

    #[Validate('required_if:transfereeExisted, false')]
    public $name;

    #[Validate('required_if:transfereeExisted, false')]
    public $passport;

    #[Validate('required_if:transfereeExisted, false|email')]
    public $email;

    #[Validate('required_if:transfereeExisted, false')]
    public $address;

    #[Validate('required_if:transfereeExisted, false')]
    public $phone;

    #[Validate('required|numeric')]
    public $sharesToTransfer;

    #[Validate('required|numeric')]
    public $amountConsideration;

    public function mount($id) {
        $this->id = $id;
    }

    public function render() {
        $shareholders = CompanyShareholder::where('company_id', $this->id)->where('is_active', true)->get();
        return view('livewire.tenant.forms.cosec.transfer-share-form', [
            'shareholders' => $shareholders
        ]);
    }

    #[On('requestTransferShareData')]
    public function sendData() {
        $this->validate();
        $transferror = CompanyShareholder::find($this->transferror)->name;
        if ($this->transfereeExisted == 'true') {
            $transferee = CompanyShareholder::find($this->transferee)->name;
        } else {
            $transferee = $this->name;
        }

        $this->dispatch('sendData', [
            'transfereeExisted' => $this->transfereeExisted,
            'transferror' => $this->transferror,
            'transferror_name' => $transferror,
            'transferee' => $this->transferee,
            'transferee_name' => $transferee,
            'name' => $this->name,
            'passport' => $this->passport,
            'email' => $this->email,
            'address' => $this->address,
            'phone' => $this->phone,
            'sharesToTransfer' => $this->sharesToTransfer,
            'amountConsideration' => $this->amountConsideration
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
