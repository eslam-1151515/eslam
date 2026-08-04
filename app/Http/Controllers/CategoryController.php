<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $categories = Category::query()
            ->when($q, function ($query) use ($q) {
                $like = "%" . str_replace(['%','_'], ['\\%','\\_'], $q) . "%";
                $query->where(function ($q2) use ($like) {
                    $q2->where('name', 'like', $like)
                       ->orWhere('name_ar', 'like', $like)
                       ->orWhere('name_en', 'like', $like)
                       ->orWhere('description', 'like', $like);
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('categories.index', compact('categories', 'q'));
    }

    public function create()
    {
    $parents = Category::orderByRaw("COALESCE(NULLIF(name_ar, ''), name) ASC")->get(['id','name','name_ar']);
        return view('categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_ar' => ['required','string','max:255'],
            'name_en' => ['nullable','string','max:255'],
            'description' => ['nullable','string'],
            'parent_id' => ['nullable', 'integer', Rule::exists('categories','id')],
            'main_category' => ['required', Rule::in(Category::getMainCategories())],
            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ], [
            'name_ar.required' => 'اسم القسم مطلوب',
            'name_ar.max' => 'اسم القسم يجب ألا يزيد عن 255 حرفًا',
            'main_category.required' => 'القسم الرئيسي مطلوب',
            'main_category.in' => 'القسم الرئيسي المحدد غير صحيح',
            'image.image' => 'يجب أن يكون الملف صورة',
            'image.mimes' => 'الصيغ المسموح بها: jpg, jpeg, png, webp',
            'image.max' => 'الحد الأقصى لحجم الصورة 2 ميجابايت',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $validated['image_path'] = $path;
        }

        // keep legacy 'name' in sync with Arabic name for backward compatibility
        $validated['name'] = $validated['name_ar'];

        Category::create($validated);

        return redirect()->route('categories.index')->with('status', 'تم إضافة القسم بنجاح');
    }

    public function edit(Category $category)
    {
        $parents = Category::where('id', '!=', $category->id)
            ->orderByRaw("COALESCE(NULLIF(name_ar, ''), name) ASC")
            ->get(['id','name','name_ar']);
        return view('categories.edit', compact('category','parents'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name_ar' => ['required','string','max:255'],
            'name_en' => ['nullable','string','max:255'],
            'description' => ['nullable','string'],
            'parent_id' => ['nullable', 'integer', Rule::exists('categories','id'), Rule::notIn([$category->id])],
            'main_category' => ['required', Rule::in(Category::getMainCategories())],
            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ], [
            'name_ar.required' => 'اسم القسم مطلوب',
            'name_ar.max' => 'اسم القسم يجب ألا يزيد عن 255 حرفًا',
            'main_category.required' => 'القسم الرئيسي مطلوب',
            'main_category.in' => 'القسم الرئيسي المحدد غير صحيح',
            'image.image' => 'يجب أن يكون الملف صورة',
            'image.mimes' => 'الصيغ المسموح بها: jpg, jpeg, png, webp',
            'image.max' => 'الحد الأقصى لحجم الصورة 2 ميجابايت',
            'parent_id.not_in' => 'لا يمكن اختيار القسم نفسه كقسم أب',
        ]);

        if ($request->hasFile('image')) {
            // delete old if exists
            if ($category->image_path && \Storage::disk('public')->exists($category->image_path)) {
                \Storage::disk('public')->delete($category->image_path);
            }
            $path = $request->file('image')->store('categories', 'public');
            $validated['image_path'] = $path;
        }

        // إذا لم يتم إدخال اسم، استخدم اسم القسم الرئيسي
        if (empty($validated['name_ar'])) {
            $validated['name_ar'] = $validated['main_category'];
        }

        // sync legacy name
        $validated['name'] = $validated['name_ar'];

        $category->update($validated);

        return redirect()->route('categories.index')->with('status', 'تم تحديث القسم بنجاح');
    }

    public function destroy(Category $category)
    {
        if ($category->image_path && \Storage::disk('public')->exists($category->image_path)) {
            \Storage::disk('public')->delete($category->image_path);
        }
        $category->delete();
        return redirect()->route('categories.index')->with('status', 'تم حذف القسم بنجاح');
    }
}
