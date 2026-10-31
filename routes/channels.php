<?php

use Illuminate\Support\Facades\Broadcast;

// Private channel milik masing-masing user.
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

// Channel khusus notifikasi produk untuk Super Admin.
Broadcast::channel('super-admin.products', function ($user) {
    return $user->role === 'super-admin';
});
