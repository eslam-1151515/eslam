<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'يجب تسجيل الدخول', 'requires_auth' => true], 401);
        }
        $tenant = $request->attributes->get('tenant');
        $items = Wishlist::where('user_id', Auth::id())
            ->where('tenant_id', $tenant->id)
            ->with('product')
            ->get()
            ->map(fn($w) => [
                'id' => $w->id,
                'product_id' => $w->product_id,
                'name' => $w->product->name,
                'price' => $w->product->price,
                'sale_price' => $w->product->sale_price,
                'image' => $w->product->images[0] ?? null,
                'slug' => $w->product->slug,
                'in_stock' => ($w->product->stock ?? 0) > 0,
            ]);
        return response()->json(['success' => true, 'items' => $items, 'count' => $items->count()]);
    }

    public function toggle(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'يجب تسجيل الدخول', 'requires_auth' => true], 401);
        }
        $request->validate(['product_id' => 'required|integer']);
        $tenant = $request->attributes->get('tenant');
        $existing = Wishlist::where('user_id', Auth::id())
            ->where('tenant_id', $tenant->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existing) {
            $existing->delete();
            $added = false;
        } else {
            Wishlist::create(['user_id' => Auth::id(), 'tenant_id' => $tenant->id, 'product_id' => $request->product_id]);
            $added = true;
        }

        $count = Wishlist::where('user_id', Auth::id())->where('tenant_id', $tenant->id)->count();
        return response()->json(['success' => true, 'added' => $added, 'count' => $count]);
    }

    public function check(Request $request)
    {
        if (!Auth::check()) return response()->json(['in_wishlist' => false]);
        $tenant = $request->attributes->get('tenant');
        $productIds = explode(',', $request->product_ids ?? '');
        $wishlisted = Wishlist::where('user_id', Auth::id())
            ->where('tenant_id', $tenant->id)
            ->whereIn('product_id', $productIds)
            ->pluck('product_id')
            ->toArray();
        return response()->json(['in_wishlist' => $wishlisted]);
    }

    public function remove(Request $request, Wishlist $wishlist)
    {
        abort_unless(Auth::id() === $wishlist->user_id, 403);
        $wishlist->delete();
        return response()->json(['success' => true]);
    }
}
