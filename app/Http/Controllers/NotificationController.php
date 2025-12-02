<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\User;

class NotificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get notifications for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotifications()
    {
        $user = Auth::user();
        
        $notifications = Notification::where('user_id', $user->user_id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'notifications' => $notifications
        ]);
    }
    
    /**
     * Mark notifications as read.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Request $request)
    {
        $user = Auth::user();
        
        $ids = $request->input('ids', []);
        
        if (!empty($ids)) {
            Notification::whereIn('id', $ids)
                ->where('user_id', $user->user_id)
                ->update(['is_read' => 1]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Notifications marked as read.'
        ]);
    }
    
    /**
     * Delete notifications.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteNotifications(Request $request)
    {
        $user = Auth::user();
        
        $ids = $request->input('ids', []);
        
        if (!empty($ids)) {
            Notification::whereIn('id', $ids)
                ->where('user_id', $user->user_id)
                ->delete();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Notifications deleted.'
        ]);
    }
    
    /**
     * Get unread notification count.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnreadCount()
    {
        $user = Auth::user();
        
        $count = Notification::where('user_id', $user->user_id)
            ->where('is_read', 0)
            ->count();
        
        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }
    
    /**
     * Broadcast a notification (Unit Director only).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function broadcast(Request $request)
    {
        $user = Auth::user();
        
        // Validate request
        $validated = $request->validate([
            'audience' => 'required|in:all,staff,heads',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:1000'
        ]);
        
        // Determine recipients based on audience
        $recipients = collect();
        
        switch ($validated['audience']) {
            case 'all':
                $recipients = User::pluck('user_id');
                break;
            case 'staff':
                $recipients = User::where('role', 'staff')->pluck('user_id');
                break;
            case 'heads':
                $recipients = User::where('role', 'head')->pluck('user_id');
                break;
        }
        
        // Create notifications for each recipient
        foreach ($recipients as $recipientId) {
            Notification::create([
                'user_id' => $recipientId,
                'title' => $validated['subject'] ?? 'Broadcast Message',
                'message' => $validated['message'],
                'is_read' => 0
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Notification broadcast successfully to ' . $recipients->count() . ' users.'
        ]);
    }
}