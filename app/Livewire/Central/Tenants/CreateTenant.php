<?php

namespace App\Livewire\Central\Tenants;

use Exception;
use App\Models\User;
use App\Models\Central\Tenant;
use App\Models\Tenant\AuditFirmAddress;
use Livewire\Component;
use Masmerise\Toaster\Toaster;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Hash;

class CreateTenant extends Component
{
    #[Validate('required')]
    public $domainName;

    #[Validate('required')]
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

    #[Validate('required')]
    public $email;

    #[Validate('required')]
    public $password;

    #[Validate('required')]
    public $name;

    #[Validate('required')]
    public $username;

    public $phoneNumber;

    public $role;

    public function render()
    {
        return view('livewire.central.tenants.create-tenant');
    }

    public function create()
    {
        try {
            $tenant = Tenant::create([
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

            $tenant->domains()->create(
                [
                    'domain' => $this->domainName . '.' . config('app.domain')
                ]
            );

            $tenant->run(function () use ($tenant) {
                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                    'username' => $this->username,
                    'phone_number' => $this->phoneNumber
                ]);

                $tenant->update([
                    'adminId' => $user->id
                ]);

                AuditFirmAddress::create([
                    'branch' => 'HQ',
                    'address_line1' => $this->address1,
                    'address_line2' => $this->address2,
                    'postcode' => $this->zip,
                    'town' => $this->city,
                    'state' => $this->state
                ]);
            });

            return redirect()->route('index.tenant');
        } catch (Exception $e) {
            error_log('create tenant - ' . $e->getMessage());
        }
    }
}
