<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Jury;
use Illuminate\Support\Facades\Auth;

class JuryController extends Controller
{
    /**
     * Confirmer sa participation au jury.
     */
    public function confirm(Jury $jury)
    {
        abort_if($jury->utilisateur_id !== Auth::id(), 403);

        $jury->statut_confirmation = 'confirme';
        $jury->save();

        return redirect()->route('enseignant.dashboard')
            ->with('success', 'Participation au jury confirmée');
    }

    /**
     * Refuser sa participation au jury.
     */
    public function decline(Jury $jury)
    {
        abort_if($jury->utilisateur_id !== Auth::id(), 403);

        $jury->statut_confirmation = 'refuse';
        $jury->save();

        return redirect()->route('enseignant.dashboard')
            ->with('success', 'Participation au jury refusée');
    }
}
