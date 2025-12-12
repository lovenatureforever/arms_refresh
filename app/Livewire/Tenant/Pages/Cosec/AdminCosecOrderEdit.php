<?php

namespace App\Livewire\Tenant\Pages\Cosec;

use Livewire\Component;
use App\Models\Tenant\CosecOrder;
use App\Models\Tenant\CosecTemplate;
use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyDirector;
use Livewire\Attributes\Locked;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class AdminCosecOrderEdit extends Component
{
    #[Locked]
    public $id;

    public $formData = [];
    public $selectedDirectors = [];
    public $customCreditCost;
    public $showPreview = false;
    public $previewContent = '';

    public function mount($id)
    {
        $this->id = $id;
        $order = CosecOrder::with(['template', 'company'])->find($id);

        if (!$order) {
            abort(404, 'Order not found');
        }

        // Prevent editing approved orders
        if ($order->status === CosecOrder::STATUS_APPROVED) {
            abort(403, 'Approved orders cannot be edited. Please use the View or PDF options.');
        }

        // Authorization check: subscribers can only access orders for companies they created
        $user = auth()->user();
        if ($user->isCosecSubscriber()) {
            $company = $order->company;
            if (!$company || $company->created_by !== $user->id) {
                abort(403, 'You do not have permission to access this order');
            }
        }

        $company = $order->company;

        $this->customCreditCost = $order->custom_credit_cost;

        // Load existing form data if saved
        $existingData = $order->data ?? [];
        if (isset($existingData['form_data'])) {
            $this->formData = $existingData['form_data'];
        }
        if (isset($existingData['selected_directors'])) {
            $this->selectedDirectors = $existingData['selected_directors'];
        }

        // Initialize form fields from template with auto-fill values
        if ($order->template) {
            $allPlaceholders = $order->template->getAllEditablePlaceholders();

            // Pre-fill with default values if not already set
            foreach ($allPlaceholders as $placeholder) {
                if (!isset($this->formData[$placeholder])) {
                    $this->formData[$placeholder] = $this->getDefaultValue($placeholder, $company);
                }
            }
        }

        // Initialize director selections
        if ($order->template && empty($this->selectedDirectors)) {
            $requiredSignatures = $order->template->getRequiredDirectorSignatures();
            for ($i = 0; $i < $requiredSignatures; $i++) {
                $this->selectedDirectors[$i] = null;
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

        // Secretary placeholders (without is_active filter to match DirectorPlaceOrder)
        if (str_starts_with($placeholder, 'secretary_')) {
            $secretary = $company->secretaries()->first();
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

    public function generatePreview()
    {
        $order = CosecOrder::with(['template', 'company'])->find($this->id);
        if (!$order || !$order->template) {
            LivewireAlert::withOptions([
                'position' => 'top-end',
                'icon' => 'error',
                'title' => 'Template not found',
                'timer' => 2000
            ])->show();
            return;
        }

        $template = $order->template;

        // Validate form data
        $customPlaceholders = $template->getUserInputPlaceholders();
        $rules = [];
        foreach ($customPlaceholders as $placeholder) {
            $rules['formData.' . $placeholder] = 'required';
        }

        // Validate director selections
        $requiredSignatures = $template->getRequiredDirectorSignatures();
        for ($i = 0; $i < $requiredSignatures; $i++) {
            $rules['selectedDirectors.' . $i] = 'required';
        }

        if (!empty($rules)) {
            $this->validate($rules, [
                'formData.*.required' => 'This field is required.',
                'selectedDirectors.*.required' => 'Please select a director.',
            ]);
        }

        // Build placeholder values
        $values = $this->buildPlaceholderValues($order);

        // Generate preview
        $this->previewContent = $template->fillPlaceholders($values);
        $this->showPreview = true;
    }

    protected function buildPlaceholderValues(CosecOrder $order): array
    {
        $values = [];
        $company = $order->company;

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
            if (!$directorId) continue;

            $director = CompanyDirector::with('defaultSignature')->find($directorId);
            if ($director) {
                $num = $index + 1;
                $values["director_name_{$num}"] = $director->name;
                $values["signer_name_{$num}"] = $director->name;

                $signature = $director->defaultSignature;
                if ($signature) {
                    $signatureUrl = '/tenancy/assets/' . $signature->signature_path;
                    $values["director_signature_{$num}"] = '<img src="' . $signatureUrl . '" alt="Signature" style="max-width: 150px; max-height: 50px; width: 150px; height: 50px; object-fit: contain;">';
                } else {
                    $values["director_signature_{$num}"] = '<span style="color: red;">[No signature uploaded]</span>';
                }
            }
        }

        // Secretary placeholders - use form data values if available, otherwise fetch from DB
        if ($company) {
            // First try to get secretary from DB (without is_active filter to match DirectorPlaceOrder)
            $secretary = $company->secretaries()->first();

            // Secretary text fields - prefer form data, then DB, then empty
            $secretaryFields = [
                'secretary_name',
                'secretary_license',
                'secretary_ssm',
                'secretary_company',
                'secretary_address',
                'secretary_email',
            ];

            foreach ($secretaryFields as $field) {
                // Use form data if it has a value
                if (!empty($this->formData[$field])) {
                    $values[$field] = $this->formData[$field];
                } elseif ($secretary) {
                    // Otherwise get from secretary record
                    $values[$field] = match($field) {
                        'secretary_name' => $secretary->name ?? '',
                        'secretary_license' => $secretary->license_no ?? $secretary->secretary_no ?? '',
                        'secretary_ssm' => $secretary->ssm_no ?? '',
                        'secretary_company' => $secretary->company_name ?? '',
                        'secretary_address' => $secretary->address ?? '',
                        'secretary_email' => $secretary->email ?? '',
                        default => ''
                    };
                } else {
                    $values[$field] = '';
                }
            }

            // Secretary signature - always from DB since it's an image
            if ($secretary && !empty($secretary->signature_path)) {
                $signatureUrl = '/tenancy/assets/' . $secretary->signature_path;
                $values['secretary_signature'] = '<img src="' . $signatureUrl . '" alt="Secretary Signature" style="max-width: 150px; max-height: 50px; width: 150px; height: 50px; object-fit: contain;">';
            } else {
                $values['secretary_signature'] = '';
            }
        }

        // Member placeholders (for Increase Paid Up Capital template)
        // Members are typically shareholders who need to sign
        if ($company) {
            $shareholders = $company->shareholders()->where('is_active', true)->get();
            foreach ($shareholders as $index => $shareholder) {
                $num = $index + 1;
                $values["member_name_{$num}"] = $shareholder->name ?? '';
                $values["member_shares_{$num}"] = number_format($shareholder->shares ?? 0, 0);
                $values["member_percentage_{$num}"] = $shareholder->percentage ?? '';
                // Member signature placeholder
                $values["member_signature_{$num}"] = '';
            }
        }

        return $values;
    }

    public function saveDraft()
    {
        $order = CosecOrder::find($this->id);

        // Save form data and director selections
        $data = $order->data ?? [];
        $data['form_data'] = $this->formData;
        $data['selected_directors'] = $this->selectedDirectors;

        $order->update([
            'data' => $data,
            'custom_credit_cost' => $this->customCreditCost,
        ]);

        LivewireAlert::withOptions([
            'position' => 'top-end',
            'icon' => 'success',
            'title' => 'Draft saved!',
            'showConfirmButton' => false,
            'timer' => 1500
        ])->show();
    }

    public function approve()
    {
        $order = CosecOrder::with(['template', 'company'])->find($this->id);

        if (!$order->template) {
            LivewireAlert::withOptions([
                'position' => 'top-end',
                'icon' => 'error',
                'title' => 'Template not found',
                'timer' => 2000
            ])->show();
            return;
        }

        // Build and save final document content
        $values = $this->buildPlaceholderValues($order);
        $filledContent = $order->template->fillPlaceholders($values);

        // Save form data
        $data = $order->data ?? [];
        $data['form_data'] = $this->formData;
        $data['selected_directors'] = $this->selectedDirectors;

        $order->update([
            'data' => $data,
            'document_content' => $filledContent,
            'custom_credit_cost' => $this->customCreditCost,
            'status' => CosecOrder::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        LivewireAlert::withOptions([
            'position' => 'top-end',
            'icon' => 'success',
            'title' => 'Order approved! Director can now download the PDF.',
            'showConfirmButton' => false,
            'timer' => 2000
        ])->show();

        return redirect()->route('admin.cosec.index');
    }

    public function reject()
    {
        $order = CosecOrder::find($this->id);
        $order->update(['status' => CosecOrder::STATUS_REJECTED]);

        LivewireAlert::withOptions([
            'position' => 'top-end',
            'icon' => 'warning',
            'title' => 'Order rejected.',
            'showConfirmButton' => false,
            'timer' => 1500
        ])->show();

        return redirect()->route('admin.cosec.index');
    }

    public function closePreview()
    {
        $this->showPreview = false;
    }

    public function printPdf()
    {
        return redirect()->route('cosec.print-pdf', $this->id);
    }

    public function render()
    {
        $order = CosecOrder::with(['template', 'company'])->find($this->id);
        $company = $order->company;
        $directors = $company ? $company->directors()->with('defaultSignature')->where('is_active', true)->get() : collect();

        $customPlaceholders = $order->template
            ? $order->template->getAllEditablePlaceholders()
            : [];

        $requiredSignatures = $order->template
            ? $order->template->getRequiredDirectorSignatures()
            : 0;

        return view('livewire.tenant.pages.cosec.admin-cosec-order-edit', [
            'order' => $order,
            'company' => $company,
            'directors' => $directors,
            'customPlaceholders' => $customPlaceholders,
            'requiredSignatures' => $requiredSignatures,
        ]);
    }
}
