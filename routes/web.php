<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RelasionalObatController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Root redirect
Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return redirect('/login');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });

    // Admin only routes
    Route::middleware(['role:admin'])->group(function () {
        // Kategori Obat
        Route::get('/relasional-obat/create', [RelasionalObatController::class, 'create']);
        Route::post('/relasional-obat', [RelasionalObatController::class, 'store']);
        Route::get('/relasional-obat/{id}/edit', [RelasionalObatController::class, 'edit']);
        Route::put('/relasional-obat/{id}', [RelasionalObatController::class, 'update']);
        Route::delete('/relasional-obat/{id}', [RelasionalObatController::class, 'destroy']);
        Route::get('/relasional-obat', [RelasionalObatController::class, 'index']);

        // Obat (admin full access)
        Route::get('/obat/create', [ObatController::class, 'create']);
        Route::post('/obat', [ObatController::class, 'store']);
        Route::get('/obat/{id}/edit', [ObatController::class, 'edit']);
        Route::put('/obat/{id}', [ObatController::class, 'update']);
        Route::delete('/obat/{id}', [ObatController::class, 'destroy']);

        // Supplier
        Route::get('/supplier/create', [SupplierController::class, 'create']);
        Route::post('/supplier', [SupplierController::class, 'store']);
        Route::get('/supplier/{id}/edit', [SupplierController::class, 'edit']);
        Route::put('/supplier/{id}', [SupplierController::class, 'update']);
        Route::delete('/supplier/{id}', [SupplierController::class, 'destroy']);
        Route::get('/supplier', [SupplierController::class, 'index']);

        // User (petugas)
        Route::get('/user/create', [UserController::class, 'create']);
        Route::post('/user', [UserController::class, 'store']);
        Route::get('/user/{id}/edit', [UserController::class, 'edit']);
        Route::put('/user/{id}', [UserController::class, 'update']);
        Route::delete('/user/{id}', [UserController::class, 'destroy']);
        Route::get('/user', [UserController::class, 'index']);
    });

    // Admin & Petugas routes
    Route::middleware(['role:admin,petugas'])->group(function () {
        // Obat (read only for petugas)
        Route::get('/obat', [ObatController::class, 'index']);

        // Pelanggan
        Route::get('/pelanggan/create', [PelangganController::class, 'create']);
        Route::post('/pelanggan', [PelangganController::class, 'store']);
        Route::post('/pelanggan/store-ajax', [PelangganController::class, 'storeAjax']);
        Route::get('/pelanggan/{id}/edit', [PelangganController::class, 'edit']);
        Route::put('/pelanggan/{id}', [PelangganController::class, 'update']);
        Route::delete('/pelanggan/{id}', [PelangganController::class, 'destroy']);
        Route::get('/pelanggan', [PelangganController::class, 'index']);

        // Pembelian
        Route::get('/pembelian/create', [PembelianController::class, 'create']);
        Route::post('/pembelian', [PembelianController::class, 'store']);
        Route::get('/pembelian/{id}', [PembelianController::class, 'show']);
        Route::delete('/pembelian/{id}', [PembelianController::class, 'destroy']);
        Route::get('/pembelian', [PembelianController::class, 'index']);

        // Penjualan
        Route::get('/penjualan/create', [PenjualanController::class, 'create']);
        Route::post('/penjualan', [PenjualanController::class, 'store']);
        Route::get('/penjualan/{id}', [PenjualanController::class, 'show']);
        Route::delete('/penjualan/{id}', [PenjualanController::class, 'destroy']);
        Route::get('/penjualan', [PenjualanController::class, 'index']);
    });
});
