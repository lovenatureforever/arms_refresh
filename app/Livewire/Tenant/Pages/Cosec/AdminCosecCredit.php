<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use Livewire\Component;
use App\Models\User;
use App\Models\Tenant\CreditTransaction;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class AdminCosecCredit extends Component
{
    public $users;
    public $editId;
    public $credit;
    public $adjustmentReason = '';

    public function mount()
    {
        $this->users = User::all();
    }

    public function edit($id)
    {
        $user = User::find($id);
        $this->editId = $user->id;
        $this->credit = $user->credit;
        $this->adjustmentReason = '';
    }

    public function save()
    {
        $this->validate([
            'credit' => 'required|numeric|min:0',
            'adjustmentReason' => 'nullable|string|max:255'
        ]);

        try {
            $user = User::find($this->editId);
            $oldCredit = $user->credit ?? 0;
            $newCredit = (int) $this->credit;
            $difference = $newCredit - $oldCredit;

            if ($difference != 0) {
                $reason = $this->adjustmentReason ?: 'Admin credit adjustment';

                if ($difference > 0) {
                    // Adding credits
                    CreditTransaction::addCredits(
                        $this->editId,
                        $difference,
                        $reason,
                        CreditTransaction::REF_ADMIN_ADJUSTMENT,
                        null,
                        auth()->id()
                    );
                } else {
                    // Deducting credits
                    CreditTransaction::deductCredits(
                        $this->editId,
                        abs($difference),
                        $reason,
                        CreditTransaction::REF_ADMIN_ADJUSTMENT,
                        null,
                        auth()->id()
                    );
                }
            }

            $this->reset(['editId', 'credit', 'adjustmentReason']);
            $this->users = User::all();

            LivewireAlert::withOptions([
                "position" => "top-end",
                "icon" => "success",
                "title" => "Credit updated successfully.",
                "showConfirmButton" => false,
                "timer" => 1500
            ])->show();
        } catch (\Exception $e) {
            LivewireAlert::withOptions([
                "position" => "top-end",
                "icon" => "error",
                "title" => "Error updating credit: " . $e->getMessage(),
                "showConfirmButton" => false,
                "timer" => 3000
            ])->show();
        }
    }

    public function cancelEdit()
    {
        $this->reset(['editId', 'credit', 'adjustmentReason']);
    }

    public function showCreditHistory($userId)
    {
        return redirect()->route('admin.cosec.credit-history', $userId);
    }

    public function render()
    {
        return view('livewire.tenant.pages.cosec.admin-cosec-credit');
    }
}
