<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = ['purchase_id','product_id','quantity','received_quantity','damaged_quantity','unit_price','subtotal'];

    protected function casts(): array
    {
        return ['quantity'=>'integer','received_quantity'=>'integer','damaged_quantity'=>'integer','unit_price'=>'decimal:2','subtotal'=>'decimal:2'];
    }

    public function purchase() { return $this->belongsTo(Purchase::class); }
    public function product() { return $this->belongsTo(Product::class); }
}