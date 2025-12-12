<?php

namespace App\Livewire\Tenant\Pages\Company;

use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyDirector;
use App\Models\Tenant\CompanyDirectorChange;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class DirectorCreate extends Component
{
    #[Locked]
    public $companyId;

    #[Locked]
    public $company;

    public $directors = [];

    // Form fields
    #[Validate('required|min:3')]
    public $name = '';

    #[Validate('required')]
    public $designation = 'Director';

    #[Validate('required')]
    public $idType = 'Mykad';

    #[Validate('required|min:6')]
    public $idNo = '';

    public $gender = null; // Boolean: true = Male, false = Female, null = not set

    // User account fields (for auto-creating user)
    #[Validate('required|email')]
    public $email = '';

    #[Validate('required|min:6')]
    public $password = '';

    public $isActive = true;

    public $isDefaultSignerCosec = false;

    // Edit mode
    public $editingId = null;

    public function mount($id)
    {
        $this->companyId = $id;
        $this->company = Company::findOrFail($id);
        $this->loadDirectors();
    }

    public function loadDirectors()
    {
        $this->directors = CompanyDirector::where('company_id', $this->companyId)
            ->orderBy('sort')
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.tenant.pages.company.director-create', [
            'designations' => CompanyDirector::$designations,
            'idTypes' => CompanyDirector::$idTypes,
        ]);
    }

    public function save()
    {
        // Custom validation - password required only for new directors
        $rules = [
            'name' => 'required|min:3',
            'designation' => 'required',
            'idType' => 'required',
            'idNo' => 'required|min:6',
            'email' => 'required|email',
        ];

        if (!$this->editingId) {
            $rules['password'] = 'required|min:6';
        } else {
            $rules['password'] = 'nullable|min:6';
        }

        $this->validate($rules);

        // Handle user account
        $existingUser = User::where('email', $this->email)->first();
        if ($existingUser) {
            // Link to existing user with same email
            $linkedUserId = $existingUser->id;
            // Update password if provided (password is auto-hashed by User model cast)
            if ($this->password) {
                $existingUser->update(['password' => $this->password]);
            }
        } else {
            // Create new user account for the director
            // Note: password is auto-hashed by User model's 'hashed' cast
            $newUser = User::create([
                'name' => $this->name,
                'username' => $this->email, // Use email as username
                'email' => $this->email,
                'password' => $this->password,
                'user_type' => User::USER_TYPE_DIRECTOR,
            ]);

            // Assign the cosec_director role for COSEC access
            $newUser->assignRole(User::ROLE_COSEC_DIRECTOR);

            $linkedUserId = $newUser->id;
        }

        if ($this->editingId) {
            // Update existing director
            $director = CompanyDirector::find($this->editingId);
            if ($director) {
                $director->update([
                    'name' => $this->name,
                    'designation' => $this->designation,
                    'id_type' => $this->idType,
                    'id_no' => $this->idNo,
                    'gender' => $this->gender,
                    'user_id' => $linkedUserId,
                    'is_active' => $this->isActive,
                    'is_default_signer_cosec' => $this->isDefaultSignerCosec,
                ]);

                LivewireAlert::withOptions([
                    'position' => 'top-end',
                    'icon' => 'success',
                    'title' => 'Director updated successfully.',
                    'showConfirmButton' => false,
                    'timer' => 1500
                ])->show();
            }
        } else {
            // Create new director
            $maxSort = CompanyDirector::where('company_id', $this->companyId)->max('sort') ?? 0;

            $director = CompanyDirector::create([
                'company_id' => $this->companyId,
                'name' => $this->name,
                'designation' => $this->designation,
                'id_type' => $this->idType,
                'id_no' => $this->idNo,
                'gender' => $this->gender,
                'user_id' => $linkedUserId,
                'is_active' => $this->isActive,
                'is_default_signer_cosec' => $this->isDefaultSignerCosec,
                'sort' => $maxSort + 1,
            ]);

            // Create director change record (Director Appointed) for corporate info sync
            CompanyDirectorChange::create([
                'company_director_id' => $director->id,
                'change_nature' => CompanyDirectorChange::CHANGE_NATURE_DIRECTOR_APPOINTED,
                'id_type' => $this->idType,
                'id_no' => $this->idNo,
                'effective_date' => now(),
            ]);

            LivewireAlert::withOptions([
                'position' => 'top-end',
                'icon' => 'success',
                'title' => 'Director created successfully.',
                'showConfirmButton' => false,
                'timer' => 1500
            ])->show();
        }

        $this->resetForm();
        $this->loadDirectors();
    }

    public function edit($id)
    {
        $director = CompanyDirector::with('user')->find($id);
        if ($director && $director->company_id == $this->companyId) {
            $this->editingId = $id;
            $this->name = $director->name;
            $this->designation = $director->designation;
            $this->idType = $director->id_type;
            $this->idNo = $director->id_no;
            $this->gender = $director->gender;
            $this->email = $director->user?->email ?? '';
            $this->password = ''; // Don't show existing password
            $this->isActive = $director->is_active;
            $this->isDefaultSignerCosec = $director->is_default_signer_cosec;
        }
    }

    public function delete($id)
    {
        $director = CompanyDirector::find($id);
        if ($director && $director->company_id == $this->companyId) {
            $director->delete();

            LivewireAlert::withOptions([
                'position' => 'top-end',
                'icon' => 'success',
                'title' => 'Director deleted successfully.',
                'showConfirmButton' => false,
                'timer' => 1500
            ])->show();

            $this->loadDirectors();
        }
    }

    public function toggleActive($id)
    {
        $director = CompanyDirector::find($id);
        if ($director && $director->company_id == $this->companyId) {
            $director->update(['is_active' => !$director->is_active]);
            $this->loadDirectors();
        }
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
        $this->designation = 'Director';
        $this->idType = 'Mykad';
        $this->idNo = '';
        $this->gender = null;
        $this->email = '';
        $this->password = '';
        $this->isActive = true;
        $this->isDefaultSignerCosec = false;
        $this->resetValidation();
    }

    public function cancelEdit()
    {
        $this->resetForm();
    }
}
