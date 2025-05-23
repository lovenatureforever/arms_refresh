<?php

namespace App\Livewire\Tenant\Pages\AuditPartner;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant\Auditor;
use App\Models\Tenant\AuditorLicense;

class ShowAuditPartner extends Component
{
    public $auditor;

    #[Validate('required|min:3')]
    public $name;

    #[Validate('required|min:3')]
    public $title;

    public $auditorLicense;

    public $isActive = '1';


    #[Validate('required|email')]
    public $email;

    #[Validate('required|min:3')]
    public $username;

    #[Validate('required|min:10')]
    public $phoneNumber;

    public $internal_roles = [];

    public $isqm_roles = [];

    public $ids = [];

    #[Validate('required|array|min:1')]
    public $licenseNumbers = [];

    #[Validate('required|array|min:1')]
    public $effectiveDates = [];

    #[Validate('required|array|min:1')]
    public $expiryDates = [];

    #[Validate(
    'required|array|min:1',
    message: [
        'required' => 'License number is required',
        'array' => 'License must be an array',
        'min' => 'At least one license must be provided'
    ]
)]
    public $inputs = [];
    public $i;

    public function mount($id)
    {
        $this->auditor = Auditor::with(['user', 'licenses'])->findOrFail($id);

        $this->email = $this->auditor->user->email;
        $this->name = $this->auditor->user->name;
        $this->username = $this->auditor->user->username;
        $this->phoneNumber = $this->auditor->user->phone_number;
        $this->isActive = $this->auditor->user->is_active;

        $this->internal_roles = $this->auditor->user->roles()->where('name', 'like', 'internal%')->pluck('name')->toArray();
        $this->isqm_roles = $this->auditor->user->roles()->where('name', 'like', 'isqm%')->pluck('name')->toArray();


        $this->title = $this->auditor->title;
        $this->auditorLicense = $this->auditor->selected_license->license_no;

        $this->i = $this->auditor->licenses->count();

        foreach ($this->auditor->licenses as $key => $license) {
            array_push($this->inputs, $key);
            array_push($this->ids, $license->id);
            array_push($this->licenseNumbers, $license->license_no);
            array_push($this->effectiveDates, Carbon::parse($license->effective_date)->format('Y-m-d'));
            array_push($this->expiryDates, Carbon::parse($license->expiry_date)->format('Y-m-d'));
        }
    }

    public function render()
    {
        $roles = Role::all();

        return view('livewire.tenant.pages.audit-partner.show-audit-partner', [
            'roles' => $roles,
        ]);
    }

    public function update()
    {
        DB::beginTransaction();
        try {
            $this->validate();

            $user = User::find($this->auditor->user_id);
            $user->email = $this->email;
            $user->name = $this->name;
            $user->username = $this->username;
            $user->phone_number = $this->phoneNumber;
            $user->is_active = $this->isActive;
            $user->save();

            $roles = array_merge($this->internal_roles, $this->isqm_roles);
            $user->syncRoles($roles);

            $this->auditor->title = $this->title;
            $this->auditor->save();

            $licenseIndexes = [0, ...$this->inputs];
            foreach ($licenseIndexes as $i) {
                if (isset($this->ids[$i])) {
                    AuditorLicense::where('id', $this->ids[$i])->update([
                        'license_no' => $this->licenseNumbers[$i],
                        'effective_date' => $this->effectiveDates[$i],
                        'expiry_date' => $this->expiryDates[$i]
                    ]);
                }
                else {
                    AuditorLicense::create([
                        'auditor_id' => $this->auditor->id,
                        'license_no' => $this->licenseNumbers[$i],
                        'effective_date' => $this->effectiveDates[$i],
                        'expiry_date' => $this->expiryDates[$i]
                    ]);
                }

            }

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
        if (isset($this->ids[$this->inputs[$i]])) {
            AuditorLicense::where('id', $this->ids[$this->inputs[$i]])->delete();
        }
        unset($this->inputs[$i]);
    }
}
