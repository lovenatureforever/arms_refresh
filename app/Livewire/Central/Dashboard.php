<?php

namespace App\Livewire\Central;

use App\Models\Central\Tenant;
use Livewire\Component;

class Dashboard extends Component
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
        return view('livewire.central.dashboard');
    }
}
