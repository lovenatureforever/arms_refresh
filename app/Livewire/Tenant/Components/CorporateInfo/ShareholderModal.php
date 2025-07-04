<?php

namespace App\Livewire\Tenant\Components\CorporateInfo;


use App\Livewire\Tenant\Pages\CorporateInfo\Shareholder;
use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyShareholder;
use App\Models\Tenant\CompanyDirector;
use App\Models\Tenant\CompanyShareholderChange;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use LivewireUI\Modal\ModalComponent;

class ShareholderModal extends ModalComponent
{
    #[Locked]
    public $id;

    #[Locked]
    public $companyId;

    #[Locked]
    public $shareholder;

    #[Locked]
    public $shareholderChange;

    #[Locked]
    public $isStart;

    public $changeNature;

    public $isNewShareholder;

    public $selectedShareholder;

    #[Validate('required')]
    public $name;

    #[Validate('required', as: 'Type of member')]
    public $type;

    #[Validate('required')]
    public $idType;

    public $idTypeList = [];

    #[Validate('required')]
    public $idNo;

    #[Validate('required', as: 'Type of share')]
    public $shareType;

    public $shares;

    #[Validate('required_if:isStart,false')]
    public $effectiveDate;

    public $remarks;

    public $shareholders;
    public $directors;
    public $relatedDirectors = [];

    public $companyDomicile;

    #[Locked]
    public $company;

    public function mount($companyId, $id = null, $isStart)
    {
        $this->company = Company::find($companyId);
        $this->directors = $this->company->directors()->where('is_active', true)->get();

        $this->idType = CompanyShareholder::ID_TYPE_MYKAD;
        $this->changeNature = CompanyShareholderChange::CHANGE_NATURE_ALLOT;
        $this->shareType = CompanyShareholderChange::SHARETYPE_ORDINARY;
        $this->isNewShareholder = false;
        if ($id) {
            $this->id = $id;
            $this->shareholderChange = CompanyShareholderChange::with('companyShareholder')->find($id);
            $this->type = $this->shareholderChange->companyShareholder->type;
            $this->shareType = $this->shareholderChange->share_type;
            $this->shares = displayNumber($this->shareholderChange->shares);
            $this->updatedType($this->shareholderChange->companyShareholder->type);
            $this->changeNature = $this->shareholderChange->change_nature;
            $this->name = $this->shareholderChange->companyShareholder->name;
            $this->selectedShareholder = $this->shareholderChange->company_shareholder_id;
            $this->idType = $this->shareholderChange->companyShareholder->id_type;
            $this->idNo = $this->shareholderChange->companyShareholder->id_no;
            $this->companyDomicile = $this->shareholderChange->companyShareholder->company_domicile;
            $this->effectiveDate = $this->shareholderChange->effective_date->format('Y-m-d');
            $this->remarks = $this->shareholderChange->remarks;
            $this->relatedDirectors = $this->shareholderChange->companyShareholder->related_directors()->pluck('id')->toArray();
        }
        $this->companyId = $companyId;
        $this->isStart = $isStart;
        if ($this->isStart) {
            $this->effectiveDate = "2000-01-01";
        }
    }

    public function render()
    {
        $this->shareholders = CompanyShareholder::where('company_id', $this->companyId)
            ->where('is_active', true)
            ->get();
        return view('livewire.tenant.components.corporate-info.shareholder-modal');
    }

    public function submit()
    {
        $this->validate();
        $this->validate([
            'shares' => [
                'required',
                function ($attribute, $value, $fail) {
                    $number = readNumber($value);
                    // Check if the value is a valid number
                    if ($number === null) {
                        $fail('The '.$attribute.' must be a valid number.');
                        return;
                    }

                    // Check if the value is less than 0
                    if ($number < 0) {
                        $fail('The '.$attribute.' must not be less than 0.');
                    }
                },
            ],
        ], [], ['shares' => 'No. Of Shares']);
        // creating a shareholder_change record
        if (!$this->id) {
            if ($this->isNewShareholder) {

                // For new shareholder company
                if ($this->type == CompanyShareholder::TYPE_COMPANY) {
                    $this->validate([
                        'companyDomicile' => 'required',
                    ]);
                    $this->shareholder = CompanyShareholder::create([
                        'company_id' => $this->companyId,
                        'name' => $this->name,
                        'type' => $this->type,
                        'id_type' => CompanyShareholder::ID_TYPE_COMPANY_ID,
                        'id_no' => $this->idNo,
                        'company_domicile' => $this->companyDomicile,
                    ]);
                    $this->shareholder->related_directors()->sync($this->relatedDirectors);
                }
                // For new shareholder individual
                else {
                    $this->shareholder = CompanyShareholder::create([
                        'company_id' => $this->companyId,
                        'name' => $this->name,
                        'type' => $this->type,
                        'id_type' => $this->idType,
                        'id_no' => $this->idNo,
                    ]);
                }

                $this->selectedShareholder = $this->shareholder->id;
            } else {
                $this->shareholder = CompanyShareholder::find($this->selectedShareholder);
            }
            $this->shareholderChange = CompanyShareholderChange::create([
                'company_id' => $this->companyId,
                'company_shareholder_id' => $this->selectedShareholder,
                'change_nature' => $this->changeNature,
                'share_type' => $this->shareType,
                'shares' => readNumber($this->shares),
                'effective_date' => $this->effectiveDate,
                'remarks' => $this->remarks,
            ]);
            $this->closeModalWithEvents([
                Shareholder::class => 'successCreated'
            ]);
        }
        // Updating an existing shareholder
        else {
            $this->shareholderChange->update([
                'company_shareholder_id' => $this->selectedShareholder,
                'change_nature' => $this->changeNature,
                'share_type' => $this->shareType,
                'shares' => readNumber($this->shares),
                'effective_date' => $this->effectiveDate,
                'remarks' => $this->remarks,
            ]);
            $this->closeModalWithEvents([
                Shareholder::class => 'successUpdated'
            ]);
        }
    }

    public function updatedChangeNature($value) {
        if ($value !== CompanyShareholderChange::CHANGE_NATURE_ALLOT) {
        }
    }

    public function updatedType($value)
    {
        if ($value == 'Individual') {
            $this->idTypeList = [
                'Mykad',
                'Passport'
            ];

            $this->directors = [];
        } else if ($value == 'Company') {
            $this->idTypeList = [
                'Company ID'
            ];
            $this->idType = CompanyShareholder::ID_TYPE_COMPANY_ID;
        }
    }

    public function updatedSelectedShareholder($value)
    {
        $this->name = CompanyShareholder::find($value)->name;
        $this->idType = CompanyShareholder::find($value)->id_type;
        $this->idNo = CompanyShareholder::find($value)->id_no;
        $this->type = 'Individual';
        $this->shareType = 'Ordinary shares';
    }
}
