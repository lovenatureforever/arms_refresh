<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CosecOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        // 'tenant_id',
        'tenant_company_id',
        'company_name',
        'company_no',
        'company_old_no',
        'tenant_user_id',
        'user',
        'uuid',
        'form_type',
        'form_name',
        'requested_at',
        'data',
        'cost',
        'status'
    ];

    protected $casts = [
        'data' => 'json'
    ];

}
