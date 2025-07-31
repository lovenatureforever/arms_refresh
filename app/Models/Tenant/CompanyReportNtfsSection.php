<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyReportNtfsSection extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'columns',
        'company_report_item_id',
        'company_report_id',
    ];

    protected $casts = [
        'columns' => 'array',
    ];

    public function company_report(): BelongsTo
    {
        return $this->belongsTo(CompanyReport::class);
    }

    public function company_report_item(): BelongsTo
    {
        return $this->belongsTo(CompanyReportItem::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CompanyReportNtfsItem::class, 'company_report_ntfs_section_id');
    }
}
