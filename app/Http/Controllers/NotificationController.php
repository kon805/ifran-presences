<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
            public function index()
            {
                $notifications = Auth::user()
                    ->notifications
                    ->sortByDesc('created_at')
                    ->values()
                    ->map(function ($notification) {
                        $data = $notification->data;
                        return [
                            'id' => $notification->id,
                            'type' => $data['type'] ?? 'system',
                            'message' => $data['message'] ?? 'Notification système',
                            'created_at' => $notification->created_at->diffForHumans(),
                            'read' => !is_null($notification->read_at),
                            'icon' => $data['icon'] ?? '📝',
                            'data' => $data
                        ];
                    })
                    ->take(15);

                return response()->json($notifications);
            }

    public function getUnreadCount()
    {
        $count = Auth::user()->unreadNotifications->count();
        return response()->json(['count' => $count]);
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()
            ->notifications
            ->where('id', $id)
            ->first();

        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }

    public function deleteNotification($id)
    {
        $notification = Auth::user()
            ->notifications
            ->where('id', $id)
            ->first();

        if ($notification) {
            $notification->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    public function deleteAllNotifications()
    {
        Auth::user()->notifications->each->delete();
        return response()->json(['success' => true]);
    }

    private function formatMessage($notification)
    {
        if ($notification->type === 'App\Notifications\StatusChangeNotification') {
            $status = $notification->data['new_status'] ? 'autorisé' : 'droppé';
            $isParent = Auth::user()->role === 'parent';

            if ($isParent) {
                return "Votre enfant a été " . $status . " dans la matière {$notification->data['matiere_nom']}. " .
                       "Taux de présence : " . number_format($notification->data['taux_presence'], 2) . "%";
            } else {
                return "Votre statut dans la matière {$notification->data['matiere_nom']} a changé en '$status'. " .
                       "Taux de présence : " . number_format($notification->data['taux_presence'], 2) . "%";
            }
        }

        return $notification->data['message'] ?? 'Notification système';
    }
}
