<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyShareholder extends Model
{
    use SoftDeletes, HasCompany;

    protected $fillable = [
        'company_id',
        // 'is_year_start',
        'company_director_id',
        'name',
        'type',
        'id_type',
        'id_no',
        'is_active',
        // 'effective_date',
    ];

    protected $casts = [
        // 'effective_date' => 'date',
        // 'is_year_start' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get company director.
     */
    public function director()
    {
        return $this->belongsTo(CompanyDirector::class);
    }

    /**
     * Get shareholder's changes.
     */
    public function changes()
    {
        return $this->hasMany(CompanyShareholderChange::class);
    }

    public const ID_TYPE_MYKAD = 'Mykad';
    public const ID_TYPE_PASSPORT = 'Passport';
    public const ID_TYPE_COMPANY_ID = 'Company ID';
    public static $idTypes = [
        self::ID_TYPE_MYKAD,
        self::ID_TYPE_PASSPORT,
        self::ID_TYPE_COMPANY_ID,
    ];

    public const TYPE_INDIVIDUAL = 'Individual';
    public const TYPE_COMPANY = 'Company';
    public static $types = [
        self::TYPE_INDIVIDUAL,
        self::TYPE_COMPANY,
    ];
}
