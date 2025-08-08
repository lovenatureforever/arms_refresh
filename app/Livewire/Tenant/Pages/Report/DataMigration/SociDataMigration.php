<?php

namespace App\Livewire\Tenant\Pages\Report\DataMigration;

use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use App\Models\Tenant\CompanyReport;
use App\Models\Tenant\CompanyReportItem;
use App\Models\Tenant\CompanyReportType;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Models\Tenant\CompanyReportAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SociDataMigration extends Component
{
    #[Locked]
    public $id;

    public $hideEmpty = false;
    public $this_year_values = [];
    public $last_year_values = [];
    public $actual_accounts = [];
    public $actual_displays = [];
    public $check_boxes = [];
    public $skin_check_boxes = [];
    #[Locked]
    public $company_report_items;
    #[Locked]
    public $company_report_type;
    public $activeTab = 'soci';

    public function mount($id)
    {
        $this->id = $id;
        CompanyReport::findOrFail($this->id);
        $this->refresh();
    }

    public function render()
    {
        return view('livewire.tenant.pages.report.data-migration.soci-data-migration');
    }

    public function updatingSkinCheckBoxes($value, $id) {
        if (!$value) {
            $this->actual_displays[$id] = null;
        }
    }

    #[On('successCreated')]
    public function successCreated()
    {
        session()->flash('success', 'Report item was created');
        $this->refresh();
    }

    private function refresh() {
        $this->company_report_type = CompanyReportType::where('company_report_id', $this->id)->where('name', 'SOCI')->first();
        $this->company_report_items = CompanyReportItem::where('company_report_type_id', $this->company_report_type->id)->orderBy('sort')->orderBy('id')->get();
        foreach ($this->company_report_items as $item) {
            $this->actual_displays[$item->id] = $item->display;
            $this->check_boxes[$item->id] = !!$item->is_report;
            $this->skin_check_boxes[$item->id] = $item->show_display;
            $this->this_year_values[$item->id] = displayNumber($item->this_year_amount);
            $this->last_year_values[$item->id] = displayNumber($item->last_year_amount);
        }
    }

    public function save()
    {
        foreach ($this->company_report_items as $item) {
            if ($item->type == CompanyReportItem::TYPE_VALUE) {
                $item->display = $this->actual_displays[$item->id];
                $item->this_year_amount = readNumber($this->this_year_values[$item->id]);
                $item->last_year_amount = readNumber($this->last_year_values[$item->id]);
                $item->is_report = $this->check_boxes[$item->id];
                $item->show_display = $this->skin_check_boxes[$item->id];
                $item->save();
            }
        }
        $this->refresh();
        LivewireAlert::withOptions([
            "position" => "top-end",
            "icon" => "success",
            "title" => "Saved successful!",
            "showConfirmButton" => false,
            "timer" => 1500
        ])->show();
    }
}
