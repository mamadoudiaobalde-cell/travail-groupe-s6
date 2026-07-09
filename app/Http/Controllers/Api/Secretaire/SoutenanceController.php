<?php

namespace App\Http\Controllers\Api\Secretaire;

use App\Http\Controllers\Controller;
use App\Http\Resources\SoutenanceResource;
use App\Models\Soutenance;
use App\Services\AuditService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SoutenanceController extends Controller
{
    public function __construct(
        protected AuditService $auditService,
        protected NotificationService $notificationService,
    ) {}

    public function index()
    {
        $soutenances = Soutenance::with(['etudiant', 'directeur', 'salle'])
            ->latest()
            ->paginate(15);

        return SoutenanceResource::collection($soutenances);
    }

    public function indexEtudiant()
    {
        $soutenances = Soutenance::with(['salle', 'jury.utilisateur', 'pv'])
            ->where('etudiant_id', Auth::id())
            ->latest()
            ->get();

        return SoutenanceResource::collection($soutenances);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'etudiant_id' => 'required|exists:users,id',
            'directeur_id' => 'required|exists:users,id',
            'titre' => 'required|string|max:255',
            'filiere' => 'required|string|max:255',
            'type' => 'required|in:licence,master,doctorat',
            'date' => 'nullable|date',
            'heure' => 'nullable|date_format:H:i',
            'salle_id' => 'nullable|exists:salles,id',
        ]);

        $soutenance = Soutenance::create($validated);
        $this->auditService->log(Auth::id(), 'soutenance.store', "Soutenance #{$soutenance->id} créée");

        return (new SoutenanceResource($soutenance->load(['etudiant', 'directeur', 'salle'])))
            ->response()->setStatusCode(201);
    }

    public function show(Soutenance $soutenance)
    {
        return new SoutenanceResource(
            $soutenance->load(['etudiant', 'directeur', 'salle', 'jury.utilisateur', 'pv'])
        );
    }

    public function update(Request $request, Soutenance $soutenance)
    {
        $validated = $request->validate([
            'titre' => 'sometimes|string|max:255',
            'filiere' => 'sometimes|string|max:255',
            'type' => 'sometimes|in:licence,master,doctorat',
            'date' => 'nullable|date',
            'heure' => 'nullable|date_format:H:i',
            'salle_id' => 'nullable|exists:salles,id',
            'directeur_id' => 'sometimes|exists:users,id',
        ]);

        $soutenance->update($validated);
        $this->auditService->log(Auth::id(), 'soutenance.update', "Soutenance #{$soutenance->id} mise à jour");

        return new SoutenanceResource($soutenance->load(['etudiant', 'directeur', 'salle']));
    }

    public function destroy(Soutenance $soutenance)
    {
        $soutenance->delete();
        $this->auditService->log(Auth::id(), 'soutenance.destroy', "Soutenance #{$soutenance->id} supprimée");

        return response()->json(['message' => 'Soutenance supprimée.']);
    }

    public function confirm(Soutenance $soutenance)
    {
        if (! $soutenance->salle_id) {
            return response()->json(['message' => 'Impossible de confirmer : aucune salle assignée.'], 422);
        }

        if ($soutenance->jury()->count() === 0) {
            return response()->json(['message' => 'Impossible de confirmer : aucun jury composé.'], 422);
        }

        $soutenance->statut = 'confirmee';
        $soutenance->save();

        $this->auditService->log(Auth::id(), 'soutenance.confirm', "Soutenance #{$soutenance->id} confirmée");

        return new SoutenanceResource($soutenance);
    }

    public function cancel(Soutenance $soutenance)
    {
        $soutenance->statut = 'annulee';
        $soutenance->save();

        $this->notificationService->notifierAnnulation($soutenance);
        $this->auditService->log(Auth::id(), 'soutenance.cancel', "Soutenance #{$soutenance->id} annulée");

        return new SoutenanceResource($soutenance);
    }
}
