<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use App\Models\Tenant\CosecOrder;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Locked;

class ViewCosec extends Component
{
    #[Locked]
    public $id;

    public function mount($id) {
        $this->id = $id;
    }

    public function render()
    {
        $order = CosecOrder::find($this->id);

        return view('livewire.tenant.pages.cosec.view-cosec', [
            'order' => $order
        ]);
    }

}
