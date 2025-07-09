<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyChargeChange extends Model
{
    use SoftDeletes, HasCompany;

    protected $fillable = [
        'company_id',
        // 'is_year_start',
        'change_nature',
        'creating_charge_id',
        'registered_number',
        'registration_date',
        'discharge_date',
        'charge_nature',
        'chargee_name',
        'indebtedness_amount',
        'effective_date',
        'remarks',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'registration_date' => 'date',
        'discharge_date' => 'date',
        // 'is_year_start' => 'boolean',
    ];

    /**
     * Get creating charge.
     */
    public function creatingCharge()
    {
        return $this->belongsTo(CompanyChargeChange::class, 'creating_charge_id');
    }

    /**
     * Get charge changes.
     */
    public function changes()
    {
        return $this->hasMany(CompanyChargeChange::class, 'creating_charge_id');
    }

    public const CHANGE_NATURE_CREATE = 'Create';
    public const CHANGE_NATURE_DISCHARGE = 'Discharge';
    public const CHANGE_NATURES = [
        self::CHANGE_NATURE_CREATE => 'Create',
        self::CHANGE_NATURE_DISCHARGE => 'Discharge',
    ];
}
