<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use App\Models\Pv;
use App\Models\Soutenance;
use App\Services\PdfService;
use Illuminate\Http\Request;

class PvController extends Controller
{
    public function __construct(protected PdfService $pdfService) {}

    /**
     * Saisir les résultats d'une soutenance.
     */
    public function store(Request $request, Soutenance $soutenance)
    {
        if ($soutenance->pv) {
            return redirect()->back()->with('error', 'Un PV existe déjà pour cette soutenance');
        }

        $validated = $request->validate([
            'note' => 'required|numeric|min:0|max:20',
            'observations' => 'nullable|string',
        ]);

        $validated['mention'] = Pv::calculerMention($validated['note']);

        $pv = $soutenance->pv()->create($validated);

        return redirect()->route('soutenances.show', $soutenance)
            ->with('success', 'PV enregistré avec la mention '.$pv->mention);
    }

    /**
     * Modifier les résultats d'un PV (tant qu'il n'est pas archivé).
     */
    public function update(Request $request, Pv $pv)
    {
        if ($pv->estArchive()) {
            return redirect()->back()->with('error', 'Un PV archivé ne peut plus être modifié');
        }

        $validated = $request->validate([
            'note' => 'required|numeric|min:0|max:20',
            'observations' => 'nullable|string',
        ]);

        $validated['mention'] = Pv::calculerMention($validated['note']);

        $pv->update($validated);

        return redirect()->route('soutenances.show', $pv->soutenance)
            ->with('success', 'PV mis à jour');
    }

    /**
     * Soumettre le PV pour validation par le responsable pédagogique.
     */
    public function submitForValidation(Pv $pv)
    {
        if ($pv->status !== 'brouillon') {
            return redirect()->back()->with('error', 'Seul un PV en brouillon peut être soumis');
        }

        $pv->status = 'en_validation';
        $pv->save();

        return redirect()->route('soutenances.show', $pv->soutenance)
            ->with('success', 'PV soumis pour validation');
    }

    /**
     * Générer le PV au format PDF.
     */
    public function generatePdf(Pv $pv)
    {
        $soutenance = $pv->soutenance;
        $pdfContent = $this->pdfService->generatePV($soutenance, $pv, $soutenance->jury);

        $filename = 'pv_'.$soutenance->id.'.pdf';
        $pv->fichier_pdf = $this->pdfService->save($pdfContent, $filename);
        $pv->save();

        return $this->pdfService->download($pdfContent, $filename);
    }
}
