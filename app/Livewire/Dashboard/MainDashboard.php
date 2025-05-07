<?php

namespace App\Livewire\Dashboard;

use App\Models\Tenant;
use Livewire\Component;

class MainDashboard extends Component
{
    public $totalTenant;
    public $newFiveTenant;

    public function mount()
    {
        $this->totalTenant = Tenant::all()->count();
        $this->newFiveTenant = Tenant::latest()->take(5)->get();
    }

    public function render()
    {
        return view('livewire.dashboard.main-dashboard');
    }
}
