<?php

namespace App\Livewire\Tenant\Pages\Company;

use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Locked;
use Livewire\Component;

use App\Models\Tenant\Company;

class EditCompany extends Component
{
    #[Locked]
    public $companyId;

    #[Validate('required')]
    public $name;

    public $companyGroup;

    #[Validate('required')]
    public $registrationNo;

    public $registrationNoOld;

    public $taxFileNo;

    public $employerFileNo;

    public $sstRegistrationNo;

    public $yearEnd;

    public function mount($id)
    {
        $this->companyId = $id;
        $company = Company::findOrFail($id);

        // Load current values
        $this->name = $company->name;
        $this->registrationNo = $company->registration_no;
        $this->registrationNoOld = $company->registration_no_old;
        $this->companyGroup = $company->company_group;
        $this->taxFileNo = $company->tax_file_no;
        $this->employerFileNo = $company->employer_file_no;
        $this->sstRegistrationNo = $company->sst_registration_no;
        $this->yearEnd = $company->year_end ? $company->year_end->format('Y-m-d') : null;
    }

    public function render()
    {
        return view('livewire.tenant.pages.company.edit-company');
    }

    public function update()
    {
        DB::beginTransaction();
        try {
            $this->validate();

            $company = Company::findOrFail($this->companyId);

            // Update basic company fields
            $company->update([
                'registration_no' => $this->registrationNo,
                'registration_no_old' => $this->registrationNoOld,
                'company_group' => $this->companyGroup,
                'tax_file_no' => $this->taxFileNo,
                'employer_file_no' => $this->employerFileNo,
                'sst_registration_no' => $this->sstRegistrationNo,
                'year_end' => $this->yearEnd,
            ]);

            // Update company name if changed
            if ($this->name !== $company->name) {
                $latestDetail = $company->detailChanges()->latest('effective_date')->first();
                if ($latestDetail) {
                    $latestDetail->update([
                        'name' => $this->name,
                    ]);
                }
            }

            DB::commit();

            session()->flash('success', 'Company updated successfully.');
            return $this->redirect(route('companies.show', $this->companyId));
        } catch (Exception $e) {
            info($e->getMessage());
            DB::rollBack();

            session()->flash('error', $e->getMessage());
        }
    }
}
