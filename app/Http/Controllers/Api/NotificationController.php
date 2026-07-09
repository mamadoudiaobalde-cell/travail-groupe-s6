<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('utilisateur_id', Auth::id())
            ->latest()
            ->paginate(15);

        return NotificationResource::collection($notifications);
    }

    public function markAsRead(Notification $notification)
    {
        abort_if($notification->utilisateur_id !== Auth::id(), 403, 'Action non autorisée.');

        $notification->lu = true;
        $notification->lu_le = now();
        $notification->save();

        return new NotificationResource($notification);
    }
}
