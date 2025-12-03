<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\User;
use App\Models\Office;

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
    public function getNotifications(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Build query with potential filtering
            $query = Notification::where('user_id', $user->user_id)
                ->with('sender')
                ->orderBy('created_at', 'desc');
            
            // Apply filter if provided
            $filter = $request->input('filter', 'all');
            if ($filter !== 'all') {
                switch ($filter) {
                    case 'unit_director':
                        $query->whereHas('sender', function ($q) {
                            $q->whereIn('role', ['unit_director', 'unit director']);
                        });
                        break;
                    case 'office_head':
                        $query->whereHas('sender', function ($q) {
                            $q->where('role', 'head');
                        });
                        break;
                    case 'system':
                        $query->where(function ($q) {
                            $q->whereNull('sender_id')
                                ->orWhereDoesntHave('sender');
                        });
                        break;
                }
            }
            
            $notifications = $query->get();
            
            // Check if request wants HTML response (for office head dashboard)
            if ($request->wantsJson() === false && $request->headers->get('Accept') && strpos($request->headers->get('Accept'), 'text/html') !== false) {
                return $this->renderNotificationsHTML($notifications);
            }
            
            // Add sender information to each notification for JSON response
            $notificationsWithSender = $notifications->map(function ($notification) {
                $notificationArray = $notification->toArray();
                if ($notification->sender) {
                    $notificationArray['sender_name'] = $notification->sender->full_name ?? 'Unknown Sender';
                    $notificationArray['sender_role'] = ucfirst($notification->sender->role ?? 'user');
                } else {
                    $notificationArray['sender_name'] = 'System';
                    $notificationArray['sender_role'] = 'System';
                }
                return $notificationArray;
            });
            
            return response()->json([
                'success' => true,
                'notifications' => $notificationsWithSender
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching notifications: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching notifications'
            ], 500);
        }
    }
    
    /**
     * Render notifications as HTML for office head dashboard
     */
    private function renderNotificationsHTML($notifications)
    {
        if ($notifications->isEmpty()) {
            return response()->make('<div class="text-center py-5">
                <i class="fas fa-inbox fa-3x mb-3 text-muted"></i>
                <h5>No notifications</h5>
                <p class="text-muted">You don\'t have any notifications at the moment.</p>
              </div>');
        }
        
        try {
            $html = '<div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <button id="markAllReadBtn" class="btn btn-sm btn-outline-primary me-2">
                        <i class="fas fa-check-double me-1"></i>Mark All Read
                    </button>
                    <button id="deleteAllBtn" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash-alt me-1"></i>Delete All
                    </button>
                </div>
                <div>
                    <span class="text-muted small">' . $notifications->count() . ' notifications</span>
                </div>
              </div>';
            
            $html .= '<div class="list-group">';
            
            foreach ($notifications as $notification) {
                $isUnreadClass = !$notification->is_read ? 'list-group-item-warning unread' : '';
                $unreadIndicator = !$notification->is_read ? '<span class="badge bg-warning me-2">NEW</span>' : '';
                
                // Process message to convert proof references to links
                $processedMessage = $this->processNotificationMessage($notification->message);
                
                // Get sender information
                $senderInfo = '';
                if ($notification->sender) {
                    $senderName = $notification->sender->full_name ?? 'Unknown Sender';
                    $senderRole = ucfirst($notification->sender->role ?? 'user');
                    $senderInfo = '<small class="text-muted d-block mb-1">From: ' . e($senderName) . ' (' . e($senderRole) . ')</small>';
                }
                
                $html .= '<div class="list-group-item ' . $isUnreadClass . '" data-notification-id="' . $notification->id . '">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">' . $unreadIndicator . e($notification->title) . '</h6>
                        <small class="text-muted">' . $notification->created_at->diffForHumans() . '</small>
                    </div>
                    ' . $senderInfo . '
                    <p class="mb-1">' . $processedMessage . '</p>
                </div>';
            }
            
            $html .= '</div>';
            
            return response()->make($html);
        } catch (\Exception $e) {
            \Log::error('Error rendering notifications HTML: ' . $e->getMessage());
            return response()->make('<div class="alert alert-danger">Error loading notifications. Please try again.</div>');
        }
    }
    
    /**
     * Process notification message to convert proof references to links
     */
    private function processNotificationMessage($message)
    {
        try {
            // Convert proof references to clickable links
            $user = Auth::user();
            $message = preg_replace_callback('/\[View Proof\]\(proof:(\d+)\)/', function($matches) use ($user) {
                $proofId = $matches[1];
                
                // If user is a unit director, provide link to review page
                if (in_array($user->role, ['unit_director', 'unit director'])) {
                    return '<a href="' . route('training_proofs.review', $proofId) . '" class="btn btn-sm btn-outline-primary me-2">Review Proof</a>' .
                           '<a href="' . route('training_proofs.view', $proofId) . '" target="_blank" class="btn btn-sm btn-outline-secondary me-2">View</a>' .
                           '<a href="' . route('training_proofs.view', ['id' => $proofId, 'download' => 1]) . '" class="btn btn-sm btn-outline-secondary">Download</a>';
                } else {
                    // For other users, provide standard view/download links
                    return '<a href="' . route('training_proofs.view', $proofId) . '" target="_blank" class="btn btn-sm btn-outline-primary me-2">View Proof</a>' .
                           '<a href="' . route('training_proofs.view', ['id' => $proofId, 'download' => 1]) . '" class="btn btn-sm btn-outline-secondary">Download</a>';
                }
            }, e($message));
        } catch (\Exception $e) {
            // If there's an error processing the message, return the original message
            \Log::error('Error processing notification message: ' . $e->getMessage());
        }
        
        return nl2br($message ?? '');
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
            if ($ids === 'all') {
                // Mark all notifications as read
                Notification::where('user_id', $user->user_id)
                    ->update(['is_read' => 1]);
            } else {
                Notification::whereIn('id', $ids)
                    ->where('user_id', $user->user_id)
                    ->update(['is_read' => 1]);
            }
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
            if ($ids === 'all') {
                // Delete all notifications
                Notification::where('user_id', $user->user_id)->delete();
            } else {
                Notification::whereIn('id', $ids)
                    ->where('user_id', $user->user_id)
                    ->delete();
            }
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
        try {
            $user = Auth::user();
            
            $count = Notification::where('user_id', $user->user_id)
                ->where('is_read', 0)
                ->count();
            
            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching unread count: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'count' => 0
            ]);
        }
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
                'sender_id' => $user->user_id,
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

/**
     * Send notification to staff of the authenticated user's office and to unit director(s).
     * Accessible by Office Heads.
     */
    public function officeBroadcast(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'audience' => 'required|in:unit_director,office_staff',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:1000'
        ]);

        $officeCode = $user->office_code;

        $recipients = collect();

        if ($validated['audience'] === 'office_staff') {
            if (!empty($officeCode)) {
                $recipients = User::where('office_code', $officeCode)->pluck('user_id');
            }
        } elseif ($validated['audience'] === 'unit_director') {
            // Include unit director(s). Support both role variants.
            $recipients = User::whereIn('role', ['unit_director', 'unit director'])->pluck('user_id');
        }

        // Remove duplicates and exclude the sender if present
        $recipients = $recipients->unique()->filter(function ($id) use ($user) {
            return $id !== $user->user_id;
        });

        foreach ($recipients as $recipientId) {
            Notification::create([
                'user_id' => $recipientId,
                'sender_id' => $user->user_id,
                'title' => $validated['subject'] ?? 'Office Head Message',
                'message' => $validated['message'],
                'is_read' => 0
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Notification sent to ' . $recipients->count() . ' users.'
        ]);
    }
    
    /**
     * Send notification to staff of specific offices.
     * Accessible by Unit Directors.
     */
    public function officeStaffBroadcast(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'offices' => 'required|array',
            'offices.*' => 'string',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:1000'
        ]);
        
        $officeCodes = $validated['offices'];
        
        // Get staff members from specified offices
        $recipients = User::where('role', 'staff')
            ->whereIn('office_code', $officeCodes)
            ->pluck('user_id');
        
        // Remove duplicates and exclude the sender if present
        $recipients = $recipients->unique()->filter(function ($id) use ($user) {
            return $id !== $user->user_id;
        });
        
        foreach ($recipients as $recipientId) {
            Notification::create([
                'user_id' => $recipientId,
                'sender_id' => $user->user_id,
                'title' => $validated['subject'] ?? 'Unit Director Message',
                'message' => $validated['message'],
                'is_read' => 0
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Notification sent to ' . $recipients->count() . ' staff members.'
        ]);
    }
    
    /**
     * Get all offices for notification broadcasting.
     * Accessible by Unit Directors.
     */
    public function getOffices()
    {
        $offices = Office::all();
        
        return response()->json([
            'success' => true,
            'offices' => $offices
        ]);
    }
}