<?php

namespace App\Http\Controllers\Api\Enseignant;

use App\Http\Controllers\Controller;
use App\Http\Resources\IndisponibiliteResource;
use App\Models\Indisponibilite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndisponibiliteController extends Controller
{
    public function index()
    {
        $indisponibilites = Indisponibilite::where('utilisateur_id', Auth::id())
            ->orderBy('date_debut')
            ->get();

        return IndisponibiliteResource::collection($indisponibilites);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'motif' => 'nullable|string|max:255',
        ]);

        $validated['utilisateur_id'] = Auth::id();
        $indisponibilite = Indisponibilite::create($validated);

        return (new IndisponibiliteResource($indisponibilite))->response()->setStatusCode(201);
    }

    public function update(Request $request, Indisponibilite $indisponibilite)
    {
        abort_if($indisponibilite->utilisateur_id !== Auth::id(), 403, 'Action non autorisée.');

        $validated = $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'motif' => 'nullable|string|max:255',
        ]);

        $indisponibilite->update($validated);

        return new IndisponibiliteResource($indisponibilite);
    }

    public function destroy(Indisponibilite $indisponibilite)
    {
        abort_if($indisponibilite->utilisateur_id !== Auth::id(), 403, 'Action non autorisée.');

        $indisponibilite->delete();

        return response()->json(['message' => 'Indisponibilité supprimée.']);
    }
}
