<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | ADMIN - MONITORING
    |--------------------------------------------------------------------------
    */

    Route::prefix('admin/monitoring')->name('admin.monitoring.')->group(function () {
        Route::view('/penjualan', 'admin.monitoring.penjualan.index')->name('penjualan');
        Route::view('/pembelian', 'admin.monitoring.pembelian.index')->name('pembelian');
        Route::view('/pesanan', 'admin.monitoring.pesanan.index')->name('pesanan');
        Route::view('/produk', 'admin.monitoring.produk.index')->name('produk');
        Route::view('/distributor', 'admin.monitoring.distributor.index')->name('distributor');
        Route::view('/client', 'admin.monitoring.client.index')->name('client');
        Route::view('/kurir', 'admin.monitoring.kurir.index')->name('kurir');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN - REPORT
    |--------------------------------------------------------------------------
    */

    Route::prefix('admin/report')->name('admin.report.')->group(function () {
        Route::view('/penjualan', 'admin.report.penjualan.index')->name('penjualan');
        Route::view('/pembelian', 'admin.report.pembelian.index')->name('pembelian');
        Route::view('/stok', 'admin.report.stok.index')->name('stok');
    });

    /*
    |--------------------------------------------------------------------------
    | KASIR
    |--------------------------------------------------------------------------
    */

    Route::prefix('kasir')->name('kasir.')->group(function () {
        Route::view('/transaksi-baru', 'kasir.transaksi-baru.index')->name('transaksi-baru');
        Route::view('/riwayat-transaksi', 'kasir.riwayat-transaksi.index')->name('riwayat-transaksi');
        Route::view('/retur', 'kasir.retur.index')->name('retur');
        Route::view('/buka-shift', 'kasir.buka-shift.index')->name('buka-shift');
        Route::view('/tutup-shift', 'kasir.tutup-shift.index')->name('tutup-shift');
        Route::view('/riwayat-shift', 'kasir.riwayat-shift.index')->name('riwayat-shift');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';
