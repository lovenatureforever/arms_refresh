<?php

namespace App\Livewire\Tenant\Pages\User;

use App\Models\User;
use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyDirector;
use App\Models\Tenant\CompanyDirectorChange;
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

    #[Validate('required')]
    public $userType = 'subscriber';

    // Director-specific fields
    public $selectedCompany = '';
    public $selectedDirector = '';
    public $createNewDirector = false;
    public $directorDesignation = 'Director';
    public $directorIdType = 'Mykad';
    public $directorIdNo = '';

    public function render()
    {
        $roles = Role::all();
        // Note: Company 'name' is an accessor, so we sort in PHP after retrieval
        $companies = Company::where('is_active', true)->get()->sortBy('name');

        // Get unlinked directors for selected company
        $availableDirectors = collect();
        if ($this->selectedCompany) {
            $availableDirectors = CompanyDirector::where('company_id', $this->selectedCompany)
                ->where('is_active', true)
                ->whereNull('user_id')
                ->orderBy('name')
                ->get();
        }

        return view('livewire.tenant.pages.user.create-user', [
            'roles' => $roles,
            'companies' => $companies,
            'availableDirectors' => $availableDirectors,
        ]);
    }

    public function updatedSelectedCompany()
    {
        $this->selectedDirector = '';
        $this->createNewDirector = false;
    }

    public function updatedUserType()
    {
        if ($this->userType !== 'director') {
            $this->selectedCompany = '';
            $this->selectedDirector = '';
            $this->createNewDirector = false;
        }
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
            // Additional validation for director type
            if ($this->userType === 'director') {
                if (empty($this->selectedCompany)) {
                    session()->flash('error', 'Please select a company for the director.');
                    return;
                }
                if (!$this->createNewDirector && empty($this->selectedDirector)) {
                    session()->flash('error', 'Please select an existing director or create a new one.');
                    return;
                }
                if ($this->createNewDirector && empty($this->directorIdNo)) {
                    session()->flash('error', 'Please enter the director ID number.');
                    return;
                }
            }

            $this->validate();

            DB::beginTransaction();

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'username' => $this->username,
                'phone_number' => $this->phoneNumber,
                'is_active' => $this->isActive,
                'user_type' => $this->userType
            ]);

            // Handle director linking
            if ($this->userType === 'director') {
                if ($this->createNewDirector) {
                    // Create new director and link to user
                    $director = CompanyDirector::create([
                        'company_id' => $this->selectedCompany,
                        'user_id' => $user->id,
                        'name' => $this->name,
                        'designation' => $this->directorDesignation,
                        'id_type' => $this->directorIdType,
                        'id_no' => $this->directorIdNo,
                        'is_active' => true,
                    ]);

                    // Create director appointment change record
                    CompanyDirectorChange::create([
                        'company_director_id' => $director->id,
                        'change_nature' => 'Director appointed',
                        'name' => $this->name,
                        'designation' => $this->directorDesignation,
                        'id_type' => $this->directorIdType,
                        'id_no' => $this->directorIdNo,
                        'effective_date' => now(),
                    ]);
                } else {
                    // Link existing director to user
                    CompanyDirector::where('id', $this->selectedDirector)
                        ->update(['user_id' => $user->id]);
                }
            }

            $roles = array_merge($this->internal_roles, $this->isqm_roles);
            if ($this->outsider_roles) {
                $roles[] = $this->outsider_roles;
            }

            // Add COSEC role based on user type
            $cosecRoleMap = [
                'admin' => User::ROLE_COSEC_ADMIN,
                'subscriber' => User::ROLE_COSEC_SUBSCRIBER,
                'director' => User::ROLE_COSEC_DIRECTOR,
            ];
            if (isset($cosecRoleMap[$this->userType])) {
                $roles[] = $cosecRoleMap[$this->userType];
            }

            $user->syncRoles($roles);

            DB::commit();

            $this->redirect(IndexUser::class);
        } catch (Exception $e) {
            DB::rollBack();
            info($e->getMessage());
            session()->flash('error', $e->getMessage());
        }
    }
}
