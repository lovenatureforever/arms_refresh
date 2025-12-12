<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use Livewire\Component;
use App\Models\Tenant\CosecTemplate;

class AdminCosecTemplate extends Component
{
    public $templates;
    public $showPreviewModal = false;
    public $previewTemplateId = null;

    public function mount()
    {
        $this->loadTemplates();
    }

    public function loadTemplates()
    {
        $this->templates = CosecTemplate::all();
    }

    public function create()
    {
        return redirect()->route('admin.cosec.templates.create');
    }

    public function edit($id)
    {
        return redirect()->route('admin.cosec.templates.edit', $id);
    }

    public function delete($id)
    {
        $template = CosecTemplate::find($id);
        if ($template) {
            $template->delete();
            $this->loadTemplates();
        }
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

    public function render()
    {
        return view('livewire.tenant.pages.cosec.admin-cosec-template');
    }
}
