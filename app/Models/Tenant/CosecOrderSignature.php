<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class CosecOrderSignature extends Model
{
    protected $fillable = [
        'cosec_order_id',
        'director_id',
        'signature_status',
        'signed_at'
    ];

    protected $casts = [
        'signed_at' => 'datetime'
    ];

    // Signature statuses
    const STATUS_PENDING = 'pending';
    const STATUS_SIGNED = 'signed';
    const STATUS_REJECTED = 'rejected';

    public function order()
    {
        return $this->belongsTo(CosecOrder::class, 'cosec_order_id');
    }

    public function director()
    {
        return $this->belongsTo(CompanyDirector::class, 'director_id');
    }
}