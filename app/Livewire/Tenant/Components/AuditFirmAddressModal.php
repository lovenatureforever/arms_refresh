<?php

namespace App\Livewire\Tenant\Components;

use App\Livewire\Tenant\Pages\AuditFirm\ShowAuditFirm;
use App\Models\Tenant\AuditFirmAddress;
use Exception;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class AuditFirmAddressModal extends ModalComponent
{
    #[Locked]
    public $id;

    public $firmBranch;

    #[Validate('required|min:3')]
    public $address1;
    public $address2;
    public $address3;
    public $postcode;
    public $town;
    public $state;

    public $firmAddress;

    public function mount($id = null)
    {
        $this->id = $id;
        $this->firmAddress = AuditFirmAddress::find($id);
        if ($this->firmAddress) {
            $this->id = $this->firmAddress->id;
            $this->firmBranch = $this->firmAddress->branch;
            $this->address1 = $this->firmAddress->address_line1;
            $this->address2 = $this->firmAddress->address_line2;
            $this->address3 = $this->firmAddress->address_line3;
            $this->postcode = $this->firmAddress->postcode;
            $this->town = $this->firmAddress->town;
            $this->state = $this->firmAddress->state;
        }
    }

    public function render()
    {
        return view('livewire.tenant.components.audit-firm-address-modal');
    }

    public function submit()
    {
        $this->validate();
        info($this->id);
        if ($this->id) {
            $this->update();
        } else {
            $this->create();
        }
    }

    public function create()
    {
        AuditFirmAddress::create([
            'branch' => $this->firmBranch,
            'address_line1' => $this->address1,
            'address_line2' => $this->address2,
            'address_line3' => $this->address3,
            'postcode' => $this->postcode,
            'town' => $this->town,
            'state' => $this->state,
            'country' => 'Malaysia',
        ]);

        $this->closeModalWithEvents([
            ShowAuditFirm::class => 'successCreated'
        ]);
    }

    public function update()
    {
        $this->firmAddress->update([
            'branch' => $this->firmBranch,
            'address_line1' => $this->address1,
            'address_line2' => $this->address2,
            'address_line3' => $this->address3,
            'postcode' => $this->postcode,
            'town' => $this->town,
            'state' => $this->state,
            'country' => 'Malaysia',
        ]);

        $this->closeModalWithEvents([
            ShowAuditFirm::class => 'successUpdated'
        ]);
    }
}
