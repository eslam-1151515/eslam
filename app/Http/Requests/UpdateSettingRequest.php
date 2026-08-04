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
            'phone' => ['nullable', 'string', 'regex:/^\+?[0-9]{10,15}$/'],
            'whatsapp' => ['nullable', 'string', 'regex:/^\+?[0-9]{10,15}$/'],
            'store_name' => ['nullable', 'string', 'max:100'],
            'facebook_pixel_id' => ['nullable', 'string', 'max:50'],
            'tiktok_pixel_id' => ['nullable', 'string', 'max:50'],
            'snapchat_pixel_id' => ['nullable', 'string', 'max:50'],
            'google_analytics_id' => ['nullable', 'string', 'max:50'],
            'facebook_page' => ['nullable', 'url', 'max:500'],
            'instagram_page' => ['nullable', 'url', 'max:500'],
            'tiktok_page' => ['nullable', 'url', 'max:500'],
            'google_maps_url' => ['nullable', 'url', 'max:1000'],
            'address' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:20480'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'phone.regex' => 'برجاء كتابة الرقم بشكل صحيح بدون مسافات أو حروف (أرقام فقط من 10 إلى 15 رقم)',
            'whatsapp.regex' => 'برجاء كتابة الرقم بشكل صحيح بدون مسافات أو حروف (أرقام فقط من 10 إلى 15 رقم)',
            'logo.image' => 'يجب أن يكون الملف صورة',
            'logo.mimes' => 'الصيغ المسموح بها: jpg, jpeg, png, webp, gif, svg',
            'logo.max' => 'الحد الأقصى لحجم الصورة 20 ميجابايت',
            'facebook_page.url' => 'برجاء كتابة رابط الفيسبوك بشكل صحيح (مثال: https://facebook.com/yourpage)',
            'instagram_page.url' => 'برجاء كتابة رابط الانستجرام بشكل صحيح (مثال: https://instagram.com/yourprofile)',
            'tiktok_page.url' => 'برجاء كتابة رابط التيك توك بشكل صحيح (مثال: https://tiktok.com/@yourprofile)',
            'google_maps_url.url' => 'برجاء كتابة رابط موقع الخريطة بشكل صحيح',
        ];
    }
}
