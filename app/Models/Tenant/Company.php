<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Company extends Model
{
    use HasFactory, SoftDeletes, HasChangeRecords;

    protected $fillable = [
        'registration_no',
        'registration_no_old',
        'current_is_first_year',
        'current_year_type',
        'current_report_header_format',
        'current_year',
        'current_year_from',
        'current_year_to',
        'last_is_first_year',
        'last_year_type',
        'last_report_header_format',
        'last_year',
        'last_year_from',
        'last_year_to',
        'audit_fee',
        'no_business_address',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'current_is_first_year' => 'boolean',
        'last_is_first_year' => 'boolean',
        'no_business_address' => 'boolean',
        'current_year_from' => 'date',
        'current_year_to' => 'date',
        'last_year_from' => 'date',
        'last_year_to' => 'date',
    ];

    /**
     * The company detail changes.
     */

    public function detailChanges()
    {
        return $this->hasMany(CompanyDetailChange::class);
    }

    public function getEndDateReportAttribute()
    {
        $date = $this->current_year_to;
        if ($this->reportSetting && $this->reportSetting->report_date) {
            $date = $this->reportSetting->report_date;
        }
        return $date;
    }

    /**
     * The company business changes.
     */
    public function businessChanges()
    {
        return $this->hasMany(CompanyBusinessChange::class);
    }

    /**
     * The company address changes.
     */
    public function addressChanges()
    {
        return $this->hasMany(CompanyAddressChange::class);
    }

    /**
     * The company business address changes.
     */
    public function bizAddressChanges()
    {
        return $this->hasMany(CompanyBizAddressChange::class);
    }

    /**
     * The company share capital changes.
     */
    public function sharecapitalChanges()
    {
        return $this->hasMany(CompanyShareCapitalChange::class);
    }

    /**
     * The company directors
     */
    public function directors()
    {
        return $this->hasMany(CompanyDirector::class);
    }

    /**
     * The company director changes.
     */
    public function directorChanges()
    {
        return $this->hasManyThrough(CompanyDirectorChange::class, CompanyDirector::class);
    }

    /**
     * The company shareholders.
     */
    public function shareholders()
    {
        return $this->hasMany(CompanyShareholder::class);
    }

    /**
     * The company shareholder changes.
     */
    public function shareholderChanges()
    {
        return $this->hasManyThrough(CompanyShareholderChange::class, CompanyShareholder::class);
    }

    /**
     * The company secretaries.
     */
    public function secretaries()
    {
        return $this->hasMany(CompanySecretary::class);
    }
    /**
     * The company secretary changes.
     */
    public function secretaryChanges()
    {
        return $this->hasManyThrough(CompanySecretaryChange::class, CompanySecretary::class);
    }

    /**
     * The company charge changes.
     */
    public function chargeChanges()
    {
        return $this->hasMany(CompanyChargeChange::class);
    }

    /**
     * The company dividend changes.
     */
    public function dividendChanges()
    {
        return $this->hasMany(CompanyDividendChange::class);
    }

    /**
     * The company report settings.
     */
    public function reportSetting()
    {
        return $this->hasOne(CompanyReportSetting::class);
    }

    public function auditorSetting()
    {
        return $this->hasOne(CompanyAuditorSetting::class);
    }

    public function getNameAttribute()
    {
        return $this->detailAtLast()->name ?? null;
    }

    public function ordinaryShareCapitalAtStart()
    {
        return $this->sharecapitalChanges()->where('effective_date', '<=', $this->current_year_from)->where('share_type', 'Ordinary shares')->latest('effective_date')->first();
    }

    public function preferenceShareCapitalAtStart()
    {
        return $this->sharecapitalChanges()->where('effective_date', '<=', $this->current_year_from)->where('share_type', 'Preference shares')->latest('effective_date')->first();
    }

    /* public function getLatestDetailBefore($date)
    {
        if (!$date) {
            return null;
        }

        return $this->detailChanges()
            ->where('effective_date', '<=', $date)
            ->orderByDesc('effective_date')
            ->first();
    }

    public function getDetailAtStart()
    {
        return $this->getLatestDetailBefore($this->current_year_from);
    }

    public function getDetailAtLast($date = null)
    {
        $date = $date ? Carbon::parse($date) : $this->current_year_to;

        return $this->getLatestDetailBefore($date);
    }

    public function getDetailChangesCurrentYear()
    {
        if (!$this->current_year_from || !$this->current_year_to) {
            return null;
        }

        return $this->detailChanges()
            ->whereBetween('effective_date', [$this->current_year_from, $this->current_year_to])
            ->orderBy('effective_date')
            ->get();
    } */

    /* public function getLatestBusinessBefore($date)
    {
        if (!$date) {
            return null;
        }

        return $this->businessChanges()
            ->where('effective_date', '<=', $date)
            ->orderByDesc('effective_date')
            ->first();
    }

    public function getBusinessAtStart()
    {
        return $this->getLatestBusinessBefore($this->current_year_from);
    }

    public function getBusinessAtLast($date = null)
    {
        $date = $date ? Carbon::parse($date) : $this->current_year_to;

        return $this->getLatestBusinessBefore($date);
    }

    public function getBusinessChangesCurrentYear()
    {
        if (!$this->current_year_from || !$this->current_year_to) {
            return null;
        }

        return $this->businessChanges()
            ->whereBetween('effective_date', [$this->current_year_from, $this->current_year_to])
            ->orderBy('effective_date')
            ->get();
    } */
}
