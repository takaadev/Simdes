<?php

use App\Http\Controllers\Public\VillageAssetController;
use App\Http\Controllers\Public\VillageDecreeController;
use App\Http\Controllers\Public\VillageOfficialController;
use App\Http\Controllers\Public\VillageRegulationController;
use App\Http\Controllers\Public\VillageTreasuryLandController;
use Illuminate\Support\Facades\Route;


Route::prefix('admumum')->group(function () {
    Route::patch('village-regulation/{id}/restore', [VillageRegulationController::class, 'restore'])->name('village-regulations.restore');
    Route::patch('village-decree/{id}/restore', [VillageDecreeController::class, 'restore'])->name('village-decrees.restore');
    Route::patch('village-asset/{id}/restore', [VillageAssetController::class, 'restore'])->name('village-assets.restore');
    Route::patch('village-official/{id}/restore', [VillageOfficialController::class, 'restore'])->name('village-officials.restore');
    Route::patch('village-treasury-land/{id}/restore', [VillageTreasuryLandController::class, 'restore'])->name('village-treasury-lands.restore');
});
