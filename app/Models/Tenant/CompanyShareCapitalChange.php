<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyShareCapitalChange extends Model
{
    use SoftDeletes, HasCompany;

    protected $fillable = [
        'company_id',
        // 'is_year_start',
        'share_type',
        'allotment_type',
        'issuance_term',
        'issuance_term_freetext',
        'issuance_purpose',
        'fully_paid_shares',
        'fully_paid_amount',
        'partially_paid_shares',
        'partially_paid_amount',

        'effective_date',
        'remarks',
    ];

    protected $casts = [
        'effective_date' => 'date',
        // 'is_year_start' => 'boolean',
    ];

    public const SHARETYPE_ORDINARY = 'ordinary';
    public const SHARETYPE_PREFERENCE = 'preference';
    public static $sharetypes = [
        self::SHARETYPE_ORDINARY,
        self::SHARETYPE_PREFERENCE
    ];
    public const ALLOTMENTTYPE_CASH = 'Cash allotment';
    public const ALLOTMENTTYPE_NONCASH = 'Non-cash allotment';
    public const ALLOTMENTTYPE_BONUS = 'Bonus issue';
    public const ALLOTMENTTYPE_CAPITALREDUCTION = 'Capital reduction';
    public static $allotmenttypes = [
        self::ALLOTMENTTYPE_CASH,
        self::ALLOTMENTTYPE_NONCASH,
        self::ALLOTMENTTYPE_BONUS,
        self::ALLOTMENTTYPE_CAPITALREDUCTION
    ];

    public const ISSUANCETERM_CASH = 'Cash';
    public const ISSUANCETERM_NONCASH = 'Non-cash';
    public const ISSUANCETERM_OTHERWISE = 'Otherwise';
    public const ISSUANCETERM_FREETEXT = 'Free text';
    public static $issuanceterms = [
        self::ISSUANCETERM_CASH,
        self::ISSUANCETERM_NONCASH,
        self::ISSUANCETERM_OTHERWISE,
        self::ISSUANCETERM_FREETEXT
    ];

    public const ISSUANCEPURPOSE_WORKINGCAPITAL = 'Working Capital';
    public const ISSUANCEPURPOSE_BONUSISSUE = 'Bonus Issue';
    public const ISSUANCEPURPOSE_REDEMPTIONOFCONVERTIBLEPREFERENCE = 'Redemption of Convertible Preference Shares';
    public static $issuancepurposes = [
        self::ISSUANCEPURPOSE_WORKINGCAPITAL,
        self::ISSUANCEPURPOSE_BONUSISSUE,
        self::ISSUANCEPURPOSE_REDEMPTIONOFCONVERTIBLEPREFERENCE
    ];
}
