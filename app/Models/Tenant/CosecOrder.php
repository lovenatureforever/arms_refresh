<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CosecOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        // 'tenant_id',
        'tenant_company_id',
        'company_name',
        'company_no',
        'company_old_no',
        'tenant_user_id',
        'user',
        'uuid',
        'form_type',
        'form_name',
        'template_id',
        'requested_at',
        'data',
        'cost',
        'status',
        'signature_status'
    ];

    protected $casts = [
        'data' => 'json',
        'requested_at' => 'datetime'
    ];

    // Status constants
    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;
    const STATUS_COMPLETED = 3;

    // Signature status constants
    const SIGNATURE_NOT_REQUIRED = 'not_required';
    const SIGNATURE_PENDING = 'pending';
    const SIGNATURE_PARTIAL = 'partial';
    const SIGNATURE_COMPLETE = 'complete';

    public function template()
    {
        return $this->belongsTo(CosecTemplate::class, 'template_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'tenant_company_id');
    }

    public function signatures()
    {
        return $this->hasMany(CosecOrderSignature::class, 'cosec_order_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'tenant_user_id');
    }

    public function requiresAllDirectorSignatures()
    {
        return $this->template && $this->template->signature_type === CosecTemplate::SIGNATURE_ALL_DIRECTORS;
    }

    public function getSignatureProgress()
    {
        if (!$this->requiresAllDirectorSignatures()) {
            return ['required' => 0, 'signed' => 0, 'complete' => true];
        }

        $required = $this->signatures()->count();
        $signed = $this->signatures()->where('signature_status', CosecOrderSignature::STATUS_SIGNED)->count();
        
        return [
            'required' => $required,
            'signed' => $signed,
            'complete' => $required > 0 && $signed === $required
        ];
    }

}
