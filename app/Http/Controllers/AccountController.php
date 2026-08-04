<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestReturnRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\Order;
use App\Models\Wishlist;
use App\Models\Setting;
use App\Models\ShippingGovernorate;
use App\Models\OrderReturn;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $tenant   = $request->attributes->get('tenant');
        $settings = is_array($tenant->settings)
            ? $tenant->settings
            : json_decode($tenant->settings ?? '{}', true);

        $theme = [
            'primary_color'   => $settings['primary_color']   ?? '#6c63ff',
            'secondary_color' => $settings['secondary_color'] ?? '#ff6584',
            'font_family'     => $settings['font_family']     ?? 'Cairo',
        ];

        return view('shop.account', compact('tenant', 'theme'));
    }

    public function getProfile(Request $request)
    {
        $user = Auth::user();
        $tenant = $request->attributes->get('tenant');
        $tenantId = optional($tenant)->id;

        // استرجاع رقم الهاتف من بيانات العميل أو الإعدادات أو آخر طلب
        $phone = $this->getUserPhone($user, $tenantId);

        // استرجاع عناوين الشحن من إعدادات العميل
        $addresses = $this->getUserAddresses($user, $tenantId, $phone);

        // حساب عدد الطلبات والمفضلة
        $ordersCount = Order::where('tenant_id', $tenantId)
            ->where(function ($q) use ($user, $phone) {
                $q->where('user_id', $user->id)
                  ->orWhere('customer_email', $user->email);
                if (!empty($phone)) {
                    $q->orWhere('customer_phone', $phone);
                }
            })->count();

        $wishlistCount = Wishlist::where('tenant_id', $tenantId)
            ->where('user_id', $user->id)->count();

        $governorates = ShippingGovernorate::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'price']);

        return response()->json([
            'success' => true,
            'user'    => [
                'id'             => $user->id,
                'name'           => $user->name,
                'email'          => $user->email,
                'phone'          => $phone ?? '',
                'avatar'         => $user->avatar,
                'created_at'     => $user->created_at->format('Y-m-d'),
                'orders_count'   => $ordersCount,
                'wishlist_count' => $wishlistCount,
            ],
            'addresses'    => $addresses,
            'governorates' => $governorates,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $tenant = $request->attributes->get('tenant');
        $tenantId = optional($tenant)->id;
        $action = $request->input('action', 'update_profile');

        // 1. حذف الحساب
        if ($action === 'delete_account') {
            if ($request->filled('password') && !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'كلمة المرور الحالية غير صحيحة للتأكيد'
                ], 422);
            }

            Auth::logout();
            try {
                $user->delete();
            } catch (\Exception $e) {
                $user->update(['is_active' => false]);
            }

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الحساب نهائياً بنجاح',
                'redirect' => '/'
            ]);
        }

        // 2. إضافة أو تعديل عنوان شحن
        if ($action === 'save_address') {
            $request->validate([
                'title'       => 'required|string|max:100',
                'address'     => 'required|string|max:500',
                'governorate' => 'required|string|max:100',
                'phone'       => 'required|string|max:20',
            ]);

            $addresses = $this->getUserAddresses($user, $tenantId, $request->phone);
            $addressId = $request->input('id');
            $isDefault = $request->boolean('is_default') || count($addresses) === 0;

            if ($isDefault) {
                foreach ($addresses as &$addr) {
                    $addr['is_default'] = false;
                }
            }

            if ($addressId) {
                $found = false;
                foreach ($addresses as &$addr) {
                    if (isset($addr['id']) && (string)$addr['id'] === (string)$addressId) {
                        $addr['title']       = $request->title;
                        $addr['address']     = $request->address;
                        $addr['governorate'] = $request->governorate;
                        $addr['phone']       = $request->phone;
                        if ($isDefault) {
                            $addr['is_default'] = true;
                        }
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $addresses[] = [
                        'id'          => uniqid(),
                        'title'       => $request->title,
                        'address'     => $request->address,
                        'governorate' => $request->governorate,
                        'phone'       => $request->phone,
                        'is_default'  => $isDefault,
                    ];
                }
            } else {
                $addresses[] = [
                    'id'          => uniqid(),
                    'title'       => $request->title,
                    'address'     => $request->address,
                    'governorate' => $request->governorate,
                    'phone'       => $request->phone,
                    'is_default'  => $isDefault,
                ];
            }

            Setting::set("user_{$user->id}_addresses", json_encode($addresses), 'user_meta', $tenantId);

            return response()->json([
                'success'   => true,
                'message'   => 'تم حفظ عنوان الشحن بنجاح',
                'addresses' => $addresses
            ]);
        }

        // 3. حذف عنوان شحن
        if ($action === 'delete_address') {
            $addressId = $request->input('id');
            $addresses = $this->getUserAddresses($user, $tenantId);
            $addresses = array_values(array_filter($addresses, fn($a) => isset($a['id']) && (string)$a['id'] !== (string)$addressId));

            if (!empty($addresses) && !collect($addresses)->contains('is_default', true)) {
                $addresses[0]['is_default'] = true;
            }

            Setting::set("user_{$user->id}_addresses", json_encode($addresses), 'user_meta', $tenantId);

            return response()->json([
                'success'   => true,
                'message'   => 'تم حذف العنوان بنجاح',
                'addresses' => $addresses
            ]);
        }

        // 4. تعيين عنوان كافتراضي
        if ($action === 'set_default_address') {
            $addressId = $request->input('id');
            $addresses = $this->getUserAddresses($user, $tenantId);
            foreach ($addresses as &$addr) {
                $addr['is_default'] = (isset($addr['id']) && (string)$addr['id'] === (string)$addressId);
            }
            Setting::set("user_{$user->id}_addresses", json_encode($addresses), 'user_meta', $tenantId);

            return response()->json([
                'success'   => true,
                'message'   => 'تم تعيين العنوان الافتراضي بنجاح',
                'addresses' => $addresses
            ]);
        }

        // 5. التحديث الطبيعي للملف الشخصي
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;
        try {
            if (Schema::hasColumn('users', 'phone')) {
                $user->phone = $request->phone;
            }
        } catch (\Throwable $e) {}
        $user->save();

        if ($request->has('phone')) {
            Setting::set("user_{$user->id}_phone", $request->phone ?? '', 'user_meta', $tenantId);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الملف الشخصي بنجاح'
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'كلمة المرور الحالية غير صحيحة',
            ], 422);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return response()->json(['success' => true, 'message' => 'تم تغيير كلمة المرور بنجاح']);
    }

    public function getOrders(Request $request)
    {
        $tenant   = $request->attributes->get('tenant');
        $tenantId = optional($tenant)->id;
        $user     = Auth::user();
        $phone    = $this->getUserPhone($user, $tenantId);

        $query = Order::where('tenant_id', $tenantId)
            ->where(function ($q) use ($user, $phone) {
                $q->where('user_id', $user->id)
                  ->orWhere('customer_email', $user->email);
                if (!empty($phone)) {
                    $q->orWhere('customer_phone', $phone);
                }
            });

        // إذا تم طلب طلب محدد لعرض الجدول الزمني والتفاصيل
        if ($request->filled('order_id') || $request->filled('reference_number')) {
            $orderRef = $request->input('order_id') ?: $request->input('reference_number');
            $order = (clone $query)->where(function($q) use ($orderRef) {
                $q->where('id', $orderRef)->orWhere('reference_number', $orderRef);
            })->first();

            if (!$order) {
                return response()->json(['success' => false, 'message' => 'الطلب غير موجود'], 404);
            }

            return response()->json([
                'success' => true,
                'order'   => $this->formatOrderDetails($order)
            ]);
        }

        $orders = $query->latest()->paginate(10);

        return response()->json([
            'success'  => true,
            'orders'   => $orders->map(fn($o) => $this->formatOrderDetails($o)),
            'has_more' => $orders->hasMorePages(),
        ]);
    }

    public function getReturns(Request $request)
    {
        $tenant   = $request->attributes->get('tenant');
        $tenantId = optional($tenant)->id;
        $user     = Auth::user();

        $returns = OrderReturn::where('tenant_id', $tenantId)
            ->where('user_id', $user->id)
            ->with('order')
            ->latest()
            ->get();

        $formatted = $returns->map(function ($r) {
            $statusLabels = [
                'pending'   => '⏳ قيد الانتظار',
                'approved'  => '👍 تمت الموافقة',
                'rejected'  => '❌ مرفوض',
                'completed' => '✅ مكتمل ومسترد',
            ];
            return [
                'id' => $r->id,
                'order_number' => $r->order ? ($r->order->reference_number ?: $r->order->id) : '',
                'items' => is_string($r->items) ? json_decode($r->items, true) : ($r->items ?? []),
                'reason' => $r->reason,
                'status' => $r->status,
                'status_label' => $statusLabels[$r->status] ?? $r->status,
                'refund_amount' => round((float) $r->refund_amount, 2),
                'notes' => $r->notes ?? '',
                'created_at' => $r->created_at ? $r->created_at->format('Y-m-d H:i') : '',
            ];
        });

        return response()->json([
            'success' => true,
            'returns' => $formatted
        ]);
    }

    public function requestReturn(RequestReturnRequest $request)
    {
        $validated = $request->validated();

        $tenantId = session()->get('tenant_id') ?? config('tenant.id') ?? optional($request->attributes->get('tenant'))->id;
        $user     = Auth::user();
        $phone    = $this->getUserPhone($user, $tenantId);

        // العثور على الطلب والتحقق من ملكيته للعميل
        $order = Order::where('tenant_id', $tenantId)
            ->where('id', $request->order_id)
            ->where(function ($q) use ($user, $phone) {
                $q->where('user_id', $user->id)
                  ->orWhere('customer_email', $user->email);
                if (!empty($phone)) {
                    $q->orWhere('customer_phone', $phone);
                }
            })
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'الطلب غير موجود أو لا ينتمي لهذا الحساب.'
            ], 404);
        }

        // يجب أن يكون الطلب قد تم توصيله ليتم إرجاعه
        if ($order->status !== 'delivered') {
            return response()->json([
                'success' => false,
                'message' => 'يمكنك فقط تقديم طلب إرجاع للطلبات المستلمة (تم التوصيل).'
            ], 422);
        }

        // التحقق من أن هذا الطلب لم يتم تقديم طلب إرجاع مكتمل أو قيد المراجعة له مسبقاً لكل المنتجات
        $existingReturnCount = OrderReturn::where('order_id', $order->id)
            ->whereIn('status', ['pending', 'approved', 'completed'])
            ->count();
        if ($existingReturnCount > 0) {
            return response()->json([
                'success' => false,
                'message' => 'لقد قمت بتقديم طلب إرجاع لهذا الطلب بالفعل.'
            ], 422);
        }

        $orderItems = is_string($order->items) ? json_decode($order->items, true) : ($order->items ?? []);
        $returnItems = [];
        $refundAmount = 0.00;

        foreach ($request->items as $reqItem) {
            $matchedItem = collect($orderItems)->first(function ($orderItem) use ($reqItem) {
                $idMatches = (string)($orderItem['id'] ?? '') === (string)$reqItem['id'];
                $sizeMatches = ($orderItem['selectedSize'] ?? '') === ($reqItem['selectedSize'] ?? '');
                $colorMatches = ($orderItem['selectedColor'] ?? '') === ($reqItem['selectedColor'] ?? '');
                return $idMatches && $sizeMatches && $colorMatches;
            });

            if (!$matchedItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'المنتج المختار غير موجود في الطلب الأصلي.'
                ], 422);
            }

            $purchasedQty = (int)($matchedItem['quantity'] ?? $matchedItem['qty'] ?? 1);
            $returnQty = (int)$reqItem['quantity'];

            if ($returnQty > $purchasedQty) {
                return response()->json([
                    'success' => false,
                    'message' => "الكمية المرتجعة ({$returnQty}) أكبر من الكمية المشتراة ({$purchasedQty}) لمنتج: " . ($matchedItem['name'] ?? '')
                ], 422);
            }

            $price = (float)($matchedItem['price'] ?? 0);
            $refundAmount += $price * $returnQty;

            $returnItems[] = [
                'id' => $matchedItem['id'],
                'name' => $matchedItem['name'],
                'quantity' => $returnQty,
                'price' => $price,
                'selectedSize' => $matchedItem['selectedSize'] ?? null,
                'selectedColor' => $matchedItem['selectedColor'] ?? null,
                'image' => $matchedItem['image'] ?? null,
            ];
        }

        if (empty($returnItems)) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى اختيار منتج واحد على الأقل لإرجاعه.'
            ], 422);
        }

        $orderReturn = OrderReturn::create([
            'tenant_id' => $tenantId,
            'order_id' => $order->id,
            'user_id' => $user->id,
            'items' => $returnItems,
            'reason' => $request->reason,
            'refund_amount' => $refundAmount,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تقديم طلب الإرجاع بنجاح وسنقوم بمراجعته والاتصال بك.',
            'return' => $orderReturn
        ]);
    }

    /**
     * تنسيق بيانات الطلب والجدول الزمني (Order Timeline)
     */
    private function formatOrderDetails(Order $o): array
    {
        $statusLabel = $this->getStatusLabel($o->status ?? 'pending');
        $items = is_string($o->items) ? json_decode($o->items, true) : ($o->items ?? []);

        return [
            'id'                   => $o->id,
            'order_number'         => $o->reference_number ?: $o->id,
            'status'               => $o->status ?? 'pending',
            'status_label'         => $statusLabel,
            'total'                => round((float) $o->total, 2),
            'subtotal'             => round((float) $o->subtotal, 2),
            'shipping_cost'        => round((float) $o->shipping_cost, 2),
            'payment_method'       => $o->payment_method ?? 'cod',
            'payment_method_label' => $this->getPaymentMethodLabel($o->payment_method ?? 'cod'),
            'shipping_address'     => $o->customer_address ?? '',
            'governorate'          => $o->governorate ?? '',
            'customer_name'        => $o->customer_name ?? '',
            'customer_phone'       => $o->customer_phone ?? '',
            'notes'                => $o->notes ?? '',
            'items'                => $items,
            'items_count'          => collect($items)->sum('quantity') ?: collect($items)->sum('qty') ?: count($items),
            'created_at'           => $o->created_at ? $o->created_at->format('Y-m-d H:i') : '',
            'timeline'             => $this->getOrderTimeline($o),
        ];
    }

    /**
     * بناء الجدول الزمني لتتبع حالات الطلب (Order Timeline)
     */
    private function getOrderTimeline(Order $order): array
    {
        $status  = $order->status ?? 'pending';
        $created = $order->created_at ? $order->created_at->format('Y-m-d H:i') : '';
        $updated = $order->updated_at ? $order->updated_at->format('Y-m-d H:i') : $created;

        if ($status === 'cancelled') {
            return [
                [
                    'stage'       => 'pending',
                    'title'       => 'تم استلام الطلب',
                    'desc'        => 'تم تسجيل الطلب في النظام',
                    'date'        => $created,
                    'completed'   => true,
                    'current'     => false,
                ],
                [
                    'stage'       => 'cancelled',
                    'title'       => 'تم إلغاء الطلب',
                    'desc'        => 'تم إلغاء الطلب من قبل المتجر أو العميل',
                    'date'        => $updated,
                    'completed'   => true,
                    'current'     => true,
                    'is_cancelled'=> true,
                ],
            ];
        }

        $stages = [
            'pending'    => ['title' => 'تم استلام الطلب ومراجعته', 'desc' => 'طلبك قيد المراجعة حالياً من فريق العمل'],
            'confirmed'  => ['title' => 'تم تأكيد الطلب',           'desc' => 'تم تأكيد طلبك وجاري البدء في التجهيز'],
            'processing' => ['title' => 'جاري تجهيز وتغليف الطلب', 'desc' => 'يتم الآن تجهيز المنتجات وتغليفها بدقة'],
            'shipped'    => ['title' => 'تم تسليم الطلب لشركة الشحن', 'desc' => 'طلبك مع مندوب الشحن وفي الطريق إليك'],
            'delivered'  => ['title' => 'تم التوصيل بنجاح',         'desc' => 'تم تسليم الطلب للعميل بنجاح'],
        ];

        $stageKeys = array_keys($stages);
        $currentIndex = array_search($status, $stageKeys);
        if ($currentIndex === false) {
            $currentIndex = 0;
        }

        $timeline = [];
        foreach ($stageKeys as $index => $key) {
            $isCompleted = ($index <= $currentIndex);
            $isCurrent   = ($index === $currentIndex);
            $date = '';
            if ($index === 0) {
                $date = $created;
            } elseif ($isCurrent) {
                $date = $updated;
            } elseif ($isCompleted) {
                $date = 'مكتمل ✓';
            }

            $timeline[] = [
                'stage'     => $key,
                'title'     => $stages[$key]['title'],
                'desc'      => $stages[$key]['desc'],
                'date'      => $date,
                'completed' => $isCompleted,
                'current'   => $isCurrent,
            ];
        }

        return $timeline;
    }

    private function getStatusLabel(string $status): array
    {
        $labels = [
            'pending'    => ['text' => 'قيد المراجعة',  'color' => '#f59e0b', 'bg' => '#fef3c7', 'icon' => '⏳'],
            'confirmed'  => ['text' => 'مؤكد',           'color' => '#3b82f6', 'bg' => '#dbeafe', 'icon' => '👍'],
            'processing' => ['text' => 'جاري التجهيز',  'color' => '#8b5cf6', 'bg' => '#ede9fe', 'icon' => '📦'],
            'shipped'    => ['text' => 'في الشحن',       'color' => '#0891b2', 'bg' => '#cffafe', 'icon' => '🚚'],
            'delivered'  => ['text' => 'تم التوصيل',     'color' => '#16a34a', 'bg' => '#dcfce7', 'icon' => '✅'],
            'cancelled'  => ['text' => 'ملغي',           'color' => '#dc2626', 'bg' => '#fee2e2', 'icon' => '❌'],
        ];

        return $labels[$status] ?? ['text' => $status, 'color' => '#888', 'bg' => '#f0f0f0', 'icon' => 'ℹ️'];
    }

    private function getPaymentMethodLabel(string $method): string
    {
        return match ($method) {
            'cod'      => '💵 الدفع عند الاستلام',
            'transfer' => '🏦 تحويل بنكي / إنستاباي',
            'visa'     => '💳 بطاقة ائتمان',
            default    => '💵 ' . $method,
        };
    }

    private function getUserPhone($user, $tenantId = null): string
    {
        try {
            if (!empty($user->phone)) {
                return $user->phone;
            }
        } catch (\Throwable $e) {}

        $phone = Setting::get("user_{$user->id}_phone", null, $tenantId);
        if (!empty($phone)) {
            return $phone;
        }

        $lastOrder = Order::where('tenant_id', $tenantId)
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('customer_email', $user->email);
            })
            ->whereNotNull('customer_phone')
            ->latest()
            ->first();

        if ($lastOrder && !empty($lastOrder->customer_phone)) {
            Setting::set("user_{$user->id}_phone", $lastOrder->customer_phone, 'user_meta', $tenantId);
            return $lastOrder->customer_phone;
        }

        return '';
    }

    private function getUserAddresses($user, $tenantId = null, $defaultPhone = null): array
    {
        $addressesJson = Setting::get("user_{$user->id}_addresses", null, $tenantId);
        $addresses = $addressesJson ? json_decode($addressesJson, true) : [];

        if (empty($addresses) || !is_array($addresses)) {
            $addresses = [];
            $phone = $defaultPhone ?: $this->getUserPhone($user, $tenantId);
            $pastOrders = Order::where('tenant_id', $tenantId)
                ->where(function ($q) use ($user, $phone) {
                    $q->where('user_id', $user->id)
                      ->orWhere('customer_email', $user->email);
                    if (!empty($phone)) {
                        $q->orWhere('customer_phone', $phone);
                    }
                })
                ->whereNotNull('customer_address')
                ->latest()
                ->limit(5)
                ->get();

            $seen = [];
            foreach ($pastOrders as $po) {
                $addrKey = trim($po->customer_address) . '_' . trim($po->governorate ?? '');
                if (!isset($seen[$addrKey]) && !empty($po->customer_address)) {
                    $seen[$addrKey] = true;
                    $addresses[] = [
                        'id'          => uniqid(),
                        'title'       => 'عنوان من الطلب #' . ($po->reference_number ?: $po->id),
                        'address'     => $po->customer_address,
                        'governorate' => $po->governorate ?? '',
                        'phone'       => $po->customer_phone ?: $phone,
                        'is_default'  => count($addresses) === 0,
                    ];
                }
            }

            if (!empty($addresses)) {
                Setting::set("user_{$user->id}_addresses", json_encode($addresses), 'user_meta', $tenantId);
            }
        }

        return $addresses;
    }
}
