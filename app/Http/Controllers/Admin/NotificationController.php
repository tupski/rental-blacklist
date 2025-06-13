<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        $user = Auth::user();
        
        // Get unread notifications
        $notifications = $user->unreadNotifications()
            ->latest()
            ->limit(10)
            ->get();
        
        $unreadCount = $user->unreadNotifications()->count();
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }
    
    public function markAsRead(Request $request)
    {
        $user = Auth::user();
        
        if ($request->has('notification_id')) {
            $notification = $user->unreadNotifications()
                ->where('id', $request->notification_id)
                ->first();
                
            if ($notification) {
                $notification->markAsRead();
            }
        } else {
            // Mark all as read
            $user->unreadNotifications->markAsRead();
        }
        
        return response()->json(['success' => true]);
    }
    
    public function index()
    {
        $user = Auth::user();
        
        $notifications = $user->notifications()
            ->latest()
            ->paginate(20);
            
        return view('admin.notifications.index', compact('notifications'));
    }
}
