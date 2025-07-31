<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

class ReportAccount extends Model
{
    use SoftDeletes, CentralConnection;

    public function report_type(): BelongsTo
    {
        return $this->belongsTo(ReportType::class);
    }

    public function report_groups(): HasMany
    {
        return $this->hasMany(ReportGroup::class);
    }
}
