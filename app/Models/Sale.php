<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice',
        'customer_id',
        'user_id',
        'shift_id',
        'sale_date',
        'subtotal',
        'tax',
        'total',
        'payment_method',
        'paid_amount',
        'change_amount',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'sale_date'     => 'datetime',
            'subtotal'      => 'decimal:2',
            'tax'           => 'decimal:2',
            'total'         => 'decimal:2',
            'paid_amount'   => 'decimal:2',
            'change_amount' => 'decimal:2',
        ];
    }

    public static function nextInvoiceNumber(): int
    {
        $today  = now()->format('ymd');
        $prefix = "INV-{$today}-";

        $lastSale = self::where('invoice', 'like', $prefix . '%')
            ->orderByDesc('invoice')
            ->first();

        if (!$lastSale) {
            return 1;
        }

        return (int) substr($lastSale->invoice, strlen($prefix)) + 1;
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(CashierShift::class, 'shift_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
}
