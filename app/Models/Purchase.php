<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = ['invoice','supplier_id','user_id','purchase_date','total','status','notes'];

    protected function casts(): array
    {
        return ['purchase_date'=>'date','total'=>'decimal:2'];
    }

    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(PurchaseItem::class); }
}