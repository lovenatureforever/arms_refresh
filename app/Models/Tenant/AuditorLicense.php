<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditorLicense extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'auditor_id',
        'license_no',
        'license_type',
        'effective_date',
        'expiry_date',

    ];

    protected $casts = [
        'effective_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function auditor()
    {
        return $this->belongsTo(Auditor::class);
    }
}
