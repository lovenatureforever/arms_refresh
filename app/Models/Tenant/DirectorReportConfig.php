<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DirectorReportConfig extends Model
{
    use SoftDeletes, HasCompany;

    protected $fillable = [
        'company_id',
        'report_content',
        'position',
        'template_type',
        'display',
        'page_break',
        'remarks',
        'is_deleteable',
        'order_no'
    ];
}
