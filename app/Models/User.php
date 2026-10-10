<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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

    /**
     * Cabang tempat user bertugas.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Semua transaksi penjualan yang dibuat user (kasir).
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Semua shift yang pernah dibuka user.
     */
    public function shifts(): HasMany
    {
        return $this->hasMany(CashierShift::class);
    }

    /**
     * Activity log yang terkait user.
     */
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

    /**
     * Cek apakah user memiliki permission tertentu.
     * Berdasarkan role + permission_overrides.
     *
     * Contoh: $user->hasPermission('sales.manage')
     */
    public function hasPermission(string $permission): bool
    {
        // Super-admin bypass: selalu punya semua permission
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Admin default punya semua permission
        if ($this->isAdmin()) {
            return true;
        }

        // Cek override di tabel permission_overrides
        $override = PermissionOverride::where('role', $this->role)
            ->where('permission', $permission)
            ->first();

        if ($override) {
            return (bool) $override->enabled;
        }

        // Default: sesuai permission bawaan role
        return in_array($permission, $this->defaultPermissions(), true);
    }

    /**
     * Default permission per role (dipakai kalau tidak ada override).
     */
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

    /**
     * Ambil shift yang sedang aktif untuk user ini.
     */
    public function activeShift(): ?CashierShift
    {
        return $this->shifts()
            ->whereNull('closed_at')
            ->latest('opened_at')
            ->first();
    }

    /**
     * Cek apakah user punya shift aktif.
     */
    public function hasActiveShift(): bool
    {
        return $this->activeShift() !== null;
    }
}
