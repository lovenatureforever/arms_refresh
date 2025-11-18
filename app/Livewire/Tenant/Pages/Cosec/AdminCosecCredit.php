<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use Livewire\Component;
use App\Models\User;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class AdminCosecCredit extends Component
{
    public $users;
    public $editId;
    public $credit;
    public function mount()
    {
        $this->users = User::all();
    }

    public function edit($id)
    {
        $user = User::find($id);
        $this->editId = $user->id;
        $this->credit = $user->credit;
    }

    public function save()
    {
        $this->validate([
            'credit' => 'required|numeric|min:0'
        ]);

        try {
            $user = User::find($this->editId);
            $user->credit = $this->credit;
            $user->save();
            
            $this->reset(['editId', 'credit']);
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

    public function showCreditHistory($userId)
    {
        return redirect()->route('users.credit-history', $userId);
    }

    public function render()
    {
        return view('livewire.tenant.pages.cosec.admin-cosec-credit');
    }
}
