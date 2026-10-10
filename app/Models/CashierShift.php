<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashierShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'shift_number',
        'user_id',
        'branch_id',
        'shift_type',
        'opened_at',
        'opening_cash',
        'closed_at',
        'closing_cash',
        'expected_cash',
        'closing_notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'opened_at'      => 'datetime',
            'closed_at'      => 'datetime',
            'opening_cash'   => 'decimal:2',
            'closing_cash'   => 'decimal:2',
            'expected_cash'  => 'decimal:2',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI
    |--------------------------------------------------------------------------
    */

    /**
     * Kasir yang bertugas di shift ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Cabang tempat shift dibuka.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Semua transaksi yang masuk dalam shift ini.
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class, 'shift_id');
    }
}
