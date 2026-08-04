<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class OrderTrackingController extends Controller
{
    /**
     * عرض صفحة تتبع الطلبات (Blade)
     */
    public function index(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        $theme  = $this->getThemeData($tenant);

        // إذا كان هناك معاملات في الرابط (رقم الطلب والهاتف/البريد) نقوم بالبحث تلقائياً
        $trackingResult = null;
        $error = null;

        $orderRef = $request->input('order_number') ?? $request->input('reference_number') ?? $request->input('ref') ?? $request->input('order_id');
        $contact  = $request->input('phone') ?? $request->input('email') ?? $request->input('contact') ?? $request->input('customer_phone') ?? $request->input('customer_email');

        if (!empty($orderRef) && !empty($contact)) {
            $result = $this->findAndFormatOrder($orderRef, $contact, $tenant);
            if ($result['success']) {
                $trackingResult = $result['data'];
            } else {
                $error = $result['message'];
            }
        } elseif (!empty($orderRef) || !empty($contact)) {
            $error = 'يرجى إدخال رقم الطلب بالإضافة إلى رقم الهاتف أو البريد الإلكتروني لمتابعة التتبع.';
        }

        return view('shop.tracking', compact('tenant', 'theme', 'trackingResult', 'error', 'orderRef', 'contact'));
    }

    /**
     * معالجة طلب التتبع (Form Submission أو AJAX API)
     */
    public function track(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        $theme  = $this->getThemeData($tenant);

        $orderRef = $request->input('order_number') ?? $request->input('reference_number') ?? $request->input('ref') ?? $request->input('order_id');
        $contact  = $request->input('phone') ?? $request->input('email') ?? $request->input('contact') ?? $request->input('customer_phone') ?? $request->input('customer_email');

        if (empty($orderRef) || empty($contact)) {
            $message = 'يرجى إدخال رقم الطلب بالإضافة إلى رقم الهاتف أو البريد الإلكتروني لمتابعة التتبع.';
            if ($request->expectsJson() || $request->is('api/*') || $request->is('public-api/*') || $request->has('ajax') || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 400)->header('Access-Control-Allow-Origin', '*');
            }
            return view('shop.tracking', [
                'tenant' => $tenant,
                'theme' => $theme,
                'trackingResult' => null,
                'error' => $message,
                'orderRef' => $orderRef,
                'contact' => $contact
            ]);
        }

        $result = $this->findAndFormatOrder($orderRef, $contact, $tenant);

        if ($request->expectsJson() || $request->is('api/*') || $request->is('public-api/*') || $request->has('ajax') || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                ], 404)->header('Access-Control-Allow-Origin', '*');
            }
            return response()->json([
                'success' => true,
                'message' => 'تم العثور على الطلب بنجاح',
                'data'    => $result['data'],
            ])->header('Access-Control-Allow-Origin', '*');
        }

        if (!$result['success']) {
            return view('shop.tracking', [
                'tenant' => $tenant,
                'theme' => $theme,
                'trackingResult' => null,
                'error' => $result['message'],
                'orderRef' => $orderRef,
                'contact' => $contact
            ]);
        }

        return view('shop.tracking', [
            'tenant' => $tenant,
            'theme' => $theme,
            'trackingResult' => $result['data'],
            'error' => null,
            'orderRef' => $orderRef,
            'contact' => $contact
        ]);
    }

    /**
     * نقطة وصول مخصصة للـ API / AJAX
     */
    public function apiTrack(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        
        $orderRef = $request->input('order_number') ?? $request->input('reference_number') ?? $request->input('ref') ?? $request->input('order_id');
        $contact  = $request->input('phone') ?? $request->input('email') ?? $request->input('contact') ?? $request->input('customer_phone') ?? $request->input('customer_email');

        if (empty($orderRef) || empty($contact)) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى إدخال رقم الطلب بالإضافة إلى رقم الهاتف أو البريد الإلكتروني لمتابعة التتبع.',
            ], 400)->header('Access-Control-Allow-Origin', '*');
        }

        $result = $this->findAndFormatOrder($orderRef, $contact, $tenant);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 404)->header('Access-Control-Allow-Origin', '*');
        }

        return response()->json([
            'success' => true,
            'message' => 'تم العثور على الطلب بنجاح',
            'data'    => $result['data'],
        ])->header('Access-Control-Allow-Origin', '*');
    }

    /**
     * البحث عن الطلب والتحقق من بيانات الاتصال وتجهيز جدول التتبع (Timeline) ومعلومات الشحن
     */
    private function findAndFormatOrder($orderRef, $contact, $tenant = null): array
    {
        try {
            $cleanRef = trim(ltrim($orderRef, '#'));
            
            $query = Order::query();
            if ($tenant) {
                $query->where('tenant_id', $tenant->id);
            }

            // البحث برقم المرجع أو معرف الطلب
            $query->where(function ($q) use ($cleanRef) {
                $q->where('reference_number', $cleanRef)
                  ->orWhere('id', $cleanRef);
            });

            $orders = $query->get();

            if ($orders->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'لم يتم العثور على طلب برقم المرجع المحدد: (' . e($cleanRef) . '). تأكد من صحة رقم الطلب.',
                ];
            }

            // التحقق من تطابق رقم الهاتف أو البريد الإلكتروني
            $contactClean = trim(mb_strtolower($contact));
            $inputPhoneDigits = preg_replace('/[^0-9]/', '', $contactClean);

            $matchedOrder = null;

            foreach ($orders as $order) {
                // 1. تطابق البريد الإلكتروني
                if (!empty($order->customer_email) && trim(mb_strtolower($order->customer_email)) === $contactClean) {
                    $matchedOrder = $order;
                    break;
                }

                // 2. تطابق رقم الهاتف
                $orderPhoneDigits = preg_replace('/[^0-9]/', '', $order->customer_phone ?? '');
                if (!empty($orderPhoneDigits) && !empty($inputPhoneDigits)) {
                    if ($orderPhoneDigits === $inputPhoneDigits) {
                        $matchedOrder = $order;
                        break;
                    }
                    // التحقق من تطابق آخر 7 أو 8 أرقام (لتجاوز مفاتيح الدول والصيغ المختلفة)
                    $minLen = min(strlen($orderPhoneDigits), strlen($inputPhoneDigits));
                    if ($minLen >= 7) {
                        $compareLen = min(8, $minLen);
                        if (substr($orderPhoneDigits, -$compareLen) === substr($inputPhoneDigits, -$compareLen)) {
                            $matchedOrder = $order;
                            break;
                        }
                    }
                }

                // 3. تطابق حرفي مع الاسم أو الهاتف أو البريد كحل احتياطي
                if (trim(mb_strtolower($order->customer_phone ?? '')) === $contactClean ||
                    trim(mb_strtolower($order->customer_name ?? '')) === $contactClean) {
                    $matchedOrder = $order;
                    break;
                }
            }

            if (!$matchedOrder) {
                return [
                    'success' => false,
                    'message' => 'رقم الهاتف أو البريد الإلكتروني المدخل غير مطابق للبيانات المسجلة بهذا الطلب.',
                ];
            }

            // تجهيز البيانات التفصيلية للطلب
            $orderData = $this->formatOrderData($matchedOrder);

            return [
                'success' => true,
                'data'    => $orderData,
            ];

        } catch (\Exception $e) {
            Log::error('OrderTrackingController error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'حدث خطأ أثناء استرجاع بيانات الطلب، يرجى المحاولة لاحقاً.',
            ];
        }
    }

    /**
     * تنسيق بيانات الطلب والجدول الزمني ومعلومات الشحن
     */
    private function formatOrderData(Order $order): array
    {
        $status = strtolower(trim($order->status ?? 'pending'));
        
        // ترجمة الحالات ومسمياتها وألوانها
        $statusMap = [
            'pending' => [
                'label' => 'قيد المراجعة',
                'description' => 'تم استلام طلبك وهو قيد المراجعة حالياً من قبل فريق المتجر.',
                'color' => '#f59e0b',
                'bg' => '#fef3c7',
                'badge_class' => 'badge-pending',
                'step' => 1
            ],
            'confirmed' => [
                'label' => 'تم التأكيد',
                'description' => 'تم تأكيد طلبك ومراجعة تفاصيل الشحن بنجاح.',
                'color' => '#3b82f6',
                'bg' => '#dbeafe',
                'badge_class' => 'badge-confirmed',
                'step' => 2
            ],
            'processing' => [
                'label' => 'جاري التجهيز',
                'description' => 'يتم الآن تجهيز وتغليف المنتجات في المخزن تمهيداً لشحنها.',
                'color' => '#8b5cf6',
                'bg' => '#ede9fe',
                'badge_class' => 'badge-processing',
                'step' => 3
            ],
            'shipped' => [
                'label' => 'تم الشحن (في الطريق)',
                'description' => 'تم تسليم طلبك لشركة الشحن وهو الآن في الطريق إلى عنوانك.',
                'color' => '#06b6d4',
                'bg' => '#cffafe',
                'badge_class' => 'badge-shipped',
                'step' => 4
            ],
            'delivered' => [
                'label' => 'تم التوصيل بنجاح',
                'description' => 'تم تسليم الطلب للعميل بنجاح، نتمنى لك تجربة ممتعة مع منتجاتنا!',
                'color' => '#10b981',
                'bg' => '#d1fae5',
                'badge_class' => 'badge-delivered',
                'step' => 5
            ],
            'cancelled' => [
                'label' => 'تم إلغاء الطلب',
                'description' => 'تم إلغاء هذا الطلب. إذا كان لديك استفسار يرجى التواصل مع الدعم الفني.',
                'color' => '#ef4444',
                'bg' => '#fee2e2',
                'badge_class' => 'badge-cancelled',
                'step' => 0
            ]
        ];

        $currentStatusInfo = $statusMap[$status] ?? $statusMap['pending'];
        $currentStep = $currentStatusInfo['step'];
        $isCancelled = ($status === 'cancelled');

        // إنشاء الجدول الزمني (Timeline) مرئي يوضح مراحل الطلب
        $timelineStages = [
            'pending' => [
                'key' => 'pending',
                'title' => 'تم استلام الطلب',
                'subtitle' => 'تم تسجيل الطلب في النظام وهو قيد المراجعة',
                'icon' => 'fas fa-clipboard-check',
                'step_number' => 1,
            ],
            'confirmed' => [
                'key' => 'confirmed',
                'title' => 'تم التأكيد',
                'subtitle' => 'تم تأكيد البيانات وتوفر المنتجات',
                'icon' => 'fas fa-check-double',
                'step_number' => 2,
            ],
            'processing' => [
                'key' => 'processing',
                'title' => 'جاري التجهيز والتغليف',
                'subtitle' => 'يتم إعداد الطلب وتغليفه بعناية في المخزن',
                'icon' => 'fas fa-box-open',
                'step_number' => 3,
            ],
            'shipped' => [
                'key' => 'shipped',
                'title' => 'تم الشحن',
                'subtitle' => 'الطلب مع مندوب التوصيل وفي الطريق إليك',
                'icon' => 'fas fa-shipping-fast',
                'step_number' => 4,
            ],
            'delivered' => [
                'key' => 'delivered',
                'title' => 'تم التوصيل',
                'subtitle' => 'تم تسليم الطلب للعميل بنجاح',
                'icon' => 'fas fa-home',
                'step_number' => 5,
            ],
        ];

        $formattedTimeline = [];
        $createdAtStr = $order->created_at ? Carbon::parse($order->created_at)->format('Y-m-d h:i A') : '';
        $updatedAtStr = $order->updated_at ? Carbon::parse($order->updated_at)->format('Y-m-d h:i A') : '';

        foreach ($timelineStages as $key => $stage) {
            $stepNum = $stage['step_number'];
            $isCompleted = (!$isCancelled && $stepNum <= $currentStep);
            $isCurrent = (!$isCancelled && $stepNum === $currentStep);

            // تحديد التاريخ المعروض لكل مرحلة
            $timeStr = 'في الانتظار...';
            if ($stepNum === 1) {
                $timeStr = $createdAtStr ?: 'مكتمل';
            } elseif ($isCurrent || ($isCompleted && $stepNum === $currentStep)) {
                $timeStr = $updatedAtStr ?: 'مكتمل';
            } elseif ($isCompleted) {
                $timeStr = 'مكتمل بنجاح';
            }

            if ($isCancelled) {
                $timeStr = ($stepNum === 1) ? $createdAtStr : 'ملغي';
            }

            $formattedTimeline[] = [
                'key' => $stage['key'],
                'title' => $stage['title'],
                'subtitle' => $stage['subtitle'],
                'icon' => $stage['icon'],
                'step_number' => $stepNum,
                'is_completed' => $isCompleted,
                'is_current' => $isCurrent,
                'timestamp' => $timeStr,
            ];
        }

        // حساب نسبة شريط التقدم (Progress Bar Percentage)
        $progressPercentage = 0;
        if (!$isCancelled) {
            $progressPercentage = min(100, ($currentStep / 5) * 100);
        }

        // فك تشفير قائمة المنتجات
        $items = is_array($order->items) ? $order->items : json_decode($order->items ?? '[]', true);
        if (!is_array($items)) {
            $items = [];
        }

        $formattedItems = [];
        foreach ($items as $item) {
            $price = (float) ($item['price'] ?? 0);
            $qty = (int) ($item['quantity'] ?? $item['qty'] ?? 1);
            $total = (float) ($item['total'] ?? ($price * $qty));

            $formattedItems[] = [
                'id' => $item['id'] ?? null,
                'name' => $item['name'] ?? 'منتج',
                'price' => $price,
                'quantity' => $qty,
                'total' => $total,
                'image' => $item['image'] ?? null,
                'selectedSize' => $item['selectedSize'] ?? null,
                'selectedColor' => $item['selectedColor'] ?? null,
            ];
        }

        // معلومات الشحن والتوصيل
        $paymentMethodLabel = 'الدفع عند الاستلام (COD)';
        if (($order->payment_method ?? '') === 'visa' || ($order->payment_method ?? '') === 'card') {
            $paymentMethodLabel = 'بطاقة ائتمان / فيزا';
        } elseif (($order->payment_method ?? '') === 'transfer' || ($order->payment_method ?? '') === 'instapay') {
            $paymentMethodLabel = 'تحويل إلكتروني / انستاباي';
        }

        $shippingInfo = [
            'customer_name' => $order->customer_name ?? 'غير محدد',
            'customer_phone' => $order->customer_phone ?? 'غير محدد',
            'customer_email' => $order->customer_email ?? null,
            'customer_address' => $order->customer_address ?? 'غير محدد',
            'governorate' => $order->governorate ?? 'غير محدد',
            'payment_method' => $paymentMethodLabel,
            'shipping_cost' => (float) ($order->shipping_cost ?? 0),
            'notes' => $order->notes ?? null,
        ];

        return [
            'order_id' => $order->id,
            'reference_number' => $order->reference_number,
            'status' => $status,
            'status_label' => $currentStatusInfo['label'],
            'status_description' => $currentStatusInfo['description'],
            'status_color' => $currentStatusInfo['color'],
            'status_bg' => $currentStatusInfo['bg'],
            'badge_class' => $currentStatusInfo['badge_class'],
            'is_cancelled' => $isCancelled,
            'created_at' => $createdAtStr,
            'updated_at' => $updatedAtStr,
            'subtotal' => (float) ($order->subtotal ?? 0),
            'shipping_cost' => (float) ($order->shipping_cost ?? 0),
            'total' => (float) ($order->total ?? 0),
            'items_count' => count($formattedItems),
            'items' => $formattedItems,
            'shipping_info' => $shippingInfo,
            'timeline' => $formattedTimeline,
            'progress_percentage' => $progressPercentage,
        ];
    }

    /**
     * استخراج بيانات الثيم من إعدادات الـ tenant
     */
    private function getThemeData($tenant): array
    {
        if (!$tenant) {
            return [
                'primary_color'   => '#6c63ff',
                'secondary_color' => '#ff6584',
                'font_family'     => 'Cairo',
            ];
        }

        $settings = is_array($tenant->settings)
            ? $tenant->settings
            : json_decode($tenant->settings ?? '{}', true);

        return [
            'primary_color'   => $settings['primary_color']   ?? '#6c63ff',
            'secondary_color' => $settings['secondary_color'] ?? '#ff6584',
            'font_family'     => $settings['font_family']     ?? 'Cairo',
        ];
    }
}
