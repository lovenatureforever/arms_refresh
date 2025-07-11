<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyAuditorSetting extends Model
{
    use SoftDeletes, HasCompany;

    protected $fillable = [
        'company_id',
        'auditor_id',
        'audit_firm_changed',
        'prior_audit_firm',
        'prior_report_date',
        'prior_report_opinion',
        'with_breakline', // Audit license no. position:
        'audit_firm_description',
        'is_default_letterhead',
        'is_letterhead_repeat', // Letterhead on:
        'blank_header_spacing',
        'is_show_firm_name',
        'is_show_firm_title',
        'is_show_firm_license',
        'is_show_firm_address',
        'is_show_firm_contact',
        'is_show_firm_email',
        'is_show_firm_fax',
        'is_firm_address_uppercase',
        'selected_firm_address_id',
        'selected_auditor_license',
    ];

    protected $casts = [
        'prior_report_date' => 'date',
        'audit_firm_changed' => 'boolean',
        'with_breakline' => 'boolean',
        'is_default_letterhead' => 'boolean',
        'is_letterhead_repeat' => 'boolean',
        'is_show_firm_name' => 'boolean',
        'is_show_firm_title' => 'boolean',
        'is_show_firm_license' => 'boolean',
        'is_show_firm_address' => 'boolean',
        'is_show_firm_contact' => 'boolean',
        'is_show_firm_email' => 'boolean',
        'is_show_firm_fax' => 'boolean',
        'is_firm_address_uppercase' => 'boolean',
    ];
}
