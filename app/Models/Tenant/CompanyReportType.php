<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyReportType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_report_id',
        'name',
        'full_name',
    ];

    public function company_report(): BelongsTo
    {
        return $this->belongsTo(CompanyReport::class);
    }

    public function company_report_accounts(): HasMany
    {
        return $this->hasMany(CompanyReportAccount::class);
    }

    public function company_report_items(): HasMany
    {
        return $this->hasMany(CompanyReportItem::class);
    }
}
