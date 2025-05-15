<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyMbrsAnswer extends Model
{
    use SoftDeletes, HasCompany;
    protected $fillable = [
        'company_id',
        'question',
        'answer',
    ];
}
