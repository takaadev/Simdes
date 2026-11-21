<?php

use App\Http\Controllers\Archieves\AgendaEntryController;
use App\Http\Controllers\Archieves\ExpeditionEntryController;
use App\Http\Controllers\Generals\VillageAssetController;
use App\Http\Controllers\Generals\VillageDecreeController;
use App\Http\Controllers\Generals\VillageLandController;
use App\Http\Controllers\Generals\VillageOfficialController;
use App\Http\Controllers\Generals\VillagePublicationController;
use App\Http\Controllers\Generals\VillageRegulationController;
use App\Http\Controllers\Generals\VillageTreasuryLandController;
use App\Http\Controllers\Residents\FamilyCardController;
use App\Http\Controllers\Residents\RecaptResidentController;
use App\Http\Controllers\Residents\ResidentController;
use App\Http\Controllers\Residents\ResidentMutationController;
use App\Http\Controllers\Residents\TemporaryResidentController;
use App\Http\Controllers\Settings\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('admumum')->group(function () {
    Route::patch('village-regulation/{id}/restore', [VillageRegulationController::class, 'restore'])->name('village-regulations.restore');
    Route::patch('village-decree/{id}/restore', [VillageDecreeController::class, 'restore'])->name('village-decrees.restore');
    Route::patch('village-asset/{id}/restore', [VillageAssetController::class, 'restore'])->name('village-assets.restore');
    Route::patch('village-official/{id}/restore', [VillageOfficialController::class, 'restore'])->name('village-officials.restore');
    Route::patch('village-treasury-land/{id}/restore', [VillageTreasuryLandController::class, 'restore'])->name('village-treasury-lands.restore');
    Route::patch('village-land/{id}/restore', [VillageLandController::class, 'restore'])->name('village-lands.restore');
    Route::patch('village-publication/{id}/restore', [VillagePublicationController::class, 'restore'])->name('village-publications.restore');
    // Printer
    Route::get('village-regulations/print', [VillageRegulationController::class, 'print'])->name('village-regulations.print');
    Route::get('village-decrees/print', [VillageDecreeController::class, 'print'])->name('village-decrees.print');
    Route::get('village-assets/print', [VillageAssetController::class, 'print'])->name('village-assets.print');
    Route::get('village-officials/print', [VillageOfficialController::class, 'print'])->name('village-officials.print');
    Route::get('village-treasury-lands/print', [VillageTreasuryLandController::class, 'print'])->name('village-treasury-lands.print');
    Route::get('village-lands/print', [VillageLandController::class, 'print'])->name('village-lands.print');
    Route::get('village-publications/print', [VillagePublicationController::class, 'print'])->name('village-publications.print');
    Route::get('expeditions/print', [ExpeditionEntryController::class, 'print'])->name('expeditions.print');
});

Route::prefix('admpenduduk')->group(function () {
    Route::patch('residents/{id}/restore', [ResidentController::class, 'restore'])->name('residents.restore');
    Route::post('residents/import-excel', [ResidentController::class, 'importexcel'])->name('residents.importexcel');
    Route::patch('mutations/{id}/restore', [ResidentMutationController::class, 'restore'])->name('mutations.restore');
    Route::post('mutations/outgoing', [ResidentMutationController::class, 'mutationOutgoing'])->name('mutations.outgoing');
    Route::patch('temporaryresidents/{id}/restore', [TemporaryResidentController::class, 'restore'])->name('temporaryresidents.restore');
    Route::put('temporaryresidents/{id}/outgoing', [TemporaryResidentController::class, 'outgoingConfirmed'])->name('temporaryresidents.outgoing-confirmed');
    Route::patch('familycards/{id}/restore', [FamilyCardController::class, 'restore'])->name('familycards.restore');
    Route::post('familycards/mutation', [FamilyCardController::class, 'mutation'])->name('familycards.mutation');
    Route::match(['put', 'patch'], 'recapts/{detail}/update-note', [RecaptResidentController::class, 'updateNote'])->name('recapts.update-note');
    Route::get('residents/print', [ResidentController::class, 'print'])->name('residents.print');
});
Route::prefix('arsip')->group(function () {
    Route::patch('agenda/{id}/restore', [AgendaEntryController::class, 'restore'])->name('agendas.restore');
    Route::post('agendas/generate-number', [AgendaEntryController::class, 'generateNumber'])->name('agendas.generate-number');
    Route::patch('ekspedisi/{id}/restore', [ExpeditionEntryController::class, 'restore'])->name('expeditions.restore');
    Route::get('expeditions/print', [ExpeditionEntryController::class, 'print'])->name('expeditions.print');
    Route::get('agendas/print', [AgendaEntryController::class, 'print'])->name('agendas.print');
});


Route::prefix('pengaturan')->group(function () {
    Route::patch('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
});
