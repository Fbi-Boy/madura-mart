<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;              // ← TAMBAH

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  // ← TAMBAH HasApiTokens

    /**
     * Kolom yang bisa diisi massal.
     */
    protected $fillable = [
        'branch_id',
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI
    |--------------------------------------------------------------------------
    */

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(CashierShift::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER — ROLE CHECK
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super-admin'], true);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super-admin';
    }

    public function isKasir(): bool
    {
        return $this->role === 'kasir';
    }

    public function isGudang(): bool
    {
        return $this->role === 'gudang';
    }

    public function isPurchasing(): bool
    {
        return $this->role === 'purchasing';
    }

    public function isKurir(): bool
    {
        return $this->role === 'kurir';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER — PERMISSION
    |--------------------------------------------------------------------------
    */

    public function hasPermission(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if ($this->isAdmin()) {
            return true;
        }

        $override = PermissionOverride::where('role', $this->role)
            ->where('permission', $permission)
            ->first();

        if ($override) {
            return (bool) $override->enabled;
        }

        return in_array($permission, $this->defaultPermissions(), true);
    }

    protected function defaultPermissions(): array
    {
        return match ($this->role) {
            'kasir' => [
                'sales.create',
                'sales.view',
                'shifts.manage',
            ],
            'gudang' => [
                'stock.view',
                'stock.opname',
                'stock.receive',
                'stock.outbound',
            ],
            'purchasing' => [
                'purchases.manage',
                'suppliers.manage',
            ],
            'kurir' => [
                'deliveries.view',
                'deliveries.update',
            ],
            'customer' => [
                'orders.create',
                'orders.view',
            ],
            default => [],
        };
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER — SHIFT AKTIF
    |--------------------------------------------------------------------------
    */

    public function activeShift(): ?CashierShift
    {
        return $this->shifts()
            ->whereNull('closed_at')
            ->latest('opened_at')
            ->first();
    }

    public function hasActiveShift(): bool
    {
        return $this->activeShift() !== null;
    }
}
