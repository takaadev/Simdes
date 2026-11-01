<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Public\VillageRegulationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});


Route::get('/dashboard', HomeController::class)->name('dashboard');
Route::resource('buku-peraturan-desa', VillageRegulationController::class)->names('village-regulations');
