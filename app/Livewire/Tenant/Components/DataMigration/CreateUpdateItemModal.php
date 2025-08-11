<?php

namespace App\Livewire\Tenant\Components\DataMigration;

use App\Livewire\Tenant\Pages\Report\DataMigration\SocfDataMigration;
use App\Livewire\Tenant\Pages\Report\DataMigration\StsooDataMigration;
use Exception;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use LivewireUI\Modal\ModalComponent;
use App\Livewire\Tenant\Pages\Report\DataMigration\NtfsDataMigration;
use App\Models\Tenant\CompanyReportAccount;
use App\Models\Tenant\CompanyReportItem;
use App\Models\Tenant\CompanyReportType;

class CreateUpdateItemModal extends ModalComponent
{
    #[Locked]
    public $company_report_type_id;

    #[Locked]
    public $parentComponent;

    #[Validate('required')]
    public $company_report_account_id;

    // #[Validate('required_if:count($subgroups),Yes', message: 'Prior Year Report Date is required')]
    public $subgroup;


    public $report_item_name;

    public $companyReportType;
    public $companyReportAccounts;
    public $companyReportAccount;
    public $subgroups = [];


    public function mount($company_report_type_id, $parentComponent)
    {
        $this->parentComponent = $parentComponent;

        $this->company_report_type_id = $company_report_type_id;
        $this->companyReportType = CompanyReportType::find($company_report_type_id);
        $this->companyReportAccounts = $this->companyReportType->company_report_accounts;
    }

    public function render()
    {
        return view('livewire.tenant.components.data-migration.create-update-item-modal');
    }

    public function updatingCompanyReportAccountId($value) {
        $this->companyReportAccount = CompanyReportAccount::find($value);
        if ($this->companyReportAccount->description == "CASH FLOWS FROM OPERATING ACTIVITIES") {
            $this->subgroups = ['1' => "Operating profit before working capital changes", '2' => "Net change in operations", '3' => "Net change in operating activities"];
        } else {
            $this->subgroups = [];
        }
    }


    public function submit()
    {
        if (count($this->subgroups) > 0) {
            $this->withValidator(function ($validator) {
                $validator->after(function ($validator) {
                    if (!$this->subgroup) {
                        $validator->errors()->add('subgroup', 'Subgroup is required for this Acc type.');
                    }
                });
            });
        }
        $this->validate();
        $item = CompanyReportItem::create([
            'company_report_id' => $this->companyReportType->company_report_id,
            'company_report_type_id' => $this->companyReportType->id,
            'company_report_account_id' => $this->companyReportAccount->id,
            'item' => $this->report_item_name,
            'type' => CompanyReportItem::TYPE_VALUE,
            'sort' => "{$this->companyReportAccount->id}.{$this->subgroup}",
            'is_report' => true,
        ]);

        $this->closeModalWithEvents([
            $this->parentComponent => 'successCreated'
        ]);
    }
}
