<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyReportNtfsItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'col_1',
        'col_2',
        'col_3',
        'col_4',
        'company_report_ntfs_section_id',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(CompanyReportNtfsSection::class);
    }
}
