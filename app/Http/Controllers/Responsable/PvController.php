<?php

namespace App\Http\Controllers\Responsable;

use App\Http\Controllers\Controller;
use App\Models\Pv;
use Illuminate\Http\Request;

class PvController extends Controller
{
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
        $pv->observations = trim(($pv->observations ? $pv->observations . "\n\n" : '') . '[Refus] ' . $validated['commentaire']);
        $pv->save();

        return redirect()->route('responsable.dashboard')
                         ->with('success', 'PV refusé et renvoyé à la secrétaire');
    }
}
