<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    private const ROLES_STAFF = ['secretaire_pedagogique', 'administrateur', 'responsable_pedagogique'];

    public function download(Document $document)
    {
        abort_unless($this->peutAcceder($document), 403, 'Accès non autorisé à ce document.');
        abort_unless(Storage::disk('public')->exists($document->chemin_fichier), 404, 'Fichier introuvable.');

        return Storage::disk('public')->download($document->chemin_fichier);
    }

    private function peutAcceder(Document $document): bool
    {
        $user = Auth::user();
        $soutenance = $document->soutenance;

        if (in_array($user->role, self::ROLES_STAFF, true)) {
            return true;
        }

        if ($user->id === $soutenance->etudiant_id) {
            return true;
        }

        return $soutenance->jury()->where('utilisateur_id', $user->id)->exists();
    }
}
