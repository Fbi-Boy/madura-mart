<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function hasPermission(string $permission): bool
    {
        $override = PermissionOverride::query()
            ->where('role', $this->role)
            ->where('permission', $permission)
            ->first();

        if ($override) {
            return $override->enabled;
        }

        return in_array(
            $this->role,
            config('permissions.roles', [])[$permission] ?? [],
            true,
        );
    }
}
