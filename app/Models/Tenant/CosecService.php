<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CosecService extends Model
{
    use SoftDeletes;

    protected $table = 'cosec_services';

    protected $fillable = [
        'name',
        'cost',
    ];
}
