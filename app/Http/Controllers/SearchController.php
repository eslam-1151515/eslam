<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $request->validate(['q' => 'required|string|min:1|max:100']);
        $tenant = $request->attributes->get('tenant');
        $query = trim($request->q);

        // Save search history in session
        $searches = session('search_history_' . $tenant->id, []);
        if (!in_array($query, $searches)) {
            array_unshift($searches, $query);
            $searches = array_slice($searches, 0, 10);
            session(['search_history_' . $tenant->id => $searches]);
        }

        $products = Product::where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('sku', 'LIKE', "%{$query}%");
            })
            ->with('category')
            ->limit(20)
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'price' => $p->price,
                'sale_price' => $p->sale_price,
                'image' => $p->images[0] ?? null,
                'slug' => $p->slug,
                'category' => $p->category?->name,
            ]);

        $categories = Category::where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->where('name', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'slug' => $c->slug]);

        return response()->json([
            'success' => true,
            'query' => $query,
            'products' => $products,
            'categories' => $categories,
            'total' => $products->count(),
        ]);
    }

    public function suggestions(Request $request)
    {
        $request->validate(['q' => 'required|string|min:1']);
        $tenant = $request->attributes->get('tenant');
        $query = trim($request->q);

        $products = Product::where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->where('name', 'LIKE', "%{$query}%")
            ->limit(6)
            ->pluck('name')
            ->toArray();

        $categories = Category::where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->where('name', 'LIKE', "%{$query}%")
            ->limit(3)
            ->pluck('name')
            ->map(fn($n) => '📂 ' . $n)
            ->toArray();

        return response()->json([
            'suggestions' => array_merge($products, $categories),
        ]);
    }

    public function history(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        return response()->json(['history' => session('search_history_' . $tenant->id, [])]);
    }

    public function popular(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        $searches = session('search_history_' . $tenant->id, []);
        return response()->json(['popular' => array_slice($searches, 0, 5)]);
    }
}
