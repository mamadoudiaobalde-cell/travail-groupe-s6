<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Jury;
use Illuminate\Http\Request;

class JuryController extends Controller
{
    /**
     * Affiche la liste des jurys pour l'enseignant connecté.
     */
    public function index()
    {
        $jurys = Jury::where('utilisateur_id', auth()->id())
                     ->with('soutenance.etudiant')
                     ->orderBy('created_at', 'desc')
                     ->get();
        return view('enseignant.jury.index', compact('jurys'));
    }

    /**
     * Confirme la participation au jury.
     */
    public function confirm($id)
    {
        $jury = Jury::findOrFail($id);
        
        // Vérifier que le jury appartient à l'enseignant connecté
        if ($jury->utilisateur_id !== auth()->id()) {
            abort(403, 'Action non autorisée');
        }

        $jury->statut_confirmation = 'confirme';
        $jury->save();

        return redirect()->route('enseignant.jury.index')
                         ->with('success', 'Participation confirmée avec succès.');
    }

    /**
     * Refuse la participation au jury.
     */
    public function refuse($id)
    {
        $jury = Jury::findOrFail($id);
        
        // Vérifier que le jury appartient à l'enseignant connecté
        if ($jury->utilisateur_id !== auth()->id()) {
            abort(403, 'Action non autorisée');
        }

        $jury->statut_confirmation = 'refuse';
        $jury->save();

        return redirect()->route('enseignant.jury.index')
                         ->with('success', 'Participation refusée.');
    }
}