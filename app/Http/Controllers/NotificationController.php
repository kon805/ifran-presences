<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('destinataire_id', Auth::id())
            ->with('etudiant:id,name,profile_photo_url')
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();

        return response()->json($notifications);
    }

    public function getUnreadCount()
    {
        $count = Notification::where('destinataire_id', Auth::id())
            ->where('lu', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('destinataire_id', Auth::id())
            ->firstOrFail();

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Notification::where('destinataire_id', Auth::id())
            ->where('lu', false)
            ->update(['lu' => true, 'read_at' => now()]);

        return response()->json(['success' => true]);
    }
}
