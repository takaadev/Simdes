<?php

use App\Http\Controllers\Archieves\AgendaEntryController;
use App\Http\Controllers\Archieves\ExpeditionEntryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Generals\VillageAssetController;
use App\Http\Controllers\Generals\VillageDecreeController;
use App\Http\Controllers\Generals\VillageOfficialController;
use App\Http\Controllers\Generals\VillagePublicationController;
use App\Http\Controllers\Generals\VillageRegulationController;
use App\Http\Controllers\Generals\VillageTreasuryLandController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});


Route::get('/dashboard', HomeController::class)->name('dashboard');

Route::prefix('admumum')->group(function () {
    Route::resource('peraturan-desa', VillageRegulationController::class)
        ->parameters(['peraturan-desa' => 'villageRegulation'])
        ->names('village-regulations')
        ->except(['create', 'edit', 'show'])->withTrashed(['update', 'destroy']);

    Route::resource('keputusan-kepala-desa', VillageDecreeController::class)
        ->parameters(['keputusan-kepala-desa' => 'villageDecree'])
        ->names('village-decrees')
        ->except(['create', 'edit', 'show'])->withTrashed(['update', 'destroy']);

    Route::resource('inventaris-dan-kekayaan-desa', VillageAssetController::class)
        ->parameters(['inventaris-dan-kekayaan-desa' => 'villageAsset'])
        ->names('village-assets')
        ->except(['create', 'edit', 'show'])->withTrashed(['update', 'destroy']);

    Route::resource('aparat-pemerintah-desa', VillageOfficialController::class)
        ->parameters(['aparat-pemerintah-desa' => 'villageOfficial'])
        ->names('village-officials')
        ->except(['create', 'edit', 'show'])->withTrashed(['update', 'destroy']);

    Route::resource('tanah-kas-desa', VillageTreasuryLandController::class)
        ->parameters(['tanah-kas-desa' => 'villageTreasuryLand'])
        ->names('village-treasury-lands')
        ->except(['create', 'edit', 'show'])->withTrashed(['update', 'destroy']);

    Route::resource('lembar-dan-berita-desa', VillagePublicationController::class)
        ->parameters(['lembar-dan-berita-desa' => 'villagePublication'])
        ->names('village-publications')
        ->except(['create', 'edit', 'show'])->withTrashed(['update', 'destroy']);
});

Route::prefix('arsip')->group(function () {
    Route::resource('agendas', AgendaEntryController::class)->names('agendas')->except(['create', 'edit', 'show'])->withTrashed(['update', 'destroy']);
    Route::resource('ekspedisi', ExpeditionEntryController::class)->names('expeditions')->except(['create', 'edit', 'show'])->withTrashed(['update', 'destroy']);
});

Route::prefix('admpenduduk')->group(function () {
    //
});
