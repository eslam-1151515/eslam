<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $tenant = $request->attributes->get('tenant');

        $orders = Order::where('tenant_id', $tenant->id)
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $orders->items(),
            'meta' => [
                'total' => $orders->total(),
                'per_page' => $orders->perPage(),
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
            ],
        ]);
    }

    public function show(Request $request, $id): JsonResponse
    {
        $tenant = $request->attributes->get('tenant');
        $order = Order::where('tenant_id', $tenant->id)->findOrFail($id);

        return response()->json(['success' => true, 'data' => $order]);
    }

    public function store(Request $request): JsonResponse
    {
        $tenant = $request->attributes->get('tenant');

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'governorate' => 'nullable|string|max:100',
            'payment_method' => 'nullable|string|max:50',
            'shipping_cost' => 'nullable|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'notes' => 'nullable|string',
        ]);

        $validated['tenant_id'] = $tenant->id;
        $order = Order::createWithReference($validated);

        return response()->json(['success' => true, 'data' => $order], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $tenant = $request->attributes->get('tenant');
        $order = Order::where('tenant_id', $tenant->id)->findOrFail($id);

        $validated = $request->validate([
            'status' => 'sometimes|string|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'notes' => 'nullable|string',
        ]);

        $order->update($validated);

        return response()->json(['success' => true, 'data' => $order]);
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $tenant = $request->attributes->get('tenant');
        $order = Order::where('tenant_id', $tenant->id)->findOrFail($id);
        $order->delete();

        return response()->json(['success' => true, 'message' => 'تم حذف الطلب بنجاح.']);
    }
}
