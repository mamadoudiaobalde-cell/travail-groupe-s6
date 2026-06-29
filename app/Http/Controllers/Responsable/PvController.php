<?php

namespace App\Http\Controllers\Responsable;

use App\Http\Controllers\Controller;
use App\Models\Pv;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PvController extends Controller
{
    public function __construct(protected AuditService $auditService) {}

    /**
     * Valider un PV soumis par la secrétaire.
     */
    public function validatePv(Pv $pv)
    {
        if ($pv->status !== 'en_validation') {
            return redirect()->back()->with('error', 'Seul un PV en validation peut être validé');
        }

        $pv->status = 'valide';
        $pv->save();

        $this->auditService->log(Auth::id(), 'pv.validate', "PV #{$pv->id} validé");

        return redirect()->route('responsable.dashboard')
            ->with('success', 'PV validé');
    }

    /**
     * Refuser un PV soumis par la secrétaire (commentaire obligatoire).
     */
    public function reject(Request $request, Pv $pv)
    {
        if ($pv->status !== 'en_validation') {
            return redirect()->back()->with('error', 'Seul un PV en validation peut être refusé');
        }

        $validated = $request->validate([
            'commentaire' => 'required|string',
        ]);

        $pv->status = 'brouillon';
        $pv->observations = trim(($pv->observations ? $pv->observations."\n\n" : '').'[Refus] '.$validated['commentaire']);
        $pv->save();

        $this->auditService->log(Auth::id(), 'pv.reject', "PV #{$pv->id} refusé : {$validated['commentaire']}");

        return redirect()->route('responsable.dashboard')
            ->with('success', 'PV refusé et renvoyé à la secrétaire');
    }
}
