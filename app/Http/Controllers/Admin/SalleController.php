<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Salle;
use Illuminate\Http\Request;

class SalleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salles = Salle::all();
        return view('admin.salles.index', compact('salles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.salles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'capacite' => 'required|integer|min:1',
            'localisation' => 'nullable|string|max:255',
            'equipements' => 'nullable|string',
            'actif' => 'sometimes|boolean',
        ]);

        Salle::create($validated);

        return redirect()->route('salles.index')
                         ->with('success', 'Salle créée avec succès');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Salle $salle)
    {
        return view('admin.salles.edit', compact('salle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Salle $salle)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'capacite' => 'required|integer|min:1',
            'localisation' => 'nullable|string|max:255',
            'equipements' => 'nullable|string',
            'actif' => 'sometimes|boolean',
        ]);

        $salle->update($validated);

        return redirect()->route('salles.index')
                         ->with('success', 'Salle modifiée avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Salle $salle)
    {
        // Vérifier si la salle est utilisée dans des soutenances
        if ($salle->soutenances()->count() > 0) {
            return redirect()->route('salles.index')
                             ->with('error', 'Cette salle est utilisée dans des soutenances');
        }

        $salle->delete();

        return redirect()->route('salles.index')
                         ->with('success', 'Salle supprimée avec succès');
    }
}