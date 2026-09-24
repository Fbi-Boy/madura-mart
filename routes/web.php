<?php

use AppHttpControllersProfileController;
use AppHttpControllersDashboardDashboardController;
use IlluminateSupportFacadesRoute;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/menu/{menu}', function (string $menu) {
        $menus = [
            'penjualan' => 'Penjualan',
            'pembelian' => 'Pembelian',
            'pesanan' => 'Pesanan',
            'produk' => 'Produk',
            'distributor' => 'Distributor',
            'client' => 'Client',
            'kurir' => 'Kurir',
            'laporan-penjualan' => 'Laporan Penjualan',
            'laporan-pembelian' => 'Laporan Pembelian',
            'stok' => 'Stok',
            'transaksi-baru' => 'Transaksi Baru',
            'riwayat-transaksi' => 'Riwayat Transaksi',
            'retur' => 'Retur',
            'buka-shift' => 'Buka Shift',
            'tutup-shift' => 'Tutup Shift',
            'riwayat-shift' => 'Riwayat Shift',
        ];

        abort_unless(isset($menus[$menu]), 404);

        return view('menu.index', [
            'title' => $menus[$menu],
        ]);
    })->name('menu.show');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';
