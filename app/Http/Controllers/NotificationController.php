<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Services\CustomerNotificationService;

class NotificationController extends Controller
{
    protected CustomerNotificationService $notificationService;

    public function __construct(CustomerNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * 1. استرجاع قائمة الإشعارات الخاصة بالعميل مع دعم التصفح (Pagination)
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success'       => false,
                'message'       => 'يجب تسجيل الدخول لعرض الإشعارات',
                'requires_auth' => true
            ], 401);
        }

        $user = Auth::user();
        $query = $user->notifications();

        // فلترة الإشعارات غير المقروءة فقط إذا تم طلب ذلك
        if ($request->boolean('unread_only')) {
            $query->whereNull('read_at');
        }

        $notifications = $query->latest()->paginate($request->input('per_page', 15));

        $formatted = $notifications->map(function ($n) {
            $data = is_array($n->data) ? $n->data : (json_decode($n->data ?? '{}', true) ?: []);
            return [
                'id'         => $n->id,
                'type'       => $data['type'] ?? $n->type,
                'title'      => $data['title'] ?? 'إشعار جديد',
                'message'    => $data['message'] ?? '',
                'icon'       => $data['icon'] ?? '🔔',
                'action_url' => $data['action_url'] ?? '',
                'read'       => $n->read_at !== null,
                'read_at'    => $n->read_at ? $n->read_at->format('Y-m-d H:i') : null,
                'created_at' => $n->created_at ? $n->created_at->format('Y-m-d H:i') : '',
                'time_ago'   => $n->created_at ? $n->created_at->diffForHumans() : '',
                'data'       => $data,
            ];
        });

        $unreadCount = $user->unreadNotifications()->count();

        if ($request->wantsJson() || $request->ajax() || !view()->exists('shop.notifications')) {
            return response()->json([
                'success'       => true,
                'notifications' => $formatted,
                'unread_count'  => $unreadCount,
                'meta'          => [
                    'current_page' => $notifications->currentPage(),
                    'last_page'    => $notifications->lastPage(),
                    'total'        => $notifications->total(),
                    'has_more'     => $notifications->hasMorePages(),
                ],
            ]);
        }

        return view('shop.notifications', [
            'notifications' => $formatted,
            'unreadCount'   => $unreadCount,
            'paginator'     => $notifications,
        ]);
    }

    /**
     * 2. الحصول على عدد الإشعارات غير المقروءة
     */
    public function unreadCount(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'count' => 0], 401);
        }

        $count = Auth::user()->unreadNotifications()->count();

        return response()->json([
            'success' => true,
            'count'   => $count,
        ]);
    }

    /**
     * 3. تحديد إشعار معين كمقروء
     */
    public function markAsRead(Request $request, $id): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'يجب تسجيل الدخول'], 401);
        }

        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->first();

        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'الإشعار غير موجود'], 404);
        }

        if ($notification->read_at === null) {
            $notification->markAsRead();
        }

        return response()->json([
            'success'      => true,
            'message'      => 'تم تحديد الإشعار كمقروء',
            'unread_count' => $user->unreadNotifications()->count(),
        ]);
    }

    /**
     * 4. تحديد جميع الإشعارات كمقروءة
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'يجب تسجيل الدخول'], 401);
        }

        $user = Auth::user();
        $user->unreadNotifications()->update(['read_at' => now()]);

        return response()->json([
            'success'      => true,
            'message'      => 'تم تحديد جميع الإشعارات كمقروءة',
            'unread_count' => 0,
        ]);
    }

    /**
     * 5. حذف إشعار معين
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'يجب تسجيل الدخول'], 401);
        }

        $user = Auth::user();
        $deleted = $user->notifications()->where('id', $id)->delete();

        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'الإشعار غير موجود أو تم حذفه بالفعل'], 404);
        }

        return response()->json([
            'success'      => true,
            'message'      => 'تم حذف الإشعار بنجاح',
            'unread_count' => $user->unreadNotifications()->count(),
        ]);
    }

    /**
     * 6. استرجاع إعدادات الإشعارات الخاصة بالعميل (تفعيل/تعطيل كل نوع)
     */
    public function getSettings(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success'       => false,
                'message'       => 'يجب تسجيل الدخول لعرض إعدادات الإشعارات',
                'requires_auth' => true
            ], 401);
        }

        $user = Auth::user();
        $tenant = $request->attributes->get('tenant');
        $tenantId = optional($tenant)->id ?? session()->get('tenant_id') ?? config('tenant.id');

        $settings = $this->notificationService->getNotificationSettings($user, $tenantId);

        return response()->json([
            'success'  => true,
            'settings' => $settings,
            'available_types' => [
                'registration_confirmation' => 'تأكيد التسجيل والترحيب في المتجر',
                'order_confirmation'        => 'تأكيد استلام الطلبات الجديدة',
                'order_status_update'       => 'تحديثات حالة وتتبع الطلبات',
                'new_offers'                => 'إشعارات العروض والخصومات والكوبونات الجديدة',
                'wishlist_back_in_stock'    => 'تنبيه توفر منتجات قائمة الأمنيات في المخزون',
                'cart_abandonment_reminder' => 'تذكير بالسلة المتروكة والمنتجات غير المشتراة',
            ],
            'available_channels' => [
                'mail'     => 'البريد الإلكتروني (Email)',
                'database' => 'إشعارات التطبيق والموقع (In-App)',
                'push'     => 'الإشعارات الفورية للموبايل (Push Notifications)',
            ],
        ]);
    }

    /**
     * 7. تحديث وحفظ إعدادات الإشعارات الخاصة بالعميل
     */
    public function updateSettings(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success'       => false,
                'message'       => 'يجب تسجيل الدخول لتحديث إعدادات الإشعارات',
                'requires_auth' => true
            ], 401);
        }

        $user = Auth::user();
        $tenant = $request->attributes->get('tenant');
        $tenantId = optional($tenant)->id ?? session()->get('tenant_id') ?? config('tenant.id');

        // استقبال الإعدادات المرسلة
        $inputSettings = $request->except(['_token', '_method']);

        $updatedSettings = $this->notificationService->updateNotificationSettings($user, $inputSettings, $tenantId);

        return response()->json([
            'success'  => true,
            'message'  => 'تم حفظ إعدادات الإشعارات بنجاح',
            'settings' => $updatedSettings,
        ]);
    }
}
