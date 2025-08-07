<?php

namespace App\Livewire\Tenant\Pages\ReportConfig\Ntfs;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Models\Tenant\NtfsConfigItem;
use App\Models\Central\DefaultNtfsConfigItem;

class EstUncertainties extends Component
{
    #[Locked]
    public $id;

    public $items;

    public function mount($id)
    {
        $this->id = $id;
        $this->loadSigAccPolicies();
        if ($this->items->count() == 0) {
            $this->initializeSigAccPolicies();
        }
    }

    public function render()
    {
        $this->loadSigAccPolicies();
        return view('livewire.tenant.pages.report-config.ntfs.est-uncertainties', []);
    }

    public function updateDisplay($id, $value)
    {
        $config = NtfsConfigItem::find($id);
        $config->is_active = $value;
        $config->save();
    }

    public function deleteItem($id)
    {
        NtfsConfigItem::find($id)->delete();
    }

    #[On('successCreated')]
    public function successCreated()
    {
        // session()->flash('success', 'Content was created');
        LivewireAlert::withOptions([
            "position" => "top-end",
            "icon" => "success",
            "title" => "Item was created!",
            "showConfirmButton" => false,
            "timer" => 1500
        ])->show();
    }

    #[On('successUpdated')]
    public function successUpdated()
    {
        // session()->flash('success', 'Content was updated');
        LivewireAlert::withOptions([
            "position" => "top-end",
            "icon" => "success",
            "title" => "Item was updated!",
            "showConfirmButton" => false,
            "timer" => 1500
        ])->show();
    }

    public function updateOrder($orderItem)
    {
        foreach ($orderItem as $item) {
            $config = NtfsConfigItem::find($item['value']);

            $config->order = $item['order'];
            $config->save();
        }
        // $this->items = NtfsConfigItem::where('company_id', $this->id)->where('section', 'eu')->orderBy('order')->get();
        LivewireAlert::withOptions([
           "position" => "top-end",
           "icon" => "success",
           "title" => "Order Saved!",
           "showConfirmButton" => false,
           "timer" => 1500
       ])->show();
    }

    private function loadSigAccPolicies()
    {
        $this->items = NtfsConfigItem::where('company_id', $this->id)->where('section', 'eu')->orderBy('order')->get();
    }

    private function initializeSigAccPolicies()
    {
        $defaultItems = DefaultNtfsConfigItem::where('section', 'eu')->get();
        foreach ($defaultItems as $defaultItem) {
            $item = NtfsConfigItem::create([
                'content' => $defaultItem->content,
                'type' => $defaultItem->type,
                'section' => $defaultItem->section,
                'position' => $defaultItem->position,
                'order' => $defaultItem->order,
                'is_active' => true,
                'is_default_content' => true,
                'company_id' => $this->id,
                'remark' => $defaultItem->remark,
            ]);
            $item->order = $item->id;
            $item->save();
        }
    }
}
