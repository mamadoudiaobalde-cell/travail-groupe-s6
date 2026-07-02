<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Indisponibilite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndisponibiliteController extends Controller
{
    /**
     * Liste des indisponibilités de l'enseignant connecté.
     */
    public function index()
    {
        $indisponibilites = Indisponibilite::where('utilisateur_id', Auth::id())
            ->orderBy('date_debut')
            ->get();

        return view('enseignant.indisponibilites.index', compact('indisponibilites'));
    }

    /**
     * Déclarer une nouvelle indisponibilité.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'motif' => 'nullable|string|max:255',
        ]);

        $validated['utilisateur_id'] = Auth::id();

        Indisponibilite::create($validated);

        return redirect()->route('enseignant.dashboard')
            ->with('success', 'Indisponibilité déclarée avec succès');
    }

    /**
     * Modifier une indisponibilité (uniquement la sienne).
     */
    public function update(Request $request, Indisponibilite $indisponibilite)
    {
        abort_if($indisponibilite->utilisateur_id !== Auth::id(), 403);

        $validated = $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'motif' => 'nullable|string|max:255',
        ]);

        $indisponibilite->update($validated);

        return redirect()->route('enseignant.dashboard')
            ->with('success', 'Indisponibilité modifiée avec succès');
    }

    /**
     * Supprimer une indisponibilité (uniquement la sienne).
     */
    public function destroy(Indisponibilite $indisponibilite)
    {
        abort_if($indisponibilite->utilisateur_id !== Auth::id(), 403);

        $indisponibilite->delete();

        return redirect()->route('enseignant.dashboard')
            ->with('success', 'Indisponibilité supprimée avec succès');
    }
}
