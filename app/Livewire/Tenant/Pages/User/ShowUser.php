<?php

namespace App\Livewire\Tenant\Pages\User;

use Exception;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use App\Models\Tenant\CompanyDirector;
use App\Models\Tenant\DirectorSignature;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class ShowUser extends Component
{
    use WithFileUploads;

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
    public $signatureFile;

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

        // Get director and signature info if user is a director
        $director = null;
        $currentSignature = null;
        if ($this->user->user_type === 'director') {
            $director = CompanyDirector::where('user_id', $this->user->id)
                ->where('is_active', true)
                ->first();

            if ($director) {
                $currentSignature = DirectorSignature::where('director_id', $director->id)
                    ->where('is_default', true)
                    ->first();
            }
        }

        return view('livewire.tenant.pages.user.show-user', [
            'roles' => $roles,
            'director' => $director,
            'currentSignature' => $currentSignature,
        ]);
    }

    public function uploadSignature()
    {
        $this->validate([
            'signatureFile' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        // Find director linked to this user
        $director = CompanyDirector::where('user_id', $this->user->id)
            ->where('is_active', true)
            ->first();

        if (!$director) {
            LivewireAlert::withOptions([
                'position' => 'top-end',
                'icon' => 'error',
                'title' => 'Director profile not found for this user',
                'timer' => 2000
            ])->show();
            return;
        }

        // Remove existing default signature and its file
        $existingSignature = DirectorSignature::where('director_id', $director->id)
            ->where('is_default', true)
            ->first();

        if ($existingSignature) {
            Storage::disk('public')->delete($existingSignature->signature_path);
            $existingSignature->delete();
        }

        // Upload new signature to public disk with signatures folder
        $path = $this->signatureFile->store('signatures', 'public');

        DirectorSignature::create([
            'director_id' => $director->id,
            'signature_path' => $path,
            'is_default' => true,
            'uploaded_by' => auth()->id()
        ]);

        $this->reset('signatureFile');

        LivewireAlert::withOptions([
            'position' => 'top-end',
            'icon' => 'success',
            'title' => 'Signature uploaded successfully!',
            'timer' => 1500
        ])->show();
    }

    public function deleteSignature()
    {
        $director = CompanyDirector::where('user_id', $this->user->id)
            ->where('is_active', true)
            ->first();

        if ($director) {
            $signature = DirectorSignature::where('director_id', $director->id)
                ->where('is_default', true)
                ->first();

            if ($signature) {
                Storage::disk('public')->delete($signature->signature_path);
                $signature->delete();

                LivewireAlert::withOptions([
                    'position' => 'top-end',
                    'icon' => 'success',
                    'title' => 'Signature deleted successfully!',
                    'timer' => 1500
                ])->show();
            }
        }
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
