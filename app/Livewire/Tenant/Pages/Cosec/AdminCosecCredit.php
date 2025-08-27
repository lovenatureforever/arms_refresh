<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use Livewire\Component;
use App\Models\User;

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
        $user = User::find($this->editId);
        $user->credit = $this->credit;
        $user->save();
        $this->reset(['editId', 'credit']);
        $this->users = User::all();
    }

    public function render()
    {
        return view('livewire.tenant.pages.cosec.admin-cosec-credit');
    }
}
