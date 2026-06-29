<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use App\Models\Jury;
use App\Models\Soutenance;
use App\Services\MailService;
use Illuminate\Http\Request;

class JuryController extends Controller
{
    public function __construct(protected MailService $mailService) {}

    /**
     * Ajouter un enseignant au jury d'une soutenance.
     */
    public function store(Request $request, Soutenance $soutenance)
    {
        $validated = $request->validate([
            'utilisateur_id' => 'required|exists:users,id',
            'role' => 'required|in:president,directeur,rapporteur,membre',
        ]);

        $jury = $soutenance->jury()->create($validated);

        $this->mailService->sendInvitationJury($jury->utilisateur, $soutenance);

        return redirect()->route('soutenances.show', $soutenance)
            ->with('success', 'Enseignant ajouté au jury, invitation envoyée');
    }

    /**
     * Retirer un membre du jury.
     */
    public function destroy(Jury $jury)
    {
        $soutenance = $jury->soutenance;
        $jury->delete();

        return redirect()->route('soutenances.show', $soutenance)
            ->with('success', 'Membre retiré du jury');
    }
}
