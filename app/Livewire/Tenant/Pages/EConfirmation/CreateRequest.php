<?php

namespace App\Livewire\Tenant\Pages\EConfirmation;

use App\Models\Tenant\Auditor;
use App\Models\Tenant\Bank;
use App\Models\Tenant\BankBranch;
use App\Models\Tenant\Company;
use App\Models\Tenant\EConfirmationRequest;
use App\Services\EConfirmationPdfService;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateRequest extends Component
{
    use WithFileUploads;
    public $companies;
    public $banks;
    public $branches = [];
    public $approvers = [];

    public $selectedCompany = null;
    public $selectedBank = '';
    public $branchSearch = '';
    public $selectedBranch = null;
    public $accountNo = '';
    public $chargeCode = '';
    public $chargeCodeUnavailable = false;
    public $selectedApprover = null;
    public $validityDays = 14;
    public $authorizationLetter = null;
    public $clientConsentAcknowledged = false;

    public function mount()
    {
        $this->companies = Company::all()->sortBy('name');
        $this->selectedCompany = $this->companies->first()?->id;
        $this->loadBanks();
        $this->approvers = Auditor::with('user')
            ->whereHas('user', fn($q) => $q->where('is_active', 1))
            ->get();
    }

    public function loadBanks()
    {
        $this->banks = Bank::where('is_active', true)->orderBy('bank_name')->get();
    }

    public function loadBranches()
    {
        if ($this->selectedBank) {
            $query = BankBranch::where('bank_id', $this->selectedBank)
                ->where('is_active', true);

            if ($this->branchSearch) {
                $query->where('branch_name', 'like', '%' . $this->branchSearch . '%');
            }

            $this->branches = $query->orderBy('branch_name')->limit(10)->get();
        } else {
            $this->branches = [];
        }
    }

    public function updatedSelectedBank()
    {
        $this->branchSearch = '';
        $this->branches = [];
        $this->selectedBranch = null;
    }

    public function updatedBranchSearch()
    {
        $this->loadBranches();
    }

    public function updatedSelectedCompany()
    {
        $this->selectedBranch = null;
        $this->selectedBank = '';
        $this->branches = [];
    }

    public function updatedChargeCodeUnavailable()
    {
        if ($this->chargeCodeUnavailable) {
            $this->chargeCode = '';
        }
    }

    public function selectBranch($branchId)
    {
        $this->selectedBranch = (int) $branchId;
        $this->branchSearch = '';
        $this->branches = [];
    }

    public function clearBranch()
    {
        $this->selectedBranch = null;
    }

    public function createRequest()
    {
        $rules = [
            'selectedCompany' => 'required|exists:companies,id',
            'selectedBranch' => 'required|exists:bank_branches,id',
            'accountNo' => 'required|string|max:100',
            'selectedApprover' => 'required|exists:users,id',
            'validityDays' => 'required|integer|min:7|max:30',
            'authorizationLetter' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'clientConsentAcknowledged' => 'accepted',
        ];

        if (!$this->chargeCodeUnavailable) {
            $rules['chargeCode'] = 'required|string|max:100';
        }

        $this->validate($rules, [
            'selectedBranch.required' => 'Please select a bank branch.',
            'accountNo.required' => 'Account number is required.',
            'chargeCode.required' => 'Charge code is required.',
            'selectedApprover.required' => 'Please select an approver.',
            'authorizationLetter.required' => 'Authorization letter is required.',
            'authorizationLetter.mimes' => 'Authorization letter must be a PDF, DOC, DOCX, JPG, or PNG file.',
            'authorizationLetter.max' => 'Authorization letter must not exceed 10MB.',
            'clientConsentAcknowledged.accepted' => 'You must acknowledge that client consent is included.',
        ]);

        $company = Company::findOrFail($this->selectedCompany);

        // Store authorization letter
        $authorizationLetterPath = $this->authorizationLetter->store('econfirmation/authorization-letters', 'public');

        $request = EConfirmationRequest::create([
            'company_id' => $this->selectedCompany,
            'created_by' => auth()->id(),
            'year_end_date' => $company->current_year_to,
            'year_end_period' => $company->current_year_to?->format('Y') ?? now()->format('Y'),
            'token' => bin2hex(random_bytes(32)),
            'token_expires_at' => now()->addDays($this->validityDays),
            'status' => EConfirmationRequest::STATUS_DRAFT,
            'total_banks' => 1,
            'validity_days' => $this->validityDays,
            'account_no' => $this->accountNo ?: null,
            'charge_code' => $this->chargeCode ?: null,
            'approved_by' => $this->selectedApprover ?: null,
            'authorization_letter_path' => $authorizationLetterPath,
            'client_consent_acknowledged' => $this->clientConsentAcknowledged,
        ]);

        // Create bank PDF record
        $bankPdf = $request->bankPdfs()->create([
            'bank_branch_id' => $this->selectedBranch,
            'status' => 'pending',
            'signatures_required' => 0,
            'signatures_collected' => 0,
        ]);

        // Create signature records for each active director
        $directors = $company->directors()->where('is_active', true)->get();
        foreach ($directors as $director) {
            $bankPdf->signatures()->create([
                'director_id' => $director->id,
                'status' => 'pending',
                'director_name' => $director->name,
            ]);
        }

        // Update signatures_required count
        $bankPdf->update(['signatures_required' => $directors->count()]);

        // Generate unsigned PDF
        $pdfService = new EConfirmationPdfService();
        $pdfService->generateUnsignedPdf($bankPdf);

        session()->flash('success', 'E-Confirmation request created successfully.');

        return redirect()->route('econfirmation.index');
    }

    public function render()
    {
        $selectedCompanyData = null;
        if ($this->selectedCompany) {
            $selectedCompanyData = Company::with('directors')->find($this->selectedCompany);
        }

        // Get selected branch with bank info for display
        $selectedBranchData = null;
        if ($this->selectedBranch) {
            $selectedBranchData = BankBranch::with('bank')->find($this->selectedBranch);
        }

        return view('livewire.tenant.pages.econfirmation.create-request', [
            'selectedCompanyData' => $selectedCompanyData,
            'selectedBranchData' => $selectedBranchData,
        ]);
    }
}
