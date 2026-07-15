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
    ->group(function () {

        // ============ VIEW ONLY (admin & owner) ============
        Route::middleware(['auth', 'role:admin,owner'])->group(function () {

            Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

            // Master Data — index & show saja
            Route::resource('kategori', Admin\KategoriController::class)->only(['index', 'show']);
            Route::resource('satuan',   Admin\SatuanController::class)->only(['index', 'show']);
            Route::resource('pemasok',  Admin\PemasokController::class)->only(['index', 'show']);
            Route::resource('barang',   Admin\BarangController::class)->only(['index', 'show']);

            // Transaksi — index & show saja
            Route::resource('barang-masuk', Admin\BarangMasukController::class)
                ->names('barang_masuk')
                ->only(['index', 'show']);

            Route::resource('barang-keluar', Admin\BarangKeluarController::class)
                ->names('barang_keluar')
                ->only(['index', 'show']);

            Route::resource('stock-opname', Admin\StockOpnameController::class)
                ->names('stock_opname')
                ->only(['index', 'show']);
        });

        // ============ FULL ACCESS (admin only) ============
        Route::middleware(['auth', 'role:admin'])->group(function () {

            // Master Data — create/store/edit/update/destroy
            Route::resource('kategori', Admin\KategoriController::class)->except(['index', 'show']);
            Route::resource('satuan',   Admin\SatuanController::class)->except(['index', 'show']);
            Route::resource('pemasok',  Admin\PemasokController::class)->except(['index', 'show']);
            Route::resource('barang',   Admin\BarangController::class)->except(['index', 'show']);

            // Transaksi — create/store/destroy (tanpa edit/update sesuai config awal)
            Route::resource('barang-masuk', Admin\BarangMasukController::class)
                ->names('barang_masuk')
                ->only(['create', 'store', 'destroy']);

            Route::resource('barang-keluar', Admin\BarangKeluarController::class)
                ->names('barang_keluar')
                ->only(['create', 'store', 'destroy']);

            Route::resource('stock-opname', Admin\StockOpnameController::class)
                ->names('stock_opname')
                ->only(['create', 'store', 'destroy']);

            // User Management — khusus admin, owner tidak boleh sama sekali
            Route::resource('user', Admin\UserController::class)->except(['show']);
        });
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
