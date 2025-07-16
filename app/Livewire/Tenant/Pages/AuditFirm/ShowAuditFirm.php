<?php

namespace App\Livewire\Tenant\Pages\AuditFirm;

use Exception;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Illuminate\Support\Facades\Log;

use App\Models\Central\Tenant;
use App\Models\Tenant\Auditor;
use App\Models\Tenant\AuditFirmAddress;

class ShowAuditFirm extends Component
{
    use WithPagination;

    public $isLetterheadRepeat;
    public $isDefaultLetterhead;
    public $isFirmAddressUppercase;
    public $blankHeaderSpacing;
    public $withBreakline;
    public $auditFirmDescription;

    public $isShowFirmName;
    public $isShowFirmTitle;
    public $isShowFirmNo;
    public $isShowFirmAddress;
    public $isShowFirmContact;
    public $isShowFirmFax;
    public $isShowFirmEmail;

    #[Validate('required|min:3')]
    public $firmName;

    #[Validate('required|min:3')]
    public $firmTitle;

    #[Validate('required|min:3')]
    public $firmNo;

    #[Validate('required|min:10')]
    public $contactNo;

    public $faxNo;

    #[Validate('required|email')]
    public $email;

    public $firmAddress;

    public $selectedAddressId;

    public function mount()
    {
        $this->isLetterheadRepeat = tenant()->isLetterheadRepeat ? '1' : '0';
        $this->isDefaultLetterhead = tenant()->isDefaultLetterhead;
        $this->isFirmAddressUppercase = tenant()->isFirmAddressUppercase;
        $this->blankHeaderSpacing = tenant()->blankHeaderSpacing;
        $this->withBreakline = tenant()->withBreakline ? '1' : '0';
        $this->auditFirmDescription = tenant()->auditFirmDescription;
        $this->isShowFirmName = tenant()->isShowFirmName;
        $this->isShowFirmTitle = tenant()->isShowFirmTitle;
        $this->isShowFirmAddress = tenant()->isShowFirmAddress;
        $this->isShowFirmNo = tenant()->isShowFirmNo;
        $this->isShowFirmContact = tenant()->isShowFirmContact;
        $this->isShowFirmFax = tenant()->isShowFirmFax;
        $this->isShowFirmEmail = tenant()->isShowFirmEmail;

        $this->selectedAddressId = tenant()->selectedAddressId;

        $this->firmName = tenant()->firmName;
        $this->firmNo = tenant()->firmNo;
        $this->firmTitle = tenant()->firmTitle;
        $this->contactNo = tenant()->firmContact;
        $this->faxNo = tenant()->firmFax;
        $this->email = tenant()->firmEmail;
    }

    public function render()
    {
        $addresses = AuditFirmAddress::paginate(10);
        $address = AuditFirmAddress::find(tenant()->selectedAddressId);
        $this->firmAddress = implode(', ', array_filter([$address->address_line1, $address->address_line2, $address->address_line3, $address->postcode, $address->town, $address->state]));

        return view('livewire.tenant.pages.audit-firm.show-audit-firm', [
            'addresses' => $addresses
        ]);
    }

    public function updatedIsDefaultLetterhead($value)
    {
        if (!$value) {
            $this->isFirmAddressUppercase = false;
        }
    }

    public function save()
    {
        DB::beginTransaction();
        try {

            $this->validate();

            $tenant = Tenant::find(tenant()->id);

            info($tenant);

            $tenant->update([
                'firmName' => $this->firmName,
                'firmTitle' => $this->firmTitle,
                'firmNo' => $this->firmNo,
                'firmEmail' => $this->email,
                'firmContact' => $this->contactNo,
                'firmFax' => $this->faxNo,

                'isLetterheadRepeat' => $this->isLetterheadRepeat == '1',
                'isDefaultLetterhead' => $this->isDefaultLetterhead,
                'isFirmAddressUppercase' => $this->isFirmAddressUppercase,
                'blankHeaderSpacing' => $this->blankHeaderSpacing,
                'withBreakline' => $this->withBreakline == '1',
                'auditFirmDescription' => $this->auditFirmDescription,
                'isShowFirmName' => $this->isShowFirmName,
                'isShowFirmTitle' => $this->isShowFirmTitle,
                'isShowFirmNo' => $this->isShowFirmNo,
                'isShowFirmAddress' => $this->isShowFirmAddress,
                'isShowFirmContact' => $this->isShowFirmContact,
                'isShowFirmFax' => $this->isShowFirmFax,
                'isShowFirmEmail' => $this->isShowFirmEmail,
                'selectedAddressId' => $this->selectedAddressId,
            ]);

            $tenant->save();

            DB::commit();
            LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Audit Firm updated successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
        } catch (Exception $e) {
            info($e->getMessage());
            session()->flash('error', $e->getMessage());
            DB::rollBack();
        }
    }

    public function deleteAddress($id)
    {
        DB::beginTransaction();
        try {
            if (tenant()->selectedAddressId == $id) {
                // session()->flash('error', 'You cannot delete the selected address.');
                LivewireAlert::withOptions(["position" => "top-end", "icon" => "error", "title" => "You cannot delete the selected address.", "showConfirmButton" => false, "timer" => 1500])->show();
                return;
            }
            $address = AuditFirmAddress::find($id);
            $address->delete();

            DB::commit();

            LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Address deleted successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
        } catch (Exception $e) {
            info($e->getMessage());
            session()->flash('error', $e->getMessage());
            DB::rollBack();
        }
    }

    #[On('successCreated')]
    public function successCreated()
    {
        LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Address created successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
    }

    #[On('successUpdated')]
    public function successUpdated()
    {
        LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Address updated successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
    }
}
