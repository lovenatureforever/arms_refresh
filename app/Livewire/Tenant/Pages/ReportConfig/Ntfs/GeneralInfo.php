<?php

namespace App\Livewire\Tenant\Pages\ReportConfig\Ntfs;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\Tenant\NtfsConfigItem;
use App\Models\Central\DefaultNtfsConfigItem;

class GeneralInfo extends Component
{
    #[Locked]
    public $id;

    public $items;

    public function mount($id)
    {
        $this->id = $id;
        $this->loadGeneralInfoItems();
        if ($this->items->count() == 0) {
            $this->initializeGeneralInfoItems();
        }
    }

    public function render()
    {
        $this->loadGeneralInfoItems();
        return view('livewire.tenant.pages.report-config.ntfs.general-info', []);
    }

    public function updateDisplay($id, $value)
    {
        $config = NtfsConfigItem::find($id);
        $config->is_active = $value;
        $config->save();
    }

    public function setDefaultContent($id, $value) {
        $isDefault = filter_var($value, FILTER_VALIDATE_BOOLEAN);
        $config = NtfsConfigItem::find($id);
        $config->is_default_content = $isDefault;
        $config->save();
    }

    #[On('successUpdatedTitle')]
    public function successUpdatedTitle()
    {
        // session()->flash('success', 'Content was created');
        $this->alert('success', 'Title was updated!');
        $this->render();
    }

    #[On('successUpdatedContent')]
    public function successUpdatedContent()
    {
        $this->alert('success', 'Custom content was updated!');
        $this->render();
    }

    private function loadGeneralInfoItems()
    {
        $this->items = NtfsConfigItem::where('company_id', $this->id)->where('section', 'gi')->orderBy('order')->get();
    }

    private function initializeGeneralInfoItems()
    {
        $defaultItems = DefaultNtfsConfigItem::where('section', 'gi')->get();
        foreach ($defaultItems as $defaultItem) {
            $item = NtfsConfigItem::create([
                'default_title' => $defaultItem->title,
                'title' => $defaultItem->title,
                'default_content' => $defaultItem->content,
                'type' => $defaultItem->type,
                'section' => $defaultItem->section,
                'position' => $defaultItem->position,
                'order' => $defaultItem->order,
                'is_active' => true,
                'is_default_title' => true,
                'is_default_content' => true,
                'company_id' => $this->id,
            ]);
            $item->order = $item->id;
            $item->save();
        }
    }
}
