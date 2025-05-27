<?php

namespace App\Livewire\Tenant\Pages\CorporateInfo;

use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyShareCapitalChange;
use Exception;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;

class ShareCapital extends Component
{
    #[Locked]
    public $id;

    #[Locked]
    public $company;

    #[Validate('required')]
    public $ordinaryFullNoShare;

    #[Validate('required')]
    public $ordinaryFullPaidAmount;

    #[Validate('required')]
    public $ordinaryPartialNoShare;

    #[Validate('required')]
    public $ordinaryPartialPaidAmount;

    public $ordinaryTotalShare;

    public $ordinaryTotalAmount;

    #[Validate('required')]
    public $preferenceFullNoShare;

    #[Validate('required')]
    public $preferenceFullPaidAmount;

    #[Validate('required')]
    public $preferencePartialNoShare;

    #[Validate('required')]
    public $preferencePartialPaidAmount;

    public $preferenceTotalShare;

    public $preferenceTotalAmount;

    #[Locked]
    public $sharecapitalChangesCurrentYear;

    #[Locked]
    public $ordinaryShareAtStart;

    #[Locked]
    public $preferenceShareAtStart;


    public $startFullOrdinaryShares = 0;
    public $additionFullOrdinaryShares = 0;
    public $reductionFullOrdinaryShares = 0;
    public $sumFullOrdinaryShares = 0;
    public $startFullOrdinaryAmount = 0;
    public $additionFullOrdinaryAmount = 0;
    public $reductionFullOrdinaryAmount = 0;
    public $sumFullOrdinaryAmount = 0;

    public $startPartiallyOrdinaryShares = 0;
    public $additionPartiallyOrdinaryShares = 0;
    public $reductionPartiallyOrdinaryShares = 0;
    public $sumPartiallyOrdinaryShares = 0;
    public $startPartiallyOrdinaryAmount = 0;
    public $additionPartiallyOrdinaryAmount = 0;
    public $reductionPartiallyOrdinaryAmount = 0;
    public $sumPartiallyOrdinaryAmount = 0;

    public $startTotalOrdinaryShares = 0;
    public $additionTotalOrdinaryShares = 0;
    public $reductionTotalOrdinaryShares = 0;
    public $sumTotalOrdinaryShares = 0;
    public $startTotalOrdinaryAmount = 0;
    public $additionTotalOrdinaryAmount = 0;
    public $reductionTotalOrdinaryAmount = 0;
    public $sumTotalOrdinaryAmount = 0;


    public $startFullPreferenceShares = 0;
    public $additionFullPreferenceShares = 0;
    public $reductionFullPreferenceShares = 0;
    public $sumFullPreferenceShares = 0;
    public $startFullPreferenceAmount = 0;
    public $additionFullPreferenceAmount = 0;
    public $reductionFullPreferenceAmount = 0;
    public $sumFullPreferenceAmount = 0;

    public $startPartiallyPreferenceShares = 0;
    public $additionPartiallyPreferenceShares = 0;
    public $reductionPartiallyPreferenceShares = 0;
    public $sumPartiallyPreferenceShares = 0;
    public $startPartiallyPreferenceAmount = 0;
    public $additionPartiallyPreferenceAmount = 0;
    public $reductionPartiallyPreferenceAmount = 0;
    public $sumPartiallyPreferenceAmount = 0;

    public $startTotalPreferenceShares = 0;
    public $additionTotalPreferenceShares = 0;
    public $reductionTotalPreferenceShares = 0;
    public $sumTotalPreferenceShares = 0;
    public $startTotalPreferenceAmount = 0;
    public $additionTotalPreferenceAmount = 0;
    public $reductionTotalPreferenceAmount = 0;
    public $sumTotalPreferenceAmount = 0;

    public function mount($id)
    {
        $this->id = $id;
        $this->company = Company::find($id);
        if (!CompanyShareCapitalChange::where('company_id', $this->id)->count()) {
            CompanyShareCapitalChange::create([
                'company_id' => $this->id,
                'share_type' => 'Ordinary shares',
                'allotment_type' => 'Cash allotment',
                'issuance_term' => 'Cash',
                'issuance_purpose' => 'Working Capital',
                'fully_paid_shares' => 0,
                'fully_paid_amount' => 0,
                'partially_paid_shares' => 0,
                'partially_paid_amount' => 0,
                'effective_date' => '2000-01-01',
                'remarks' => '',
            ]);
            CompanyShareCapitalChange::create([
                'company_id' => $this->id,
                'share_type' => 'Preference shares',
                'allotment_type' => 'Cash allotment',
                'issuance_term' => 'Cash',
                'issuance_purpose' => 'Working Capital',
                'fully_paid_shares' => 0,
                'fully_paid_amount' => 0,
                'partially_paid_shares' => 0,
                'partially_paid_amount' => 0,
                'effective_date' => '2000-01-01',
                'remarks' => '',
            ]);
        }
        $this->ordinaryShareAtStart = $this->company->ordinaryShareCapitalAtStart();
        $this->preferenceShareAtStart = $this->company->preferenceShareCapitalAtStart();

        if ($this->ordinaryShareAtStart != null) {
            $this->ordinaryFullNoShare = displayNumber($this->ordinaryShareAtStart->fully_paid_shares);
            $this->ordinaryFullPaidAmount = displayNumber($this->ordinaryShareAtStart->fully_paid_amount);
            $this->ordinaryPartialNoShare = displayNumber($this->ordinaryShareAtStart->partially_paid_shares);
            $this->ordinaryPartialPaidAmount = displayNumber($this->ordinaryShareAtStart->partially_paid_amount);
            $this->ordinaryTotalShare = readNumber($this->ordinaryFullNoShare) + readNumber($this->ordinaryPartialNoShare);
            $this->ordinaryTotalAmount = readNumber($this->ordinaryFullPaidAmount) + readNumber($this->ordinaryPartialPaidAmount);
        }

        if ($this->preferenceShareAtStart != null) {
            $this->preferenceFullNoShare = displayNumber($this->preferenceShareAtStart->fully_paid_shares);
            $this->preferenceFullPaidAmount = displayNumber($this->preferenceShareAtStart->fully_paid_amount);
            $this->preferencePartialNoShare = displayNumber($this->preferenceShareAtStart->partially_paid_shares);
            $this->preferencePartialPaidAmount = displayNumber($this->preferenceShareAtStart->partially_paid_amount);
            $this->preferenceTotalShare = readNumber($this->preferenceFullNoShare) + readNumber($this->preferencePartialNoShare);
            $this->preferenceTotalAmount = readNumber($this->preferenceFullPaidAmount) + readNumber($this->preferencePartialPaidAmount);
        }
    }

    public function updated($name, $value)
    {
        $this->validateOnly($name);
        if ($name == 'ordinaryFullNoShare' || $name == 'ordinaryPartialNoShare') {
            $this->ordinaryTotalShare = readNumber($this->ordinaryFullNoShare) + readNumber($this->ordinaryPartialNoShare);
        }

        if ($name == 'ordinaryFullPaidAmount' || $name == 'ordinaryPartialPaidAmount') {
            $this->ordinaryTotalAmount = readNumber($this->ordinaryFullPaidAmount) + readNumber($this->ordinaryPartialPaidAmount);
        }

        if ($name == 'preferenceFullNoShare' || $name == 'preferencePartialNoShare') {
            $this->preferenceTotalShare = readNumber($this->preferenceFullNoShare) + readNumber($this->preferencePartialNoShare);
        }

        if ($name == 'preferenceFullPaidAmount' || $name == 'preferencePartialPaidAmount') {
            $this->preferenceTotalAmount = readNumber($this->preferenceFullPaidAmount) + readNumber($this->preferencePartialPaidAmount);
        }
    }

    public function render()
    {
        $this->sharecapitalChangesCurrentYear = $this->company->sharecapitalChangesCurrentYear();
        $this->calculateResult();

        return view('livewire.tenant.pages.corporate-info.share-capital');
    }

    public function deleteShare($id)
    {
        $res = CompanyShareCapitalChange::find($id);

        if ($res) {
            $res->delete();

            // session()->flash('success', 'Share Capital Deleted');
            LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Share Capital Deleted.", "showConfirmButton" => false, "timer" => 1500])->show();
        }
    }

    #[On('successCreated')]
    public function successCreated()
    {
        // session()->flash('success', 'Share Capital Created');
        LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Share Capital Created.", "showConfirmButton" => false, "timer" => 1500])->show();
    }

    #[On('successUpdated')]
    public function successUpdated()
    {
        // session()->flash('success', 'Share Capital Updated');
        LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Share Capital Updated.", "showConfirmButton" => false, "timer" => 1500])->show();
    }

    public function ordinarySave()
    {
        $this->validateOnly('ordinaryFullNoShare');
        $this->validateOnly('ordinaryFullPaidAmount');
        $this->validateOnly('ordinaryPartialNoShare');
        $this->validateOnly('ordinaryPartialPaidAmount');
        DB::beginTransaction();
        try {
            $this->ordinaryShareAtStart->update([
                'fully_paid_shares' => readNumber($this->ordinaryFullNoShare),
                'fully_paid_amount' => readNumber($this->ordinaryFullPaidAmount),
                'partially_paid_shares' => readNumber($this->ordinaryPartialNoShare),
                'partially_paid_amount' => readNumber($this->ordinaryPartialPaidAmount),
                // 'total_shares' => readNumber($this->ordinaryTotalShare),
                // 'total_amount' => readNumber($this->ordinaryTotalAmount)
            ]);

            DB::commit();
            // session()->flash('success', 'Ordinary Share saved');
            LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Ordinary Share saved.", "showConfirmButton" => false, "timer" => 1500])->show();
        } catch (Exception $e) {
            info($e->getMessage());
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }

    public function preferenceSave()
    {
        $this->validateOnly('preferenceFullNoShare');
        $this->validateOnly('preferenceFullPaidAmount');
        $this->validateOnly('preferencePartialNoShare');
        $this->validateOnly('preferencePartialPaidAmount');
        DB::beginTransaction();
        try {
            $this->preferenceShareAtStart->update([
                'fully_paid_shares' => readNumber($this->preferenceFullNoShare),
                'fully_paid_amount' => readNumber($this->preferenceFullPaidAmount),
                'partially_paid_shares' => readNumber($this->preferencePartialNoShare),
                'partially_paid_amount' => readNumber($this->preferencePartialPaidAmount),
                // 'total_shares' => readNumber($this->preferenceTotalShare),
                // 'total_amount' => readNumber($this->preferenceTotalAmount)
            ]);

            DB::commit();
            // session()->flash('success', 'Preference Share saved');
            LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Preference Share saved.", "showConfirmButton" => false, "timer" => 1500])->show();
        } catch (Exception $e) {
            info($e->getMessage());
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }

    public function calculateResult()
    {
        $this->startFullOrdinaryShares = $this->ordinaryShareAtStart->fully_paid_shares;
        $this->startPartiallyOrdinaryShares = $this->ordinaryShareAtStart->partially_paid_shares;
        $this->startTotalOrdinaryShares = $this->startFullOrdinaryShares + $this->startPartiallyOrdinaryShares;
        $this->startFullPreferenceShares = $this->preferenceShareAtStart->fully_paid_shares;
        $this->startPartiallyPreferenceShares = $this->preferenceShareAtStart->partially_paid_shares;
        $this->startTotalPreferenceShares = $this->startFullPreferenceShares + $this->startPartiallyPreferenceShares;

        $this->startFullOrdinaryAmount = $this->ordinaryShareAtStart->fully_paid_amount;
        $this->startPartiallyOrdinaryAmount = $this->ordinaryShareAtStart->partially_paid_amount;
        $this->startTotalOrdinaryAmount = $this->startFullOrdinaryAmount + $this->startPartiallyOrdinaryAmount;
        $this->startFullPreferenceAmount = $this->preferenceShareAtStart->fully_paid_amount;
        $this->startPartiallyPreferenceAmount = $this->preferenceShareAtStart->partially_paid_amount;
        $this->startTotalPreferenceAmount = $this->startFullPreferenceAmount + $this->startPartiallyPreferenceAmount;

        foreach ($this->sharecapitalChangesCurrentYear as $share)
        {
            if ($share->share_type == 'Ordinary shares')
            {
                if ($share->allotment_type != 'Capital Reduction')
                {
                    $this->additionFullOrdinaryShares += $share->fully_paid_shares;
                    $this->additionFullOrdinaryAmount += $share->fully_paid_amount;
                    $this->additionPartiallyOrdinaryShares += $share->partially_paid_shares;
                    $this->additionPartiallyOrdinaryAmount += $share->partially_paid_amount;
                }
                else
                {
                    $this->reductionFullOrdinaryShares += $share->fully_paid_shares;
                    $this->reductionFullOrdinaryAmount += $share->fully_paid_amount;
                    $this->reductionPartiallyOrdinaryShares += $share->partially_paid_shares;
                    $this->reductionPartiallyOrdinaryAmount += $share->partially_paid_amount;
                }
            }
            else if ($share->share_type == 'Preference shares')
            {
                if ($share->allotment_type != 'Capital Reduction')
                {
                    $this->additionFullPreferenceShares += $share->fully_paid_shares;
                    $this->additionFullPreferenceAmount += $share->fully_paid_amount;
                    $this->additionPartiallyPreferenceShares += $share->partially_paid_shares;
                    $this->additionPartiallyPreferenceAmount += $share->partially_paid_amount;
                }
                else
                {
                    $this->reductionFullPreferenceShares += $share->fully_paid_shares;
                    $this->reductionFullPreferenceAmount += $share->fully_paid_amount;
                    $this->reductionPartiallyPreferenceShares += $share->partially_paid_shares;
                    $this->reductionPartiallyPreferenceAmount += $share->partially_paid_amount;
                }
            }
        }
        $this->sumFullOrdinaryShares = $this->startFullOrdinaryShares +  $this->additionFullOrdinaryShares + $this->reductionFullOrdinaryShares;
        $this->sumPartiallyOrdinaryShares = $this->startPartiallyOrdinaryShares +  $this->additionPartiallyOrdinaryShares + $this->reductionPartiallyOrdinaryShares;
        $this->sumFullOrdinaryAmount = $this->startFullOrdinaryAmount +  $this->additionFullOrdinaryAmount + $this->reductionFullOrdinaryAmount;
        $this->sumPartiallyOrdinaryAmount = $this->startPartiallyOrdinaryAmount +  $this->additionPartiallyOrdinaryAmount + $this->reductionPartiallyOrdinaryAmount;
        $this->additionTotalOrdinaryShares = $this->additionFullOrdinaryShares + $this->additionPartiallyOrdinaryShares;
        $this->additionTotalOrdinaryAmount = $this->additionFullOrdinaryAmount + $this->additionPartiallyOrdinaryAmount;
        $this->sumTotalOrdinaryShares = $this->sumFullOrdinaryShares + $this->sumPartiallyOrdinaryShares;
        $this->sumTotalOrdinaryAmount = $this->sumFullOrdinaryAmount + $this->sumPartiallyOrdinaryAmount;


        $this->sumFullPreferenceShares = $this->startFullPreferenceShares +  $this->additionFullPreferenceShares + $this->reductionFullPreferenceShares;
        $this->sumPartiallyPreferenceShares = $this->startPartiallyPreferenceShares +  $this->additionPartiallyPreferenceShares + $this->reductionPartiallyPreferenceShares;
        $this->sumFullPreferenceAmount = $this->startFullPreferenceAmount +  $this->additionFullPreferenceAmount + $this->reductionFullPreferenceAmount;
        $this->sumPartiallyPreferenceAmount = $this->startPartiallyPreferenceAmount +  $this->additionPartiallyPreferenceAmount + $this->reductionPartiallyPreferenceAmount;
        $this->additionTotalPreferenceShares = $this->additionFullPreferenceShares + $this->additionPartiallyPreferenceShares;
        $this->additionTotalPreferenceAmount = $this->additionFullPreferenceAmount + $this->additionPartiallyPreferenceAmount;
        $this->sumTotalPreferenceShares = $this->sumFullPreferenceShares + $this->sumPartiallyPreferenceShares;
        $this->sumTotalPreferenceAmount = $this->sumFullPreferenceAmount + $this->sumPartiallyPreferenceAmount;
    }
}
