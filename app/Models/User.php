<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'credit'
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
            'is_active' => 'boolean',
        ];
    }

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
}
