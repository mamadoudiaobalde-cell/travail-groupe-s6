<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use App\Models\Soutenance;
use App\Models\User;
use App\Models\Salle;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class SoutenanceController extends Controller
{
    public function __construct(protected NotificationService $notificationService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $soutenances = Soutenance::with(['etudiant', 'directeur', 'salle'])->get();
        return view('secretaire.soutenances.index', compact('soutenances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $etudiants = User::where('role', 'etudiant')->get();
        $enseignants = User::where('role', 'enseignant')->get();
        $salles = Salle::where('actif', true)->get();
        
        return view('secretaire.soutenances.create', compact('etudiants', 'enseignants', 'salles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'etudiant_id' => 'required|exists:users,id',
            'directeur_id' => 'required|exists:users,id',
            'titre' => 'required|string|max:255',
            'filiere' => 'required|string|max:255',
            'type' => 'required|in:licence,master,doctorat',
            'date' => 'required|date|after:today',
            'heure' => 'required',
            'salle_id' => 'nullable|exists:salles,id',
            'statut' => 'sometimes|in:brouillon,planifiee,confirmee,realisee,annulee',
        ]);

        Soutenance::create($validated);

        return redirect()->route('soutenances.index')
                         ->with('success', 'Soutenance planifiée avec succès');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Soutenance $soutenance)
    {
        $etudiants = User::where('role', 'etudiant')->get();
        $enseignants = User::where('role', 'enseignant')->get();
        $salles = Salle::where('actif', true)->get();
        
        return view('secretaire.soutenances.edit', compact('soutenance', 'etudiants', 'enseignants', 'salles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Soutenance $soutenance)
    {
        $validated = $request->validate([
            'etudiant_id' => 'required|exists:users,id',
            'directeur_id' => 'required|exists:users,id',
            'titre' => 'required|string|max:255',
            'filiere' => 'required|string|max:255',
            'type' => 'required|in:licence,master,doctorat',
            'date' => 'required|date',
            'heure' => 'required',
            'salle_id' => 'nullable|exists:salles,id',
            'statut' => 'required|in:brouillon,planifiee,confirmee,realisee,annulee',
        ]);

        $soutenance->update($validated);

        return redirect()->route('soutenances.index')
                         ->with('success', 'Soutenance modifiée avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Soutenance $soutenance)
    {
        $soutenance->delete();

        return redirect()->route('soutenances.index')
                         ->with('success', 'Soutenance supprimée avec succès');
    }

    /**
     * Confirm a soutenance
     */
    public function confirm($id)
    {
        $soutenance = Soutenance::findOrFail($id);

        if (! $soutenance->salle_id) {
            return redirect()->back()->with('error', 'Impossible de confirmer : aucune salle n\'est assignée');
        }

        if ($soutenance->jury()->count() === 0) {
            return redirect()->back()->with('error', 'Impossible de confirmer : aucun jury n\'est composé');
        }

        $soutenance->statut = 'confirmee';
        $soutenance->save();

        return redirect()->route('soutenances.index')
                         ->with('success', 'Soutenance confirmée avec succès');
    }

    /**
     * Cancel a soutenance
     */
    public function cancel($id)
    {
        $soutenance = Soutenance::findOrFail($id);
        $soutenance->statut = 'annulee';
        $soutenance->save();

        $this->notificationService->notifierAnnulation($soutenance);

        return redirect()->route('soutenances.index')
                         ->with('success', 'Soutenance annulée avec succès');
    }
}