<?php

namespace App\Http\Requests;

use App\Models\Coupon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tenantId = session('tenant_id') ?: config('tenant.id');
        $coupon = $this->route('coupon');
        $couponId = $coupon instanceof Coupon ? $coupon->id : $coupon;

        return [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('coupons', 'code')->where('tenant_id', $tenantId)->ignore($couponId)
            ],
            'type' => ['required', 'string', Rule::in(['percentage', 'fixed'])],
            'value' => ['required', 'numeric', 'min:0.01'],
            'min_order_value' => ['nullable', 'numeric', 'min:0'],
            'max_uses' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['boolean']
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'رمز الكوبون مطلوب.',
            'code.unique' => 'رمز الكوبون هذا مستخدم بالفعل.',
            'code.max' => 'رمز الكوبون يجب ألا يتجاوز 50 حرفاً.',
            'type.required' => 'نوع الخصم مطلوب.',
            'type.in' => 'نوع الخصم غير صحيح.',
            'value.required' => 'قيمة الخصم مطلوبة.',
            'value.numeric' => 'قيمة الخصم يجب أن تكون رقماً.',
            'value.min' => 'قيمة الخصم يجب أن تكون أكبر من 0.',
            'min_order_value.numeric' => 'الحد الأدنى للطلب يجب أن يكون رقماً.',
            'min_order_value.min' => 'الحد الأدنى للطلب يجب أن يكون 0 أو أكثر.',
            'max_uses.integer' => 'أقصى عدد للاستخدام يجب أن يكون رقماً صحيحاً.',
            'max_uses.min' => 'أقصى عدد للاستخدام يجب أن يكون 1 أو أكثر.',
            'starts_at.date' => 'تاريخ البدء غير صحيح.',
            'expires_at.date' => 'تاريخ الانتهاء غير صحيح.',
            'expires_at.after_or_equal' => 'تاريخ الانتهاء يجب أن يكون مساوياً أو بعد تاريخ البدء.',
        ];
    }
}
