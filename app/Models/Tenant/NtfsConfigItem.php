<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NtfsConfigItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'default_title',
        'default_content',
        'title',
        'content',
        'type',
        'section',
        'position',
        'order',
        'is_active',
        'is_default_title',
        'is_default_content',
        'company_id',
        'remark',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default_title' => 'boolean',
        'is_default_content' => 'boolean',
    ];
}
