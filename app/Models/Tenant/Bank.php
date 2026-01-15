<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'banks';

    protected $fillable = [
        'bank_name',
        'bank_code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all branches for this bank.
     */
    public function branches(): HasMany
    {
        return $this->hasMany(BankBranch::class);
    }

    /**
     * Get only active branches for this bank.
     */
    public function activeBranches(): HasMany
    {
        return $this->hasMany(BankBranch::class)->where('is_active', true);
    }

    /**
     * Scope to filter only active banks.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the full name attribute.
     */
    public function getFullNameAttribute(): string
    {
        return $this->bank_name;
    }
}
