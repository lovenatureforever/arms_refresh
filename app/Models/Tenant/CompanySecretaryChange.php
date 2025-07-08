<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanySecretaryChange extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_secretary_id',
        // 'is_year_start',
        'change_nature',
        'effective_date',
        'remarks',
    ];

    protected $casts = [
        'effective_date' => 'date',
        // 'is_year_start' => 'boolean',
    ];

    public function companySecretary()
    {
        return $this->belongsTo(CompanySecretary::class);
    }

    public const CHANGE_NATURE_SECRETARY_APPOINTED = 'Secretary appointed';
    public const CHANGE_NATURE_SECRETARY_RESIGNED = 'Secretary resigned';
    public const CHANGE_NATURE_SECRETARY_DECEASED = 'Secretary deceased';
    public static $changeNatures = [
        self::CHANGE_NATURE_SECRETARY_APPOINTED,
        self::CHANGE_NATURE_SECRETARY_RESIGNED,
        self::CHANGE_NATURE_SECRETARY_DECEASED,
    ];
}
