<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuRequest extends FormRequest
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
            'location' => ['required', 'string', 'in:header,footer,sidebar,custom'],
            'items' => ['required', 'array'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'اسم القائمة مطلوب',
            'location.required' => 'موقع القائمة مطلوب',
            'location.in' => 'الموقع المحدد غير صالح',
            'items.required' => 'محتويات القائمة مطلوبة',
            'items.array' => 'يجب أن تكون محتويات القائمة مصفوفة صالحة',
        ];
    }
}
