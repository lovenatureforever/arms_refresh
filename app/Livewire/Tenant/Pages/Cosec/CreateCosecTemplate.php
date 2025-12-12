<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use Livewire\Component;
use App\Models\Tenant\CosecTemplate;

class CreateCosecTemplate extends Component
{
    public $templateId = null;
    public $name = '';
    public $description = '';
    public $credit_cost = 10;
    public $signature_type = 'sole_director';
    public $is_active = true;
    public $htmlContent = '';
    public $cssContent = '';
    public $isEditing = false;

    public function mount($id = null)
    {
        if ($id) {
            $this->templateId = $id;
            $this->isEditing = true;
            $this->loadTemplate($id);
        }
    }

    public function loadTemplate($id)
    {
        $template = CosecTemplate::find($id);
        if ($template) {
            $this->name = $template->name;
            $this->description = $template->description ?? '';
            $this->credit_cost = $template->credit_cost;
            $this->signature_type = $template->signature_type ?? 'default';
            $this->is_active = $template->is_active;

            // Parse content to separate HTML and CSS if stored together
            $content = $template->content ?? '';
            if (preg_match('/<style>(.*?)<\/style>/s', $content, $matches)) {
                $this->cssContent = trim($matches[1]);
                $this->htmlContent = trim(preg_replace('/<style>.*?<\/style>/s', '', $content));
            } else {
                $this->htmlContent = $content;
                $this->cssContent = '';
            }
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'credit_cost' => 'required|numeric|min:0',
            'signature_type' => 'required|in:sole_director,two_directors,all_directors',
            'htmlContent' => 'nullable|string',
        ]);

        // Combine HTML with CSS
        $fullContent = '';
        if ($this->cssContent) {
            $fullContent = "<style>\n{$this->cssContent}\n</style>\n";
        }
        $fullContent .= $this->htmlContent;

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'credit_cost' => $this->credit_cost,
            'signature_type' => $this->signature_type,
            'is_active' => $this->is_active,
            'content' => $fullContent,
        ];

        if ($this->templateId) {
            $template = CosecTemplate::find($this->templateId);
            $template->update($data);
            session()->flash('message', 'Template updated successfully!');
        } else {
            CosecTemplate::create($data);
            session()->flash('message', 'Template created successfully!');
        }

        return redirect()->route('admin.cosec.templates');
    }

    public function cancel()
    {
        return redirect()->route('admin.cosec.templates');
    }

    public function render()
    {
        return view('livewire.tenant.pages.cosec.create-cosec-template');
    }
}
