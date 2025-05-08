<?php

namespace App\Models\Tenant;

use Illuminate\Support\Str;
use BadMethodCallException;
use Carbon\Carbon;

trait HasCompany
{
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeEffectiveBefore($query, $endDate)
    {
        return $query->where('effective_date', '<=', $endDate);
    }
}
