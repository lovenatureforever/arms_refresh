<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditFirmAddress extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'branch',
        'country',
        'address_line1',
        'address_line2',
        'address_line3',
        'postcode',
        'town',
        'state',
        'mbrs_state',
    ];
}
