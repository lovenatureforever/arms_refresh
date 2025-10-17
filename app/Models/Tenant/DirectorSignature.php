<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DirectorSignature extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'director_id',
        'signature_path',
        'is_default',
        'uploaded_by'
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ];

    public function director()
    {
        return $this->belongsTo(CompanyDirector::class, 'director_id');
    }

    public function uploader()
    {
        return $this->belongsTo(\App\Models\User::class, 'uploaded_by');
    }
}