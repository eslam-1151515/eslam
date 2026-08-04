<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlacklistRecordRequest extends FormRequest
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
            'type' => ['required', 'string', 'in:ip,phone,email'],
            'value' => ['required', 'string', 'max:255'],
            'reason' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'type.required' => 'يجب تحديد نوع الحظر',
            'type.in' => 'نوع الحظر غير صالح',
            'value.required' => 'يجب إدخال القيمة المراد حظرها',
            'value.max' => 'القيمة طويلة جداً',
            'reason.max' => 'السبب طويل جداً',
        ];
    }
}
