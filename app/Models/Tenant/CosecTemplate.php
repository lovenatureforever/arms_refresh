<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CosecTemplate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'form_type',
        'template_path',
        'content',
        'template_file',
        'signature_type',
        'default_signatory_id',
        'credit_cost',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Signature types
    const SIGNATURE_DEFAULT = 'default';
    const SIGNATURE_ALL_DIRECTORS = 'all_directors';

    public function defaultSignatory()
    {
        return $this->belongsTo(CompanyDirector::class, 'default_signatory_id');
    }

    public function orders()
    {
        return $this->hasMany(CosecOrder::class, 'template_id');
    }
}
