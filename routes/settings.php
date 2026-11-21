<?php

use App\Http\Controllers\Settings\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')
    ->prefix('pengaturan')
    ->name('settings.')
    ->group(function () {

        // Pengaturan Umum
        Route::get('/umum', function () {
            // TODO: Implementasi halaman pengaturan umum
        })->name('general');

        // Manajemen Pengguna
        Route::resource('pengguna', UserController::class)
            ->parameters(['pengguna' => 'user'])
            ->names('users')
            ->except(['create', 'edit', 'show'])
            ->withTrashed(['update', 'destroy']);

        // Ubah Kata Sandi Pengguna
        Route::put('pengguna/{user}/password', [UserController::class, 'updatePassword'])
            ->name('users.update-password');
    });