<?php
<<<<<<< HEAD

=======
>>>>>>> origin/ibrahimadev
namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Jury;
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> origin/ibrahimadev
use App\Services\AuditService;
use Illuminate\Support\Facades\Auth;

class JuryController extends Controller
{
    public function __construct(protected AuditService $auditService) {}

    /**
<<<<<<< HEAD
     * Confirmer sa participation au jury.
     */
    public function confirm(Jury $jury)
    {
        abort_if($jury->utilisateur_id !== Auth::id(), 403);
=======
use Illuminate\Http\Request;

class JuryController extends Controller
{
    /**
=======
>>>>>>> origin/ibrahimadev
     * Affiche la liste des jurys pour l'enseignant connecté.
     */
    public function index()
    {
        $jurys = Jury::where('utilisateur_id', auth()->id())
                     ->with('soutenance.etudiant')
                     ->orderBy('created_at', 'desc')
                     ->get();
<<<<<<< HEAD
=======
        
>>>>>>> origin/ibrahimadev
        return view('enseignant.jury.index', compact('jurys'));
    }

    /**
<<<<<<< HEAD
     * Confirme la participation au jury.
=======
     * Confirmer sa participation au jury.
>>>>>>> origin/ibrahimadev
     */
    public function confirm($id)
    {
        $jury = Jury::findOrFail($id);
        
<<<<<<< HEAD
        // Vérifier que le jury appartient à l'enseignant connecté
        if ($jury->utilisateur_id !== auth()->id()) {
            abort(403, 'Action non autorisée');
        }
>>>>>>> layebara-tech
=======
        // Vérifier que l'utilisateur connecté est bien le membre du jury
        if ($jury->utilisateur_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à confirmer ce jury.');
        }
>>>>>>> origin/ibrahimadev

        $jury->statut_confirmation = 'confirme';
        $jury->save();

<<<<<<< HEAD
<<<<<<< HEAD
        $this->auditService->log(Auth::id(), 'jury.confirm', "Participation confirmée au jury de la soutenance #{$jury->soutenance_id}");

        return redirect()->route('enseignant.dashboard')
            ->with('success', 'Participation au jury confirmée');
=======
        // Log de l'action
        $this->auditService->log(
            Auth::id(), 
            'jury.confirm', 
            "Participation confirmée au jury de la soutenance #{$jury->soutenance_id}"
        );

        return redirect()->route('enseignant.dashboard')
            ->with('success', 'Participation au jury confirmée avec succès.');
>>>>>>> origin/ibrahimadev
    }

    /**
     * Refuser sa participation au jury.
     */
<<<<<<< HEAD
    public function decline(Jury $jury)
    {
        abort_if($jury->utilisateur_id !== Auth::id(), 403);
=======
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
>>>>>>> layebara-tech
=======
    public function decline($id)
    {
        $jury = Jury::findOrFail($id);
        
        // Vérifier que l'utilisateur connecté est bien le membre du jury
        if ($jury->utilisateur_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à refuser ce jury.');
        }
>>>>>>> origin/ibrahimadev

        $jury->statut_confirmation = 'refuse';
        $jury->save();

<<<<<<< HEAD
<<<<<<< HEAD
        $this->auditService->log(Auth::id(), 'jury.decline', "Participation refusée au jury de la soutenance #{$jury->soutenance_id}");

        return redirect()->route('enseignant.dashboard')
            ->with('success', 'Participation au jury refusée');
    }
}
=======
        return redirect()->route('enseignant.jury.index')
                         ->with('success', 'Participation refusée.');
    }
}
>>>>>>> layebara-tech
=======
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
>>>>>>> origin/ibrahimadev
