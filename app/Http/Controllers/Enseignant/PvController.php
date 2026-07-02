<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Pv;
use App\Models\Soutenance;
use Illuminate\Http\Request;

class PvController extends Controller
{
    /**
     * Affiche le formulaire de création d'un PV.
     */
    public function create($soutenanceId)
    {
        $soutenance = Soutenance::with(['etudiant', 'directeur', 'salle'])
                                 ->findOrFail($soutenanceId);
        
        // Vérifier que l'enseignant est bien le directeur de cette soutenance
        if ($soutenance->directeur_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à saisir ce PV.');
        }

        // Vérifier qu'un PV n'existe pas déjà
        if ($soutenance->pv) {
            return redirect()->route('enseignant.soutenances.index')
                             ->with('warning', 'Un PV existe déjà pour cette soutenance.');
        }

        return view('enseignant.pv.create', compact('soutenance'));
    }

    /**
     * Enregistre un nouveau PV.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'soutenance_id' => 'required|exists:soutenances,id',
            'note' => 'required|numeric|min:0|max:20',
            'observations' => 'nullable|string|max:1000',
        ]);

        $soutenance = Soutenance::findOrFail($validated['soutenance_id']);
        
        // Vérifier que l'enseignant est bien le directeur
        if ($soutenance->directeur_id !== auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        // Vérifier qu'un PV n'existe pas déjà
        if ($soutenance->pv) {
            return redirect()->route('enseignant.soutenances.index')
                             ->with('warning', 'Un PV existe déjà pour cette soutenance.');
        }

        // Créer le PV
        $pv = Pv::create([
            'soutenance_id' => $validated['soutenance_id'],
            'note' => $validated['note'],
            'mention' => Pv::calculerMention($validated['note']),
            'observations' => $validated['observations'] ?? null,
            'status' => 'brouillon',
        ]);

        // Mettre à jour le statut de la soutenance
        $soutenance->statut = 'realisee';
        $soutenance->save();

        return redirect()->route('enseignant.soutenances.index')
                         ->with('success', 'PV enregistré avec succès. Note : ' . $validated['note'] . '/20');
    }

    /**
     * Affiche le formulaire d'édition d'un PV.
     */
    public function edit($id)
    {
        $pv = Pv::with('soutenance.etudiant')->findOrFail($id);
        
        // Vérifier que l'enseignant est bien le directeur
        if ($pv->soutenance->directeur_id !== auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        return view('enseignant.pv.edit', compact('pv'));
    }

    /**
     * Met à jour un PV existant.
     */
    public function update(Request $request, $id)
    {
        $pv = Pv::with('soutenance')->findOrFail($id);
        
        // Vérifier que l'enseignant est bien le directeur
        if ($pv->soutenance->directeur_id !== auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        $validated = $request->validate([
            'note' => 'required|numeric|min:0|max:20',
            'observations' => 'nullable|string|max:1000',
        ]);

        $pv->update([
            'note' => $validated['note'],
            'mention' => Pv::calculerMention($validated['note']),
            'observations' => $validated['observations'] ?? null,
        ]);

        return redirect()->route('enseignant.soutenances.index')
                         ->with('success', 'PV mis à jour avec succès.');
    }

    /**
     * Affiche le détail d'un PV.
     */
    public function show($id)
    {
        $pv = Pv::with('soutenance.etudiant')->findOrFail($id);
        return view('enseignant.pv.show', compact('pv'));
    }
}