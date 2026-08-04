<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
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
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:8192'],
            'source' => ['required', 'in:fastorder,shopify,woocommerce'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'file.required' => 'يرجى اختيار ملف للاستيراد.',
            'file.mimes' => 'يجب أن يكون الملف بصيغة CSV.',
            'file.max' => 'الحد الأقصى لحجم الملف هو 8 ميجابايت.',
            'source.required' => 'يرجى تحديد مصدر البيانات.',
            'source.in' => 'مصدر البيانات المحدد غير صالح.',
        ];
    }
}
