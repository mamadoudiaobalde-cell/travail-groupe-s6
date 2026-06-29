<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\Enseignant\JuryController as EnseignantJuryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Responsable\PvController as ResponsablePvController;
use App\Http\Controllers\Secretaire\JuryController as SecretaireJuryController;
use App\Http\Controllers\Secretaire\PvController as SecretairePvController;
use App\Http\Controllers\Secretaire\SoutenanceController;
use Illuminate\Support\Facades\Route;

// ==========================================
// PAGE D'ACCUEIL - Redirection vers login
// ==========================================
Route::get('/', function () {
    return redirect()->route('login');
});

// ==========================================
// DASHBOARD
// ==========================================
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ==========================================
// PROFIL
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================================
// DOCUMENTS & NOTIFICATIONS - Accessibles à tout utilisateur authentifié
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::put('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});

// ==========================================
// ADMIN - Routes protégées par rôle
// ==========================================
Route::middleware(['auth', 'verified', 'role:administrateur'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('/users', UserController::class);
});

// ==========================================
// SECRETAIRE - Routes protégées par rôle
// ==========================================
Route::middleware(['auth', 'verified', 'role:secretaire_pedagogique,administrateur'])->prefix('secretaire')->group(function () {
    Route::get('/dashboard', function () {
        return view('secretaire.dashboard');
    })->name('secretaire.dashboard');
    Route::resource('/soutenances', SoutenanceController::class);
    Route::put('/soutenances/{soutenance}/confirm', [SoutenanceController::class, 'confirm'])->name('secretaire.soutenances.confirm');
    Route::put('/soutenances/{soutenance}/cancel', [SoutenanceController::class, 'cancel'])->name('secretaire.soutenances.cancel');

    Route::post('/soutenances/{soutenance}/jury', [SecretaireJuryController::class, 'store'])->name('secretaire.jury.store');
    Route::delete('/jury/{jury}', [SecretaireJuryController::class, 'destroy'])->name('secretaire.jury.destroy');

    Route::post('/soutenances/{soutenance}/pv', [SecretairePvController::class, 'store'])->name('secretaire.pv.store');
    Route::put('/pv/{pv}', [SecretairePvController::class, 'update'])->name('secretaire.pv.update');
    Route::put('/pv/{pv}/submit', [SecretairePvController::class, 'submitForValidation'])->name('secretaire.pv.submit');
    Route::get('/pv/{pv}/pdf', [SecretairePvController::class, 'generatePdf'])->name('secretaire.pv.pdf');
});

// ==========================================
// ENSEIGNANT - Routes protégées par rôle
// ==========================================
Route::middleware(['auth', 'verified', 'role:enseignant'])->prefix('enseignant')->group(function () {
    Route::get('/dashboard', function () {
        return view('enseignant.dashboard');
    })->name('enseignant.dashboard');

    Route::put('/jury/{jury}/confirm', [EnseignantJuryController::class, 'confirm'])->name('enseignant.jury.confirm');
    Route::put('/jury/{jury}/decline', [EnseignantJuryController::class, 'decline'])->name('enseignant.jury.decline');
});

// ==========================================
// ETUDIANT - Routes protégées par rôle
// ==========================================
Route::middleware(['auth', 'verified', 'role:etudiant'])->prefix('etudiant')->group(function () {
    Route::get('/dashboard', function () {
        return view('etudiant.dashboard');
    })->name('etudiant.dashboard');
});

// ==========================================
// RESPONSABLE - Routes protégées par rôle
// ==========================================
Route::middleware(['auth', 'verified', 'role:responsable_pedagogique'])->prefix('responsable')->group(function () {
    Route::get('/dashboard', function () {
        return view('responsable.dashboard');
    })->name('responsable.dashboard');

    Route::put('/pv/{pv}/validate', [ResponsablePvController::class, 'validatePv'])->name('responsable.pv.validate');
    Route::put('/pv/{pv}/reject', [ResponsablePvController::class, 'reject'])->name('responsable.pv.reject');
});

// ==========================================
// AUTHENTIFICATION (Breeze)
// ==========================================
require __DIR__.'/auth.php';
