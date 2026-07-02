<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfService
{
    /**
     * Générer une convocation PDF
     */
    public function generateConvocation($soutenance, $etudiant, $jury = [])
    {
        $data = [
            'soutenance' => $soutenance,
            'etudiant' => $etudiant,
            'jury' => $jury,
        ];
        
        $pdf = Pdf::loadView('pdf.convocation', $data);
        return $pdf->output();
    }

    /**
     * Générer un PV PDF
     */
    public function generatePV($soutenance, $pv, $jury = [])
    {
        $data = [
            'soutenance' => $soutenance,
            'pv' => $pv,
            'jury' => $jury,
        ];
        
        $pdf = Pdf::loadView('pdf.pv', $data);
        return $pdf->output();
    }

    /**
     * Générer une attestation PDF
     */
    public function generateAttestation($etudiant, $soutenance)
    {
        $data = [
            'etudiant' => $etudiant,
            'soutenance' => $soutenance,
        ];
        
        $pdf = Pdf::loadView('pdf.attestation', $data);
        return $pdf->output();
    }

    /**
     * Sauvegarder un PDF
     */
    public function save($content, $filename, $path = 'uploads/pv/')
    {
        $fullPath = storage_path('app/public/' . $path . $filename);
        file_put_contents($fullPath, $content);
        return $path . $filename;
    }

    /**
     * Télécharger un PDF
     */
    public function download($content, $filename)
    {
        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }
}