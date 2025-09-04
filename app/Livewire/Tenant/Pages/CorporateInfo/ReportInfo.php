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
    public $activeDirectors;
    public $secretaries;

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

    // public $selectedDirectorId;
    // public $selectedSecretaryId;

    public function mount($id)
    {
        $this->id = $id;
        $this->init();
    }

    private function init() {
        $this->company = Company::find($this->id);
        $this->reportSetting = CompanyReportSetting::where('company_id', $this->id)->first();
        $alternate_ids = CompanyDirector::where('alternate_id', '!=', null)->where('is_active', true)->pluck('alternate_id')->toArray();
        $this->directors = CompanyDirector::where('company_id', $this->id)->where('is_active', true)->whereNotIn('id', $alternate_ids)->get();
        $date = $this->company->end_date_report;
        $this->activeDirectors = CompanyDirector::where('company_id', $this->id)
            ->whereHas('changes', function($query) use ($date) {
                $query->where('effective_date', '<=', $date)
                    ->latest('effective_date');
            })
            ->orderBy('sort')
            ->get();

        $this->isAlternateSignings = $this->directors->pluck('is_alternate_signing', 'id')->toArray();
        $this->isRepresentatives = $this->directors->pluck('is_rep_statement', 'id')->toArray();
        $this->selectedDirectorForStatutory = $this->directors->where('is_rep_statutory', true)->first()->id ?? null;

        $this->secretaries = CompanySecretary::where('company_id', $this->id)->where('is_active', true)->get();

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
        $this->auditorRemuneration = displayNumber($this->reportSetting->auditor_remuneration);

        $this->coverSignPosition = $this->reportSetting->cover_sign_position ?? '';
        $this->coverSignName = $this->reportSetting->cover_sign_name ?? '';
        $this->coverSignaturePosition = $this->reportSetting->cover_signature_position ?? '';
        $this->coverSignSecretaryNo = $this->reportSetting->cover_sign_secretary_no ?? '';
        // $this->selectedDirectorId = $this->reportSetting->selected_director_id ?? null;
        // $this->selectedSecretaryId = $this->reportSetting->selected_secretary_id ?? null;
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
                'circulationDate' => 'required|date',
                'declarationCountry' => 'required',
                'foreignAct' => 'required_unless:declarationCountry,Malaysia',
                'declarationCommissioner' => 'required',
                'auditorRemuneration' => 'nullable',
                'coverSignPosition' => 'required',
                'coverSignName' => 'required',
                'coverSignaturePosition' => 'required',
            ]);

            foreach ($this->directors as $director) {
                $director->update([
                    'is_rep_statutory' => $director->id == $this->selectedDirectorForStatutory,
                    'is_rep_statement' => $this->isRepresentatives[$director->id] == "1",
                    'is_alternate_signing' => $this->isAlternateSignings[$director->id] == "1",
                ]);
            }

            $this->reportSetting->update([
                'officer_name' => $this->officerName,
                'officer_id_type' => $this->officerIdType,
                'officer_id' => $this->officerId,
                'officer_mia_no' => $this->officerMiaNo,
                'director_report_location' => $this->directorReportLocation,
                'report_date' => $this->directorReportDate,
                'statement_location' => $this->statementLocation,
                'statement_date' => $this->statementDate,
                'statement_as_report_date' => $this->statementAsReportDate,
                'statutory_location' => $this->statutoryLocation,
                'statutory_date' => $this->statutoryDate,
                'statutory_as_report_date' => $this->statutoryAsReportDate,
                'auditor_report_location' => $this->auditorReportLocation,
                'auditor_report_date' => $this->auditorReportDate,
                'auditor_report_as_report_date' => $this->auditorReportAsReportDate,
                'circulation_date' => $this->circulationDate,
                'declaration_country' => $this->declarationCountry,
                'foreign_act' => $this->foreignAct,
                'declaration_commissioner' => $this->declarationCommissioner,
                'auditor_remuneration' => readNumber($this->auditorRemuneration),
                'is_declaration_officer' => $this->isDeclarationOfficer,
                'is_declaration_mia' => $this->isDeclarationMia,
                // 'selected_director_id' => $this->selectedDirectorId,
                // 'selected_secretary_id' => $this->selectedSecretaryId,
                'cover_sign_position' => $this->coverSignPosition,
                'cover_sign_name' => $this->coverSignName,
                'cover_signature_position' => $this->coverSignaturePosition,
                'cover_sign_secretary_no' => $this->coverSignSecretaryNo
            ]);
            LivewireAlert::withOptions(["position" => "top-end", "icon" => "success", "title" => "Company Report Setting Updated successfully.", "showConfirmButton" => false, "timer" => 1500])->show();
        } catch (Exception $e) {
            info($e->getMessage());
            LivewireAlert::withOptions(["position" => "top-end", "icon" => "error", "title" => $e->getMessage(), "showConfirmButton" => false, "timer" => 1500])->show();
        }
    }

    public function resetForm()
    {
        $this->init();
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
            $this->selectedDirectorForStatutory = $this->directors->where('is_rep_statutory', true)->first()->id ?? null;
        } else {
            $this->selectedDirectorForStatutory = null;
            $this->isDeclarationMia = true;
            $this->repStatutory = $this->officerName ? $this->officerName . ' (' . $this->officerId . ')' : '';
        }
    }

    public function updatedDeclarationCountry($value)
    {
        if ($value === 'Malaysia') {
            $this->foreignAct = '';
            $this->declarationCommissioner = 'Commissioner for Oaths';
        } else {
            $this->foreignAct = $this->reportSetting->foreign_act ?? '';
            $this->declarationCommissioner = $this->reportSetting->declaration_commissioner ?? '';
        }
    }

    public function updatedDirectorReportDate($value)
    {
        if ($this->statementAsReportDate) {
            $this->statementDate = $value;
        }
        if ($this->statutoryAsReportDate) {
            $this->statutoryDate = $value;
        }
        if ($this->auditorReportAsReportDate) {
            $this->auditorReportDate = $value;
        }

    }

    public function updatedStatementAsReportDate($value)
    {
        if ($value) {
            $this->statementDate = $this->directorReportDate;
        }
    }

    public function updatedStatutoryAsReportDate($value)
    {
        if ($value) {
            $this->statutoryDate = $this->directorReportDate;
        }
    }

    public function updatedAuditorReportAsReportDate($value)
    {
        if ($value) {
            $this->auditorReportDate = $this->directorReportDate;
        }
    }

    public function updatedCoverSignPosition($value)
    {
        $this->coverSignName = '';
        $this->coverSignSecretaryNo = '';
        $this->coverSignaturePosition = '';
    }

    public function updatedCoverSignName($value)
    {
        if ($this->coverSignPosition === 'Secretary') {
            $sec = $this->secretaries->where('id', $value)->first();
            $this->coverSignSecretaryNo = $this->reportSetting->cover_sign_secretary_no ?? '';
            $this->coverSignSecretaryNo = ($this->coverSignSecretaryNo == '' && $sec) ? $sec->secretary_no : '';
            $this->coverSignaturePosition = 'Company Secretary';
        } elseif ($this->coverSignPosition === 'Director') {
            $this->coverSignSecretaryNo = '';
            $this->coverSignaturePosition = 'Company Director';
        }
        if ($value === '') {
            $this->coverSignSecretaryNo = '';
            $this->coverSignaturePosition = '';
        }
    }
}
