<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Tenant\Auditor;
use App\Models\Tenant\CompanyDirector;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'phone_number',
        'is_active',
        'is_audit_partner',
        'credit',
        'user_type'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_audit_partner' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function scopeIsAuditPartner(Builder $query): void
    {
        $query->where('is_audit_partner', 1);
    }

    public function auditor(): void
    {
        $this->hasOne(Auditor::class);
    }

    public function cosecOrders()
    {
        return $this->hasMany(\App\Models\Tenant\CosecOrder::class, 'tenant_user_id');
    }

    /**
     * Get the director profile linked to this user.
     */
    public function director()
    {
        return $this->hasOne(CompanyDirector::class, 'user_id');
    }

    // COSEC Role constants (Spatie roles)
    public const ROLE_COSEC_ADMIN = 'cosec_admin';
    public const ROLE_COSEC_SUBSCRIBER = 'cosec_subscriber';
    public const ROLE_COSEC_DIRECTOR = 'cosec_director';

    public const COSEC_ROLES = [
        self::ROLE_COSEC_ADMIN => 'Admin',
        self::ROLE_COSEC_SUBSCRIBER => 'Subscriber (Company Secretary)',
        self::ROLE_COSEC_DIRECTOR => 'Director',
    ];

    // Legacy user_type constants (for backward compatibility during migration)
    public const USER_TYPE_ADMIN = 'admin';
    public const USER_TYPE_SUBSCRIBER = 'subscriber';
    public const USER_TYPE_DIRECTOR = 'director';

    public const USER_TYPES = [
        self::USER_TYPE_ADMIN => 'Admin',
        self::USER_TYPE_SUBSCRIBER => 'Subscriber (Company Secretary)',
        self::USER_TYPE_DIRECTOR => 'Director',
    ];

    // Internal workflow roles
    public const ROLE_INTERNAL_ADMIN = 'internal_admin';
    public const ROLE_INTERNAL_REVIEWER = 'internal_reviewer';
    public const ROLE_INTERNAL_EXECUTOR = 'internal_executor';
    public const ROLE_INTERNAL_2ND_REVIEWER = 'internal_2nd_reviewer';
    public const ROLE_OUTSIDER_VIEWER = 'outsider_viewer';
    public const ROLE_OUTSIDER_CLIENT = 'outsider_client';
    public const ROLE_ISQM_APPROVER = 'isqm_approver';
    public const ROLE_ISQM_REVIEWER = 'isqm_reviewer';
    public const ROLE_ISQM_PREPARER = 'isqm_preparer';

    public const USER_ROLES = [
        self::ROLE_INTERNAL_ADMIN => 'Internal Admin',
        self::ROLE_INTERNAL_REVIEWER => 'Internal Reviewer',
        self::ROLE_INTERNAL_EXECUTOR => 'Internal Executor',
        self::ROLE_INTERNAL_2ND_REVIEWER => 'Internal 2nd Reviewer',
        self::ROLE_OUTSIDER_VIEWER => 'Outsider Viewer',
        self::ROLE_OUTSIDER_CLIENT => 'Outsider Client',
        self::ROLE_ISQM_APPROVER => 'ISQM Approver',
        self::ROLE_ISQM_REVIEWER => 'ISQM Reviewer',
        self::ROLE_ISQM_PREPARER => 'ISQM Preparer',
    ];

    /**
     * Check if user is a COSEC admin.
     */
    public function isCosecAdmin(): bool
    {
        return $this->hasRole(self::ROLE_COSEC_ADMIN);
    }

    /**
     * Check if user is a COSEC subscriber (company secretary).
     */
    public function isCosecSubscriber(): bool
    {
        return $this->hasRole(self::ROLE_COSEC_SUBSCRIBER);
    }

    /**
     * Check if user is a COSEC director.
     */
    public function isCosecDirector(): bool
    {
        return $this->hasRole(self::ROLE_COSEC_DIRECTOR);
    }

    /**
     * Check if user has admin or subscriber role.
     */
    public function canManageCompanies(): bool
    {
        return $this->hasAnyRole([self::ROLE_COSEC_ADMIN, self::ROLE_COSEC_SUBSCRIBER]);
    }

    /**
     * Get the user's COSEC role name for display.
     */
    public function getCosecRoleAttribute(): ?string
    {
        if ($this->hasRole(self::ROLE_COSEC_ADMIN)) {
            return 'admin';
        }
        if ($this->hasRole(self::ROLE_COSEC_SUBSCRIBER)) {
            return 'subscriber';
        }
        if ($this->hasRole(self::ROLE_COSEC_DIRECTOR)) {
            return 'director';
        }
        return null;
    }
}
