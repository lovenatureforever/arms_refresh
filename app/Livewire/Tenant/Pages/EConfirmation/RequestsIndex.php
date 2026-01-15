<?php

namespace App\Livewire\Tenant\Pages\EConfirmation;

use App\Models\Tenant\Bank;
use App\Models\Tenant\BankBranch;
use App\Models\Tenant\Company;
use App\Models\Tenant\EConfirmationRequest;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use ZipArchive;

class RequestsIndex extends Component
{
    use WithPagination;

    public $selectedCompany = null;
    public $selectedYearEnd = '';
    public $yearFilter = 'all';
    public $statusFilter = 'all';
    public $selectedBank = '';
    public $selectedBranch = '';
    public $accountNo = '';
    public $chargeCode = '';
    public $search = '';

    public $companies;
    public $yearEndOptions = [];
    public $availableYears = [];
    public $banks = [];
    public $branches = [];

    public function mount()
    {
        $this->companies = Company::all()->sortBy('name');
        // Don't select company by default - show all records
        $this->loadYearEndOptions();
        $this->loadAvailableYears();
        $this->banks = Bank::active()->orderBy('bank_name')->get();
    }

    public function loadYearEndOptions()
    {
        // Get unique year_end values from companies (same as CP204)
        $this->yearEndOptions = Company::whereNotNull('year_end')
            ->distinct()
            ->pluck('year_end')
            ->sort()
            ->values()
            ->toArray();
    }

    public function loadAvailableYears()
    {
        $this->availableYears = EConfirmationRequest::query()
            ->distinct()
            ->pluck('year_end_period')
            ->sort()
            ->values()
            ->toArray();
    }

    public function render()
    {
        $query = EConfirmationRequest::query()
            ->with(['company', 'creator', 'bankPdfs']);

        // Apply filters
        if ($this->selectedCompany) {
            $query->where('company_id', $this->selectedCompany);
        }

        if ($this->selectedYearEnd) {
            $query->whereHas('company', function ($q) {
                $q->where('year_end', $this->selectedYearEnd);
            });
        }

        if ($this->yearFilter !== 'all') {
            $query->where('year_end_period', $this->yearFilter);
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->search) {
            $query->whereHas('company.detailChanges', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->selectedBank) {
            $query->whereHas('bankPdfs.bankBranch', function ($q) {
                $q->where('bank_id', $this->selectedBank);
            });
        }

        if ($this->selectedBranch) {
            $query->whereHas('bankPdfs', function ($q) {
                $q->where('bank_branch_id', $this->selectedBranch);
            });
        }

        if ($this->accountNo) {
            $query->where('account_no', 'like', '%' . $this->accountNo . '%');
        }

        if ($this->chargeCode) {
            $query->where('charge_code', 'like', '%' . $this->chargeCode . '%');
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = $this->getStats();

        return view('livewire.tenant.pages.econfirmation.requests-index', [
            'requests' => $requests,
            'stats' => $stats,
        ]);
    }

    protected function getStats(): array
    {
        $baseQuery = EConfirmationRequest::query();

        // Apply only active filters (year end and search)
        if ($this->selectedYearEnd) {
            $baseQuery->whereHas('company', function ($q) {
                $q->where('year_end', $this->selectedYearEnd);
            });
        }

        if ($this->search) {
            $baseQuery->whereHas('company.detailChanges', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        return [
            'total' => (clone $baseQuery)->count(),
            'draft' => (clone $baseQuery)->where('status', EConfirmationRequest::STATUS_DRAFT)->count(),
            'pending' => (clone $baseQuery)->where('status', EConfirmationRequest::STATUS_PENDING_SIGNATURES)->count(),
            'completed' => (clone $baseQuery)->where('status', EConfirmationRequest::STATUS_COMPLETED)->count(),
            'expired' => (clone $baseQuery)->where('status', EConfirmationRequest::STATUS_EXPIRED)->count(),
        ];
    }

    public function updatedSelectedCompany()
    {
        $this->resetPage();
    }

    public function updatedSelectedYearEnd()
    {
        $this->resetPage();
    }

    public function updatedYearFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedBank()
    {
        $this->selectedBranch = '';
        $this->loadBranches();
        $this->resetPage();
    }

    public function updatedSelectedBranch()
    {
        $this->resetPage();
    }

    public function updatedAccountNo()
    {
        $this->resetPage();
    }

    public function updatedChargeCode()
    {
        $this->resetPage();
    }

    public function loadBranches()
    {
        if ($this->selectedBank) {
            $this->branches = BankBranch::where('bank_id', $this->selectedBank)
                ->active()
                ->orderBy('branch_name')
                ->get();
        } else {
            $this->branches = [];
        }
    }

    public function viewRequest($requestId)
    {
        return redirect()->route('econfirmation.view', $requestId);
    }

    public function downloadZip($requestId)
    {
        $request = EConfirmationRequest::with(['bankPdfs.bankBranch.bank', 'company'])
            ->findOrFail($requestId);

        if ($request->status !== EConfirmationRequest::STATUS_COMPLETED) {
            session()->flash('error', 'Cannot download - request is not completed.');
            return;
        }

        $companyName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $request->company->name);
        $zipFileName = "econfirmation_{$request->id}_{$companyName}.zip";
        $zipPath = storage_path("app/temp/{$zipFileName}");

        // Ensure temp directory exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $hasFiles = false;

            foreach ($request->bankPdfs as $bankPdf) {
                if ($bankPdf->signed_pdf_path && Storage::disk('public')->exists($bankPdf->signed_pdf_path)) {
                    $pdfContent = Storage::disk('public')->get($bankPdf->signed_pdf_path);
                    $bankName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $bankPdf->bankBranch->bank->bank_name);
                    $branchName = $bankPdf->bankBranch->branch_name
                        ? preg_replace('/[^a-zA-Z0-9_-]/', '_', $bankPdf->bankBranch->branch_name)
                        : 'Main';
                    $pdfName = "{$bankName}_{$branchName}.pdf";
                    $zip->addFromString($pdfName, $pdfContent);
                    $hasFiles = true;
                }
            }
            $zip->close();

            if ($hasFiles) {
                return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
            }

            // Clean up empty zip
            @unlink($zipPath);
            session()->flash('error', 'No signed PDFs available for download.');
            return;
        }

        session()->flash('error', 'Failed to create ZIP file.');
    }
}
