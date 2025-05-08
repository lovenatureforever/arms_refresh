<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class CompanyBizAddressChange extends Model
{
    use SoftDeletes, HasCompany;

    protected $fillable = [
        'company_id',
        // 'is_year_start',
        'change_nature',
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

    protected $appends = ['full_address'];

    protected $casts = [
        'effective_date' => 'date',
        // 'is_year_start' => 'boolean',
    ];

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
}
