<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Owner;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Spare Parts Workshop
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => redirect()->route('login'));

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// ─────────────────────────────────────────────
// Admin Routes
// ─────────────────────────────────────────────
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Master Data
        Route::resource('kategori',  Admin\KategoriController::class);
        Route::resource('satuan',    Admin\SatuanController::class);
        Route::resource('pemasok',   Admin\PemasokController::class);
        Route::resource('barang',    Admin\BarangController::class);

        // Transaksi
        Route::resource('barang-masuk',  Admin\BarangMasukController::class)
            ->names('barang_masuk')
            ->except(['edit', 'update']);

        Route::resource('barang-keluar', Admin\BarangKeluarController::class)
            ->names('barang_keluar')
            ->except(['edit', 'update']);

        Route::resource('stock-opname', Admin\StockOpnameController::class)
            ->names('stock_opname')
            ->except(['edit', 'update']);

        // User Management
        Route::resource('user', Admin\UserController::class)->except(['show']);
    });

// ─────────────────────────────────────────────
// Owner Routes
// ─────────────────────────────────────────────
Route::prefix('owner')
    ->name('owner.')
    ->middleware(['auth', 'role:owner'])
    ->group(function () {

        Route::get('/dashboard', [Owner\DashboardController::class, 'index'])->name('dashboard');

        // Laporan
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/stok',    [Owner\LaporanController::class, 'stok'])->name('stok');
            Route::get('/masuk',   [Owner\LaporanController::class, 'masuk'])->name('masuk');
            Route::get('/keluar',  [Owner\LaporanController::class, 'keluar'])->name('keluar');
            Route::get('/mutasi',  [Owner\LaporanController::class, 'mutasi'])->name('mutasi');
        });
    });
