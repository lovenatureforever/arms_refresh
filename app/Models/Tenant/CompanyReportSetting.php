<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyReportSetting extends Model
{
    use SoftDeletes, HasCompany;
    protected $fillable = [
        'company_id',
        'officer_name',
        'officer_id_type',
        'officer_id',
        'officer_mia_no',
        'cover_sign_position',
        'cover_sign_name',
        'cover_signature_position',
        'cover_sign_secretary_no',
        'report_date',
        'director_report_location',
        'statement_date',
        'statement_location',
        'statement_as_report_date',
        'statutory_date',
        'statutory_location',
        'statutory_as_report_date',
        'auditor_report_date',
        'auditor_report_location',
        'auditor_report_as_report_date',
        'circulation_date',
        'declaration_country',
        'foreign_act',
        'declaration_commissioner',
        'auditor_remuneration',
        'is_declaration_officer',
        'is_declaration_mia',
        'selected_director',
        'selected_secretary',
        'is_approved_application',
        'is_exempt',
    ];

    protected $casts = [
        'report_date' => 'date',
        'statement_date' => 'date',
        'statutory_date' => 'date',
        'auditor_report_date' => 'date',
        'circulation_date' => 'date',
        'is_declaration_officer' => 'boolean',
        'is_declaration_mia' => 'boolean',
        'is_approved_application' => 'boolean',
        'is_exempt' => 'boolean',
        'statement_as_report_date' => 'boolean',
        'statutory_as_report_date' => 'boolean',
        'auditor_report_as_report_date' => 'boolean',
    ];
}
