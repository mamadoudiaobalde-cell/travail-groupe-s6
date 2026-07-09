<?php

namespace App\Http\Controllers\Api\Secretaire;

use App\Http\Controllers\Controller;
use App\Http\Resources\PvResource;
use App\Models\Pv;
use App\Models\Soutenance;
use App\Services\AuditService;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PvController extends Controller
{
    public function __construct(
        protected PdfService $pdfService,
        protected AuditService $auditService,
    ) {}

    public function store(Request $request, Soutenance $soutenance)
    {
        if ($soutenance->pv) {
            return response()->json(['message' => 'Un PV existe déjà pour cette soutenance.'], 409);
        }

        $validated = $request->validate([
            'note' => 'required|numeric|min:0|max:20',
            'observations' => 'nullable|string',
        ]);

        $validated['mention'] = Pv::calculerMention($validated['note']);
        $pv = $soutenance->pv()->create($validated);

        $this->auditService->log(Auth::id(), 'pv.store', "PV créé pour la soutenance #{$soutenance->id}");

        return (new PvResource($pv))->response()->setStatusCode(201);
    }

    public function update(Request $request, Pv $pv)
    {
        if ($pv->estArchive()) {
            return response()->json(['message' => 'Un PV archivé ne peut plus être modifié.'], 422);
        }

        $validated = $request->validate([
            'note' => 'required|numeric|min:0|max:20',
            'observations' => 'nullable|string',
        ]);

        $validated['mention'] = Pv::calculerMention($validated['note']);
        $pv->update($validated);

        $this->auditService->log(Auth::id(), 'pv.update', "PV #{$pv->id} mis à jour");

        return new PvResource($pv);
    }

    public function submitForValidation(Pv $pv)
    {
        if ($pv->status !== 'brouillon') {
            return response()->json(['message' => 'Seul un PV en brouillon peut être soumis.'], 422);
        }

        $pv->status = 'en_validation';
        $pv->save();

        $this->auditService->log(Auth::id(), 'pv.submit', "PV #{$pv->id} soumis pour validation");

        return new PvResource($pv);
    }

    public function generatePdf(Pv $pv)
    {
        $soutenance = $pv->soutenance->load('jury');
        $pdfContent = $this->pdfService->generatePV($soutenance, $pv, $soutenance->jury);

        $filename = 'pv_'.$soutenance->id.'.pdf';
        $pv->fichier_pdf = $this->pdfService->save($pdfContent, $filename);
        $pv->save();

        return $this->pdfService->download($pdfContent, $filename);
    }
}
