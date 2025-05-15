<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanySecretary extends Model
{
    use SoftDeletes, HasCompany;

    protected $fillable = [
        'company_id',
        // 'is_year_start',
        'name',
        'id_type',
        'id_no',
        'secretary_no',
        'is_active',
    ];

    protected $casts = [
        // 'is_year_start' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get secretary's changes.
     */
    public function changes()
    {
        return $this->hasMany(CompanySecretaryChange::class);
    }

    public const ID_TYPE_MYKAD = 'Mykad';
    public const ID_TYPE_PASSPORT = 'Passport';
    public static $idTypes = [
        self::ID_TYPE_MYKAD,
        self::ID_TYPE_PASSPORT,
    ];
}
