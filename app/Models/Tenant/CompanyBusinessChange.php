<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyBusinessChange extends Model
{
    use SoftDeletes, HasCompany;

    protected $fillable = [
        'company_id',
        // 'is_year_start',
        'paragraph1',
        'paragraph2',
        'effective_date',
        'remarks',
    ];

    protected $casts = [
        'effective_date' => 'date',
        // 'is_year_start' => 'boolean',
    ];
}
