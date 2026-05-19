<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id', // Added role_id
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
        ];
    }

    // Define the relationship: a user belongs to a role.
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Check if the user has permission based on a configuration key.
    public function hasAccess(string $permission): bool
    {
        // Retrieve allowed roles for this permission from configuration.
        $allowedRoles = config("permissions.{$permission}", []);

        // If no roles are set, allow access by default.
        if (empty($allowedRoles)) {
            return true;
        }

        // Deny access if the user does not have an associated role.
        if (!$this->role) {
            return false;
        }

        // Compare the user's role in a case-insensitive manner.
        $userRole = strtolower($this->role->name);
        $allowedRoles = array_map('strtolower', $allowedRoles);

        return in_array($userRole, $allowedRoles, true);
    }
}
