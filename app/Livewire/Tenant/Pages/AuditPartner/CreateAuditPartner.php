<?php

namespace App\Livewire\Tenant\Pages\AuditPartner;

use Exception;
use App\Models\User;
use Livewire\Component;
use App\Models\Tenant\Auditor;
use App\Models\Tenant\AuditorLicense;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;

class CreateAuditPartner extends Component
{
    #[Validate('required|min:3')]
    public $name;

    #[Validate('required|min:3')]
    public $title;

    public $isActive = '1';


    #[Validate('required|email|unique:users,email')]
    public $email;

    #[Validate('required|min:3|unique:users,username')]
    public $username;

    #[Validate('required|min:10')]
    public $phoneNumber;

    #[Validate('required|min:3|confirmed')]
    public $password;

    public $password_confirmation;

    public $internal_roles = [];

    public $isqm_roles = [];

    #[Validate('required|array|min:1')]
    public $licenseNumbers = [];

    #[Validate('required|array|min:1')]
    public $effectiveDates = [];

    #[Validate('required|array|min:1')]
    public $expiryDates = [];

    public $inputs = [0];
    public $i = 1;

    public function mount()
    {
        array_push($this->internal_roles, 'internal_2nd_reviewer');
    }

    public function render()
    {
        $roles = Role::all();

        return view('livewire.tenant.pages.audit-partner.create-audit-partner', [
            'roles' => $roles,
        ]);
    }

    public function create()
    {
        DB::beginTransaction();
        try {
            $this->validate();

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'username' => $this->username,
                'phone_number' => $this->phoneNumber,
                'password' => Hash::make($this->password),
                'is_active' => $this->isActive,
                'is_audit_partner' => 1
            ]);

            $auditor = Auditor::create([
                'user_id' => $user->id,
                'title' => $this->title,
                'is_active' => 1
            ]);

            $licenseIndexes = [0, ...$this->inputs];
            foreach ($licenseIndexes as $i) {
                AuditorLicense::create([
                    'auditor_id' => $auditor->id,
                    'license_no' => $this->licenseNumbers[$i],
                    'effective_date' => $this->effectiveDates[$i],
                    'expiry_date' => $this->expiryDates[$i]
                ]);
            }

            $roles = array_merge($this->internal_roles, $this->isqm_roles);

            $user->syncRoles($roles);

            DB::commit();

            $this->redirect(IndexAuditPartner::class);
        } catch (Exception $e) {
            info($e->getMessage());

            session()->flash('error', $e->getMessage());
            DB::rollBack();
        }
    }

    public function addLicense($i)
    {
        $this->i = $i;
        array_push($this->inputs, $i);
        $i = $i + 1;
    }

    public function removeLicense($i)
    {
        unset($this->inputs[$i]);
    }
}
