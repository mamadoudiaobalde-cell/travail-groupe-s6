<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    /**
     * Affiche la liste des logs d'audit
     */
    public function index()
    {
        $audits = AuditLog::with('utilisateur')
                          ->orderBy('created_at', 'desc')
                          ->paginate(20);
        
        return view('admin.audit', compact('audits'));
    }

    /**
     * Affiche les logs d'un utilisateur spécifique
     */
    public function show($id)
    {
        $audits = AuditLog::where('utilisateur_id', $id)
                          ->with('utilisateur')
                          ->orderBy('created_at', 'desc')
                          ->paginate(20);
        
        return view('admin.audit', compact('audits'));
    }

    /**
     * Supprime les logs anciens (plus de 90 jours)
     */
    public function clean()
    {
        $deleted = AuditLog::where('created_at', '<', now()->subDays(90))->delete();
        
        return redirect()->route('admin.audit')
                         ->with('success', "{$deleted} logs supprimés");
    }
}