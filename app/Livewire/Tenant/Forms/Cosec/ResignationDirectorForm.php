<?php

namespace App\Livewire\Tenant\Forms\Cosec;

use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;

use App\Models\Tenant\CompanyDirector;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\On;

class ResignationDirectorForm extends Component {

    #[Locked]
    public $id;

    #[Validate('required')]
    public $resignedDirector;

    public function mount($id) {
        $this->id = $id;
    }

    public function render() {
        $directors = CompanyDirector::where('company_id', $this->id)->where('is_active', true)->get();
        return view('livewire.tenant.forms.cosec.resignation-director-form', [
            'directors' => $directors
        ]);
    }

    #[On('requestResignationDirectorData')]
    public function sendData() {
        $this->validate();
        $director = CompanyDirector::find($this->resignedDirector);
        $this->dispatch('sendData', [
            'resignedDirector' => $this->resignedDirector,
            'name' => $director->name,
            'passport' => $director->id_no
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
