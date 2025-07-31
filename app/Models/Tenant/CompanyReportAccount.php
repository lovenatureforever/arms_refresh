<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Central\ReportAccount;

class CompanyReportAccount extends Model
{
    use Softdeletes;

    protected $fillable = [
        'company_report_id',
        'company_report_type_id',
        'name',
        'display',
        'description',
    ];

    public function company_report(): BelongsTo
    {
        return $this->belongsTo(CompanyReport::class);
    }

    public function report_account(): BelongsTo
    {
        return $this->belongsTo(ReportAccount::class);
    }

    public function company_report_type(): BelongsTo
    {
        return $this->belongsTo(CompanyReportType::class);
    }

    public function company_report_items(): HasMany
    {
        return $this->hasMany(CompanyReportItem::class);
    }
}
