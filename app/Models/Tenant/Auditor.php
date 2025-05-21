<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Auditor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        // 'selected_license_id',
        'title',
    ];

    public function licenses()
    {
        return $this->hasMany(AuditorLicense::class);
    }

    public function getSelectedLicenseAttribute()
    {
        return $this->licenses()->latest('effective_date')->first();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
