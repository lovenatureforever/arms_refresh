<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use Livewire\Component;
use Livewire\Attributes\Locked;
use App\Livewire\Tenant\OrderService;
use Livewire\Attributes\On;

class IndexCosec extends Component
{

    public $selectedOption;

    public $services;
    public $serviceCost;

    #[Locked]
    public $id;

    public function mount($id)
    {
        $this->id = $id;
        $services = \App\Models\Tenant\CosecService::all();
        $this->services = $services->pluck('name')->toArray();
        $this->serviceCost = $services->pluck('cost')->toArray();
    }

    public function requestData()
    {

        $this->validate([
            'selectedOption' => 'required'
        ]);

        switch ($this->selectedOption) {
            case 1:
                $this->dispatch('requestAppointmentDirectorData');
                break;

            case 2:
                $this->dispatch('requestResignationDirectorData');
                break;

            case 3:
                $this->dispatch('requestTransferShareData');
                break;

            case 4:
                $this->dispatch('requestIncreasePaidCapitalData');
                break;

            case 5:
                $this->dispatch('requestBusinessAddressData');
                break;

            case 6:
                $this->dispatch('requestBranchAddressData');
                break;

            case 7:
                $this->dispatch('requestBankAccountData');
                break;

            case 8:
                $this->dispatch('requestBankSignatoriesData');
                break;

            case 9:
                $this->dispatch('requestMakerOrCheckerData');
                break;
        }
    }

    public function render()
    {
        return view('livewire.tenant.pages.cosec.index', [

        ]);
    }

    #[On('sendData')]
    public function addToOrder(OrderService $orderService, $data) {
        if ($this->selectedOption < 1 || $this->selectedOption > 9) {
            return;
        }

        $orderService->addToOrder(
            $this->selectedOption,
            $this->services[$this->selectedOption - 1],
            $this->serviceCost[$this->selectedOption - 1],
            $data
        );
    }

}
