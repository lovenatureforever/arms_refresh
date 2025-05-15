<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditFirmSetting extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'firm_description',
        'selected_firm_address',
        'with_breakline',
        'audit_firm_description',
        'is_default_letterhead',
        'is_custom_letterhead',
        'is_letterhead_repeat',
        'blank_header_spacing',
        'is_show_firm_name',
        'is_show_firm_title',
        'is_show_firm_license',
        'is_show_firm_address',
        'is_show_firm_contact',
        'is_show_firm_fax',
        'is_show_firm_email',
        'is_firm_address_uppercase',
    ];

    protected $casts = [
        'with_breakline' => 'boolean',
        'is_default_letterhead' => 'boolean',
        'is_custom_letterhead' => 'boolean',
        'is_letterhead_repeat' => 'boolean',
        'is_show_firm_name' => 'boolean',
        'is_show_firm_title' => 'boolean',
        'is_show_firm_license' => 'boolean',
        'is_show_firm_address' => 'boolean',
        'is_show_firm_contact' => 'boolean',
        'is_show_firm_fax' => 'boolean',
        'is_show_firm_email' => 'boolean',
        'is_firm_address_uppercase' => 'boolean',
    ];

}
