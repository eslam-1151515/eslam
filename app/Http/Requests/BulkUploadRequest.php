<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkUploadRequest extends FormRequest
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
            'file' => ['required', 'file', 'max:5120'], // 5MB كحد أقصى
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'file.required' => 'يرجى اختيار ملف للرفع',
            'file.file' => 'الملف المرفوع غير صحيح',
            'file.max' => 'الحد الأقصى لحجم الملف هو 5 ميجابايت',
        ];
    }
}
