<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationApiController extends Controller
{
    /**
     * Display a listing of user notifications
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $perPage = $request->get('per_page', 20);
        $unreadOnly = $request->get('unread_only', false);

        $query = $user->notifications();

        if ($unreadOnly) {
            $query->whereNull('read_at');
        }

        $notifications = $query->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $notifications->items(),
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
                'unread_count' => $user->unreadNotifications()->count()
            ]
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, string $notificationId): JsonResponse
    {
        $user = $request->user();
        $notification = $user->notifications()->where('id', $notificationId)->first();

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], 404);
        }

        if ($notification->read_at) {
            return response()->json([
                'success' => false,
                'message' => 'Notification already marked as read'
            ], 400);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
            'data' => $notification
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $user = $request->user();
        $count = $user->unreadNotifications()->count();

        $user->unreadNotifications->markAsRead();

        return response()->json([
            'success' => true,
            'message' => "Marked {$count} notifications as read",
            'marked_count' => $count
        ]);
    }

    /**
     * Delete a notification
     */
    public function destroy(Request $request, string $notificationId): JsonResponse
    {
        $user = $request->user();
        $notification = $user->notifications()->where('id', $notificationId)->first();

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], 404);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully'
        ]);
    }

    /**
     * Get notification statistics
     */
    public function getStats(Request $request): JsonResponse
    {
        $user = $request->user();

        $stats = [
            'total_notifications' => $user->notifications()->count(),
            'unread_notifications' => $user->unreadNotifications()->count(),
            'today_notifications' => $user->notifications()
                ->whereDate('created_at', today())
                ->count(),
            'this_week_notifications' => $user->notifications()
                ->where('created_at', '>=', now()->startOfWeek())
                ->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get notification preferences
     */
    public function getPreferences(Request $request): JsonResponse
    {
        $user = $request->user();

        // Default preferences - in real app this would come from user settings
        $preferences = [
            'email_notifications' => true,
            'push_notifications' => true,
            'loan_reminders' => true,
            'overdue_notifications' => true,
            'new_book_notifications' => false,
            'recommendation_notifications' => true
        ];

        return response()->json([
            'success' => true,
            'data' => $preferences
        ]);
    }

    /**
     * Update notification preferences
     */
    public function updatePreferences(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'loan_reminders' => 'boolean',
            'overdue_notifications' => 'boolean',
            'new_book_notifications' => 'boolean',
            'recommendation_notifications' => 'boolean'
        ]);

        // In a real app, save these to user_preferences table
        // For now, just return success

        return response()->json([
            'success' => true,
            'message' => 'Notification preferences updated successfully',
            'data' => $validated
        ]);
    }
}