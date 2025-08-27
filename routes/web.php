<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OffreEmploisController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\JeuneController as AdminLocalJeunesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// les routes vers les pages des utilisateurs

Route::get('/employeurs/dashboard', function() {
    return view('pages.employeurs.home');
    })->name('pages.employeurs.dashboard')->middleware(['role:employeur', 'status:actif']);

Route::get('/admins/local/dashboard', function() {
    return view('pages.admins.local.dashboard');
    })->name('pages.admins.local.dashboard')->middleware(['role:admin_local', 'status:actif']);

Route::get('/admins/national/dashboard', function() {
    return view('pages.admins.national.dashboard');
    })->name('pages.admins.national.dashboard')->middleware(['role:admin_national', 'status:actif']);

    
// Routes pour les offres d'emplois
// Admin local - gestion Jeunes (menu milieu)
Route::middleware(['auth', 'role:admin_local', 'status:actif'])->group(function () {
    Route::get('/admins/local/jeunes', [AdminLocalJeunesController::class, 'indexAdminLocal'])
        ->name('admin.local.jeunes.index');
    Route::get('/admins/local/jeunes/table', [AdminLocalJeunesController::class, 'tableAdminLocal'])
        ->name('admin.local.jeunes.table');
    Route::get('/admins/local/jeunes/create', [AdminLocalJeunesController::class, 'createAdminLocal'])
        ->name('admin.local.jeunes.create');
    Route::post('/admins/local/jeunes', [AdminLocalJeunesController::class, 'storeAdminLocal'])
        ->name('admin.local.jeunes.store');
    Route::get('/admins/local/jeunes/{jeune}', [AdminLocalJeunesController::class, 'showAdminLocal'])
        ->name('admin.local.jeunes.show');
    Route::get('/admins/local/jeunes/{jeune}/edit', [AdminLocalJeunesController::class, 'editAdminLocal'])
        ->name('admin.local.jeunes.edit');
    Route::put('/admins/local/jeunes/{jeune}', [AdminLocalJeunesController::class, 'updateAdminLocal'])
        ->name('admin.local.jeunes.update');
    Route::delete('/admins/local/jeunes/{jeune}', [AdminLocalJeunesController::class, 'destroyAdminLocal'])
        ->name('admin.local.jeunes.destroy');
    Route::post('/admins/local/jeunes/{jeune}/status', [AdminLocalJeunesController::class, 'updateStatusAdminLocal'])
        ->name('admin.local.jeunes.status');
});
Route::middleware(['auth', 'status:actif'])->group(function () {
    // Routes accessibles à tous les utilisateurs authentifiés
    Route::get('/offres-emplois', [OffreEmploisController::class, 'index'])->name('offres-emplois.index');
    Route::get('/offres-emplois/create', [OffreEmploisController::class, 'create'])->name('offres-emplois.create');
    Route::post('/offres-emplois', [OffreEmploisController::class, 'store'])->name('offres-emplois.store');
    Route::get('/offres-emplois/{offreEmplois}', [OffreEmploisController::class, 'show'])->name('offres-emplois.show');
    
    // Routes protégées pour les employeurs et admins (même groupe, inutile de re-nicher auth/status)
    Route::get('/offres-emplois/{offreEmplois}/edit', [OffreEmploisController::class, 'edit'])->name('offres-emplois.edit');
    Route::put('/offres-emplois/{offreEmplois}', [OffreEmploisController::class, 'update'])->name('offres-emplois.update');
    Route::delete('/offres-emplois/{offreEmplois}', [OffreEmploisController::class, 'destroy'])->name('offres-emplois.destroy');
});

// Routes pour les jeunes
Route::middleware(['auth', 'role:jeune', 'status:actif'])->prefix('jeunes')->name('jeunes.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\JeuneController::class, 'dashboard'])->name('dashboard');
    Route::get('/offres', [App\Http\Controllers\JeuneController::class, 'offres'])->name('offres');
    Route::get('/offres/{offre}', [App\Http\Controllers\JeuneController::class, 'showOffre'])->name('offres.show');
    Route::get('/offres/{offre}/candidature', [App\Http\Controllers\JeuneController::class, 'candidature'])->name('offres.candidature');
    Route::post('/offres/{offre}/postuler', [App\Http\Controllers\JeuneController::class, 'postuler'])->name('offres.postuler');
    Route::get('/candidatures', [App\Http\Controllers\JeuneController::class, 'candidatures'])->name('candidatures');
    Route::get('/candidatures/{postulation}', [App\Http\Controllers\JeuneController::class, 'showCandidature'])->name('candidatures.show');
    Route::post('/candidatures/{postulation}/annuler', [App\Http\Controllers\JeuneController::class, 'annulerCandidature'])->name('candidatures.annuler');
    Route::get('/documents', [App\Http\Controllers\JeuneController::class, 'documents'])->name('documents');
    Route::post('/documents/upload', [App\Http\Controllers\JeuneController::class, 'uploadDocument'])->name('documents.upload');
    Route::delete('/documents/{document}', [App\Http\Controllers\JeuneController::class, 'supprimerDocument'])->name('documents.supprimer');
    Route::get('/documents/{document}/telecharger', [App\Http\Controllers\JeuneController::class, 'telechargerDocument'])->name('documents.telecharger');
});

// Routes pour les statistiques
Route::middleware(['auth', 'status:actif'])->prefix('statistiques')->name('statistiques.')->group(function () {
    // Statistiques générales (pour tous les utilisateurs)
    Route::get('/generales', [StatistiqueController::class, 'statistiquesGenerales'])->name('generales');
    
    // Statistiques employeur
    Route::middleware(['role:employeur'])->group(function () {
        Route::get('/employeur', [StatistiqueController::class, 'statistiquesEmployeur'])->name('employeur');
        Route::get('/employeur/{employeurId}', [StatistiqueController::class, 'statistiquesEmployeur'])->name('employeur.show');
    });
    
    // Statistiques jeune
    Route::middleware(['role:jeune'])->group(function () {
        Route::get('/jeune', [StatistiqueController::class, 'statistiquesJeune'])->name('jeune');
        Route::get('/jeune/{jeuneId}', [StatistiqueController::class, 'statistiquesJeune'])->name('jeune.show');
    });
    
    // Statistiques locales (admin local)
    Route::middleware(['role:admin_local'])->group(function () {
        Route::get('/locales', [StatistiqueController::class, 'statistiquesLocales'])->name('locales');
        Route::get('/locales/{region}', [StatistiqueController::class, 'statistiquesLocales'])->name('locales.region');
    });
    
    // Statistiques nationales (admin national)
    Route::middleware(['role:admin_national'])->group(function () {
        Route::get('/nationales', [StatistiqueController::class, 'statistiquesNationales'])->name('nationales');
    });
});

// Admin national - gestion Jeunes
Route::middleware(['auth', 'role:admin_national', 'status:actif'])->group(function () {
    Route::get('/admins/national/jeunes', [\App\Http\Controllers\JeuneController::class, 'indexAdminNational'])
        ->name('admin.national.jeunes.index');
    Route::get('/admins/national/jeunes/table', [\App\Http\Controllers\JeuneController::class, 'tableAdminNational'])
        ->name('admin.national.jeunes.table');
    Route::get('/admins/national/jeunes/{jeune}', [\App\Http\Controllers\JeuneController::class, 'showAdminNational'])
        ->name('admin.national.jeunes.show');
    Route::get('/admins/national/documents/{document}/telecharger', [\App\Http\Controllers\JeuneController::class, 'telechargerDocumentAdminNational'])
        ->name('admin.national.documents.telecharger');
});

require __DIR__.'/auth.php';
