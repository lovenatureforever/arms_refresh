<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

class ReportGroup extends Model
{
    use SoftDeletes, CentralConnection;

    public function report_account(): BelongsTo
    {
        return $this->belongsTo(ReportAccount::class);
    }
}
