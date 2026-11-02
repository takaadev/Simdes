<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Public\VillageRegulationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});


Route::get('/dashboard', HomeController::class)->name('dashboard');

Route::prefix('admumum')->group(function () {
    // DataTables AJAX
    Route::get('buku-peraturan-desa/data',[VillageRegulationController::class, 'data'])->name('buku-peraturan-desa.data');
    // Resource CRUD
    Route::resource('buku-peraturan-desa',VillageRegulationController::class)->names('village-regulations');
});

Route::prefix('admpenduduk')->group(function(){
// 
});
