<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tenantId = session('tenant_id') ?: config('tenant.id');
        $category = $this->route('category');
        $categoryId = $category instanceof Category ? $category->id : $category;

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
                }),
                Rule::notIn([$categoryId])
            ],
            'main_category' => ['nullable', 'string', 'max:255'],
            'image'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:20480'],
        ];
    }

    public function messages(): array
    {
        return [
            'name_ar.required'       => 'اسم التصنيف مطلوب',
            'main_category.required' => 'القسم الرئيسي مطلوب',
            'main_category.in'       => 'القسم الرئيسي المحدد غير صحيح',
            'parent_id.not_in'       => 'لا يمكن اختيار التصنيف نفسه كتصنيف أب',
            'image.image'            => 'يجب أن يكون الملف صورة',
            'image.max'              => 'الحد الأقصى لحجم الصورة 20 ميجابايت',
            'parent_id.exists'       => 'التصنيف الأب المحدد غير موجود أو لا ينتمي لمتجرك',
        ];
    }
}
