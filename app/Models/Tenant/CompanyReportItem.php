<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Central\ReportAccount;
use App\Models\Central\ReportGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyReportItem extends Model
{
    use SoftDeletes;

    public const TYPE_VALUE = 'value';
    public const TYPE_TOTAL = 'total';
    public const TYPE_GRANDTOTAL = 'grandtotal';
    public const TYPE_LABEL = 'label';
    public const TYPE_GROUP = 'group';

    protected $fillable = [
        'company_report_id',
        'company_report_type_id',
        'report_account_id',
        'report_group_id',
        'company_report_account_id',
        'item',
        'display',
        'this_year_amount',
        'last_year_amount',
        'is_report',
        'type',
        'sort',
        'show_display'
    ];

    protected $casts = [
        'is_report' => 'boolean',
        'show_display' => 'boolean',
    ];

    public function company_report(): BelongsTo
    {
        return $this->belongsTo(CompanyReport::class);
    }

    public function company_report_type(): BelongsTo
    {
        return $this->belongsTo(CompanyReportType::class);
    }

    public function company_report_account(): BelongsTo
    {
        return $this->belongsTo(CompanyReportAccount::class);
    }

    public function report_account(): BelongsTo
    {
        return $this->belongsTo(ReportAccount::class, 'report_account_id');
    }

    public function report_group(): BelongsTo
    {
        return $this->belongsTo(ReportGroup::class, 'report_group_id');
    }
}
