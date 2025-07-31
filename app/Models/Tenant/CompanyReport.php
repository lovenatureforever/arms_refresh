<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyReport extends Model
{
    use SoftDeletes, HasCompany;

    protected $fillable = [
        'company_id',
        'file_path',
    ];

    public function company_report_types(): HasMany
    {
        return $this->hasMany(CompanyReportType::class);
    }

    public function company_report_items(): HasMany
    {
        return $this->hasMany(CompanyReportItem::class);
    }
}
