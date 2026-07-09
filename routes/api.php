<?php

use App\Http\Controllers\Api\Admin\AuditController;
use App\Http\Controllers\Api\Admin\SalleController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\Enseignant\IndisponibiliteController;
use App\Http\Controllers\Api\Enseignant\JuryController as EnseignantJuryController;
use App\Http\Controllers\Api\Enseignant\SoutenanceController as EnseignantSoutenanceController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\Responsable\ExportController as ResponsableExportController;
use App\Http\Controllers\Api\Responsable\PvController as ResponsablePvController;
use App\Http\Controllers\Api\Secretaire\JuryController as SecretaireJuryController;
use App\Http\Controllers\Api\Secretaire\PvController as SecretairePvController;
use App\Http\Controllers\Api\Secretaire\SoutenanceController;
use Illuminate\Support\Facades\Route;

// ==========================================
// AUTHENTIFICATION (publique)
// ==========================================
Route::post('/login', [AuthController::class, 'login']);

// ==========================================
// ROUTES PROTÉGÉES PAR TOKEN SANCTUM
// ==========================================
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Documents & Notifications (tous rôles authentifiés)
    Route::get('/documents/{document}/download', [DocumentController::class, 'download']);
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::put('/notifications/{notification}/read', [NotificationController::class, 'markAsRead']);

    // ==========================================
    // ADMIN
    // ==========================================
    Route::middleware('role:administrateur')->prefix('admin')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('salles', SalleController::class);
        Route::get('audit', [AuditController::class, 'index']);
        Route::delete('audit/clean', [AuditController::class, 'clean']);
    });

    // ==========================================
    // SECRÉTAIRE (+ administrateur)
    // ==========================================
    Route::middleware('role:secretaire_pedagogique,administrateur')->prefix('secretaire')->group(function () {
        Route::apiResource('soutenances', SoutenanceController::class);
        Route::put('soutenances/{soutenance}/confirm', [SoutenanceController::class, 'confirm']);
        Route::put('soutenances/{soutenance}/cancel', [SoutenanceController::class, 'cancel']);
        Route::post('soutenances/{soutenance}/jury', [SecretaireJuryController::class, 'store']);
        Route::delete('jury/{jury}', [SecretaireJuryController::class, 'destroy']);
        Route::post('soutenances/{soutenance}/pv', [SecretairePvController::class, 'store']);
        Route::put('pv/{pv}', [SecretairePvController::class, 'update']);
        Route::put('pv/{pv}/submit', [SecretairePvController::class, 'submitForValidation']);
        Route::get('pv/{pv}/pdf', [SecretairePvController::class, 'generatePdf']);
    });

    // ==========================================
    // ENSEIGNANT
    // ==========================================
    Route::middleware('role:enseignant')->prefix('enseignant')->group(function () {
        Route::get('soutenances', [EnseignantSoutenanceController::class, 'index']);
        Route::get('jury', [EnseignantJuryController::class, 'index']);
        Route::put('jury/{jury}/confirm', [EnseignantJuryController::class, 'confirm']);
        Route::put('jury/{jury}/decline', [EnseignantJuryController::class, 'decline']);
        Route::apiResource('indisponibilites', IndisponibiliteController::class)->except(['show']);
    });

    // ==========================================
    // RESPONSABLE PÉDAGOGIQUE
    // ==========================================
    Route::middleware('role:responsable_pedagogique')->prefix('responsable')->group(function () {
        Route::get('pv', [ResponsablePvController::class, 'index']);
        Route::put('pv/{pv}/validate', [ResponsablePvController::class, 'validatePv']);
        Route::put('pv/{pv}/reject', [ResponsablePvController::class, 'reject']);
        Route::get('export/{format}', [ResponsableExportController::class, 'export']);
    });

    // ==========================================
    // ÉTUDIANT
    // ==========================================
    Route::middleware('role:etudiant')->prefix('etudiant')->group(function () {
        Route::get('soutenances', [SoutenanceController::class, 'indexEtudiant']);
    });
});
