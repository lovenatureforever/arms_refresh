<?php

namespace App\Livewire\Tenant\Pages\ReportConfig;

use App\Models\Central\ReportConfig;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Models\Tenant\DirectorReportConfig as TenantsDirectorReportConfig;

class DirectorReportConfig extends Component
{
    #[Locked]
    public $id;

    public function mount($id)
    {
        $this->id = $id;

        $count = TenantsDirectorReportConfig::where('company_id', $this->id)->count();
        if ($count == 0) {
            $configs = ReportConfig::all();
            foreach ($configs as $config) {
                TenantsDirectorReportConfig::create([
                    'company_id' => $this->id,
                    'report_content' => $config->report_content,
                    'position' => $config->position,
                    'template_type' => $config->template_type,
                    'display' => $config->display,
                    'page_break' => $config->page_break,
                    'is_deleteable' => 0,
                    'remarks' => $config->remarks,
                    'order_no' => $config->id
                ]);
            }
        }
    }

    public function render()
    {
        $reportConfigs = TenantsDirectorReportConfig::where('company_id', $this->id)->get();

        return view('livewire.tenant.pages.report-config.director-report-config', [
            'reportConfigs' => $reportConfigs
        ]);
    }

    public function updateDisplay($id, $value)
    {
        $config = TenantsDirectorReportConfig::find($id);
        $config->display = $value;
        $config->save();
    }

    public function updatePageBreak($id, $value)
    {
        $config = TenantsDirectorReportConfig::find($id);
        $config->page_break = $value;
        $config->save();
    }

    #[On('successCreated')]
    public function successCreated()
    {
        // session()->flash('success', 'Content was created');
        LivewireAlert::withOptions([
            "position" => "top-end",
            "icon" => "success",
            "title" => "Item was created!",
            "showConfirmButton" => false,
            "timer" => 1500
        ])->show();
    }

    #[On('successUpdated')]
    public function successUpdated()
    {
        // session()->flash('success', 'Content was updated');
        LivewireAlert::withOptions([
            "position" => "top-end",
            "icon" => "success",
            "title" => "Item was updated!",
            "showConfirmButton" => false,
            "timer" => 1500
        ])->show();
    }

    public function deleteItem($id)
    {
        TenantsDirectorReportConfig::find($id)->delete();
    }

    public function updateReportOrder($orderItem)
    {
        foreach ($orderItem as $item) {
            $report = TenantsDirectorReportConfig::find($item['value']);

            $report->order_no = $item['order'];
            $report->save();
        }

       LivewireAlert::withOptions([
           "position" => "top-end",
           "icon" => "success",
           "title" => "Order Saved!",
           "showConfirmButton" => false,
           "timer" => 1500
       ])->show();
    }
}
