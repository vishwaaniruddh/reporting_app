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
        'role_id'
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

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    //     public function hasPermission($permissionSlug){
//     // Admin has all permissions
//     if ($this->role === 'admin') {
//         return true;
//     }

    //     // Check if role exists and has permissions loaded
//     if (!$this->role || !$this->relationLoaded('role') || !$this->role->relationLoaded('permissions')) {
//         $this->load('role.permissions');
//     }

    //     // Check role permissions
//     return $this->role->permissions->contains('slug', $permissionSlug);
// }

    public function hasPermission($permissionSlug): bool
    {
        // Debugging - remove after testing
        // \Log::info('Checking permission', [
        //     'user_id' => $this->id,
        //     'role_id' => $this->role_id,
        //     'permission' => $permissionSlug
        // ]);

        // Admin has all permissions
        if ($this->role && $this->role->name === 'admin') {
            return true;
        }

        // Check if role and permissions are loaded
        if (!$this->relationLoaded('role.permissions')) {
            $this->load('role.permissions');
        }

        // Check permissions
        return $this->role && $this->role->permissions->contains('slug', $permissionSlug);
    }
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }


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
}
