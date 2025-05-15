<?php

namespace App\Livewire\Tenant\Pages\Users;

use Exception;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class ShowUser extends Component
{
    public $email;

    #[Validate('required|min:3')]
    public $name;

    public $username;

    #[Validate('required|min:3')]
    public $phoneNumber;

    #[Validate('required')]
    public $role = [];
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
        $this->role = $this->user->roles->pluck('name');
    }

    public function render()
    {
        $roles = Role::all();

        return view('livewire.tenant.pages.users.show-user', [
            'roles' => $roles
        ]);
    }

    public function update()
    {
        DB::beginTransaction();
        try {
            $this->validate();

            $this->user->email = $this->email;
            $this->user->name = $this->name;
            $this->user->username = $this->username;
            $this->user->phone_number = $this->phoneNumber;
            $this->user->is_active = $this->isActive;
            $this->user->save();

            $this->user->syncRoles($this->role);

            $this->redirect(IndexUser::class);

            DB::commit();
        } catch (Exception $e) {
            info($e->getMessage());
            session()->flash('error', $e->getMessage());
            DB::rollBack();
        }
    }
}
