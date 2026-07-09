<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SalleResource;
use App\Models\Salle;
use Illuminate\Http\Request;

class SalleController extends Controller
{
    public function index()
    {
        return SalleResource::collection(Salle::orderBy('nom')->paginate(15));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|unique:salles,nom',
            'capacite' => 'required|integer|min:1',
            'localisation' => 'nullable|string|max:255',
            'equipements' => 'nullable|string',
            'actif' => 'boolean',
        ]);

        return new SalleResource(Salle::create($validated));
    }

    public function show(Salle $salle)
    {
        return new SalleResource($salle);
    }

    public function update(Request $request, Salle $salle)
    {
        $validated = $request->validate([
            'nom' => 'sometimes|string|unique:salles,nom,'.$salle->id,
            'capacite' => 'sometimes|integer|min:1',
            'localisation' => 'nullable|string|max:255',
            'equipements' => 'nullable|string',
            'actif' => 'boolean',
        ]);

        $salle->update($validated);

        return new SalleResource($salle);
    }

    public function destroy(Salle $salle)
    {
        if ($salle->soutenances()->exists()) {
            return response()->json(['message' => 'Impossible de supprimer : salle utilisée par des soutenances.'], 409);
        }

        $salle->delete();

        return response()->json(['message' => 'Salle supprimée.']);
    }
}
