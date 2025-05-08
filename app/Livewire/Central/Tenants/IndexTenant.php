<?php

namespace App\Livewire\Central\Tenants;

use Exception;
use App\Models\Central\Tenant;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class IndexTenant extends Component
{
    use WithPagination;

    public function render()
    {
        $tenants = Tenant::paginate(10);

        return view('livewire.central.tenants.index-tenant', [
            'tenants' => $tenants
        ]);
    }

    public function deleteTenant($id)
    {
        try {
            Tenant::findOrFail($id)->delete();
        } catch (Exception $e) {
            info('delete tenant - ' . $e->getMessage());
        }
    }

    public function showTenant($id)
    {
        redirect()->route('show.tenant', $id);
    }
}
