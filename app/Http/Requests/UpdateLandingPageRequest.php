<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLandingPageRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'product_id' => ['nullable', 'integer'],
            'template' => ['nullable', 'string', 'max:255'],
            'color_theme' => ['nullable', 'string', 'max:50'],
            'sections' => ['nullable', 'array'],
            'content' => ['nullable', 'array'],
            'custom_css' => ['nullable', 'string'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'string', 'max:255'],
            'facebook_pixel_id' => ['nullable', 'string', 'max:255'],
            'tiktok_pixel_id' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ];
    }
}
