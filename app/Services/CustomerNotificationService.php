<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Illuminate\Notifications\Notification;

class CustomerNotificationService
{
    /**
     * قائمة الإعدادات الافتراضية لإشعارات العميل
     */
     public static function getDefaultSettings(): array
     {
         return [
             'registration_confirmation' => true,
             'order_confirmation'        => true,
             'order_status_update'       => true,
             'new_offers'                => true,
             'wishlist_back_in_stock'    => true,
             'cart_abandonment_reminder' => true,
             'channels' => [
                 'mail'     => true,
                 'database' => true,
                 'push'     => true,
             ]
         ];
     }

    /**
     * استرجاع إعدادات الإشعارات الخاصة بالعميل
     */
    public function getNotificationSettings($user, $tenantId = null): array
    {
        $userId = $this->extractUserId($user);
        if (!$userId) {
            return self::getDefaultSettings();
        }

        if ($tenantId === null) {
            $tenantId = session()->get('tenant_id') ?? config('tenant.id');
        }

        $json = Setting::get("user_{$userId}_notification_settings", null, $tenantId);
        $savedSettings = is_string($json) ? json_decode($json, true) : (is_array($json) ? $json : []);

        if (!is_array($savedSettings)) {
            $savedSettings = [];
        }

        return array_replace_recursive(self::getDefaultSettings(), $savedSettings);
    }

    /**
     * تحديث وحفظ إعدادات الإشعارات للعميل
     */
    public function updateNotificationSettings($user, array $newSettings, $tenantId = null): array
    {
        $userId = $this->extractUserId($user);
        if (!$userId) {
            return self::getDefaultSettings();
        }

        if ($tenantId === null) {
            $tenantId = session()->get('tenant_id') ?? config('tenant.id');
        }

        $currentSettings = $this->getNotificationSettings($user, $tenantId);
        
        // تحديث الإعدادات الفردية
        foreach (self::getDefaultSettings() as $key => $defaultValue) {
            if ($key === 'channels') {
                if (isset($newSettings['channels']) && is_array($newSettings['channels'])) {
                    foreach ($defaultValue as $channelKey => $channelDefault) {
                        if (isset($newSettings['channels'][$channelKey])) {
                            $currentSettings['channels'][$channelKey] = filter_var($newSettings['channels'][$channelKey], FILTER_VALIDATE_BOOLEAN);
                        }
                    }
                }
            } elseif (isset($newSettings[$key])) {
                $currentSettings[$key] = filter_var($newSettings[$key], FILTER_VALIDATE_BOOLEAN);
            }
        }

        Setting::set(
            "user_{$userId}_notification_settings",
            json_encode($currentSettings, JSON_UNESCAPED_UNICODE),
            'user_meta',
            $tenantId
        );

        return $currentSettings;
    }

    /**
     * التحقق مما إذا كان نوع معين من الإشعارات مفعّلاً للعميل
     */
    public function isNotificationEnabled($user, string $notificationType, $tenantId = null): bool
    {
        if (!$user) {
            return true;
        }

        $userId = $this->extractUserId($user);
        if (!$userId) {
            return true;
        }

        $settings = $this->getNotificationSettings($user, $tenantId);
        return !empty($settings[$notificationType]);
    }

    /**
     * التحقق مما إذا كانت قناة إرسال معينة (بريد، إشعار فوري، إلخ) مفعّلة للعميل
     */
    public function isChannelEnabled($user, string $channel, $tenantId = null): bool
    {
        if (!$user) {
            return true;
        }

        $userId = $this->extractUserId($user);
        if (!$userId) {
            return true;
        }

        $settings = $this->getNotificationSettings($user, $tenantId);
        return !empty($settings['channels'][$channel]);
    }

    /**
     * الحصول على القنوات المسموح بالإرسال عبرها بناءً على إعدادات العميل ونوع الإشعار
     */
    public function getEnabledChannels($user, string $notificationType, array $defaultChannels = ['mail', 'database'], $tenantId = null): array
    {
        // إذا كان الإشعار معطلاً بالكامل من العميل
        if (!$this->isNotificationEnabled($user, $notificationType, $tenantId)) {
            return [];
        }

        $enabledChannels = [];
        foreach ($defaultChannels as $channel) {
            if ($this->isChannelEnabled($user, $channel, $tenantId)) {
                $enabledChannels[] = $channel;
            }
        }

        return $enabledChannels;
    }

    /**
     * إرسال إشعار للعميل مع مراعاة إعدادات الإشعارات الخاصة به
     */
    public function notify($user, Notification $notification, string $type = null, $tenantId = null): bool
    {
        if ($type && !$this->isNotificationEnabled($user, $type, $tenantId)) {
            Log::info("Notification [{$type}] skipped for user: user disabled this type.");
            return false;
        }

        try {
            if (is_object($user) && method_exists($user, 'notify')) {
                $user->notify($notification);
                return true;
            }

            if (is_numeric($user)) {
                $userModel = User::find($user);
                if ($userModel) {
                    $userModel->notify($notification);
                    return true;
                }
            }

            if (is_string($user) && filter_var($user, FILTER_VALIDATE_EMAIL)) {
                NotificationFacade::route('mail', $user)->notify($notification);
                return true;
            }

            if (is_array($user) && !empty($user['email'])) {
                NotificationFacade::route('mail', $user['email'])->notify($notification);
                return true;
            }
        } catch (\Throwable $e) {
            Log::error('Error sending customer notification: ' . $e->getMessage(), [
                'type'      => $type,
                'exception' => $e
            ]);
            return false;
        }

        return false;
    }

    /**
     * 1. إشعار تأكيد التسجيل (Registration confirmation)
     */
    public function sendRegistrationConfirmation($user, array $data = [], $tenantId = null): bool
    {
        $notification = new \App\Notifications\RegistrationConfirmationNotification($data);
        return $this->notify($user, $notification, 'registration_confirmation', $tenantId);
    }

    /**
     * 2. إشعار تأكيد الطلب (Order confirmation)
     */
    public function sendOrderConfirmation($user, $order, array $data = [], $tenantId = null): bool
    {
        $notification = new \App\Notifications\OrderConfirmationNotification($order, $data);
        return $this->notify($user, $notification, 'order_confirmation', $tenantId);
    }

    /**
     * 3. إشعار تحديث حالة الطلب (Order status update)
     */
    public function sendOrderStatusUpdate($user, $order, string $oldStatus = '', string $newStatus = '', array $data = [], $tenantId = null): bool
    {
        $notification = new \App\Notifications\OrderStatusUpdateNotification($order, $oldStatus, $newStatus, $data);
        return $this->notify($user, $notification, 'order_status_update', $tenantId);
    }

    /**
     * 4. إشعار بالعروض الجديدة (New offers notification)
     */
    public function sendNewOffers($user, array $offerData, $tenantId = null): bool
    {
        $notification = new \App\Notifications\NewOffersNotification($offerData);
        return $this->notify($user, $notification, 'new_offers', $tenantId);
    }

    /**
     * 5. تنبيه عند توفر منتج في قائمة الأمنيات (Wishlist back in stock)
     */
    public function sendWishlistBackInStock($user, $product, $tenantId = null): bool
    {
        $notification = new \App\Notifications\WishlistBackInStockNotification($product);
        return $this->notify($user, $notification, 'wishlist_back_in_stock', $tenantId);
    }

    /**
     * 6. تذكير بالسلة المتروكة (Cart abandonment reminder)
     */
    public function sendCartAbandonmentReminder($user, $cart = null, array $data = [], $tenantId = null): bool
    {
        $notification = new \App\Notifications\CartAbandonmentReminderNotification($cart, $data);
        return $this->notify($user, $notification, 'cart_abandonment_reminder', $tenantId);
    }

    /**
     * إرسال إشعار لمجموعة من العملاء (مثلاً العروض الجديدة للجميع)
     */
    public function sendToMultiple(iterable $users, Notification $notification, string $type = null, $tenantId = null): int
    {
        $count = 0;
        foreach ($users as $user) {
            if ($this->notify($user, $notification, $type, $tenantId)) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * استخراج معرف العميل من كائن العميل أو المعرف
     */
    protected function extractUserId($user): ?int
    {
        if (is_numeric($user)) {
            return (int) $user;
        }
        if (is_object($user) && isset($user->id)) {
            return (int) $user->id;
        }
        if (is_array($user) && isset($user['id'])) {
            return (int) $user['id'];
        }
        return null;
    }
}
