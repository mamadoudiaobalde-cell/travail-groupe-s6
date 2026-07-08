<?php

namespace App\Http\Controllers\Api\Responsable;

use App\Http\Controllers\Controller;
use App\Http\Resources\PvResource;
use App\Models\Pv;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PvController extends Controller
{
    public function __construct(protected AuditService $auditService) {}

    public function index()
    {
        $pvs = Pv::with(['soutenance.etudiant', 'soutenance.salle', 'soutenance.jury'])
            ->latest()
            ->paginate(15);

        return PvResource::collection($pvs);
    }

    public function validatePv(Pv $pv)
    {
        if ($pv->status !== 'en_validation') {
            return response()->json(['message' => 'Seul un PV en validation peut être validé.'], 422);
        }

        $pv->status = 'valide';
        $pv->save();

        $this->auditService->log(Auth::id(), 'pv.validate', "PV #{$pv->id} validé");

        return new PvResource($pv);
    }

    public function reject(Request $request, Pv $pv)
    {
        if ($pv->status !== 'en_validation') {
            return response()->json(['message' => 'Seul un PV en validation peut être refusé.'], 422);
        }

        $validated = $request->validate([
            'commentaire' => 'required|string',
        ]);

        $pv->status = 'brouillon';
        $pv->observations = trim(($pv->observations ? $pv->observations."\n\n" : '').'[Refus] '.$validated['commentaire']);
        $pv->save();

        $this->auditService->log(Auth::id(), 'pv.reject', "PV #{$pv->id} refusé");

        return new PvResource($pv);
    }
}
