<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\UpdateCouponRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CouponController extends Controller
{
    /**
     * عرض قائمة الكوبونات
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q', ''));

        $coupons = Coupon::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('code', 'like', '%' . $q . '%');
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // تنسيق التواريخ للعرض
        $coupons->getCollection()->transform(function ($coupon) {
            $coupon->starts_at_formatted = $coupon->starts_at ? $coupon->starts_at->format('Y-m-d') : null;
            $coupon->expires_at_formatted = $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : null;
            return $coupon;
        });

        $stats = [
            'total' => Coupon::count(),
            'active' => Coupon::active()->count(),
            'total_uses' => (int) Coupon::sum('uses_count'),
        ];

        return Inertia::render('Merchant/Coupons/Index', [
            'coupons' => $coupons,
            'filters' => [
                'q' => $q
            ],
            'stats' => $stats,
        ]);
    }

    /**
     * حفظ كوبون جديد
     */
    public function store(StoreCouponRequest $request)
    {
        $validated = $request->validated();

        // تعيين القيمة الافتراضية لـ is_active إذا لم ترسَل
        $validated['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : true;

        Coupon::create($validated);

        return redirect()->route('merchant.coupons.index')
            ->with('success', 'تم إنشاء الكوبون بنجاح ✓');
    }

    /**
     * تحديث كوبون
     */
    public function update(UpdateCouponRequest $request, Coupon $coupon)
    {
        $validated = $request->validated();

        $validated['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : $coupon->is_active;

        $coupon->update($validated);

        return redirect()->route('merchant.coupons.index')
            ->with('success', 'تم تحديث الكوبون بنجاح ✓');
    }

    /**
     * تفعيل/تعطيل حالة الكوبون
     */
    public function toggle(Coupon $coupon)
    {
        $coupon->update([
            'is_active' => !$coupon->is_active
        ]);

        $message = $coupon->is_active ? 'تم تفعيل الكوبون بنجاح ✓' : 'تم تعطيل الكوبون بنجاح ✓';
        return redirect()->route('merchant.coupons.index')->with('success', $message);
    }

    /**
     * حذف كوبون
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('merchant.coupons.index')
            ->with('success', 'تم حذف الكوبون بنجاح ✓');
    }
}
