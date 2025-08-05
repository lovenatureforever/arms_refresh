<?php

namespace App\Livewire\Tenant\Components\ReportConfig;

use App\Livewire\Tenant\Pages\ReportConfig\Ntfs\EstUncertainties;
use App\Models\Tenant\NtfsConfigItem;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use LivewireUI\Modal\ModalComponent;

class EstUncertaintiesModal extends ModalComponent
{
    #[Locked]
    public $companyId;

    #[Locked]
    public $isUpdate;

    #[Locked]
    public $id;

    public $item;

    #[Validate('required')]
    public $content;

    #[Validate('required')]
    public $type;

    // public $mbrs_mapping;
    public $position;

    public $remark;

    public function mount($companyId, $isUpdate, $id = null)
    {
        $this->companyId = $companyId;
        $this->isUpdate = $isUpdate;
        $this->id = $id;
        $this->item = NtfsConfigItem::findOrFail($id);

        if ($this->isUpdate) {
            $this->content = $this->item->content;
            $this->type = $this->item->type;
            $this->position = $this->item->position;
            // $this->display = $this->item->display;
            // $this->mbrs_mapping = $this->item->mbrs_mapping;
            $this->remark = $this->item->remark;
        }
    }

    public function render()
    {
        return view('livewire.tenant.components.report-config.est-uncertainties-modal');
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

            $newItem = NtfsConfigItem::create([
                'company_id' => $this->companyId,
                'content' => $this->content,
                'position' => $this->position,
                'section' => 'eu',
                'is_default_content' => false,
                'type' => $this->type,
                'is_active' => true,
                'remark' => $this->remark,
                // 'mbrs_mapping' => $this->item->mbrs_mapping
            ]);

            $newItem->update([
                'order' => $newItem->id
            ]);

            DB::commit();

            $this->closeModalWithEvents([
                EstUncertainties::class => 'successCreated'
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
                'company_id' => $this->companyId,
                'content' => $this->content,
                'position' => $this->position,
                'type' => $this->type,
                // 'is_active' => $this->is_active == '1' ? true : false,
                'remark' => $this->remark,
                // 'mbrs_mapping' => $this->mbrs_mapping
            ]);

            DB::commit();

            $this->closeModalWithEvents([
                EstUncertainties::class => 'successUpdated'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            info($e->getMessage());

            session()->flash('error', $e->getMessage());
        }
    }
}
