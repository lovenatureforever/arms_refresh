<?php

namespace App\Livewire\Tenant\Pages\CorporateInfo;

use App\Models\Tenant\Company;
use App\Models\Tenant\CompanyDirector;
use App\Models\Tenant\CompanyReportSetting;
use App\Models\Tenant\CompanySecretary;
use Exception;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Illuminate\Support\Facades\Log;


class ReportInfo extends Component
{
    use WithPagination;

    #[Locked]
    public $id;

    #[Locked]
    public $company;

    public $reportSetting;

    public $directors;

    public $isAlternateSignings = [];
    public $selectedDirectorForStatutory;
    public $isRepresentatives = [];


    public $isDeclarationOfficer;
    public $officerName;
    public $officerIdType;
    public $officerId;

    public $isDeclarationMia;
    public $repStatutory;
    public $officerMiaNo;

    #[Validate('required')]
    public $directorReportLocation;
    public $directorReportDate;
    public $statementLocation;
    public $statementDate;
    public $statementAsReportDate = false;
    public $statutoryLocation;
    public $statutoryDate;
    public $statutoryAsReportDate = false;
    public $auditorReportLocation;
    public $auditorReportDate;
    public $auditorReportAsReportDate = false;
    public $circulationDate;


    public $declarationCountry;
    public $foreignAct;
    public $declarationCommissioner;
    public $auditorRemuneration;


    public $coverSignPosition;
    public $coverSignName;
    public $coverSignaturePosition;
    public $coverSignSecretaryNo;

    public function mount($id)
    {
        $this->id = $id;
        $this->init();
    }

    private function init() {
        $this->company = Company::find($this->id);
        $this->reportSetting = CompanyReportSetting::where('company_id', $this->id)->first();
        $this->directors = CompanyDirector::where('company_id', $this->id)->where('is_active', true)->get();
        $this->isAlternateSignings = $this->directors->pluck('is_alternate_signing', 'id')->toArray();
        $this->isRepresentatives = $this->directors->pluck('is_rep_statement', 'id')->toArray();
        $this->selectedDirectorForStatutory = $this->directors->where('is_rep_statutory', true)->first()->id ?? null;

        $this->isDeclarationOfficer = $this->reportSetting->is_declaration_officer ?? false;
        $this->officerName = $this->isDeclarationOfficer ? $this->reportSetting->officer_name ?? '' : '';
        $this->officerIdType = $this->isDeclarationOfficer ? $this->reportSetting->officer_id_type ?? '' : '';
        $this->officerId = $this->isDeclarationOfficer ? $this->reportSetting->officer_id ?? '' : '';

        $this->isDeclarationMia = $this->isDeclarationOfficer ? $this->reportSetting->is_declaration_mia ?? false : false;
        $this->repStatutory = $this->isDeclarationMia ? $this->officerName . ' (' . $this->officerId . ')' : '';
        $this->officerMiaNo = $this->isDeclarationMia ? $this->reportSetting->officer_mia_no ?? '' : '';

        $this->directorReportLocation = $this->reportSetting->director_report_location ?? '';
        $this->directorReportDate = $this->reportSetting->report_date ? $this->reportSetting->report_date->format('Y-m-d') : '';
        $this->statementLocation = $this->reportSetting->statement_location ?? '';
        $this->statementDate = $this->reportSetting->statement_date ? $this->reportSetting->statement_date->format('Y-m-d') : '';
        $this->statementAsReportDate = $this->reportSetting->statement_as_report_date ?? false;
        $this->statutoryLocation = $this->reportSetting->statutory_location ?? '';
        $this->statutoryDate = $this->reportSetting->statutory_date ? $this->reportSetting->statutory_date->format('Y-m-d') : '';
        $this->statutoryAsReportDate = $this->reportSetting->statutory_as_report_date ?? false;
        $this->auditorReportLocation = $this->reportSetting->auditor_report_location ?? '';
        $this->auditorReportDate = $this->reportSetting->auditor_report_date ? $this->reportSetting->auditor_report_date->format('Y-m-d') : '';
        $this->auditorReportAsReportDate = $this->reportSetting->auditor_report_as_report_date ?? false;
        $this->circulationDate = $this->reportSetting->circulation_date ? $this->reportSetting->circulation_date->format('Y-m-d') : '';

        $this->declarationCountry = $this->reportSetting->declaration_country;
        $this->foreignAct = $this->reportSetting->foreign_act ?? '';
        $this->declarationCommissioner = $this->reportSetting->declaration_commissioner ?? '';
        $this->auditorRemuneration = $this->reportSetting->auditor_remuneration ?? '';

        $this->coverSignPosition = $this->reportSetting->cover_sign_position ?? '';
        $this->coverSignName = $this->reportSetting->cover_sign_name ?? '';
        $this->coverSignaturePosition = $this->reportSetting->cover_signature_position ?? '';
        $this->coverSignSecretaryNo = $this->reportSetting->cover_sign_secretary_no ?? '';
    }

    public function render()
    {
        return view('livewire.tenant.pages.corporate-info.report-info');
    }

    public function save()
    {
        try {
            $this->validate([
                'officerName' => 'required_if:isDeclarationOfficer,true',
                'officerIdType' => 'required_if:isDeclarationOfficer,true',
                'officerId' => 'required_if:isDeclarationOfficer,true',
                'officerMiaNo' => 'required_if:isDeclarationMia,true',
                'directorReportLocation' => 'required',
                'directorReportDate' => 'required|date',
                'statementLocation' => 'required',
                'statementDate' => 'required|date',
                'statutoryLocation' => 'required',
                'statutoryDate' => 'required|date',
                'auditorReportLocation' => 'required',
                'auditorReportDate' => 'required|date',
            ]);
            /* $reportSetting = $this->reportSetting->update([
                'company_id' => $this->id,
                'officer_name' => $this->officerName,
                'officer_id_type' => $this->officerIdType,
                'officer_id' => $this->officerId,
                'officer_mia_no' => $this->officerMiaNo,
                'is_declaration_officer' => true, // Assuming this is always true for now
                'is_declaration_mia' => false, // Assuming this is always false for now

            ]); */
            LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Report Setting Updated successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
        } catch (Exception $e) {
            info($e->getMessage());
            LivewireAlert::withOptions(["position" => "top-end", "icon" => "error", "title" => $e->getMessage(), "showConfirmButton" => false, "timer" => 1500])->show();
        }
    }

    public function updatedIsDeclarationOfficer($value)
    {
        if (!$value) {
            $this->officerName = '';
            $this->officerIdType = '';
            $this->officerId = '';
            $this->isDeclarationMia = false;
            $this->repStatutory = '';
            $this->officerMiaNo = '';
        } else {
            $this->isDeclarationMia = true; // Default to true when officer declaration is enabled
            $this->repStatutory = $this->officerName . ' (' . $this->officerId . ')';
        }
    }
}
