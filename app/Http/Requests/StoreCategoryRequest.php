<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tenantId = session('tenant_id') ?: config('tenant.id');

        return [
            'name_ar'       => ['required', 'string', 'max:255'],
            'name_en'       => ['nullable', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'parent_id'     => [
                'nullable',
                'integer',
                Rule::exists('categories', 'id')->where(function ($query) use ($tenantId) {
                    if ($tenantId) {
                        $query->where('tenant_id', $tenantId);
                    }
                })
            ],
            'main_category' => ['nullable', 'string', 'max:255'],
            'image'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:20480'],
        ];
    }

    public function messages(): array
    {
        return [
            'name_ar.required'       => 'اسم التصنيف مطلوب',
            'name_ar.max'            => 'اسم التصنيف يجب ألا يزيد عن 255 حرفاً',
            'main_category.required' => 'القسم الرئيسي مطلوب',
            'main_category.in'       => 'القسم الرئيسي المحدد غير صحيح',
            'image.image'            => 'يجب أن يكون الملف صورة',
            'image.max'              => 'الحد الأقصى لحجم الصورة 20 ميجابايت',
            'parent_id.exists'       => 'التصنيف الأب المحدد غير موجود أو لا ينتمي لمتجرك',
        ];
    }
}
