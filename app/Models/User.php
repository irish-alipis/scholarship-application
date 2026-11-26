<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\AccountStatus;
use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'phoneNumber', 'role', 'status',];

    protected $hidden = ['password', 'remember_token'];

    protected $attributes = [
        'role' => 'applicant',
        'status' => 'active',
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
            'role' => Role::class,
            'status' => AccountStatus::class,
        ];
    }

    public function applicant(): HasOne
    {
        return $this->hasOne(Applicant::class);
    }

    public function evaluator(): HasOne
    {
        return $this->hasOne(Evaluator::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    // Named appNotifications because Laravel's Notifiable trait already uses notifications().
    public function appNotifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function isActive(): bool
    {
        return $this->status === AccountStatus::Active;
    }

    public function hasRole(Role $role): bool
    {
        return $this->role === $role;
    }
    public function approve(): void
    {
        $this->update(['status' => AccountStatus::Active]);
    }
    public function activate(): void
    {
        $this->update(['status' => AccountStatus::Active]);
    }
    public function suspend(): void
    {
        $this->update(['status' => AccountStatus::Suspended]);
    }
    public function deactivate(): void
    {
        $this->update(['status' => AccountStatus::Deactivated]);
    }

    public function reject(): void
    {
        $this->update(['status' => AccountStatus::Rejected]);
    }

    public function changeRole(Role $role): void
    {
        $this->update(['role' => $role]);

        if ($role === Role::Evaluator) {
            Evaluator::firstOrCreate(
                ['user_id' => $this->id],
                ['employee_no' => 'EMP-' . str_pad($this->id, 4, '0', STR_PAD_LEFT)]
            );
        }
    }
}