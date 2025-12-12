<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use Livewire\Component;
use App\Models\Tenant\CosecTemplate;
use App\Models\Tenant\CosecOrder;
use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyDirector;
use App\Models\Tenant\CreditTransaction;
use App\Models\Tenant\CompanySecretary;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class DirectorPlaceOrder extends Component
{
    public $templates;
    public $companyId;
    public $company;
    public $director;

    // Template Preview modal
    public $showPreviewModal = false;
    public $previewTemplateId = null;

    // Order form modal
    public $showOrderModal = false;
    public $selectedTemplate = null;
    public $formData = [];
    public $selectedDirectors = [];
    public $showDocumentPreview = false;
    public $documentPreviewContent = '';

    public function mount()
    {
        $user = auth()->user();

        // Find director linked to current user
        $this->director = CompanyDirector::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if ($this->director) {
            $this->companyId = $this->director->company_id;
            $this->company = Company::find($this->companyId);
        }

        $this->loadTemplates();
    }

    public function loadTemplates()
    {
        $this->templates = CosecTemplate::where('is_active', true)->get();
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

    public function openOrderModal($templateId)
    {
        $this->selectedTemplate = CosecTemplate::find($templateId);
        $this->selectedDirectors = [];
        $this->showDocumentPreview = false;
        $this->documentPreviewContent = '';

        // Pre-fill form data with default values
        $this->formData = [];
        if ($this->selectedTemplate) {
            $allPlaceholders = $this->selectedTemplate->getAllEditablePlaceholders();
            foreach ($allPlaceholders as $placeholder) {
                $this->formData[$placeholder] = $this->getDefaultValue($placeholder);
            }
        }

        $this->showOrderModal = true;
    }

    /**
     * Get default auto-fill value for a placeholder.
     */
    protected function getDefaultValue(string $placeholder): string
    {
        if (!$this->company) return '';

        // Company placeholders
        $companyDefaults = [
            'company_name' => $this->company->name ?? '',
            'company_no' => $this->company->registration_no ?? '',
            'company_old_no' => $this->company->registration_no_old ?? '',
            'company_address' => $this->company->address ?? '',
        ];

        if (isset($companyDefaults[$placeholder])) {
            return $companyDefaults[$placeholder];
        }

        // Secretary placeholders
        if (str_starts_with($placeholder, 'secretary_')) {
            $secretary = CompanySecretary::where('company_id', $this->companyId)->first();
            if ($secretary) {
                $secretaryDefaults = [
                    'secretary_name' => $secretary->name ?? '',
                    'secretary_license' => $secretary->license_no ?? $secretary->secretary_no ?? '',
                    'secretary_ssm' => $secretary->ssm_no ?? '',
                    'secretary_company' => $secretary->company_name ?? '',
                    'secretary_address' => $secretary->address ?? '',
                    'secretary_email' => $secretary->email ?? '',
                ];
                return $secretaryDefaults[$placeholder] ?? '';
            }
        }

        return '';
    }

    public function closeOrderModal()
    {
        $this->showOrderModal = false;
        $this->selectedTemplate = null;
        $this->formData = [];
        $this->selectedDirectors = [];
        $this->showDocumentPreview = false;
        $this->documentPreviewContent = '';
    }

    public function getRequiredSignaturesProperty()
    {
        if (!$this->selectedTemplate) {
            return 0;
        }

        return match ($this->selectedTemplate->signature_type) {
            'sole_director' => 1,
            'two_directors' => 2,
            'all_directors' => 3,
            default => 1,
        };
    }

    public function getCustomPlaceholdersProperty()
    {
        if (!$this->selectedTemplate) {
            return [];
        }
        return $this->selectedTemplate->getAllEditablePlaceholders();
    }

    public function getDirectorsProperty()
    {
        if (!$this->companyId) {
            return collect();
        }
        return CompanyDirector::where('company_id', $this->companyId)
            ->where('is_active', true)
            ->with('defaultSignature')
            ->get();
    }

    public function generateDocumentPreview()
    {
        // Validate required form fields (skip disabled fields for directors)
        $customPlaceholders = $this->customPlaceholders;
        $disabledForDirector = ['company_name', 'company_no', 'company_old_no', 'secretary_name', 'secretary_license', 'secretary_ssm'];
        $missingFields = [];
        foreach ($customPlaceholders as $placeholder) {
            // Skip validation for auto-filled fields
            if (in_array($placeholder, $disabledForDirector)) {
                continue;
            }
            if (empty($this->formData[$placeholder])) {
                $missingFields[] = CosecTemplate::placeholderToLabel($placeholder);
            }
        }

        if (!empty($missingFields)) {
            LivewireAlert::withOptions([
                'position' => 'top-end',
                'icon' => 'error',
                'title' => 'Please fill in: ' . implode(', ', array_slice($missingFields, 0, 3)) . (count($missingFields) > 3 ? '...' : ''),
                'timer' => 4000
            ])->show();
            return;
        }

        // Directors don't select signatories - admin will do that
        // Skip director selection validation for directors

        $this->documentPreviewContent = $this->buildDocumentContent();
        $this->showDocumentPreview = true;
    }

    public function closeDocumentPreview()
    {
        $this->showDocumentPreview = false;
        $this->documentPreviewContent = '';
    }

    protected function buildDocumentContent()
    {
        $template = $this->selectedTemplate;
        $content = $template->getTemplateContent();

        // Replace company placeholders
        $content = str_replace('{{company_name}}', $this->company->name ?? '', $content);
        $content = str_replace('{{company_no}}', $this->company->registration_no ?? '', $content);
        $content = str_replace('{{company_old_no}}', $this->company->registration_no_old ?? '', $content);

        // Replace custom form data
        foreach ($this->formData as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value ?? '', $content);
        }

        // Replace director signatures
        $directors = $this->directors;

        for ($i = 0; $i < count($this->selectedDirectors); $i++) {
            $directorId = $this->selectedDirectors[$i] ?? null;
            if ($directorId) {
                $director = $directors->find($directorId);
                if ($director) {
                    $index = $i + 1;
                    $content = str_replace("{{director_name_{$index}}}", $director->name ?? '', $content);
                    $content = str_replace("{{director_nric_{$index}}}", $director->nric ?? '', $content);
                    $content = str_replace("{{director_address_{$index}}}", $director->address ?? '', $content);

                    if ($director->defaultSignature) {
                        $signaturePath = '/tenancy/assets/' . $director->defaultSignature->signature_path;
                        $signatureHtml = '<img src="' . $signaturePath . '" style="max-width: 150px; max-height: 50px; width: 150px; height: 50px; object-fit: contain;" alt="Signature">';
                        $content = str_replace("{{director_signature_{$index}}}", $signatureHtml, $content);
                    } else {
                        $content = str_replace("{{director_signature_{$index}}}", '<span style="color: #999;">[No signature]</span>', $content);
                    }
                }
            }
        }

        // Replace secretary placeholders
        $secretary = CompanySecretary::where('company_id', $this->companyId)->first();
        if ($secretary) {
            $content = str_replace('{{secretary_name}}', $secretary->name ?? '', $content);
            $content = str_replace('{{secretary_license}}', $secretary->license_no ?? '', $content);
            $content = str_replace('{{secretary_ssm}}', $secretary->ssm_no ?? '', $content);
            $content = str_replace('{{secretary_company}}', $secretary->company_name ?? '', $content);
            $content = str_replace('{{secretary_address}}', $secretary->address ?? '', $content);
            $content = str_replace('{{secretary_email}}', $secretary->email ?? '', $content);
            // Secretary signature - use image if available
            if (!empty($secretary->signature_path)) {
                $signatureUrl = '/tenancy/assets/' . $secretary->signature_path;
                $content = str_replace('{{secretary_signature}}', '<img src="' . $signatureUrl . '" alt="Secretary Signature" style="max-width: 150px; max-height: 50px;">', $content);
            } else {
                $content = str_replace('{{secretary_signature}}', '', $content);
            }
        }

        // Replace resolution date with today if not set
        if (!isset($this->formData['resolution_date'])) {
            $content = str_replace('{{resolution_date}}', now()->format('d F Y'), $content);
        }

        return $content;
    }

    public function placeOrder()
    {
        if (!$this->selectedTemplate) {
            LivewireAlert::withOptions([
                'position' => 'top-end',
                'icon' => 'error',
                'title' => 'No template selected',
                'timer' => 2000
            ])->show();
            return;
        }

        // Validate required form fields (skip disabled fields for directors)
        $customPlaceholders = $this->customPlaceholders;
        $disabledForDirector = ['company_name', 'company_no', 'company_old_no', 'secretary_name', 'secretary_license', 'secretary_ssm'];
        $missingFields = [];
        foreach ($customPlaceholders as $placeholder) {
            // Skip validation for auto-filled fields
            if (in_array($placeholder, $disabledForDirector)) {
                continue;
            }
            if (empty($this->formData[$placeholder])) {
                $missingFields[] = CosecTemplate::placeholderToLabel($placeholder);
            }
        }

        if (!empty($missingFields)) {
            LivewireAlert::withOptions([
                'position' => 'top-end',
                'icon' => 'error',
                'title' => 'Please fill in: ' . implode(', ', array_slice($missingFields, 0, 3)) . (count($missingFields) > 3 ? '...' : ''),
                'timer' => 4000
            ])->show();
            return;
        }

        // Directors don't select signatories - admin will do that
        // Skip director selection validation

        $user = auth()->user();
        $template = $this->selectedTemplate;

        // Check credit balance
        $creditCost = $template->credit_cost ?? 0;
        if ($user->credit < $creditCost) {
            LivewireAlert::withOptions([
                'position' => 'top-end',
                'icon' => 'error',
                'title' => 'Insufficient credits. You need ' . $creditCost . ' credits.',
                'timer' => 3000
            ])->show();
            return;
        }

        // Structure data to match admin expected format
        $orderData = [
            'form_data' => $this->formData,
            'selected_directors' => $this->selectedDirectors,
            'placed_by_director' => true,
            'director_id' => $this->director->id,
        ];

        // Create order with pending status
        $order = CosecOrder::create([
            'tenant_company_id' => $this->companyId,
            'company_name' => $this->company->name ?? '',
            'company_no' => $this->company->registration_no ?? '',
            'company_old_no' => $this->company->registration_no_old ?? '',
            'template_id' => $template->id,
            'tenant_user_id' => $user->id,
            'user' => $user->name,
            'uuid' => \Illuminate\Support\Str::uuid(),
            'form_type' => $template->form_type ?? $template->name,
            'form_name' => $template->name,
            'data' => $orderData,
            'document_content' => null,
            'cost' => $creditCost,
            'status' => CosecOrder::STATUS_PENDING,
            'signature_status' => CosecOrder::SIGNATURE_PENDING,
            'requested_at' => now(),
        ]);

        // Deduct credits
        if ($creditCost > 0) {
            CreditTransaction::deductCredits(
                $user->id,
                $creditCost,
                'Order #' . $order->id . ' - ' . $template->name,
                CreditTransaction::REF_COSEC_ORDER,
                $order->id,
                $user->id
            );
        }

        $this->closeOrderModal();

        LivewireAlert::withOptions([
            'position' => 'top-end',
            'icon' => 'success',
            'title' => 'Order placed successfully! Admin will process your request.',
            'timer' => 3000
        ])->show();

        return redirect()->route('director.cosec.my-orders');
    }

    public function render()
    {
        $user = auth()->user();

        return view('livewire.tenant.pages.cosec.director-place-order', [
            'userCredit' => $user->credit ?? 0,
            'directors' => $this->directors,
            'requiredSignatures' => $this->requiredSignatures,
            'customPlaceholders' => $this->customPlaceholders,
        ]);
    }
}
