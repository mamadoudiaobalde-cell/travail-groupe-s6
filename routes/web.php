<?php

use App\Http\Controllers\Admin\AuditController;
use App\Http\Controllers\Admin\ConfigController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SalleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\Enseignant\DashboardController;
use App\Http\Controllers\Enseignant\IndisponibiliteController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Secretaire\JuryController;
use App\Http\Controllers\Secretaire\PvController;
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
// DOCUMENTS & NOTIFICATIONS (tous rôles authentifiés)
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
    Route::resource('/salles', SalleController::class);

    // Audit et Configuration
    Route::get('/audit', [AuditController::class, 'index'])->name('admin.audit');
    Route::delete('/audit/clean', [AuditController::class, 'clean'])->name('admin.audit.clean');
    Route::get('/config', [ConfigController::class, 'index'])->name('admin.config');
    Route::put('/config', [ConfigController::class, 'update'])->name('admin.config.update');
});

// ==========================================
// SECRETAIRE - Routes protégées par rôle
// ==========================================
Route::middleware(['auth', 'verified', 'role:secretaire_pedagogique,administrateur'])->prefix('secretaire')->group(function () {
    Route::get('/dashboard', function () {
        return view('secretaire.dashboard');
    })->name('secretaire.dashboard');

    // Soutenances (CRUD + confirm/cancel)
    Route::resource('/soutenances', SoutenanceController::class);
    Route::put('/soutenances/{soutenance}/confirm', [SoutenanceController::class, 'confirm'])->name('secretaire.soutenances.confirm');
    Route::put('/soutenances/{soutenance}/cancel', [SoutenanceController::class, 'cancel'])->name('secretaire.soutenances.cancel');

    // Jury
    Route::post('/soutenances/{soutenance}/jury', [JuryController::class, 'store'])->name('secretaire.jury.store');
    Route::delete('/jury/{jury}', [JuryController::class, 'destroy'])->name('secretaire.jury.destroy');

    // PV
    Route::post('/soutenances/{soutenance}/pv', [PvController::class, 'store'])->name('secretaire.pv.store');
    Route::put('/pv/{pv}', [PvController::class, 'update'])->name('secretaire.pv.update');
    Route::put('/pv/{pv}/submit', [PvController::class, 'submitForValidation'])->name('secretaire.pv.submit');
    Route::get('/pv/{pv}/pdf', [PvController::class, 'generatePdf'])->name('secretaire.pv.pdf');
});

// ==========================================
// ENSEIGNANT - Routes protégées par rôle
// ==========================================
Route::middleware(['auth', 'verified', 'role:enseignant'])->prefix('enseignant')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('enseignant.dashboard');

    // Jury (confirmation/refus de participation)
    Route::get('/jury', [App\Http\Controllers\Enseignant\JuryController::class, 'index'])->name('enseignant.jury');
    Route::put('/jury/{jury}/confirm', [App\Http\Controllers\Enseignant\JuryController::class, 'confirm'])->name('enseignant.jury.confirm');
    Route::put('/jury/{jury}/decline', [App\Http\Controllers\Enseignant\JuryController::class, 'decline'])->name('enseignant.jury.decline');

    // PV (consultation)
    Route::get('/pv/{soutenance}/create', [App\Http\Controllers\Enseignant\PvController::class, 'create'])->name('enseignant.pv.create');
    Route::post('/pv', [App\Http\Controllers\Enseignant\PvController::class, 'store'])->name('enseignant.pv.store');
    Route::get('/pv/{pv}/edit', [App\Http\Controllers\Enseignant\PvController::class, 'edit'])->name('enseignant.pv.edit');
    Route::put('/pv/{pv}', [App\Http\Controllers\Enseignant\PvController::class, 'update'])->name('enseignant.pv.update');
    Route::get('/pv/{pv}', [App\Http\Controllers\Enseignant\PvController::class, 'show'])->name('enseignant.pv.show');

    // Indisponibilités
    Route::get('/indisponibilites', [IndisponibiliteController::class, 'index'])->name('enseignant.indisponibilites.index');
    Route::post('/indisponibilites', [IndisponibiliteController::class, 'store'])->name('enseignant.indisponibilites.store');
    Route::put('/indisponibilites/{indisponibilite}', [IndisponibiliteController::class, 'update'])->name('enseignant.indisponibilites.update');
    Route::delete('/indisponibilites/{indisponibilite}', [IndisponibiliteController::class, 'destroy'])->name('enseignant.indisponibilites.destroy');
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

    // PV (validation/refus)
    Route::put('/pv/{pv}/validate', [App\Http\Controllers\Responsable\PvController::class, 'validatePv'])->name('responsable.pv.validate');
    Route::put('/pv/{pv}/reject', [App\Http\Controllers\Responsable\PvController::class, 'reject'])->name('responsable.pv.reject');
});

// ==========================================
// AUTHENTIFICATION (Breeze)
// ==========================================
require __DIR__.'/auth.php';
