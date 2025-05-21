<?php

namespace App\Livewire\Tenant\Pages\Users;

use Exception;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class ShowUser extends Component
{
    #[Locked]
    public $id;

    #[Validate('required|email')]
    public $email;

    #[Validate('required|min:3')]
    public $name;

    #[Validate('required|min:3')]
    public $username;

    #[Validate('required|min:10')]
    public $phoneNumber;

    public $internal_roles = [];

    public $isqm_roles = [];

    public $outsider_role;
    public $isActive;

    public $user;

    public function mount($id)
    {
        $this->user = User::find($id);

        $this->email = $this->user->email;
        $this->name = $this->user->name;
        $this->username = $this->user->username;
        $this->phoneNumber = $this->user->phone_number;
        $this->isActive = $this->user->is_active;
        $this->internal_roles = $this->user->roles()->where('name', 'like', 'internal%')->pluck('name')->toArray();
        $this->isqm_roles = $this->user->roles()->where('name', 'like', 'isqm%')->pluck('name')->toArray();
        $this->outsider_role = $this->user->roles()->where('name', 'like', 'outsider%')->pluck('name')->first();

    }

    public function render()
    {
        $roles = Role::all();

        return view('livewire.tenant.pages.users.show-user', [
            'roles' => $roles
        ]);
    }

    public function updatedInternalRoles($value)
    {
        // Log::info('roles: ', $this->internal_roles);
        if (count($this->internal_roles) > 0) {
            $this->outsider_role = null;
        }

    }

    public function updatedOutsiderRole($value)
    {
        if ($value) {
            $this->internal_roles = [];
        }
    }

    public function update()
    {
        try {
            $this->validate();

            $this->user->email = $this->email;
            $this->user->name = $this->name;
            $this->user->username = $this->username;
            $this->user->phone_number = $this->phoneNumber;
            $this->user->is_active = $this->isActive;
            $this->user->save();

            $roles = array_merge($this->internal_roles, $this->isqm_roles);
            if ($this->outsider_role) {
                $roles[] = $this->outsider_role;
            }
            $this->user->syncRoles($roles);
            $this->redirect(IndexUser::class);
        } catch (Exception $e) {
            info($e->getMessage());
            session()->flash('error', $e->getMessage());
        }
    }
}
