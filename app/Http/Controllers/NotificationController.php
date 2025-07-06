<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications for the authenticated user.
     * جلب وعرض جميع الإشعارات للمستخدم المصادق عليه.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        // جلب جميع الإشعارات، يمكنك إضافة تصفية أو تقسيم هنا إذا كانت الإشعارات كثيرة
        $notifications = $user->notifications()->paginate(15); // جلب 15 إشعارًا في كل صفحة

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Get the count of unread notifications for the authenticated user.
     * جلب عدد الإشعارات غير المقروءة للمستخدم المصادق عليه.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unreadCount()
    {
        $user = Auth::user();
        return response()->json(['count' => $user->unreadNotifications->count()]);
    }

    /**
     * Mark a specific notification as read.
     * وضع علامة على إشعار معين كمقروء.
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Request $request, $id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true, 'message' => 'تم وضع علامة على الإشعار كمقروء.']);
        }

        return response()->json(['success' => false, 'message' => 'الإشعار غير موجود.'], 404);
    }

    /**
     * Mark all unread notifications as read.
     * وضع علامة على جميع الإشعارات غير المقروءة كمقروءة.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        return response()->json(['success' => true, 'message' => 'تم وضع علامة على جميع الإشعارات كمقروءة.']);
    }
}
