<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'phone', 'email', 'address', 'city', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}