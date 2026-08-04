<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\AbandonedCart;
use App\Mail\AbandonedCartRecoveryMail;
use App\Http\Requests\SendReminderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class AbandonedCartController extends Controller
{
    /**
     * عرض قائمة السلات المتروكة مع الإحصائيات
     */
    public function index(Request $request): Response
    {
        $search = trim((string) $request->input('search', ''));
        $status = trim((string) $request->input('status', '')); // 'recovered', 'pending'

        $query = AbandonedCart::query();

        // الفلترة بالبحث
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('cart_data', 'like', "%{$search}%");
            });
        }

        // الفلترة بالحالة
        if ($status === 'recovered') {
            $query->whereNotNull('recovered_at');
        } elseif ($status === 'pending') {
            $query->whereNull('recovered_at');
        }

        // جلب السجلات مع الصفحات
        $records = $query->latest()
            ->paginate(15)
            ->withQueryString();

        // حساب الإحصائيات المتقدمة
        $allCarts = AbandonedCart::all();
        $totalCarts = $allCarts->count();
        $recoveredCarts = $allCarts->whereNotNull('recovered_at')->count();
        $pendingCarts = $totalCarts - $recoveredCarts;

        $recoveryRate = $totalCarts > 0 ? round(($recoveredCarts / $totalCarts) * 100, 1) : 0;

        $lostValue = $allCarts->whereNull('recovered_at')->sum(fn($c) => $c->cart_data['total'] ?? 0);
        $recoveredValue = $allCarts->whereNotNull('recovered_at')->sum(fn($c) => $c->cart_data['total'] ?? 0);

        return Inertia::render('Merchant/AbandonedCarts/Index', [
            'records' => $records,
            'statistics' => [
                'total_carts' => $totalCarts,
                'recovered_carts' => $recoveredCarts,
                'pending_carts' => $pendingCarts,
                'recovery_rate' => $recoveryRate,
                'lost_value' => round($lostValue, 2),
                'recovered_value' => round($recoveredValue, 2),
            ],
            'filters' => [
                'search' => $search,
                'status' => $status,
            ]
        ]);
    }

    /**
     * إرسال تذكير بالبريد الإلكتروني للعميل بشكل يدوي
     */
    public function sendReminder(SendReminderRequest $request, AbandonedCart $abandonedCart)
    {
        // التحقق من وجود بريد إلكتروني
        if (empty($abandonedCart->email)) {
            return back()->with('error', 'لا يمكن إرسال تذكير لعدم وجود بريد إلكتروني مسجل لهذه السلة.');
        }

        // التحقق من عدم استرداد السلة بالفعل
        if ($abandonedCart->recovered_at) {
            return back()->with('error', 'هذه السلة تم استعادتها بالفعل بنجاح.');
        }

        $validated = $request->validated();

        try {
            $discountCode = $request->input('discount_code');
            $discountPercentage = $request->input('discount_percentage');
            $locale = $request->input('locale', 'ar');

            // إرسال البريد الإلكتروني
            Mail::to($abandonedCart->email)->send(
                new AbandonedCartRecoveryMail(
                    $abandonedCart,
                    $discountCode,
                    $discountPercentage,
                    $locale
                )
            );

            // تحديث وقت إرسال التنبيه
            $abandonedCart->update([
                'recovery_email_sent_at' => now(),
            ]);

            return back()->with('success', 'تم إرسال بريد التذكير الترويجي بنجاح ✓');
        } catch (\Exception $e) {
            \Log::error('Failed to send abandoned cart recovery mail: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء إرسال البريد الإلكتروني: ' . $e->getMessage());
        }
    }

    /**
     * حذف سجل سلة متروكة
     */
    public function destroy(AbandonedCart $abandonedCart)
    {
        $abandonedCart->delete();

        return back()->with('success', 'تم حذف سجل السلة المتروكة بنجاح ✓');
    }
}
