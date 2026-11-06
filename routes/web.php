<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Public\VillageDecreeController;
use App\Http\Controllers\Public\VillageRegulationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});


Route::get('/dashboard', HomeController::class)->name('dashboard');

Route::prefix('admumum')->group(function () {
    Route::resource('buku-peraturan-desa', VillageRegulationController::class)->parameters(['buku-peraturan-desa' => 'villageRegulation'])->names('village-regulations');
    Route::resource('buku-keputusan-kepala-desa', VillageDecreeController::class)->parameters(['buku-keputusan-kepala-desa' => 'villageDecrees'])->names('village-decrees');
});

Route::prefix('admpenduduk')->group(function () {
    // 
});
