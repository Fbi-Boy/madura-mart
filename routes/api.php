<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Protected Routes (butuh login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', fn (\Illuminate\Http\Request $request) => $request->user());
    Route::post('/logout', [AuthController::class, 'logout']);

    // Admin-only CRUD Categories
    Route::middleware('api.role:admin,super-admin')->group(function () {
        Route::apiResource('admin/categories', CategoryController::class)
            ->names([
                'index'   => 'api.admin.categories.index',
                'store'   => 'api.admin.categories.store',
                'show'    => 'api.admin.categories.show',
                'update'  => 'api.admin.categories.update',
                'destroy' => 'api.admin.categories.destroy',
            ]);
    });

    // Kasir-only (contoh)
    Route::middleware('api.role:kasir,admin,super-admin')->group(function () {
        Route::get('/kasir/sales-summary', function (\Illuminate\Http\Request $request) {
            return response()->json([
                'message' => 'Halo kasir, ini ringkasan penjualan.',
                'user'    => $request->user()->only(['id', 'name', 'role']),
            ]);
        });
    });
});

/*
|--------------------------------------------------------------------------
| Public API Resource (dari Acara 25)
|--------------------------------------------------------------------------
*/
Route::apiResource('categories', CategoryController::class);
