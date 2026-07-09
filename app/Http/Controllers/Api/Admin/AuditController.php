<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuditLogResource;
use App\Models\AuditLog;

class AuditController extends Controller
{
    public function index()
    {
        $logs = AuditLog::with('utilisateur')
            ->latest()
            ->paginate(50);

        return AuditLogResource::collection($logs);
    }

    public function clean()
    {
        $count = AuditLog::where('created_at', '<', now()->subMonths(6))->count();
        AuditLog::where('created_at', '<', now()->subMonths(6))->delete();

        return response()->json(['message' => "{$count} entrées supprimées."]);
    }
}
