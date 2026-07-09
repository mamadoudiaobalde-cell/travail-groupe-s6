<?php

namespace App\Http\Controllers\Api\Responsable;

use App\Http\Controllers\Controller;
use App\Services\ExportService;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function __construct(protected ExportService $exportService) {}

    public function export(Request $request, string $format)
    {
        $filters = $request->only(['filiere', 'type', 'date_debut', 'date_fin']);

        return match ($format) {
            'csv'  => $this->exportService->exportSoutenances($filters),
            'excel' => $this->exportService->exportSoutenances($filters),
            default => response()->json(['message' => "Format '{$format}' non supporté."], 422),
        };
    }
}
