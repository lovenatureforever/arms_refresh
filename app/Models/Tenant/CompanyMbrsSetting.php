<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyMbrsSetting extends Model
{
    use SoftDeletes, HasCompany;

    protected $fillable = [
        'company_id',
        'dividend_disclosure_type',
        'biz_status',
        'biz_1',
        'biz_1_code',
        'biz_2',
        'biz_2_code',
        'biz_3',
        'biz_3_code',
    ];
}
