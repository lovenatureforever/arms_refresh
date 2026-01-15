<?php

namespace App\Livewire\Tenant\Components\CorporateInfo;


use App\Livewire\Tenant\Pages\CorporateInfo\Secretaries;
use App\Livewire\Tenant\Pages\CorporateInfo\Shareholder;
use App\Models\User;
use App\Models\Tenant\Company;
use App\Models\Tenant\CompanySecretary;
use App\Models\Tenant\CompanySecretaryChange;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Log;

class SecretaryModal extends ModalComponent
{
    use WithFileUploads;

    #[Locked]
    public $id;

    #[Locked]
    public $companyId;

    #[Locked]
    public $secretary;

    #[Locked]
    public $secretaryChange;

    #[Locked]
    public $isStart;

    public $changeNature;

    public $selectedSecretary;

    #[Validate('required')]
    public $name;

    #[Validate('required')]
    public $idType;

    public $idTypeList = [];

    #[Validate('required')]
    public $idNo;

    #[Validate('required')]
    public $secretaryNo;

    public $licenseNo;

    public $ssmNo;

    public $email;

    public $phone;

    public $companyName;

    public $secretaryAddress;

    public $effectiveDate;

    public $remarks;

    public $secretaries;

    #[Locked]
    public $company;

    // Signature upload properties
    public $signatureFile;

    public $existingSignaturePath;

    // Subscriber user selection
    public $selectedSubscriber;
    public $subscribers;

    public function mount($companyId, $id = null, $isStart)
    {
        $this->company = Company::find($companyId);
        $this->idTypeList = CompanySecretary::$idTypes;
        $this->idType = CompanySecretary::ID_TYPE_MYKAD;
        $this->changeNature = CompanySecretaryChange::CHANGE_NATURE_SECRETARY_APPOINTED;
        if ($id) {
            $this->id = $id;
            $this->secretaryChange = CompanySecretaryChange::with('companySecretary')->find($id);
            $this->secretary = $this->secretaryChange->companySecretary;
            $this->changeNature = $this->secretaryChange->change_nature;
            $this->name = $this->secretary->name;
            $this->selectedSecretary = $this->secretaryChange->company_secretary_id;
            $this->idType = $this->secretary->id_type;
            $this->idNo = $this->secretary->id_no;
            $this->secretaryNo = $this->secretary->secretary_no;
            $this->licenseNo = $this->secretary->license_no;
            $this->ssmNo = $this->secretary->ssm_no;
            $this->email = $this->secretary->email;
            $this->phone = $this->secretary->phone;
            $this->companyName = $this->secretary->company_name;
            $this->secretaryAddress = $this->secretary->address;
            $this->existingSignaturePath = $this->secretary->signature_path;
            $this->effectiveDate = $this->secretaryChange->effective_date->format('Y-m-d');
            $this->remarks = $this->secretaryChange->remarks;
        }
        $this->companyId = $companyId;
        $this->isStart = $isStart;
        if ($this->isStart) {
            $this->effectiveDate = "2000-01-01";
        }
    }

    public function render()
    {
        $this->secretaries = CompanySecretary::where('company_id', $this->companyId)
            ->where('is_active', true)
            ->get();
        if ($this->secretary) $this->secretaries->add($this->secretary);

        // Get all subscriber users for selection
        $this->subscribers = User::where('user_type', User::USER_TYPE_SUBSCRIBER)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('livewire.tenant.components.corporate-info.secretary-modal');
    }

    public function updatedSelectedSubscriber($userId)
    {
        if (!$userId) {
            return;
        }

        $user = User::find($userId);
        if ($user && $user->user_type === User::USER_TYPE_SUBSCRIBER) {
            // Auto-fill secretary data from subscriber user
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone_number ?? '';
            $this->licenseNo = $user->license_no ?? '';
            $this->ssmNo = $user->ssm_no ?? '';
            $this->secretaryNo = $user->secretary_no ?? '';
            $this->companyName = $user->secretary_company_name ?? '';
            $this->secretaryAddress = $user->secretary_address ?? '';
            $this->existingSignaturePath = $user->signature_path ?? null;
        }
    }

    public function submit()
    {
        $this->validate();
        $this->validateEffectiveDate();
        // creating a secretary_change record
        if (!$this->id) {
            if ($this->changeNature === CompanySecretaryChange::CHANGE_NATURE_SECRETARY_APPOINTED) {
                // Handle signature upload
                $signaturePath = null;
                if ($this->signatureFile) {
                    $this->validate([
                        'signatureFile' => 'image|mimes:png,jpg,jpeg|max:2048'
                    ]);
                    $path = $this->signatureFile->store('signatures', 'public');
                    $signaturePath = $path;
                }

                $this->secretary = CompanySecretary::create([
                    'company_id' => $this->companyId,
                    'name' => $this->name,
                    'id_type' => $this->idType,
                    'id_no' => $this->idNo,
                    'secretary_no' => $this->secretaryNo,
                    'license_no' => $this->licenseNo,
                    'ssm_no' => $this->ssmNo,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'company_name' => $this->companyName,
                    'address' => $this->secretaryAddress,
                    'signature_path' => $signaturePath,
                ]);

                $this->selectedSecretary = $this->secretary->id;
            } else {
                $this->secretary = CompanySecretary::find($this->selectedSecretary);

                $this->secretary->update([
                    'is_active' => false,
                ]);

            }
            $this->secretaryChange = CompanySecretaryChange::create([
                'company_secretary_id' => $this->selectedSecretary,
                'change_nature' => $this->changeNature,
                'effective_date' => $this->effectiveDate,
                'remarks' => $this->remarks,
            ]);
            $this->closeModalWithEvents([
                Secretaries::class => 'successCreated'
            ]);
        }
        // Updating an existing secretary
        else {
            if ($this->changeNature === CompanySecretaryChange::CHANGE_NATURE_SECRETARY_APPOINTED) {
                // Handle signature upload for update
                $signaturePath = $this->secretary->signature_path; // Keep existing by default
                if ($this->signatureFile) {
                    $this->validate([
                        'signatureFile' => 'image|mimes:png,jpg,jpeg|max:2048'
                    ]);
                    // Delete old signature if exists
                    if ($this->secretary->signature_path) {
                        Storage::disk('public')->delete($this->secretary->signature_path);
                    }
                    $path = $this->signatureFile->store('signatures', 'public');
                    $signaturePath = $path;
                }

                $this->secretary->update([
                    'name' => $this->name,
                    'id_type' => $this->idType,
                    'id_no' => $this->idNo,
                    'secretary_no' => $this->secretaryNo,
                    'license_no' => $this->licenseNo,
                    'ssm_no' => $this->ssmNo,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'company_name' => $this->companyName,
                    'address' => $this->secretaryAddress,
                    'signature_path' => $signaturePath,
                ]);
            }
            else {
                if ($this->secretary->id !== $this->selectedSecretary) {
                    $this->secretary->update([
                        'is_active' => true,
                    ]);
                    $this->secretary = CompanySecretary::find($this->selectedSecretary);
                    $this->secretary->update([
                        'is_active' => false,
                    ]);
                }
            }
            $this->secretaryChange->update([
                'company_secretary_id' => $this->selectedSecretary,
                'effective_date' => $this->effectiveDate,
                'remarks' => $this->remarks,
            ]);
            $this->closeModalWithEvents([
                Secretaries::class => 'successUpdated'
            ]);
        }
    }

    public function updatedChangeNature($value) {
        if ($value !== CompanySecretaryChange::CHANGE_NATURE_SECRETARY_APPOINTED) {
        }
    }

    /* public function updatedSelectedSecretary($value)
    {
        $secretary = CompanySecretary::find($value);
        if (!$secretary) {
            $this->name = '';
            $this->idType = '';
            $this->idNo = '';
        } else {
            $this->name = $secretary->name;
            $this->idType = $secretary->id_type;
            $this->idNo = $secretary->id_no;
        }
        $this->secretaryNo = $secretary->secretary_no;
    } */

    private function validateEffectiveDate()
    {
        if ($this->isStart) return;
        $appointedDate = null;
        $start = Carbon::make($this->company->current_year_from);
        if ($this->changeNature !== CompanySecretaryChange::CHANGE_NATURE_SECRETARY_APPOINTED) {
            $appointedChange = $this->secretary->changes()
                ->where('change_nature', CompanySecretaryChange::CHANGE_NATURE_SECRETARY_APPOINTED)
                ->latest('effective_date')
                ->first();

            $appointedDate = $appointedChange ? $appointedChange->effective_date : null;
            if ($appointedDate) {
                $appointed = Carbon::make($appointedDate);
                $start = $appointed->gt($start) ? $appointed : $start;
            }
        }
        $this->validateOnly('effectiveDate', [
            'effectiveDate' => [
                'required',
                'date',
                'after:' . $start->format('Y-m-d'),
                'before_or_equal:' . $this->company->end_date_report->format('Y-m-d'),
            ],
        ]);
    }

}
