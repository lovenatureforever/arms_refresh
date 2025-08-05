<?php

namespace App\Livewire\Tenant\Components\ReportConfig;

use App\Livewire\Tenant\Pages\ReportConfig\DirectorReportConfig as ConfigDirectorReportConfig;
use App\Models\Tenant\DirectorReportConfig as TenantsDirectorReportConfig;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use LivewireUI\Modal\ModalComponent;

class DirectorReportConfig extends ModalComponent
{
    #[Locked]
    public $companyId;

    #[Locked]
    public $isUpdate;

    #[Locked]
    public $id;

    public $item;

    #[Validate('required')]
    public $reportContent;

    #[Validate('required')]
    public $templateType;

    #[Validate('required')]
    public $display;

    #[Validate('required')]
    public $pageBreak;

    public $remarks;

    // public $trixId;

    public function mount($companyId, $isUpdate, $id = null)
    {
        $this->companyId = $companyId;
        $this->isUpdate = $isUpdate;
        $this->id = $id;
        $this->item = TenantsDirectorReportConfig::findOrFail($id);

        if ($this->isUpdate) {
            $this->reportContent = $this->item->report_content;
            $this->templateType = $this->item->template_type;
            $this->display = $this->item->display;
            $this->pageBreak = $this->item->page_break;
            $this->remarks = $this->item->remarks;
        }
        // $this->reportContent = $reportContent;
        // $this->trixId = 'trix-' . uniqid();
    }

    public function render()
    {
        return view('livewire.tenant.components.report-config.director-report-config');
    }

    public function submit()
    {
        $this->validate();
        if ($this->isUpdate) {
            $this->update();
        } else {
            $this->create();
        }
    }

    private function create()
    {
        DB::beginTransaction();
        try {

            TenantsDirectorReportConfig::create([
                'company_id' => $this->companyId,
                'report_content' => $this->reportContent,
                'position' => 999,
                'template_type' => $this->templateType,
                'display' => $this->display == '1' ? true : false,
                'page_break' => $this->pageBreak == '1' ? true : false,
                'is_deleteable' => 1,
                'remarks' => $this->remarks,
                'order_no' => $this->item->order_no
            ]);

            DB::commit();

            $this->closeModalWithEvents([
                ConfigDirectorReportConfig::class => 'successCreated'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            info($e->getMessage());

            session()->flash('error', $e->getMessage());
        }
    }

    private function update() {
        DB::beginTransaction();
        try {
            $this->item->update([
                'report_content' => $this->reportContent,
                'template_type' => $this->templateType,
                'display' => $this->display == '1' ? true : false,
                'page_break' => $this->pageBreak == '1' ? true : false,
                'remarks' => $this->remarks
            ]);

            DB::commit();

            $this->closeModalWithEvents([
                ConfigDirectorReportConfig::class => 'successUpdated'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            info($e->getMessage());

            session()->flash('error', $e->getMessage());
        }
    }
}
