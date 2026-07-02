<?php

namespace App\Services;

use App\Models\AuditLog;

class AuditService
{
    /**
     * Journaliser une action
     */
    public function log($userId, $action, $details = null)
    {
        return AuditLog::create([
            'utilisateur_id' => $userId,
            'action' => $action,
            'details' => $details,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Récupérer les logs récents
     */
    public function getRecent($limit = 50)
    {
        return AuditLog::with('utilisateur')
                       ->orderBy('created_at', 'desc')
                       ->limit($limit)
                       ->get();
    }

    /**
     * Récupérer les logs par utilisateur
     */
    public function getByUser($userId, $limit = 50)
    {
        return AuditLog::where('utilisateur_id', $userId)
                       ->orderBy('created_at', 'desc')
                       ->limit($limit)
                       ->get();
    }

    /**
     * Récupérer les logs par action
     */
    public function getByAction($action, $limit = 50)
    {
        return AuditLog::where('action', 'like', "%$action%")
                       ->orderBy('created_at', 'desc')
                       ->limit($limit)
                       ->get();
    }

    /**
     * Supprimer les logs anciens
     */
    public function cleanOld($days = 90)
    {
        return AuditLog::where('created_at', '<', now()->subDays($days))->delete();
    }
}