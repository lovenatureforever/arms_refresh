<?php

namespace App\Livewire\Tenant\Pages\EConfirmation;

use App\Models\Tenant\Bank;
use App\Models\Tenant\BankBranch;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class BankRegistry extends Component
{
    use WithPagination;

    public $search = '';

    // Bank form
    public $showBankModal = false;
    public $editingBankId = null;
    public $bankName = '';
    public $bankCode = '';
    public $bankIsActive = true;

    // Branch form
    public $showBranchModal = false;
    public $editingBranchId = null;
    public $selectedBankId = null;
    public $branchName = '';
    public $branchStreet = '';
    public $branchStreet2 = '';
    public $branchStreet3 = '';
    public $branchCity = '';
    public $branchState = '';
    public $branchPostcode = '';
    public $branchCountry = 'Malaysia';
    public $branchIsActive = true;

    // Expanded banks tracking
    public $expandedBanks = [];

    protected $rules = [
        'bankName' => 'required|string|max:255',
        'bankCode' => 'required|string|max:50',
    ];

    protected $messages = [
        'bankName.required' => 'Bank name is required.',
        'bankCode.required' => 'Bank code is required.',
    ];

    public function render()
    {
        $banks = Bank::with(['branches' => function ($query) {
            $query->orderBy('branch_name');
        }])
            ->when($this->search, function ($query) {
                $query->where('bank_name', 'like', '%' . $this->search . '%')
                    ->orWhere('bank_code', 'like', '%' . $this->search . '%');
            })
            ->orderBy('bank_name')
            ->paginate(15);

        return view('livewire.tenant.pages.econfirmation.bank-registry', [
            'banks' => $banks,
        ]);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function toggleBankExpand($bankId)
    {
        if (in_array($bankId, $this->expandedBanks)) {
            $this->expandedBanks = array_diff($this->expandedBanks, [$bankId]);
        } else {
            $this->expandedBanks[] = $bankId;
        }
    }

    // Bank CRUD methods
    public function openBankModal($bankId = null)
    {
        $this->resetBankForm();

        if ($bankId) {
            $bank = Bank::find($bankId);
            $this->editingBankId = $bankId;
            $this->bankName = $bank->bank_name;
            $this->bankCode = $bank->bank_code;
            $this->bankIsActive = $bank->is_active;
        }

        $this->showBankModal = true;
    }

    public function closeBankModal()
    {
        $this->showBankModal = false;
        $this->resetBankForm();
    }

    public function updatedBankName()
    {
        // Auto-generate bank code from name if not editing
        if (!$this->editingBankId) {
            $this->bankCode = Str::slug($this->bankName);
        }
    }

    public function saveBank()
    {
        $rules = $this->rules;

        // Check for unique bank_code
        if ($this->editingBankId) {
            $rules['bankCode'] = 'required|string|max:50|unique:banks,bank_code,' . $this->editingBankId;
        } else {
            $rules['bankCode'] = 'required|string|max:50|unique:banks,bank_code';
        }

        $this->validate($rules);

        Bank::updateOrCreate(
            ['id' => $this->editingBankId],
            [
                'bank_name' => $this->bankName,
                'bank_code' => Str::lower($this->bankCode),
                'is_active' => $this->bankIsActive,
            ]
        );

        $this->showBankModal = false;
        $this->resetBankForm();

        LivewireAlert::withOptions([
            'position' => 'top-end',
            'icon' => 'success',
            'title' => 'Bank saved successfully!',
            'showConfirmButton' => false,
            'timer' => 1500,
        ])->show();
    }

    public function deleteBank($bankId)
    {
        $bank = Bank::find($bankId);

        if ($bank) {
            $bank->delete();

            LivewireAlert::withOptions([
                'position' => 'top-end',
                'icon' => 'success',
                'title' => 'Bank deleted successfully!',
                'showConfirmButton' => false,
                'timer' => 1500,
            ])->show();
        }
    }

    public function toggleBankStatus($bankId)
    {
        $bank = Bank::find($bankId);

        if ($bank) {
            $bank->update(['is_active' => !$bank->is_active]);

            LivewireAlert::withOptions([
                'position' => 'top-end',
                'icon' => 'success',
                'title' => $bank->is_active ? 'Bank activated!' : 'Bank deactivated!',
                'showConfirmButton' => false,
                'timer' => 1500,
            ])->show();
        }
    }

    // Branch CRUD methods
    public function openBranchModal($bankId, $branchId = null)
    {
        $this->resetBranchForm();
        $this->selectedBankId = $bankId;

        if ($branchId) {
            $branch = BankBranch::find($branchId);
            $this->editingBranchId = $branchId;
            $this->branchName = $branch->branch_name ?? '';
            $this->branchStreet = $branch->street ?? '';
            $this->branchStreet2 = $branch->street_2 ?? '';
            $this->branchStreet3 = $branch->street_3 ?? '';
            $this->branchCity = $branch->city ?? '';
            $this->branchState = $branch->state ?? '';
            $this->branchPostcode = $branch->postcode ?? '';
            $this->branchCountry = $branch->country ?? 'Malaysia';
            $this->branchIsActive = $branch->is_active;
        }

        $this->showBranchModal = true;
    }

    public function closeBranchModal()
    {
        $this->showBranchModal = false;
        $this->resetBranchForm();
    }

    public function saveBranch()
    {
        $this->validate([
            'selectedBankId' => 'required|exists:banks,id',
            'branchName' => 'nullable|string|max:255',
            'branchIsActive' => 'boolean',
            'branchStreet' => 'nullable|string|max:255',
            'branchStreet2' => 'nullable|string|max:255',
            'branchStreet3' => 'nullable|string|max:255',
            'branchCity' => 'nullable|string|max:100',
            'branchState' => 'nullable|string|max:100',
            'branchPostcode' => 'nullable|string|max:10',
            'branchCountry' => 'nullable|string|max:100',
        ]);

        BankBranch::updateOrCreate(
            ['id' => $this->editingBranchId],
            [
                'bank_id' => $this->selectedBankId,
                'branch_name' => $this->branchName ?: null,
                'street' => $this->branchStreet ?: null,
                'street_2' => $this->branchStreet2 ?: null,
                'street_3' => $this->branchStreet3 ?: null,
                'city' => $this->branchCity ?: null,
                'state' => $this->branchState ?: null,
                'postcode' => $this->branchPostcode ?: null,
                'country' => $this->branchCountry ?: 'Malaysia',
                'is_active' => $this->branchIsActive,
            ]
        );

        $this->showBranchModal = false;
        $this->resetBranchForm();

        // Expand the bank to show the new branch
        if (!in_array($this->selectedBankId, $this->expandedBanks)) {
            $this->expandedBanks[] = $this->selectedBankId;
        }

        LivewireAlert::withOptions([
            'position' => 'top-end',
            'icon' => 'success',
            'title' => 'Branch saved successfully!',
            'showConfirmButton' => false,
            'timer' => 1500,
        ])->show();
    }

    public function deleteBranch($branchId)
    {
        $branch = BankBranch::find($branchId);

        if ($branch) {
            $branch->delete();

            LivewireAlert::withOptions([
                'position' => 'top-end',
                'icon' => 'success',
                'title' => 'Branch deleted successfully!',
                'showConfirmButton' => false,
                'timer' => 1500,
            ])->show();
        }
    }

    public function toggleBranchStatus($branchId)
    {
        $branch = BankBranch::find($branchId);

        if ($branch) {
            $branch->update(['is_active' => !$branch->is_active]);

            LivewireAlert::withOptions([
                'position' => 'top-end',
                'icon' => 'success',
                'title' => $branch->is_active ? 'Branch activated!' : 'Branch deactivated!',
                'showConfirmButton' => false,
                'timer' => 1500,
            ])->show();
        }
    }

    protected function resetBankForm()
    {
        $this->editingBankId = null;
        $this->bankName = '';
        $this->bankCode = '';
        $this->bankIsActive = true;
        $this->resetValidation();
    }

    protected function resetBranchForm()
    {
        $this->editingBranchId = null;
        $this->selectedBankId = null;
        $this->branchName = '';
        $this->branchStreet = '';
        $this->branchStreet2 = '';
        $this->branchStreet3 = '';
        $this->branchCity = '';
        $this->branchState = '';
        $this->branchPostcode = '';
        $this->branchCountry = 'Malaysia';
        $this->branchIsActive = true;
        $this->resetValidation();
    }
}
