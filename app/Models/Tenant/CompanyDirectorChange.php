<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyDirectorChange extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_director_id',
        // 'is_year_start',
        'change_nature',
        'id_type',
        'id_no',
        'country',
        'address_line1',
        'address_line2',
        'address_line3',
        'postcode',
        'town',
        'state',
        'effective_date',
        'remarks',
    ];

    protected $casts = [
        'effective_date' => 'date',
        // 'is_year_start' => 'boolean',
    ];

    public function companyDirector()
    {
        return $this->belongsTo(CompanyDirector::class);
    }

    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address_line1,
            $this->address_line2,
            $this->address_line3,
            trim(implode(' ', array_filter([$this->postcode, $this->town]))),
            $this->state,
        ]);

        return implode(", ", $parts);
    }

    public const CHANGE_NATURE_DIRECTOR_APPOINTED = 'Director appointed';
    public const CHANGE_NATURE_DIRECTOR_RESIGNED = 'Director resigned';
    public const CHANGE_NATURE_DIRECTOR_DECEASED = 'Director deceased';
    public const CHANGE_NATURE_DIRECTOR_RETIRED = 'Director retired';
    public const CHANGE_NATURE_CHANGED_OF_ID = 'Changed of ID';
    public const CHANGE_NATURE_CHANGED_OF_ADDRESS = 'Changed of director address';
    public static $changeNatures = [
        self::CHANGE_NATURE_DIRECTOR_APPOINTED,
        self::CHANGE_NATURE_DIRECTOR_RESIGNED,
        self::CHANGE_NATURE_DIRECTOR_DECEASED,
        self::CHANGE_NATURE_DIRECTOR_RETIRED,
        self::CHANGE_NATURE_CHANGED_OF_ID,
        self::CHANGE_NATURE_CHANGED_OF_ADDRESS,
    ];
}
