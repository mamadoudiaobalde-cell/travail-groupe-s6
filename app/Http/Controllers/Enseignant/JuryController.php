<?php
namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Jury;
use App\Services\AuditService;
use Illuminate\Support\Facades\Auth;

class JuryController extends Controller
{
    public function __construct(protected AuditService $auditService) {}

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
     * Confirmer sa participation au jury.
     */
    public function confirm($id)
    {
        $jury = Jury::findOrFail($id);
        
        // Vérifier que l'utilisateur connecté est bien le membre du jury
        if ($jury->utilisateur_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à confirmer ce jury.');
        }

        $jury->statut_confirmation = 'confirme';
        $jury->save();

        // Log de l'action
        $this->auditService->log(
            Auth::id(), 
            'jury.confirm', 
            "Participation confirmée au jury de la soutenance #{$jury->soutenance_id}"
        );

        return redirect()->route('enseignant.dashboard')
            ->with('success', 'Participation au jury confirmée avec succès.');
    }

    /**
     * Refuser sa participation au jury.
     */
    public function decline($id)
    {
        $jury = Jury::findOrFail($id);
        
        // Vérifier que l'utilisateur connecté est bien le membre du jury
        if ($jury->utilisateur_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à refuser ce jury.');
        }

        $jury->statut_confirmation = 'refuse';
        $jury->save();

        // Log de l'action
        $this->auditService->log(
            Auth::id(), 
            'jury.decline', 
            "Participation refusée au jury de la soutenance #{$jury->soutenance_id}"
        );

        return redirect()->route('enseignant.dashboard')
            ->with('success', 'Participation au jury refusée avec succès.');
    }
}