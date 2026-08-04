<?php

namespace App\Http\Controllers;

use App\Models\ShippingGovernorate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ShippingController extends Controller
{
    public function index()
    {
        $governorates = ShippingGovernorate::orderBy('name')->get();
        return view('shipping.index', compact('governorates'));
    }

    public function update(Request $request, ShippingGovernorate $governorate)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'integer', 'min:0'], // تغيير من numeric إلى integer
            'is_active' => ['boolean']
        ], [
            'name.required' => 'اسم المحافظة مطلوب',
            'name.max' => 'اسم المحافظة يجب ألا يزيد عن 255 حرفًا',
            'price.required' => 'سعر الشحن مطلوب',
            'price.integer' => 'سعر الشحن يجب أن يكون رقمًا صحيحًا',
            'price.min' => 'سعر الشحن لا يمكن أن يكون أقل من صفر',
        ]);

        // التأكد من وجود is_active في البيانات
        $validated['is_active'] = $request->has('is_active');

        $governorate->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث بيانات المحافظة بنجاح',
            'governorate' => $governorate->fresh()
        ]);
    }

    public function toggleStatus(ShippingGovernorate $governorate)
    {
        $governorate->update([
            'is_active' => !$governorate->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => $governorate->is_active ? 'تم تفعيل المحافظة' : 'تم إلغاء تفعيل المحافظة',
            'is_active' => $governorate->is_active
        ]);
    }
}
