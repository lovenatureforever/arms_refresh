<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankBranch extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bank_branches';

    protected $fillable = [
        'bank_id',
        'branch_name',
        'street',
        'street_2',
        'street_3',
        'city',
        'state',
        'postcode',
        'country',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the bank that this branch belongs to.
     */
    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    /**
     * Scope to filter only active branches.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the full address as a formatted string.
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->street,
            $this->street_2,
            $this->street_3,
            $this->city,
            $this->state,
            $this->postcode,
            $this->country,
        ]);
        return implode(', ', $parts);
    }

    /**
     * Get the display name (Bank Name - Branch Name).
     */
    public function getDisplayNameAttribute(): string
    {
        $name = $this->bank->bank_name;
        if ($this->branch_name) {
            $name .= ' - ' . $this->branch_name;
        }
        return $name;
    }
}
