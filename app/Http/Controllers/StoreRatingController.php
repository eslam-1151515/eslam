<?php

namespace App\Http\Controllers;

use App\Models\StoreRating;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreRatingController extends Controller
{
    /**
     * Store a store rating.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id'        => 'nullable|integer',
            'rating_products' => 'required|integer|min:1|max:5',
            'rating_shipping' => 'required|integer|min:1|max:5',
            'rating_service'  => 'required|integer|min:1|max:5',
            'comment'         => 'nullable|string|max:1000',
        ]);

        $tenant = $request->attributes->get('tenant');
        $tenantId = $tenant ? $tenant->id : null;

        $userId = Auth::id();
        $orderId = $request->input('order_id');

        // Verify order exists and belongs to this tenant if provided
        if ($orderId) {
            $order = Order::where('id', $orderId)->first();
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'الطلب غير موجود',
                ], 422);
            }
        }

        // Prevent duplicate ratings for the same order if order_id is provided
        if ($orderId) {
            $existing = StoreRating::where('order_id', $orderId)->first();
            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'لقد قمت بتقييم المتجر لهذا الطلب بالفعل.',
                ], 422);
            }
        }

        $storeRating = StoreRating::create([
            'tenant_id'       => $tenantId,
            'user_id'         => $userId,
            'order_id'        => $orderId,
            'rating_products' => $request->rating_products,
            'rating_shipping' => $request->rating_shipping,
            'rating_service'  => $request->rating_service,
            'comment'         => $request->comment,
            'is_visible'      => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'شكراً لك على تقييمك! آرائك تساعدنا على التحسين المستمر.',
            'rating'  => $storeRating,
        ]);
    }

    /**
     * Get store rating summary for storefront.
     */
    public function summary(Request $request)
    {
        $ratingsQuery = StoreRating::where('is_visible', true);

        $totalCount = (clone $ratingsQuery)->count();
        $avgProducts = round((clone $ratingsQuery)->avg('rating_products') ?? 0, 1);
        $avgShipping = round((clone $ratingsQuery)->avg('rating_shipping') ?? 0, 1);
        $avgService = round((clone $ratingsQuery)->avg('rating_service') ?? 0, 1);

        $overallAvg = 0;
        if ($totalCount > 0) {
            $sumAvg = (clone $ratingsQuery)->selectRaw('AVG((rating_products + rating_shipping + rating_service) / 3.0) as overall')->first()->overall;
            $overallAvg = round($sumAvg ?? 0, 1);
        }

        $recentComments = StoreRating::where('is_visible', true)
            ->whereNotNull('comment')
            ->where('comment', '!=', '')
            ->with('user')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function($r) {
                return [
                    'user_name' => $r->user ? $r->user->name : 'عميل',
                    'comment' => $r->comment,
                    'created_at' => $r->created_at->diffForHumans(),
                    'overall_rating' => round(($r->rating_products + $r->rating_shipping + $r->rating_service) / 3.0, 1)
                ];
            });

        return response()->json([
            'stats' => [
                'total_count' => $totalCount,
                'avg_products' => $avgProducts,
                'avg_shipping' => $avgShipping,
                'avg_service' => $avgService,
                'overall_average' => $overallAvg,
            ],
            'recent_comments' => $recentComments
        ]);
    }
}
