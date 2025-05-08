<?php

namespace App\Livewire\Tenant\Users;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Spatie\Permission\Models\Role;

class CreateUser extends Component
{
    #[Validate('required|email|unique:users,email')]
    public $email;

    #[Validate('required|min:3')]
    public $password;

    #[Validate('required|min:3')]
    public $password_confirmation;

    #[Validate('required|min:3')]
    public $name;

    #[Validate('required|min:3|unique:users,username')]
    public $username;

    #[Validate('required|min:10')]
    public $phoneNumber;

    #[Validate('required|array|min:1')]
    public $role = [];

    #[Validate('required')]
    public $accountStatus;

    public function render()
    {
        $roles = Role::all();

        return view('livewire.tenant.users.create-user', [
            'roles' => $roles
        ]);
    }

    public function create()
    {
        info($this->role);
        DB::beginTransaction();
        try {
            $this->validate();

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'username' => $this->username,
                'phone_number' => $this->phoneNumber,
                'account_status' => $this->accountStatus
            ]);

            foreach ($this->role as $userRole) {
                $user->assignRole($userRole);
            }

            DB::commit();

            $this->redirect(IndexUser::class);
        } catch (Exception $e) {
            info($e->getMessage());

            session()->flash('error', $e->getMessage());
            DB::rollBack();
        }
    }
}
