<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KasMasukController;
use App\Http\Controllers\KasKeluarController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| AUTH REQUIRED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // DASHBOARD (ROOT)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // KAS MASUK
    Route::get('/kas-masuk', [KasMasukController::class, 'index']);
    Route::post('/kas-masuk', [KasMasukController::class, 'store']);
    Route::put('/kas-masuk/{id}', [KasMasukController::class, 'update']);
    Route::delete('/kas-masuk/{id}', [KasMasukController::class, 'destroy']);

    // KAS KELUAR
    Route::get('/kas-keluar', [KasKeluarController::class, 'index']);
    Route::post('/kas-keluar', [KasKeluarController::class, 'store']);
    Route::put('/kas-keluar/{id}', [KasKeluarController::class, 'update']);
    Route::delete('/kas-keluar/{id}', [KasKeluarController::class, 'destroy']);

    // LAPORAN
    Route::get('/laporan', [LaporanController::class, 'index']) ->name('laporan');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (LOGIN, LOGOUT, ETC)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';