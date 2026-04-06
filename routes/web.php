<?php

use App\Http\Controllers\FirstLoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/first-login', [FirstLoginController::class, 'edit'])->name('first-login.edit');
    Route::put('/first-login', [FirstLoginController::class, 'update'])->name('first-login.update');
});

Route::middleware(['auth', 'first_login'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/admin-area', function () {
        return 'Halaman Admin';
    })->middleware('role:admin,perangkat_desa');

    Route::get('/portal-warga', function () {
        return 'Portal Warga';
    })->middleware('role:warga');
});

require __DIR__.'/auth.php';