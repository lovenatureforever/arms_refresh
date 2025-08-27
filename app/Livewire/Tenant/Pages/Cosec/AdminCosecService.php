<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use Livewire\Component;
use App\Models\Tenant\CosecService;

class AdminCosecService extends Component
{
    public $services;
    public $name;
    public $cost;
    public $editId;

    public function mount()
    {
        $this->loadServices();
    }

    public function loadServices()
    {
        $this->services = CosecService::all();
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'cost' => 'required|integer|min:0',
        ]);

        if ($this->editId) {
            $service = CosecService::find($this->editId);
            $service->update(['name' => $this->name, 'cost' => $this->cost]);
        } else {
            CosecService::create(['name' => $this->name, 'cost' => $this->cost]);
        }

        $this->reset(['name', 'cost', 'editId']);
        $this->loadServices();
    }

    public function edit($id)
    {
        $service = CosecService::find($id);
        $this->editId = $service->id;
        $this->name = $service->name;
        $this->cost = $service->cost;
    }

    public function delete($id)
    {
        CosecService::destroy($id);
        $this->loadServices();
    }

    public function render()
    {
        return view('livewire.tenant.pages.cosec.admin-cosec-service');
    }
}
