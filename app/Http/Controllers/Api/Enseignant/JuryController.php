<?php

namespace App\Http\Controllers\Api\Enseignant;

use App\Http\Controllers\Controller;
use App\Http\Resources\JuryResource;
use App\Models\Jury;
use App\Services\AuditService;
use Illuminate\Support\Facades\Auth;

class JuryController extends Controller
{
    public function __construct(protected AuditService $auditService) {}

    public function index()
    {
        $jurys = Jury::where('utilisateur_id', Auth::id())
            ->with('soutenance.etudiant')
            ->latest()
            ->get();

        return JuryResource::collection($jurys);
    }

    public function confirm(Jury $jury)
    {
        abort_if($jury->utilisateur_id !== Auth::id(), 403, 'Action non autorisée.');

        $jury->statut_confirmation = 'confirme';
        $jury->save();

        $this->auditService->log(Auth::id(), 'jury.confirm', "Participation confirmée au jury #{$jury->id}");

        return new JuryResource($jury);
    }

    public function decline(Jury $jury)
    {
        abort_if($jury->utilisateur_id !== Auth::id(), 403, 'Action non autorisée.');

        $jury->statut_confirmation = 'refuse';
        $jury->save();

        $this->auditService->log(Auth::id(), 'jury.decline', "Participation refusée au jury #{$jury->id}");

        return new JuryResource($jury);
    }
}
