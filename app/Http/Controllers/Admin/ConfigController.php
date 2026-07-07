<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ConfigController extends Controller
{
    /**
     * Affiche la page de configuration
     */
    public function index()
    {
        return view('admin.config');
    }

    /**
     * Met à jour la configuration
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'contact_email' => 'required|email',
            'delai_confirmation' => 'required|integer|min:1',
            'max_jury' => 'required|integer|min:1|max:10',
        ]);

        // Ici, on pourrait mettre à jour le fichier .env
        // Pour l'instant, on simule la mise à jour

        return redirect()->route('admin.config')
                         ->with('success', 'Configuration mise à jour avec succès');
    }
}