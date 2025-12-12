<?php

namespace App\Livewire\Tenant\Pages;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\User;
use App\Models\Tenant\CreditTransaction;
use App\Models\Tenant\CosecOrder;
use App\Models\Tenant\CompanyDirector;
use App\Models\Tenant\DirectorSignature;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class UserProfile extends Component
{
    use WithFileUploads;

    public $user;
    public $name;
    public $email;
    public $currentPassword;
    public $newPassword;
    public $newPasswordConfirmation;
    public $showPasswordForm = false;
    public $signatureFile;

    public function mount()
    {
        $this->user = auth()->user();
        $this->name = $this->user->name;
        $this->email = $this->user->email;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->user->id,
        ]);

        try {
            $this->user->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);

            LivewireAlert::withOptions([
                "position" => "top-end",
                "icon" => "success",
                "title" => "Profile updated successfully.",
                "showConfirmButton" => false,
                "timer" => 1500
            ])->show();
        } catch (\Exception $e) {
            LivewireAlert::withOptions([
                "position" => "top-end",
                "icon" => "error",
                "title" => "Error updating profile.",
                "showConfirmButton" => false,
                "timer" => 3000
            ])->show();
        }
    }

    public function togglePasswordForm()
    {
        $this->showPasswordForm = !$this->showPasswordForm;
        $this->reset(['currentPassword', 'newPassword', 'newPasswordConfirmation']);
    }

    public function updatePassword()
    {
        $this->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|min:8',
            'newPasswordConfirmation' => 'required|same:newPassword',
        ]);

        if (!Hash::check($this->currentPassword, $this->user->password)) {
            $this->addError('currentPassword', 'Current password is incorrect.');
            return;
        }

        try {
            $this->user->update([
                'password' => Hash::make($this->newPassword),
            ]);

            $this->reset(['currentPassword', 'newPassword', 'newPasswordConfirmation']);
            $this->showPasswordForm = false;

            LivewireAlert::withOptions([
                "position" => "top-end",
                "icon" => "success",
                "title" => "Password updated successfully.",
                "showConfirmButton" => false,
                "timer" => 1500
            ])->show();
        } catch (\Exception $e) {
            LivewireAlert::withOptions([
                "position" => "top-end",
                "icon" => "error",
                "title" => "Error updating password.",
                "showConfirmButton" => false,
                "timer" => 3000
            ])->show();
        }
    }

    public function uploadSignature()
    {
        $this->validate([
            'signatureFile' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        $user = auth()->user();

        // Find director linked to current user by user_id
        $director = CompanyDirector::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if (!$director) {
            LivewireAlert::withOptions([
                'position' => 'top-end',
                'icon' => 'error',
                'title' => 'Director profile not found',
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
            'signature_path' => $path,  // Store full relative path: signatures/filename.png
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
        $user = auth()->user();

        $director = CompanyDirector::where('user_id', $user->id)
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

    public function render()
    {
        // Get recent transactions
        $recentTransactions = CreditTransaction::where('user_id', $this->user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get order stats
        $totalOrders = CosecOrder::where('tenant_user_id', $this->user->id)->count();
        $pendingOrders = CosecOrder::where('tenant_user_id', $this->user->id)
            ->where('status', CosecOrder::STATUS_PENDING)
            ->count();
        $approvedOrders = CosecOrder::where('tenant_user_id', $this->user->id)
            ->where('status', CosecOrder::STATUS_APPROVED)
            ->count();

        // Get director signature (for directors only)
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

        return view('livewire.tenant.pages.user-profile', [
            'recentTransactions' => $recentTransactions,
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'approvedOrders' => $approvedOrders,
            'director' => $director,
            'currentSignature' => $currentSignature,
        ]);
    }
}
