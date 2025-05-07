<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_name',
        'company_registration_no',
        'company_registration_no_old',
        'current_audit_is_first_year',
        'current_year_period_type',
        'current_year_report_header_format',
        'current_financial_year',
        'current_audit_period_from',
        'current_audit_period_to',
        'last_audit_is_first_year',
        'last_year_period_type',
        'last_year_report_header_format',
        'last_financial_year',
        'last_audit_period_from',
        'last_audit_period_to',
        'audit_fee',
        'status',
        'no_business_address'
    ];

    public function secondReviewer()
    {
        return $this->hasMany(CompanyInchargePerson::class)->where('assign_as', '2nd Reviewer (Partner)');
    }

    public function reviewer()
    {
        return $this->hasMany(CompanyInchargePerson::class)->where('assign_as', 'Reviewer');
    }

    public function executor()
    {
        return $this->hasMany(CompanyInchargePerson::class)->where('assign_as', 'Executor');
    }

    public function company_reports(): HasMany
    {
        return $this->hasMany(CompanyReport::class);
    }

    public function current_year_end(): HasMany
    {
        return $this->hasMany(CompanyConfigYearEnd::class)->where('year_end', 'Current year end');
    }

    public function prior_year_end(): HasMany
    {
        return $this->hasMany(CompanyConfigYearEnd::class)->where('year_end', 'As at prior year end');
    }

    public function prior_company_name(): HasMany
    {
        return $this->hasMany(CompanyDetail::class);
    }

    public function business_natures(): HasMany
    {
        return $this->hasMany(CompanyBusinessNature::class);
    }

    public function declared_dividends(): HasMany
    {
        return $this->hasMany(CompanyDividends::class)->where('is_declared', 1);
    }

    public function proposed_dividends(): HasMany
    {
        return $this->hasMany(CompanyDividends::class)->where('is_declared', 0);
    }

    public function share_capitals(): HasMany
    {
        return $this->hasMany(CompanyShareCapital::class)->where('prior_year_end', 0);
    }

    public function directors(): HasMany
    {
        return $this->hasMany(CompanyDirector::class);
    }

    public function statement_directors(): HasMany
    {
        return $this->hasMany(CompanyDirector::class)->where('is_rep_statement', 1);
    }

    public function statutory_directors(): HasMany
    {
        return $this->hasMany(CompanyDirector::class)->where('is_rep_statutory', 1);
    }

    public function shareholders(): HasMany
    {
        return $this->hasMany(CompanyShareholder::class);
    }

    public function prior_ordinary_share(): HasMany
    {
        return $this->hasMany(CompanyShareCapital::class)->where('prior_year_end', 1)->where('share_type', 'Ordinary shares');
    }

    public function prior_preference_share(): HasMany
    {
        return $this->hasMany(CompanyShareCapital::class)->where('prior_year_end', 1)->where('share_type', 'Preference shares');
    }

    public function current_ordinary_share(): HasMany
    {
        return $this->hasMany(CompanyShareCapital::class)->where('prior_year_end', 0)->where('share_type', 'Ordinary shares');
    }

    public function current_preference_share(): HasMany
    {
        return $this->hasMany(CompanyShareCapital::class)->where('prior_year_end', 0)->where('share_type', 'Preference shares');
    }

    public function business_addresses(): HasMany
    {
        return $this->hasMany(CompanyBusinessAddress::class)->where('prior_year_end', 0)->where('status', 1);
    }

    public function prior_business_addresses(): HasMany
    {
        return $this->hasMany(CompanyBusinessAddress::class)->where('prior_year_end', 1)->where('status', 1);
    }

    public function prior_directors(): HasMany
    {
        return $this->hasMany(CompanyDirector::class)->where('prior_year_end', 1);
    }

    public function statement_infos(): HasMany
    {
        return $this->hasMany(CompanyReportInfo::class)->where('report_type', 'statement_director');
    }

    public function statutory_infos(): HasMany
    {
        return $this->hasMany(CompanyReportInfo::class)->where('report_type', 'statutory_declaration');
    }

    public function cover_page_directors(): HasMany
    {
        return $this->hasMany(CompanyDirector::class)->where('is_cover_page', 1);
    }

    public function cover_page_secretaries(): HasMany
    {
        return $this->hasMany(CompanySecretaries::class)->where('is_cover_page', 1);
    }

    public function related_party_transactions(): HasMany
    {
        return $this->hasMany(RelatedPartyTransaction::class);
    }
}
