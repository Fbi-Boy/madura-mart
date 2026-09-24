<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CourierController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\DistributorController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\Monitoring\ClientController;
use App\Http\Controllers\Admin\Monitoring\DistributorController;
use App\Http\Controllers\Admin\Monitoring\KurirController;
use App\Http\Controllers\Admin\Monitoring\PembelianController as MonitoringPembelianController;
use App\Http\Controllers\Admin\Monitoring\PenjualanController as MonitoringPenjualanController;
use App\Http\Controllers\Admin\Monitoring\PesananController;
use App\Http\Controllers\Admin\Monitoring\ProdukController;
use App\Http\Controllers\Admin\Report\PembelianController as ReportPembelianController;
use App\Http\Controllers\Admin\Report\PenjualanController as ReportPenjualanController;
use App\Http\Controllers\Admin\Report\StokController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Kasir\SaleController;
use App\Http\Controllers\Kasir\SaleReturnController;
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

    Route::prefix('admin/monitoring')
        ->name('admin.monitoring.')
        ->middleware('role:admin,super-admin')
        ->group(function () {
            Route::get('/penjualan', [MonitoringPenjualanController::class, 'index'])->name('penjualan');
            Route::get('/pembelian', [MonitoringPembelianController::class, 'index'])->name('pembelian');
            Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan');
            Route::get('/produk', [ProdukController::class, 'index'])->name('produk');
            Route::get('/distributor', [DistributorController::class, 'index'])->name('distributor');
            Route::get('/client', [ClientController::class, 'index'])->name('client');
            Route::get('/kurir', [KurirController::class, 'index'])->name('kurir');
        });

    /*
    |--------------------------------------------------------------------------
    | ADMIN - REPORT
    |--------------------------------------------------------------------------
    */

    Route::prefix('admin/report')
        ->name('admin.report.')
        ->middleware('role:admin,super-admin')
        ->group(function () {
            Route::get('/penjualan', [ReportPenjualanController::class, 'index'])->name('penjualan');
            Route::get('/pembelian', [ReportPembelianController::class, 'index'])->name('pembelian');
            Route::get('/stok', [StokController::class, 'index'])->name('stok');
        });

    Route::prefix('admin')->name('admin.')->middleware('role:admin,super-admin')->group(function () {
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('products', ProductController::class)->except(['show']);
        Route::resource('suppliers', SupplierController::class)->except(['show']);
        Route::resource('customers', CustomerController::class)->except(['show']);
        Route::resource('couriers', CourierController::class)->except(['show']);
        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('units', UnitController::class)->except(['show']);
        Route::resource('purchases', PurchaseController::class)->only(['index','create','store']);
        Route::resource('distributors', DistributorController::class)->except(['show']);
    });

    /*
    |--------------------------------------------------------------------------
    | KASIR
    |--------------------------------------------------------------------------
    */

    Route::prefix('kasir')
        ->name('kasir.')
        ->middleware('role:kasir')
        ->group(function () {
            Route::get('/transaksi-baru', [SaleController::class, 'create'])->name('transaksi-baru');
            Route::post('/transaksi-baru', [SaleController::class, 'store'])->name('transaksi-baru.store');
            Route::get('/riwayat-transaksi', [SaleController::class, 'index'])->name('riwayat-transaksi');
            Route::get('/retur', [SaleReturnController::class, 'index'])->name('retur');
            Route::get('/retur/baru', [SaleReturnController::class, 'create'])->name('retur.create');
            Route::post('/retur', [SaleReturnController::class, 'store'])->name('retur.store');
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
