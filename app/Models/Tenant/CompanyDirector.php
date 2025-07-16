<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyDirector extends Model
{
    use SoftDeletes, HasCompany;

    protected $fillable = [
        'company_id',
        // 'is_year_start',
        'name',
        'designation',
        'alternate_id',
        'id_type',
        'id_no',
        'gender',
        // 'set_to_report',
        'is_alternate_signing',
        'is_rep_statutory',
        'is_rep_statement',
        // 'is_cover_page',
        // 'cover_page_title',
        'sort',
        'is_active',
    ];

    protected $casts = [
        // 'effective_date' => 'date',
        // 'is_year_start' => 'boolean',
        // 'set_to_report' => 'boolean',
        'is_alternate_signing' => 'boolean',
        'is_rep_statutory' => 'boolean',
        'is_rep_statement' => 'boolean',
        // 'is_cover_page' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get alternate director.
     */
    public function alternate()
    {
        return $this->belongsTo(CompanyDirector::class, 'alternate_id');
    }

    /**
     * Get director changes.
     */
    public function changes()
    {
        return $this->hasMany(CompanyDirectorChange::class);
    }

    public function isInactive()
    {
        $inactiveStatuses = [
            CompanyDirectorChange::CHANGE_NATURE_DIRECTOR_RETIRED,
            CompanyDirectorChange::CHANGE_NATURE_DIRECTOR_RESIGNED,
            CompanyDirectorChange::CHANGE_NATURE_DIRECTOR_DECEASED,
        ];

        $latestChange = $this->changes()
                            ->where('effective_date', '<=', $this->company->end_date_report)
                            ->latest('effective_date')
                            ->first();

        return $latestChange && in_array($latestChange->change_nature, $inactiveStatuses);
    }

    public const DESIGNATION_DIRECTOR = 'Director';
    public const DESIGNATION_ALTERNATEDIRECTOR = 'Alternate Director';
    public const DESIGNATION_MANAGINGDIRECTOR = 'Managing Director';
    public static $designations = [
        self::DESIGNATION_DIRECTOR,
        self::DESIGNATION_ALTERNATEDIRECTOR,
        self::DESIGNATION_MANAGINGDIRECTOR,
    ];

    public const ID_TYPE_MYKAD = 'Mykad';
    public const ID_TYPE_PASSPORT = 'Passport';
    public static $idTypes = [
        self::ID_TYPE_MYKAD,
        self::ID_TYPE_PASSPORT,
    ];
}
