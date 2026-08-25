<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
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
            'phone' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'store_name' => ['nullable', 'string', 'max:150'],
            'facebook_pixel_id' => ['nullable', 'string', 'max:5000'],
            'tiktok_pixel_id' => ['nullable', 'string', 'max:5000'],
            'snapchat_pixel_id' => ['nullable', 'string', 'max:5000'],
            'google_analytics_id' => ['nullable', 'string', 'max:5000'],
            'facebook_page' => ['nullable', 'string', 'max:1000'],
            'instagram_page' => ['nullable', 'string', 'max:1000'],
            'tiktok_page' => ['nullable', 'string', 'max:1000'],
            'google_maps_url' => ['nullable', 'string', 'max:1000'],
            'address' => ['nullable', 'string', 'max:500'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:20480'],
            'main_categories' => ['nullable', 'array'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'logo.image' => 'يجب أن يكون ملف الشعار صورة صالحة',
            'logo.mimes' => 'صيغ الشعار المسموح بها: jpg, jpeg, png, webp, gif, svg',
            'logo.max' => 'الحد الأقصى لحجم صورة الشعار 20 ميجابايت',
            'store_name.max' => 'اسم المتجر يجب ألا يتجاوز 150 حرفاً',
            'phone.max' => 'رقم الهاتف يجب ألا يتجاوز 50 حرفاً/رقماً',
            'whatsapp.max' => 'رقم الواتساب يجب ألا يتجاوز 50 حرفاً/رقماً',
            'facebook_pixel_id.max' => 'معرف فيسبوك بيكسل طويل جداً (الحد الأقصى 5000 حرف)',
            'tiktok_pixel_id.max' => 'معرف تيك توك بيكسل طويل جداً (الحد الأقصى 5000 حرف)',
            'snapchat_pixel_id.max' => 'معرف سناب شات بيكسل طويل جداً (الحد الأقصى 5000 حرف)',
            'google_analytics_id.max' => 'معرف جوجل أناليتكس طويل جداً (الحد الأقصى 5000 حرف)',
        ];
    }
}
