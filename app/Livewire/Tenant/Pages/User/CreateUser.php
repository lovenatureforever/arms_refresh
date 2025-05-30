<?php

namespace App\Livewire\Tenant\Pages\User;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Spatie\Permission\Models\Role;

class CreateUser extends Component
{
    #[Validate('required|email|unique:users,email')]
    public $email;

    #[Validate('required|min:3|confirmed')]
    public $password;

    public $password_confirmation;

    #[Validate('required|min:3')]
    public $name;

    #[Validate('required|min:3|unique:users,username')]
    public $username;

    #[Validate('required|min:10')]
    public $phoneNumber;

    public $internal_roles = [];

    public $isqm_roles = [];

    public $outsider_roles = [];

    #[Validate('required')]
    public $isActive = '1';

    public function render()
    {
        $roles = Role::all();

        return view('livewire.tenant.pages.user.create-user', [
            'roles' => $roles
        ]);
    }

    public function updatedInternalRoles($value)
    {
        // Log::info('roles: ', $this->internal_roles);
        if (count($this->internal_roles) > 0) {
            $this->outsider_roles = null;
        }

    }

    public function updatedOutsiderRole($value)
    {
        if ($value) {
            $this->internal_roles = [];
        }
    }

    public function create()
    {
        try {
            $this->validate();
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'username' => $this->username,
                'phone_number' => $this->phoneNumber,
                'is_active' => $this->isActive
            ]);
            $roles = array_merge($this->internal_roles, $this->isqm_roles);
            if ($this->outsider_roles) {
                $roles[] = $this->outsider_roles;
            }
            $user->syncRoles($roles);
            $this->redirect(IndexUser::class);
        } catch (Exception $e) {
            info($e->getMessage());
            session()->flash('error', $e->getMessage());
        }
    }
}
