<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

class ReportConfig extends Model
{
    use HasFactory, SoftDeletes, CentralConnection;

    protected $fillable = [
        'report_content',
        'position',
        'template_type',
        'display',
        'page_break',
        'remarks',
        'is_deleteable',
        'order_no'
    ];
}
