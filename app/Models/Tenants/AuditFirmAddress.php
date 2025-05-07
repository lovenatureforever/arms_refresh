<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditFirmAddress extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'firm_branch',
        'firm_address1',
        'firm_address2',
        'firm_postcode',
        'firm_city',
        'firm_state'
    ];
}
