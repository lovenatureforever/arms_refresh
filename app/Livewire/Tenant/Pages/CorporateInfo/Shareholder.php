<?php

namespace App\Livewire\Tenant\Pages\CorporateInfo;

use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyShareholder;
use App\Models\Tenant\CompanyDirector;
use App\Models\Tenant\CompanyShareholderChange;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Shareholder extends Component
{
    #[Locked]
    public $id;

    #[Locked]
    public $company;

    #[Locked]
    public $shareholderChangesAtStart;

    #[Locked]
    public $shareholdersAtLast;

    #[Locked]
    public $shareholderChangesCurrentYear;

    public $resultSummary = [];

    public function mount($id)
    {
        $this->id = $id;
        $this->company = Company::with(relations: ['shareholders', 'shareholderChanges'])->find($this->id);
        $directors = $this->company->directors()->where('is_active', true)->get();
        foreach ($directors as $director) {
            $test = CompanyShareholder::where('company_id', $id)
                ->where('company_director_id', $director->id)
                ->exists();
            if (!$test) {
                CompanyShareholder::updateOrCreate(
                    [
                        'company_id' => $id,
                        'company_director_id' => $director->id,
                    ],
                    [
                        'name' => $director->name,
                        'type' => 'Individual',
                        'id_type' => $director->id_type,
                        'id_no' => $director->id_no,
                        'is_active' => true,
                    ]
                );
            }
        }
    }

    public function render()
    {
        $this->shareholderChangesAtStart = CompanyShareholderChange::with(['companyShareholder' => function($query) {
                $query->where('company_id', $this->id);
            }])
            ->whereHas('companyShareholder', function($query) {
                $query->where('company_id', $this->id);
            }, '=', 1) // Explicit count for better performance
            ->where('effective_date', '<', $this->company->current_year_from)
            ->latest('effective_date')
            ->get();
        $date = $this->company->current_year_to;

        $this->shareholdersAtLast = CompanyShareholder::where('company_id', $this->id)
            ->whereHas('changes', function($query) use ($date) {
                $query->where('effective_date', '<=', $date)
                    ->latest('effective_date');
            })
            ->get();

        $this->shareholderChangesCurrentYear = $this->company->shareholderChangesCurrentYear();
        $this->resultSummary = $this->calculateResult();
        return view('livewire.tenant.pages.corporate-info.shareholder');
    }

    public function deleteShareholderChange($id)
    {
        $res = CompanyShareholderChange::find($id);
        if ($res) {
            $res->delete();
            // session()->flash('success', 'Address Deleted');
            LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Shareholder Change Deleted successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
        }
    }

    #[On('successCreated')]
    public function successCreated()
    {
        // session()->flash('success', 'Address Created');
        LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Shareholder Created successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
    }

    #[On('successUpdated')]
    public function successUpdated()
    {
        // session()->flash('success', 'Address Updated');
        LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Shareholder Updated successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
    }

    private function calculateResult()
    {
        $shareChanges = [
            CompanyShareholderChange::SHARETYPE_ORDINARY => [],
            CompanyShareholderChange::SHARETYPE_PREFERENCE => [],
        ];

        $totalShares = [
            CompanyShareholderChange::SHARETYPE_ORDINARY => ['bf' => 0, 'bought' => 0, 'sold' => 0, 'cf' => 0],
            CompanyShareholderChange::SHARETYPE_PREFERENCE => ['bf' => 0, 'bought' => 0, 'sold' => 0, 'cf' => 0],
        ];

        $results = DB::select('
            SELECT
                s.`id`,
                s.`company_director_id`,
                s.`name`,
                s.`type`,
                sc.`change_nature`,
                sc.`share_type`,
                SUM(sc.`shares`) as `shares`,
                sc.`effective_date`
            FROM `company_shareholders` s
            JOIN `company_shareholder_changes` sc
                ON s.`id` = sc.`company_shareholder_id`
            WHERE s.`company_id` = ?
            AND sc.`effective_date` <= ?
            GROUP BY s.`id`, s.`type`, s.`company_director_id`, s.`name`, sc.`change_nature`, sc.`share_type`, sc.`effective_date`
            ORDER BY sc.`share_type`, s.`name`
        ', [$this->id, $this->company->current_year_to]);

        foreach ($results as $row) {
            $shareType = $row->share_type;
            $name = $row->name;

            // Initialize if not set
            if (!isset($shareChanges[$shareType][$name])) {
                $shareChanges[$shareType][$name] = [
                    'id' => $row->id,
                    'name' => $name,
                    'bf' => 0,
                    'bought' => 0,
                    'sold' => 0,
                    'cf' => 0,
                    'percentage' => 0,
                    'isDirector' => !!$row->company_director_id,
                    'isHoldingCompany' => $row->type === CompanyShareholder::TYPE_COMPANY ? true : false,
                ];
            }
            $tmp = &$shareChanges[$shareType][$name];

            // Determine action
            if (Carbon::parse($row->effective_date)->lt($this->company->current_year_from)) {
                $tmp['bf'] += $row->shares;
                $totalShares[$shareType]['bf'] += $row->shares;
                $totalShares[$shareType]['cf'] += $row->shares;
            } elseif (
                $row->change_nature === CompanyShareholderChange::CHANGE_NATURE_ALLOT ||
                $row->change_nature === CompanyShareholderChange::CHANGE_NATURE_TRANSFER_IN
            ) {
                $tmp['bought'] += $row->shares;
                $totalShares[$shareType]['bought'] += $row->shares;
                $totalShares[$shareType]['cf'] += $row->shares;
            } elseif (
                $row->change_nature === CompanyShareholderChange::CHANGE_NATURE_TRANSFER_OUT ||
                $row->change_nature === CompanyShareholderChange::CHANGE_NATURE_REDUCTION
            ) {
                $tmp['sold'] += $row->shares;
                $totalShares[$shareType]['sold'] += $row->shares;
                $totalShares[$shareType]['cf'] -= $row->shares;
            }
            $tmp['cf'] = $tmp['bf'] + $tmp['bought'] - $tmp['sold'];
        }

        return [
            'ordinaryShareChanges' => $shareChanges[CompanyShareholderChange::SHARETYPE_ORDINARY],
            'totalOrdinaryShares' => $totalShares[CompanyShareholderChange::SHARETYPE_ORDINARY],
            'preferenceShareChanges' => $shareChanges[CompanyShareholderChange::SHARETYPE_PREFERENCE],
            'totalPreferenceShares' => $totalShares[CompanyShareholderChange::SHARETYPE_PREFERENCE],
        ];
    }

}
