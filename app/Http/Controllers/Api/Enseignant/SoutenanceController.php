<?php

namespace App\Http\Controllers\Api\Enseignant;

use App\Http\Controllers\Controller;
use App\Http\Resources\SoutenanceResource;
use App\Models\Soutenance;
use Illuminate\Support\Facades\Auth;

class SoutenanceController extends Controller
{
    public function index()
    {
        $id = Auth::id();

        $soutenances = Soutenance::with(['etudiant', 'salle', 'pv'])
            ->where('directeur_id', $id)
            ->orWhereHas('jury', fn ($q) => $q->where('utilisateur_id', $id))
            ->latest()
            ->get();

        return SoutenanceResource::collection($soutenances);
    }
}
