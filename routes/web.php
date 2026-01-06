<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\EmploiController;

Route::get('/', fn () => redirect()->route('etudiants.index'));

Route::resource('etudiants', EtudiantController::class);
Route::resource('enseignants', EnseignantController::class)->only(['index']);
Route::resource('groups', GroupController::class)->only(['index']);

Route::get('/presences', [PresenceController::class, 'index'])->name('presences.index');
Route::post('/presences', [PresenceController::class, 'store'])->name('presences.store');

Route::get('/presences/export/excel', [PresenceController::class, 'exportExcel'])->name('presences.export.excel');
Route::get('/presences/export/pdf',   [PresenceController::class, 'exportPdf'])->name('presences.export.pdf');

// Emploi du temps
Route::get('/emploi', [EmploiController::class, 'index'])->name('emploi.index');
Route::post('/emploi', [EmploiController::class, 'store'])->name('emploi.store');
Route::put('/emploi/{emploi}', [EmploiController::class, 'update'])->name('emploi.update');
Route::delete('/emploi/{emploi}', [EmploiController::class, 'destroy'])->name('emploi.destroy');

// Export PDF emploi
Route::get('/emploi/export/pdf', [EmploiController::class, 'exportPdf'])->name('emploi.export.pdf');
