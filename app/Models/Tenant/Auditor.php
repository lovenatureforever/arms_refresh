<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Auditor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'selected_license_id',
        'is_active',
        'title',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function licenses()
    {
        return $this->hasMany(AuditorLicense::class);
    }

    public function selectedLicense()
    {
        return $this->belongsTo(AuditorLicense::class, 'selected_license_id');
    }
}
