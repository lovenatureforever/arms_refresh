<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use Livewire\Component;
use App\Models\Tenant\CosecTemplate;
use Livewire\Attributes\Locked;

class AdminCosecTemplate extends Component
{
    public $templates;
    public $editingId = null;
    public $name;
    public $content;
    public $credit_cost;
    public $is_active;

    #[Locked]
    public $form_type;

    public function mount()
    {
        $this->loadTemplates();
    }

    public function loadTemplates()
    {
        $this->templates = CosecTemplate::all();
    }

    public function edit($id)
    {
        $template = CosecTemplate::find($id);
        $this->editingId = $template->id;
        $this->name = $template->name;
        $this->content = $template->content;
        $this->credit_cost = $template->credit_cost;
        $this->is_active = $template->is_active;
        $this->form_type = $template->form_type;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'content' => 'nullable|string',
            'credit_cost' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($this->editingId) {
            $template = CosecTemplate::find($this->editingId);
            $template->update([
                'name' => $this->name,
                'content' => $this->content,
                'credit_cost' => $this->credit_cost,
                'is_active' => $this->is_active,
            ]);
        }

        $this->reset(['editingId', 'name', 'content', 'credit_cost', 'is_active']);
        $this->loadTemplates();
    }

    public function cancel()
    {
        $this->reset(['editingId', 'name', 'content', 'credit_cost', 'is_active']);
    }

    public function render()
    {
        return view('livewire.tenant.pages.cosec.admin-cosec-template');
    }
}
