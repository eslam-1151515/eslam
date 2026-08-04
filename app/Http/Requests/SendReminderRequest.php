<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendReminderRequest extends FormRequest
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
            'discount_code' => ['nullable', 'string', 'max:50'],
            'discount_percentage' => ['nullable', 'numeric', 'min:1', 'max:100'],
            'locale' => ['required', 'string', 'in:ar,en'],
        ];
    }
}
