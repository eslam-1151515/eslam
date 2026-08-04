<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\StoreRating;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StoreRatingController extends Controller
{
    /**
     * Display a listing of the store ratings.
     */
    public function index(Request $request): Response
    {
        $ratingsQuery = StoreRating::query();

        $totalCount = (clone $ratingsQuery)->count();
        $avgProducts = round((clone $ratingsQuery)->avg('rating_products') ?? 0, 1);
        $avgShipping = round((clone $ratingsQuery)->avg('rating_shipping') ?? 0, 1);
        $avgService = round((clone $ratingsQuery)->avg('rating_service') ?? 0, 1);

        $overallAvg = 0;
        if ($totalCount > 0) {
            $sumAvg = (clone $ratingsQuery)->selectRaw('AVG((rating_products + rating_shipping + rating_service) / 3.0) as overall')->first()->overall;
            $overallAvg = round($sumAvg ?? 0, 1);
        }

        $ratings = StoreRating::with(['user', 'order'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Merchant/StoreRatings/Index', [
            'ratings' => $ratings,
            'stats' => [
                'total_count' => $totalCount,
                'avg_products' => $avgProducts,
                'avg_shipping' => $avgShipping,
                'avg_service' => $avgService,
                'overall_average' => $overallAvg,
            ]
        ]);
    }

    /**
     * Toggle visibility of a store rating.
     */
    public function toggleVisibility(Request $request, $id)
    {
        $rating = StoreRating::findOrFail($id);
        $rating->is_visible = !$rating->is_visible;
        $rating->save();

        return back()->with('success', 'تم تعديل ظهور التقييم بنجاح');
    }
}
