<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePromotionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', Rule::in(['flash_sale', 'seasonal', 'clearance', 'bundle'])],
            'discount_type' => ['required', 'string', Rule::in(['percentage', 'fixed'])],
            'discount_value' => ['required', 'numeric', 'min:0.01'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'banner_image' => ['nullable', 'string', 'max:2048'],
            'is_active' => ['boolean'],
            'products' => ['nullable', 'array'],
            'products.*' => ['integer', 'exists:products,id'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['integer', 'exists:categories,id'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'اسم العرض الترويجي مطلوب.',
            'type.required' => 'نوع العرض مطلوب.',
            'type.in' => 'نوع العرض غير صحيح.',
            'discount_type.required' => 'نوع الخصم مطلوب.',
            'discount_value.required' => 'قيمة الخصم مطلوبة.',
            'discount_value.numeric' => 'قيمة الخصم يجب أن تكون رقماً.',
            'discount_value.min' => 'قيمة الخصم يجب أن تكون أكبر من 0.',
            'ends_at.after_or_equal' => 'تاريخ الانتهاء يجب أن يكون مساوياً أو بعد تاريخ البدء.',
        ];
    }
}
