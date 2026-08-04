<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\ShippingGovernorate;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShippingController extends Controller
{
    public function index()
    {
        $governorates = ShippingGovernorate::orderBy('name')->get();
        
        return Inertia::render('Merchant/Shipping/Index', [
            'governorates' => $governorates,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'price'     => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ], [
            'name.required'  => 'اسم المحافظة مطلوب',
            'price.required' => 'سعر الشحن مطلوب',
            'price.integer'  => 'سعر الشحن يجب أن يكون رقمًا صحيحًا',
            'price.min'      => 'سعر الشحن لا يمكن أن يكون أقل من صفر',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        ShippingGovernorate::create($validated);

        return redirect()->route('shipping.index')
            ->with('success', 'تمت إضافة المحافظة بنجاح');
    }

    public function update(Request $request, ShippingGovernorate $governorate)
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'price'     => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ], [
            'name.required'  => 'اسم المحافظة مطلوب',
            'name.max'       => 'اسم المحافظة يجب ألا يزيد عن 255 حرفًا',
            'price.required' => 'سعر الشحن مطلوب',
            'price.integer'  => 'سعر الشحن يجب أن يكون رقمًا صحيحًا',
            'price.min'      => 'سعر الشحن لا يمكن أن يكون أقل من صفر',
        ]);

        $validated['is_active'] = $request->boolean('is_active', false);

        $governorate->update($validated);

        return redirect()->route('shipping.index')
            ->with('success', 'تم تحديث بيانات المحافظة بنجاح');
    }

    public function toggleStatus(ShippingGovernorate $governorate)
    {
        $governorate->update([
            'is_active' => !$governorate->is_active
        ]);

        return redirect()->route('shipping.index')
            ->with('success', $governorate->is_active ? 'تم تفعيل المحافظة' : 'تم إلغاء تفعيل المحافظة');
    }

    public function destroy(ShippingGovernorate $governorate)
    {
        $governorate->delete();

        return redirect()->route('shipping.index')
            ->with('success', 'تم حذف المحافظة بنجاح');
    }
}

