<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionOverride extends Model
{
    protected $fillable = [
        'role',
        'permission',
        'enabled',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
        ];
    }
}
