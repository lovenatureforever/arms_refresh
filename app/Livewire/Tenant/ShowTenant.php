<?php

namespace App\Livewire\Tenant;

use App\Models\Tenant;
use Livewire\Component;

class ShowTenant extends Component
{
    public $tenant;

    public $domainName;

    public $firmName;
    public $firmTitle;
    public $firmNo;
    public $firmEmail;
    public $firmContact;
    public $firmFax;

    public $address1;
    public $address2;
    public $city;
    public $state;
    public $zip;

    public function mount($id)
    {
        $this->tenant = Tenant::find($id);

        $this->domainName = $this->tenant->domains[0]->domain;
        $this->firmName = $this->tenant->firmName;
        $this->firmTitle = $this->tenant->firmTitle;
        $this->firmNo = $this->tenant->firmNo;
        $this->firmEmail = $this->tenant->firmEmail;
        $this->firmContact = $this->tenant->firmContact;
        $this->firmFax = $this->tenant->firmFax;
        $this->address1 = $this->tenant->address1;
        $this->address2 = $this->tenant->address2;
        $this->city = $this->tenant->city;
        $this->state = $this->tenant->state;
        $this->zip = $this->tenant->zip;
    }

    public function render()
    {
        return view('livewire.tenant.show-tenant');
    }

    public function update()
    {
        $this->tenant->update([
            'firmName' => $this->firmName,
            'firmTitle' => $this->firmTitle,
            'firmNo' => $this->firmNo,
            'firmEmail' => $this->firmEmail,
            'firmContact' => $this->firmContact,
            'firmFax' => $this->firmFax,
            'address1' => $this->address1,
            'address2' => $this->address2,
            'city' => $this->city,
            'state' => $this->state,
            'zip' => $this->zip,
        ]);

        return redirect()->route('index.tenant');
    }
}
