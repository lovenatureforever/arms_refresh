<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use Livewire\Component;
use App\Models\Tenant\CosecTemplate;
use Livewire\Attributes\Locked;
use Livewire\WithFileUploads;

class AdminCosecTemplate extends Component
{
    use WithFileUploads;
    public $templates;
    public $editingId = null;
    public $name;
    public $content;
    public $credit_cost;
    public $is_active;
    public $templateFile;
    public $signature_type;
    public $showPreviewModal = false;
    public $previewTemplateId = null;

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
        $this->signature_type = $template->signature_type ?? 'default';
        $this->form_type = $template->form_type;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'content' => 'nullable|string',
            'credit_cost' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'templateFile' => 'nullable|file|mimes:docx|max:10240',
        ]);

        if ($this->editingId) {
            $template = CosecTemplate::find($this->editingId);
            $updateData = [
                'name' => $this->name,
                'content' => $this->content,
                'credit_cost' => $this->credit_cost,
                'is_active' => $this->is_active,
                'signature_type' => $this->signature_type,
            ];
            
            if ($this->templateFile) {
                $path = $this->templateFile->store('cosec-templates', 'public');
                $updateData['template_file'] = $path;
            }
            
            $template->update($updateData);
        }

        $this->reset(['editingId', 'name', 'content', 'credit_cost', 'is_active', 'templateFile', 'signature_type']);
        $this->loadTemplates();
    }

    public function cancel()
    {
        $this->reset(['editingId', 'name', 'content', 'credit_cost', 'is_active', 'templateFile', 'signature_type']);
    }

    public function showPreview($templateId)
    {
        $this->previewTemplateId = $templateId;
        $this->showPreviewModal = true;
    }

    public function closePreview()
    {
        $this->showPreviewModal = false;
        $this->previewTemplateId = null;
    }

    public function previewTemplate($templateId)
    {
        return redirect()->route('admin.cosec.template.preview', $templateId);
    }

    public function render()
    {
        return view('livewire.tenant.pages.cosec.admin-cosec-template');
    }
}
