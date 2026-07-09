<?php

namespace App\Http\Controllers\Api\Secretaire;

use App\Http\Controllers\Controller;
use App\Http\Resources\JuryResource;
use App\Models\Jury;
use App\Models\Soutenance;
use App\Services\AuditService;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JuryController extends Controller
{
    public function __construct(
        protected MailService $mailService,
        protected AuditService $auditService,
    ) {}

    public function store(Request $request, Soutenance $soutenance)
    {
        $validated = $request->validate([
            'utilisateur_id' => 'required|exists:users,id',
            'role' => 'required|in:president,directeur,rapporteur,membre',
        ]);

        $jury = $soutenance->jury()->create($validated);
        $this->mailService->sendInvitationJury($jury->utilisateur, $soutenance);
        $this->auditService->log(Auth::id(), 'jury.store', "Membre #{$jury->utilisateur_id} ajouté au jury de la soutenance #{$soutenance->id}");

        return (new JuryResource($jury->load('utilisateur')))->response()->setStatusCode(201);
    }

    public function destroy(Jury $jury)
    {
        $soutenanceId = $jury->soutenance_id;
        $jury->delete();
        $this->auditService->log(Auth::id(), 'jury.destroy', "Membre retiré du jury de la soutenance #{$soutenanceId}");

        return response()->json(['message' => 'Membre retiré du jury.']);
    }
}
