<?php

namespace App\Livewire\Tenant\Pages\AuditPartner;

use Exception;
use App\Models\User;
use App\Models\Tenant\Auditor;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class IndexAuditPartner extends Component
{
    public function render()
    {
        $auditors = Auditor::with('user', 'licenses')->paginate(10);

        return view('livewire.tenant.pages.audit-partner.index-audit-partner', [
            'auditors' => $auditors
        ]);
    }

    public function confirmDelete($id)
    {
        LivewireAlert::title('Are you sure?')
            ->withConfirmButton() // Enables button with default text
            ->confirmButtonText('Yes')
            ->onConfirm('deleteAuditor', ['id' => $id])
            ->withDenyButton()    // Enables button with default text
            ->denyButtonText('No')
            ->show();
    }

    public function deleteAuditor($data)
    {
        DB::beginTransaction();
        try {
            $auditor = Auditor::findOrFail($data['id']);
            if ($auditor->user_id == auth()->user()->id) {
                // session()->flash('error', 'You cannot delete your own account.');
                LivewireAlert::withOptions(["position" => "top-end", "icon" => "error", "title" => "You cannot delete your own account.", "showConfirmButton" => false, "timer" => 1500])->show();
                return;
            }
            if (User::find($auditor->user_id)->hasRole(User::ROLE_INTERNAL_ADMIN)) {
                // session()->flash('error', 'You cannot delete a user with admin role.');
                LivewireAlert::withOptions(["position" => "top-end", "icon" => "error", "title" => "You cannot delete a user with admin role.", "showConfirmButton" => false, "timer" => 1500])->show();
                return;
            }
            $user = User::find($auditor->user_id);

            $user->delete();
            $auditor->delete();

            LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Auditor deleted successfully.", "showConfirmButton" => false, "timer" => 1500])->show();

            DB::commit();
        } catch (Exception $e) {
            info('delete audit partner - ' . $e->getMessage());
            DB::rollBack();
        }
    }

    public function showAuditor($id)
    {
        redirect()->route('auditpartners.show', $id);
    }
}
