<?php

use App\Http\Controllers\Archieves\AgendaEntryController;
use App\Http\Controllers\Generals\VillageAssetController;
use App\Http\Controllers\Generals\VillageDecreeController;
use App\Http\Controllers\Generals\VillageOfficialController;
use App\Http\Controllers\Generals\VillagePublicationController;
use App\Http\Controllers\Generals\VillageRegulationController;
use App\Http\Controllers\Generals\VillageTreasuryLandController;
use Illuminate\Support\Facades\Route;


Route::prefix('admumum')->group(function () {
    Route::patch('village-regulation/{id}/restore', [VillageRegulationController::class, 'restore'])->name('village-regulations.restore');
    Route::patch('village-decree/{id}/restore', [VillageDecreeController::class, 'restore'])->name('village-decrees.restore');
    Route::patch('village-asset/{id}/restore', [VillageAssetController::class, 'restore'])->name('village-assets.restore');
    Route::patch('village-official/{id}/restore', [VillageOfficialController::class, 'restore'])->name('village-officials.restore');
    Route::patch('village-treasury-land/{id}/restore', [VillageTreasuryLandController::class, 'restore'])->name('village-treasury-lands.restore');
    Route::patch('village-publication/{id}/restore', [VillagePublicationController::class, 'restore'])->name('village-publications.restore');
});

Route::prefix('arsip')->group(function () {
    Route::patch('agenda/{id}/restore', [AgendaEntryController::class, 'restore'])->name('agenda.restore');
});
