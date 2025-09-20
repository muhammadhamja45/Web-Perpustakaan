<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_approved',
        'approved_by',
        'approved_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
            'is_approved' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' && $this->is_approved;
    }

    /**
     * Check if user is student
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Check if user is pending approval
     */
    public function isPending(): bool
    {
        return $this->role === 'admin' && !$this->is_approved;
    }

    /**
     * Approve admin user
     */
    public function approve(User $approver = null): void
    {
        $this->update([
            'is_approved' => true,
            'approved_by' => $approver?->id,
            'approved_at' => now(),
        ]);
    }

    /**
     * Get the user who approved this admin
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get pending admin registrations
     */
    public static function pendingAdmins()
    {
        return static::where('role', 'admin')
                    ->where('is_approved', false)
                    ->orderBy('created_at', 'desc');
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}
