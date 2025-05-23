<?php

namespace App\Livewire\Tenant\Pages\User;

use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class IndexUser extends Component
{
    use WithPagination;

    public function render()
    {
        $users = User::where('is_audit_partner', false)->paginate(10);

        return view('livewire.tenant.pages.user.index-user', [
            'users' => $users
        ]);
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->id == auth()->user()->id) {
            // session()->flash('error', 'You cannot delete your own account.');
            LivewireAlert::withOptions(["position" => "top-end", "icon" => "error", "title" => "You cannot delete your own account.", "showConfirmButton" => false, "timer" => 1500])->show();
            return;
        }
        if ($user->hasRole(User::ROLE_INTERNAL_ADMIN)) {
            // session()->flash('error', 'You cannot delete a user with admin role.');
            LivewireAlert::withOptions(["position" => "top-end", "icon" => "error", "title" => "You cannot delete a user with admin role.", "showConfirmButton" => false, "timer" => 1500])->show();
            return;
        }
        $user->delete();
        // session()->flash('success', 'User deleted successfully.');
        LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "User deleted successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
    }

    public function showUser($id)
    {
        redirect()->route('users.show', $id);
    }
}
