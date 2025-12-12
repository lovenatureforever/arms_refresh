<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use Livewire\Component;
use Livewire\Attributes\Locked;
use App\Models\Tenant\CosecTemplate;
use App\Models\Tenant\CosecOrder;
use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyDirector;
use App\Models\Tenant\DirectorSignature;
use App\Models\Tenant\CreditTransaction;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class PlaceOrderCosec extends Component
{
    #[Locked]
    public $companyId;

    public $selectedTemplateId = null;
    public $formData = [];
    public $selectedDirectors = [];
    public $showPreview = false;
    public $previewContent = '';

    public function mount($id)
    {
        $this->companyId = $id;

        // Verify user has access to this company
        $this->authorizeCompanyAccess();
    }

    /**
     * Check if the current user has access to the company.
     * - Admin (cosec_admin): Can access any company
     * - Subscriber (cosec_subscriber): Can only access companies they created
     * - Director (cosec_director): Can only access companies they are linked to
     */
    protected function authorizeCompanyAccess(): void
    {
        $user = auth()->user();
        $company = Company::find($this->companyId);

        if (!$company) {
            abort(404, 'Company not found.');
        }

        // Admin can access all companies
        if ($user->hasRole('cosec_admin')) {
            return;
        }

        // Subscriber can only access companies they created
        if ($user->hasRole('cosec_subscriber')) {
            if ($company->created_by !== $user->id) {
                abort(403, 'You do not have access to this company.');
            }
            return;
        }

        // Director can only access companies they are linked to
        if ($user->hasRole('cosec_director')) {
            $isLinked = CompanyDirector::where('company_id', $this->companyId)
                ->where('user_id', $user->id)
                ->exists();

            if (!$isLinked) {
                abort(403, 'You do not have access to this company.');
            }
            return;
        }

        // No valid role - deny access
        abort(403, 'You do not have permission to access this page.');
    }

    public function updatedSelectedTemplateId($value)
    {
        $this->reset(['formData', 'selectedDirectors', 'showPreview', 'previewContent']);

        if ($value) {
            $template = CosecTemplate::find($value);
            if ($template) {
                $company = Company::find($this->companyId);

                // Initialize form data for all editable placeholders with default values
                $allPlaceholders = $template->getAllEditablePlaceholders();
                foreach ($allPlaceholders as $placeholder) {
                    $this->formData[$placeholder] = $this->getDefaultValue($placeholder, $company);
                }

                // Initialize director selections based on required signatures
                $requiredSignatures = $template->getRequiredDirectorSignatures();
                for ($i = 0; $i < $requiredSignatures; $i++) {
                    $this->selectedDirectors[$i] = null;
                }
            }
        }
    }

    /**
     * Get default auto-fill value for a placeholder.
     */
    protected function getDefaultValue(string $placeholder, ?Company $company): string
    {
        if (!$company) return '';

        // Company placeholders
        $companyDefaults = [
            'company_name' => $company->name ?? '',
            'company_no' => $company->registration_no ?? '',
            'company_old_no' => $company->registration_no_old ?? '',
            'company_address' => $company->address ?? '',
        ];

        if (isset($companyDefaults[$placeholder])) {
            return $companyDefaults[$placeholder];
        }

        // Secretary placeholders
        if (str_starts_with($placeholder, 'secretary_')) {
            $secretary = $company->secretaries()->where('is_active', true)->first();
            if ($secretary) {
                $secretaryDefaults = [
                    'secretary_name' => $secretary->name ?? '',
                    'secretary_license' => $secretary->license_no ?? $secretary->secretary_no ?? '',
                    'secretary_ssm_no' => $secretary->ssm_no ?? '',
                    'secretary_company' => $secretary->company_name ?? '',
                    'secretary_address' => $secretary->address ?? '',
                    'secretary_email' => $secretary->email ?? '',
                ];
                return $secretaryDefaults[$placeholder] ?? '';
            }
        }

        return '';
    }

    public function generatePreview()
    {
        $template = CosecTemplate::find($this->selectedTemplateId);
        if (!$template) {
            return;
        }

        // Validate form data
        $customPlaceholders = $template->getAllEditablePlaceholders();
        $rules = [];
        foreach ($customPlaceholders as $placeholder) {
            $rules['formData.' . $placeholder] = 'required';
        }

        // Validate director selections
        $requiredSignatures = $template->getRequiredDirectorSignatures();
        for ($i = 0; $i < $requiredSignatures; $i++) {
            $rules['selectedDirectors.' . $i] = 'required';
        }

        $this->validate($rules, [
            'formData.*.required' => 'This field is required.',
            'selectedDirectors.*.required' => 'Please select a director.',
        ]);

        // Build values array for placeholders
        $values = $this->buildPlaceholderValues($template);

        // Generate preview content
        $previewContent = $template->fillPlaceholders($values);

        // Store preview content for the modal
        $this->previewContent = $previewContent;
        $this->showPreview = true;
    }

    protected function buildPlaceholderValues(CosecTemplate $template): array
    {
        $values = [];
        $company = Company::find($this->companyId);

        // Company placeholders
        if ($company) {
            $values['company_name'] = $company->name ?? '';
            $values['company_no'] = $company->registration_no ?? '';
            $values['company_old_no'] = $company->registration_no_old ?? '';
        }

        // Custom form data
        foreach ($this->formData as $key => $value) {
            $values[$key] = $value;
        }

        // Director signatures and names
        foreach ($this->selectedDirectors as $index => $directorId) {
            $director = CompanyDirector::with('defaultSignature')->find($directorId);
            if ($director) {
                $num = $index + 1;
                $values["director_name_{$num}"] = $director->name;
                $values["signer_name_{$num}"] = $director->name;

                // Get signature image
                $signature = $director->defaultSignature;
                if ($signature) {
                    $signatureUrl = '/tenancy/assets/' . $signature->signature_path;
                    $values["director_signature_{$num}"] = '<img src="' . $signatureUrl . '" alt="Signature" style="max-width: 150px; max-height: 50px; width: 150px; height: 50px; object-fit: contain;">';
                } else {
                    $values["director_signature_{$num}"] = '<span style="color: red;">[No signature uploaded]</span>';
                }
            }
        }

        // Secretary placeholders (from company secretary if exists)
        if ($company) {
            $secretary = $company->secretaries()->where('is_active', true)->first();
            if ($secretary) {
                $values['secretary_name'] = $secretary->name ?? '';
                $values['secretary_license'] = $secretary->license_no ?? '';
                $values['secretary_ssm_no'] = $secretary->ssm_no ?? '';
                $values['secretary_signature'] = ''; // Can be enhanced later
            } else {
                // Default values when no secretary is assigned
                $values['secretary_name'] = '[Secretary Name]';
                $values['secretary_license'] = '[License No]';
                $values['secretary_ssm_no'] = '[SSM No]';
                $values['secretary_signature'] = '';
            }
        }

        return $values;
    }

    public function submitOrder()
    {
        $template = CosecTemplate::find($this->selectedTemplateId);
        if (!$template) {
            LivewireAlert::withOptions([
                'position' => 'top-end',
                'icon' => 'error',
                'title' => 'Template not found',
                'timer' => 2000
            ])->show();
            return;
        }

        $user = auth()->user();

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

        // Build final content
        $values = $this->buildPlaceholderValues($template);
        $filledContent = $template->fillPlaceholders($values);

        // Get company info
        $company = Company::find($this->companyId);

        // Get required signatures count
        $requiredSignatures = $template->getRequiredDirectorSignatures();

        // Create order
        $order = CosecOrder::create([
            'tenant_company_id' => $this->companyId,
            'company_name' => $company->name ?? '',
            'company_no' => $company->registration_no ?? '',
            'company_old_no' => $company->registration_no_old ?? '',
            'template_id' => $this->selectedTemplateId,
            'tenant_user_id' => $user->id,
            'user' => $user->name,
            'uuid' => \Illuminate\Support\Str::uuid(),
            'form_type' => $template->form_type ?? $template->name,
            'form_name' => $template->name,
            'data' => array_merge($this->formData, ['selected_directors' => $this->selectedDirectors]),
            'document_content' => $filledContent,
            'cost' => $creditCost,
            'status' => CosecOrder::STATUS_PENDING,
            'signature_status' => $requiredSignatures > 0 ? CosecOrder::SIGNATURE_PENDING : CosecOrder::SIGNATURE_NOT_REQUIRED,
            'requested_at' => now(),
        ]);

        // Create signature requests for selected directors
        if ($requiredSignatures > 0) {
            foreach ($this->selectedDirectors as $index => $directorId) {
                if ($directorId) {
                    \App\Models\Tenant\CosecOrderSignature::create([
                        'cosec_order_id' => $order->id,
                        'director_id' => $directorId,
                        'signature_status' => 'pending',
                    ]);
                }
            }
        }

        // Deduct credits using the proper method that tracks balance
        if ($creditCost > 0) {
            CreditTransaction::deductCredits(
                $user->id,
                $creditCost,
                'Order #' . $order->id . ' - ' . $template->name,
                CosecOrder::class,
                $order->id
            );
        }

        LivewireAlert::withOptions([
            'position' => 'top-end',
            'icon' => 'success',
            'title' => 'Order placed successfully!',
            'timer' => 2000
        ])->show();

        // Redirect to order view
        return redirect()->route('cosec.view', $order->id);
    }

    public function closePreview()
    {
        $this->showPreview = false;
    }

    public function render()
    {
        $company = Company::find($this->companyId);
        $templates = CosecTemplate::where('is_active', true)->get();
        $directors = $company ? $company->directors()->where('is_active', true)->get() : collect();

        $selectedTemplate = $this->selectedTemplateId
            ? CosecTemplate::find($this->selectedTemplateId)
            : null;

        $customPlaceholders = $selectedTemplate
            ? $selectedTemplate->getAllEditablePlaceholders()
            : [];

        $requiredSignatures = $selectedTemplate
            ? $selectedTemplate->getRequiredDirectorSignatures()
            : 0;

        return view('livewire.tenant.pages.cosec.place-order-cosec', [
            'company' => $company,
            'templates' => $templates,
            'directors' => $directors,
            'selectedTemplate' => $selectedTemplate,
            'customPlaceholders' => $customPlaceholders,
            'requiredSignatures' => $requiredSignatures,
        ]);
    }
}
