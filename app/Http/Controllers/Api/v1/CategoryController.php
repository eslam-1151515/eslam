<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $tenant = $request->attributes->get('tenant');

        $categories = Category::where('tenant_id', $tenant->id)
            ->with('children')
            ->orderBy('name')
            ->paginate(50);

        return response()->json([
            'success' => true,
            'data' => $categories->items(),
            'meta' => [
                'total' => $categories->total(),
                'per_page' => $categories->perPage(),
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
            ],
        ]);
    }

    public function show(Request $request, $id): JsonResponse
    {
        $tenant = $request->attributes->get('tenant');
        $category = Category::where('tenant_id', $tenant->id)
            ->with(['children', 'products'])
            ->findOrFail($id);

        return response()->json(['success' => true, 'data' => $category]);
    }

    public function store(Request $request): JsonResponse
    {
        $tenant = $request->attributes->get('tenant');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $validated['tenant_id'] = $tenant->id;
        $category = Category::create($validated);

        return response()->json(['success' => true, 'data' => $category], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $tenant = $request->attributes->get('tenant');
        $category = Category::where('tenant_id', $tenant->id)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $category->update($validated);

        return response()->json(['success' => true, 'data' => $category]);
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $tenant = $request->attributes->get('tenant');
        $category = Category::where('tenant_id', $tenant->id)->findOrFail($id);
        $category->delete();

        return response()->json(['success' => true, 'message' => 'تم حذف القسم بنجاح.']);
    }
}
