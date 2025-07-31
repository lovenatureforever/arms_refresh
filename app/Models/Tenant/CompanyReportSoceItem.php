<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyReportSoceItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'row_id',
        'col_id',
        'company_report_id',
        'value'
    ];

    public function row()
    {
        return $this->belongsTo(CompanyReportSoceRow::class, 'row_id');
    }

    public function column()
    {
        return $this->belongsTo(CompanyReportSoceCol::class, 'col_id');
    }
}
