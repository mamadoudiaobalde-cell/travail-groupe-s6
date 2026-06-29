<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Liste des notifications de l'utilisateur connecté.
     */
    public function index()
    {
        $notifications = Notification::where('utilisateur_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Marquer une notification comme lue.
     */
    public function markAsRead(Notification $notification)
    {
        abort_if($notification->utilisateur_id !== Auth::id(), 403);

        $notification->lu = true;
        $notification->lu_le = now();
        $notification->save();

        return redirect()->back()->with('success', 'Notification marquée comme lue');
    }
}
