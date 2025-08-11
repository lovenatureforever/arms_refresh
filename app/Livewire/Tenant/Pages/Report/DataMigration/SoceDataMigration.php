<?php

namespace App\Livewire\Tenant\Pages\Report\DataMigration;

use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use App\Models\Tenant\CompanyReport;
use App\Models\Tenant\CompanyReportItem;
use App\Models\Tenant\CompanyReportSoceCol;
use App\Models\Tenant\CompanyReportSoceRow;
use App\Models\Tenant\CompanyReportSoceItem;
use App\Models\Tenant\CompanyReportType;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Models\Tenant\CompanyReportAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SoceDataMigration extends Component
{
    #[Locked]
    public $id;

    public $hideEmpty = false;
    public $soce_rows = [];
    public $soce_cols = [];
    public $soce_items = [];
    public $activeTab = 'soce';
    #[Locked]
    public $company_report_type;

    public function mount($id)
    {
        $this->id = $id;
        CompanyReport::findOrFail($this->id);
        $this->refresh();
    }

    public function render()
    {
        return view('livewire.tenant.pages.report.data-migration.soce-data-migration');
    }

    public function updatingSkinCheckBoxes($value, $id) {
        if (!$value) {
            $this->actual_displays[$id] = null;
        }
    }

    #[On('successCreated')]
    public function successCreated()
    {
        LivewireAlert::withOptions([
            "position" => "top-end",
            "icon" => "success",
            "title" => "Report item was created",
            "showConfirmButton" => false,
            "timer" => 1500
        ])->show();
        $this->refresh();
    }

    private function refresh() {
        $this->company_report_type = CompanyReportType::where('company_report_id', $this->id)->where('name', 'SOCE')->first();
        $this->soce_rows = CompanyReportSoceRow::where('company_report_id', $this->id)->orderBy('sort')->get();
        $this->soce_cols = CompanyReportSoceCol::where('company_report_id', $this->id)->orderBy('sort')->get();
        $this->soce_items = [];
        foreach ($this->soce_rows as $soce_row) {
            foreach ($this->soce_cols as $soce_col) {
                $soce_item = CompanyReportSoceItem::where('company_report_id', $this->id)->where('row_id', $soce_row->id)->where('col_id', $soce_col->id)->first();
                $this->soce_items[$soce_row->id][$soce_col->id] = $soce_item?->value;
                if ($soce_col->data_type == 'number') {
                    $this->soce_items[$soce_row->id][$soce_col->id] = displayNumber($soce_item?->value);
                }
            }
        }
    }

    public function save()
    {
        $total_col = CompanyReportSoceCol::where('company_report_id', $this->id)->where('name', 'Total (RM)')->first();
        foreach ($this->soce_rows as $soce_row) {
            $total = 0.0;
            foreach ($this->soce_cols as $soce_col) {
                if ($soce_col->name === 'Total (RM)') {
                    $total_item = CompanyReportSoceItem::where('company_report_id', $this->id)->where('row_id', $soce_row->id)->where('col_id', $total_col->id)->first();
                    if (!$total_item) {
                        $total_item = new CompanyReportSoceItem();
                        $total_item->company_report_id = $this->id;
                        $total_item->row_id = $soce_row->id;
                        $total_item->col_id = $total_col->id;
                    }
                    $total_item->value = $total;
                    $total_item->save();
                    continue;
                }
                if (isset($this->soce_items[$soce_row->id][$soce_col->id])) {
                    $item = CompanyReportSoceItem::where('company_report_id', $this->id)->where('row_id', $soce_row->id)->where('col_id', $soce_col->id)->first();
                    if (!$item) {
                        $item = new CompanyReportSoceItem();
                        $item->company_report_id = $this->id;
                        $item->row_id = $soce_row->id;
                        $item->col_id = $soce_col->id;
                    }
                    $item->value = $this->soce_items[$soce_row->id][$soce_col->id];
                    if ($soce_col->data_type == 'number') {
                        $item->value = readNumber($this->soce_items[$soce_row->id][$soce_col->id]);
                        $total += (float) $item->value;
                    }
                    $item->save();
                }
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
