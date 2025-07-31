<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

class ReportType extends Model
{
    use SoftDeletes, CentralConnection;

    public function report_accounts(): HasMany
    {
        return $this->hasMany(ReportAccount::class);
    }
}
