<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
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
});

// ==========================================
// ENSEIGNANT - Routes protégées par rôle
// ==========================================
Route::middleware(['auth', 'verified', 'role:enseignant'])->prefix('enseignant')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Enseignant\DashboardController::class, 'index'])->name('enseignant.dashboard');
    
    // Routes Jury
    Route::get('/jury', [App\Http\Controllers\Enseignant\JuryController::class, 'index'])->name('enseignant.jury');
    Route::post('/jury/{id}/confirm', [App\Http\Controllers\Enseignant\JuryController::class, 'confirm'])->name('enseignant.jury.confirm');
    Route::post('/jury/{id}/refuse', [App\Http\Controllers\Enseignant\JuryController::class, 'refuse'])->name('enseignant.jury.refuse');
    
    // Routes PV
    Route::get('/pv/{soutenance}/create', [App\Http\Controllers\Enseignant\PvController::class, 'create'])->name('enseignant.pv.create');
    Route::post('/pv', [App\Http\Controllers\Enseignant\PvController::class, 'store'])->name('enseignant.pv.store');
    Route::get('/pv/{pv}/edit', [App\Http\Controllers\Enseignant\PvController::class, 'edit'])->name('enseignant.pv.edit');
    Route::put('/pv/{pv}', [App\Http\Controllers\Enseignant\PvController::class, 'update'])->name('enseignant.pv.update');
    Route::get('/pv/{pv}', [App\Http\Controllers\Enseignant\PvController::class, 'show'])->name('enseignant.pv.show');
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
});

// ==========================================
// AUTHENTIFICATION (Breeze)
// ==========================================
require __DIR__.'/auth.php';