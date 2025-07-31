<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyReportSoceRow extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'company_report_id',
        'sort',
    ];

    public function data()
    {
        return $this->hasMany(CompanyReportSoceItem::class, 'row_id');
    }
}
