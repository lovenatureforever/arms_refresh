<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyShareholderChange extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_shareholder_id',
        // 'is_year_start',
        'change_nature',
        'share_type',
        'shares',
        'effective_date',
        'remarks',
    ];

    protected $casts = [
        'effective_date' => 'date',
        // 'is_year_start' => 'boolean',
    ];

    public function companyShareholder()
    {
        return $this->belongsTo(CompanyShareholder::class);
    }

    public const CHANGE_NATURE_ALLOT = 'Allot';
    public const CHANGE_NATURE_TRANSFER_IN = 'Transfer in';
    public const CHANGE_NATURE_TRANSFER_OUT = 'Transfer out';
    public const CHANGE_NATURE_REDUCTION = 'Reduction';
    public static $changeNatures = [
        self::CHANGE_NATURE_ALLOT,
        self::CHANGE_NATURE_TRANSFER_IN,
        self::CHANGE_NATURE_TRANSFER_OUT,
        self::CHANGE_NATURE_REDUCTION,
    ];

    public const SHARETYPE_ORDINARY = 'Ordinary shares';
    public const SHARETYPE_PREFERENCE = 'Preference shares';
    public static $sharetypes = [
        self::SHARETYPE_ORDINARY,
        self::SHARETYPE_PREFERENCE
    ];
}
