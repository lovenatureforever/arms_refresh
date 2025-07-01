<?php

namespace App\Livewire\Tenant\Components\CorporateInfo;


use App\Livewire\Tenant\Pages\CorporateInfo\Director;
use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyDirector;
use App\Models\Tenant\CompanyDirectorChange;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use LivewireUI\Modal\ModalComponent;

class DirectorModal extends ModalComponent
{
    #[Locked]
    public $id;

    #[Locked]
    public $companyId;

    #[Locked]
    public $director;

    #[Locked]
    public $directorChange;

    #[Locked]
    public $isStart;

    public $changeNature;

    #[Validate('required_if:changeNature,"Director appointed"')]
    public $name;

    #[Validate('required_unless:changeNature,"Director appointed"')]
    public $selectedDirector;

    #[Validate('required')]
    public $designation;

    #[Validate('required_if:designation,"Alternate Director"')]
    public $alternateTo;

    #[Validate('required_if:changeNature,"Director appointed", "Changed of ID"')]
    public $idType;

    #[Validate('required_if:changeNature,"Director appointed", "Changed of ID"')]
    public $idNo;

    #[Validate('required_if:changeNature,"Director appointed", "Changed of director address"')]
    public $addressLine1;

    public $addressLine2;

    public $addressLine3;

    #[Validate('required_if:changeNature,"Director appointed", "Changed of director address"')]
    public $postcode;

    #[Validate('required_if:changeNature,"Director appointed", "Changed of director address"')]
    public $town;

    #[Validate('required_if:changeNature,"Director appointed", "Changed of director address"')]
    public $state;

    #[Validate('required_if:changeNature,"Director appointed", "Changed of director address"')]
    public $country;

    #[Validate('required_if:isStart,false')]
    public $effectiveDate;

    public $remarks;

    public $directors;

    #[Locked]
    public $company;

    public function mount($companyId, $id = null, $isStart)
    {
        $this->company = Company::find($companyId);
        $this->country = 'Malaysia';
        $this->idType = CompanyDirector::ID_TYPE_MYKAD;
        $this->changeNature = CompanyDirectorChange::CHANGE_NATURE_DIRECTOR_APPOINTED;
        $this->designation = CompanyDirector::DESIGNATION_DIRECTOR;
        if ($id) {
            $this->id = $id;
            $this->directorChange = CompanyDirectorChange::with('companyDirector')->find($id);
            $this->changeNature = $this->directorChange->change_nature;
            $this->name = $this->directorChange->companyDirector->name;
            $this->selectedDirector = $this->directorChange->company_director_id;
            $this->designation = $this->directorChange->companyDirector->designation;
            $this->alternateTo = $this->directorChange->companyDirector->alternate_id;
            $this->idType = $this->directorChange->companyDirector->id_type;
            $this->idNo = $this->directorChange->companyDirector->id_no;
            $this->addressLine1 = $this->directorChange->address_line1;
            $this->addressLine2 = $this->directorChange->address_line2;
            $this->addressLine3 = $this->directorChange->address_line3;
            $this->postcode = $this->directorChange->postcode;
            $this->town = $this->directorChange->town;
            $this->state = $this->directorChange->state;
            $this->country = $this->directorChange->country;
            $this->effectiveDate = $this->directorChange->effective_date->format('Y-m-d');
            $this->remarks = $this->directorChange->remarks;
        }
        $this->companyId = $companyId;
        $this->isStart = $isStart;
        if ($this->isStart) {
            $this->effectiveDate = "2000-01-01";
        }
    }

    public function render()
    {
        $this->directors = CompanyDirector::where('company_id', $this->companyId)
            // ->where('is_active', true)
            ->orderBy('sort')
            ->get();
        return view('livewire.tenant.components.corporate-info.director-modal');
    }

    public function submit()
    {
        $this->validate();
        // creating a director_change record
        if (!$this->id) {
            if ($this->changeNature === CompanyDirectorChange::CHANGE_NATURE_DIRECTOR_APPOINTED) {
                if ($this->designation !== CompanyDirector::DESIGNATION_ALTERNATEDIRECTOR) {
                    $this->alternateTo = null;
                }
                $new_director = CompanyDirector::create([
                    'company_id' => $this->companyId,
                    'name' => $this->name,
                    'designation' => $this->designation,
                    'alternate_id' => $this->alternateTo,
                    'id_type' => $this->idType,
                    'id_no' => $this->idNo,
                ]);

                CompanyDirectorChange::create([
                    'company_id' => $this->companyId,
                    'company_director_id' => $new_director->id ?? null,
                    'change_nature' => $this->changeNature,
                    'name' => $this->name,
                    'designation' => $this->designation,
                    'alternate_id' => $this->alternateTo,
                    'id_type' => $this->idType,
                    'id_no' => $this->idNo,
                    'address_line1' => $this->addressLine1,
                    'address_line2' => $this->addressLine2,
                    'address_line3' => $this->addressLine3,
                    'postcode' => $this->postcode,
                    'town' => $this->town,
                    'state' => $this->state,
                    'country' => $this->country,
                    'effective_date' => $this->effectiveDate,
                    'remarks' => $this->remarks,
                ]);
            }
            else if ($this->changeNature === CompanyDirectorChange::CHANGE_NATURE_CHANGED_OF_ID) {
                $current_director = CompanyDirector::where('id', $this->selectedDirector)->first();
                $this->directorChange = CompanyDirectorChange::create([
                    'company_id' => $this->companyId,
                    'company_director_id' => $current_director->id,
                    'change_nature' => $this->changeNature,
                    'id_type' => $this->idType,
                    'id_no' => $this->idNo,
                    'effective_date' => $this->effectiveDate,
                    'remarks' => $this->remarks,
                ]);
                $current_director->update([
                    'id_type' => $this->idType,
                    'id_no' => $this->idNo,
                ]);
            }
            else if ($this->changeNature === CompanyDirectorChange::CHANGE_NATURE_CHANGED_OF_ADDRESS) {
                $current_director = CompanyDirector::where('id', $this->selectedDirector)->first();
                $this->directorChange = CompanyDirectorChange::create([
                    'company_id' => $this->companyId,
                    'company_director_id' => $current_director->id,
                    'change_nature' => $this->changeNature,
                    'address_line1' => $this->addressLine1,
                    'address_line2' => $this->addressLine2,
                    'address_line3' => $this->addressLine3,
                    'postcode' => $this->postcode,
                    'town' => $this->town,
                    'state' => $this->state,
                    'country' => $this->country,
                    'effective_date' => $this->effectiveDate,
                    'remarks' => $this->remarks,
                ]);
            }
            else {
                $current_director = CompanyDirector::where('id', $this->selectedDirector)->first();
                $this->directorChange = CompanyDirectorChange::create([
                    'company_id' => $this->companyId,
                    'company_director_id' => $current_director->id,
                    'change_nature' => $this->changeNature,
                    'effective_date' => $this->effectiveDate,
                    'remarks' => $this->remarks,
                ]);
                if ($current_director->isInactive()) {
                    $current_director->is_active = false;
                    $current_director->save();
                }
            }

            $this->closeModalWithEvents([
                Director::class => 'successCreated'
            ]);
        }
        // Updating an existing director
        else {
            if ($this->changeNature === CompanyDirectorChange::CHANGE_NATURE_DIRECTOR_APPOINTED) {
                if ($this->designation !== CompanyDirector::DESIGNATION_ALTERNATEDIRECTOR) {
                    $this->alternateTo = null;
                }
                // If the director is already appointed, we update the existing director
                if ($this->directorChange->change_nature === CompanyDirectorChange::CHANGE_NATURE_DIRECTOR_APPOINTED) {
                    $this->directorChange->companyDirector->update([
                        'name' => $this->name,
                        'designation' => $this->designation,
                        'alternate_id' => $this->alternateTo,
                        'id_type' => $this->idType,
                        'id_no' => $this->idNo,
                    ]);
                }
                // If the director is not appointed, we create a new director
                else {
                    $new_director = CompanyDirector::create([
                        'company_id' => $this->companyId,
                        'name' => $this->name,
                        'designation' => $this->designation,
                        'alternate_id' => $this->alternateTo,
                        'id_type' => $this->idType,
                        'id_no' => $this->idNo,
                    ]);
                    $this->directorChange->companyDirector()->associate($new_director);
                }

                $this->directorChange->update([
                    'change_nature' => $this->changeNature,
                    'name' => $this->name,
                    'designation' => $this->designation,
                    'alternate_id' => $this->alternateTo,
                    'id_type' => $this->idType,
                    'id_no' => $this->idNo,
                    'address_line1' => $this->addressLine1,
                    'address_line2' => $this->addressLine2,
                    'address_line3' => $this->addressLine3,
                    'postcode' => $this->postcode,
                    'town' => $this->town,
                    'state' => $this->state,
                    'country' => $this->country,
                    'effective_date' => $this->effectiveDate,
                    'remarks' => $this->remarks,
                ]);

            }
            // When the nature of change is not director appointed.
            else {
                if ($this->directorChange->change_nature === CompanyDirectorChange::CHANGE_NATURE_DIRECTOR_APPOINTED) {
                    $this->directorChange->companyDirector->update([
                        'is_active' => false,
                    ]);
                }

                if ($this->changeNature === CompanyDirectorChange::CHANGE_NATURE_CHANGED_OF_ID) {
                    $current_director = CompanyDirector::where('id', $this->selectedDirector)->first();
                    $this->directorChange = CompanyDirectorChange::find($this->id)->update([
                        'company_id' => $this->companyId,
                        'company_director_id' => $current_director->id,
                        'change_nature' => $this->changeNature,
                        'id_type' => $this->idType,
                        'id_no' => $this->idNo,
                        'effective_date' => $this->effectiveDate,
                        'remarks' => $this->remarks,
                    ]);

                    // check wether the effectiveDate is behind the last change record's effective_date
                    $effect = $current_director->changes()
                        ->where('change_nature', CompanyDirectorChange::CHANGE_NATURE_DIRECTOR_APPOINTED)
                        ->orWhere('change_nature', CompanyDirectorChange::CHANGE_NATURE_CHANGED_OF_ID)
                        ->whereBetween('effective_date', [$this->effectiveDate, $this->company->end_date_report])
                        ->count();

                    if ($effect === 0) {
                        $current_director->update([
                            'id_type' => $this->idType,
                            'id_no' => $this->idNo,
                        ]);
                    }
                } else if ($this->changeNature === CompanyDirectorChange::CHANGE_NATURE_CHANGED_OF_ADDRESS) {
                    $current_director = CompanyDirector::where('id', $this->selectedDirector)->first();
                    $this->directorChange->companyDirector()->associate($current_director);
                } else {
                    // For other change natures, we just update the effective date and remarks
                }
            }
            $this->directorChange->companyDirector()->associate($this->directorChange->companyDirector);
            $this->directorChange->companyDirector->save();

            $this->closeModalWithEvents([
                Director::class => 'successUpdated'
            ]);
        }
    }

    public function updatedChangeNature($value) {
        if ($value !== CompanyDirectorChange::CHANGE_NATURE_DIRECTOR_APPOINTED) {
            $this->designation = CompanyDirector::DESIGNATION_DIRECTOR;
        }
    }
}
