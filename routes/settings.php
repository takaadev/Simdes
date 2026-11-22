<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('pengaturan')->name('settings.')->group(function () {
    Route::get('/', SettingsController::class)->name('index');
    Route::put('/{setting}/update-profile', [SettingsController::class, 'updateProfile'])->name('update.profile');
    Route::put('/{setting}/update-syestem', [SettingsController::class, 'updateSystem'])->name('update.system');
});

Route::middleware('auth')->prefix('pengguna')->group(function () {
    Route::resource('/', UserController::class)
        ->parameters(['pengguna' => 'user'])
        ->names('users')
        ->except(['create', 'edit', 'show'])
        ->withTrashed(['update', 'destroy']);
    Route::put('pengguna/{user}/password', [UserController::class, 'updatePassword'])
        ->name('users.update-password');
});
