<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyDividendChange extends Model
{
    use SoftDeletes, HasCompany;

    protected $fillable = [
        'company_id',
        'is_declared',
        'declared_date',
        'payment_date',
        'year_end',
        'share_type',
        'dividend_type',
        'is_free_text',
        'rate_unit',
        'rate',
        'amount',
        'effective_date',
        'remarks',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'declared_date' => 'date',
        'payment_date' => 'date',
        'year_end' => 'date',
        'is_declared' => 'boolean',
        'is_free_text' => 'boolean',
    ];

    public const SHARETYPE_ORDINARY = 'Ordinary Share';
    public const SHARETYPE_PREFERENCE = 'Preference Share';
    public static $shareTypes = [
        self::SHARETYPE_ORDINARY,
        self::SHARETYPE_PREFERENCE
    ];

    public const DIVIDENDTYPE_INTERIM_SINGLE_TIER = 'Interim single-tier dividend';
    public const DIVIDENDTYPE_FINAL_SINGLE_TIER = 'Final single-tier dividend';
    public static $dividendTypes = [
        self::DIVIDENDTYPE_INTERIM_SINGLE_TIER,
        self::DIVIDENDTYPE_FINAL_SINGLE_TIER
    ];

    public const RATEUNIT_CENTS = 'cents';
    public const RATEUNIT_RM = 'RM';
    public static $rateUnits = [
        self::RATEUNIT_CENTS,
        self::RATEUNIT_RM
    ];

    public const DISCLOSURETYPE_RECOMMENDED = 'Recommended';
    public const DISCLOSURETYPE_DECLARED = 'Declared';
    public const DISCLOSURETYPE_PAID = 'Paid';
    public const DISCLOSURETYPE_NOT_MENTIONED = 'Not mentioned';
    public const DISCLOSURETYPE_MENTIONED_BUT_NOT_RECOMMENDED = 'Mentioned but not recommended';
    public static $disclosureTypes = [
        self::DISCLOSURETYPE_RECOMMENDED,
        self::DISCLOSURETYPE_DECLARED,
        self::DISCLOSURETYPE_PAID,
        self::DISCLOSURETYPE_NOT_MENTIONED,
        self::DISCLOSURETYPE_MENTIONED_BUT_NOT_RECOMMENDED
    ];
}
