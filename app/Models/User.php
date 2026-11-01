<?php

namespace App\Models;

use App\Enums\StaffRole;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    public const ROLE_ADMIN = StaffRole::ADMINISTRATOR->value;

    public const ROLE_BUSINESS_OWNER = StaffRole::BUSINESS_OWNER->value;

    public const ROLE_OPERATOR = StaffRole::LEAD_OPERATOR->value;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'company_id',
        'name',
        'email',
        'password',
        'role',
        'last_seen_at',
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
            'last_seen_at' => 'datetime',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    public function chatSessions(): HasMany
    {
        return $this->hasMany(ChatSession::class, 'assigned_user_id');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function currentStatus(): string
    {
        if ($this->trashed()) {
            return 'Terminated';
        }

        if (! $this->last_seen_at || $this->last_seen_at->lt(now()->subMinutes(5))) {
            return 'Offline';
        }

        $hasActiveChat = $this->relationLoaded('chatSessions')
            ? $this->chatSessions->contains(fn (ChatSession $session): bool => in_array($session->status, ['handed_off', 'human_active'], true))
            : $this->chatSessions()->whereIn('status', ['handed_off', 'human_active'])->exists();

        return $hasActiveChat ? 'Active' : 'Online';
    }

    public function hasRole(string $role): bool
    {
        return StaffRole::normalize($this->role) === StaffRole::normalize($role);
    }

    public function isAdministrator(): bool
    {
        return $this->hasRole(self::ROLE_ADMIN);
    }

    public function isTenantUser(): bool
    {
        return in_array(StaffRole::normalize($this->role), [
            StaffRole::BUSINESS_OWNER->value,
            StaffRole::LEAD_OPERATOR->value,
        ], true);
    }

    protected function role(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: static fn (?string $value): ?string => StaffRole::normalize($value),
            set: static fn (?string $value): ?string => StaffRole::normalize($value),
        );
    }

    /**
     * Send the password reset notification via Resend.
     */
    public function sendPasswordResetNotification(#[\SensitiveParameter] $token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
