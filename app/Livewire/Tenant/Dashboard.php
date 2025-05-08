<?php

namespace App\Livewire\Tenant;

use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public $totalUsers;
    public $newFiveUsers;

    public function mount()
    {
        $this->totalUsers = User::count();
        $this->newFiveUsers = User::latest()->take(5)->get();
    }

    public function render()
    {
        return view('livewire.tenant.dashboard');
    }
}
