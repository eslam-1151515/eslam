<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewReplyRequest;
use App\Http\Requests\HelpfulReviewRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\WebhookSender;

class ReviewController extends Controller
{
    public function index(Request $request, $productId)
    {
        $tenant = $request->attributes->get('tenant');

        // دعم البحث بالـ ID أو الـ slug
        $product = is_numeric($productId)
            ? Product::where('tenant_id', $tenant->id)->where('id', $productId)->firstOrFail()
            : Product::where('tenant_id', $tenant->id)->where('slug', $productId)->firstOrFail();

        $baseQuery = Review::where('product_id', $product->id)->where('is_approved', true);

        // حساب ملخص التقييمات (Distribution bar) قبل أي فلترة
        $totalCount = (clone $baseQuery)->count();
        $avgRating = round((clone $baseQuery)->avg('rating') ?? 0, 1);
        
        $rawDist = (clone $baseQuery)
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        $distribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $distribution[$i] = (int) ($rawDist[$i] ?? 0);
        }

        $stats = [
            'average'      => $avgRating,
            'total'        => $totalCount,
            'distribution' => $distribution,
        ];

        // تطبيق الفلترة حسب التقييم إن وجدت
        $query = clone $baseQuery;
        if ($request->has('rating') && (int) $request->rating > 0 && (int) $request->rating <= 5) {
            $query->where('rating', (int) $request->rating);
        }

        // تطبيق الترتيب (الأحدث أو الأكثر فائدة)
        $sort = $request->input('sort', 'latest');
        if ($sort === 'helpful') {
            $query->orderByDesc('helpful_count')->orderByDesc('created_at');
        } else {
            $query->latest();
        }

        $reviews = $query->with('user')->paginate(10);

        // تهيئة المراجعات للواجهة الأمامية
        $formattedReviews = collect($reviews->items())->map(function ($review) {
            $imgs = [];
            if (is_array($review->images)) {
                foreach ($review->images as $img) {
                    if (is_string($img)) {
                        $imgs[] = str_starts_with($img, 'http') ? $img : asset('storage/' . $img);
                    }
                }
            }
            $review->formatted_images = $imgs;
            $review->user_name = $review->user ? $review->user->name : 'عميل';
            return $review;
        });

        return response()->json([
            'reviews'  => $formattedReviews,
            'stats'    => $stats,
            'has_more' => $reviews->hasMorePages(),
        ]);
    }

    public function store(StoreReviewRequest $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success'       => false,
                'message'       => 'يجب تسجيل الدخول أولاً لكتابة مراجعة',
                'requires_auth' => true,
            ], 401);
        }

        $validated = $request->validated();

        $tenant = $request->attributes->get('tenant');

        // تحقق من أن المنتج ينتمي للتينانت الفعال
        $product = Product::where('tenant_id', $tenant->id)->where('id', $request->product_id)->first();
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'المنتج غير موجود أو لا ينتمي لهذا المتجر',
            ], 404);
        }

        $existing = Review::where('product_id', $request->product_id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'لقد قمت بمراجعة وتقييم هذا المنتج من قبل',
            ], 422);
        }

        // التحقق من كونه مشتريًا فعليًا (الطلبات تُخزَّن كـ JSON items)
        $isVerified = Order::where('tenant_id', $tenant->id)
            ->where('user_id', Auth::id())
            ->where(function ($query) use ($request) {
                $pid = $request->product_id;
                $query->whereRaw("JSON_SEARCH(items, 'one', ?, NULL, '$[*].product_id') IS NOT NULL", [(string) $pid])
                      ->orWhereRaw("JSON_SEARCH(items, 'one', ?, NULL, '$[*].product_id') IS NOT NULL", [(int) $pid])
                      ->orWhere('items', 'like', '%"product_id":' . ((int) $pid) . '%')
                      ->orWhere('items', 'like', '%"product_id":"' . ((int) $pid) . '"%')
                      ->orWhere('items', 'like', '%"id":' . ((int) $pid) . '%')
                      ->orWhere('items', 'like', '%"id":"' . ((int) $pid) . '"%');
            })
            ->exists();

        // معالجة الصور المرفقة (سواء ملفات أو Base64 أو روابط)
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('reviews', 'public');
                    $imagePaths[] = $path;
                }
            }
        } elseif ($request->has('images') && is_array($request->input('images'))) {
            foreach ($request->input('images') as $img) {
                if (is_string($img) && str_starts_with($img, 'data:image')) {
                    $parts = explode(',', $img);
                    if (count($parts) > 1) {
                        $decoded = base64_decode($parts[1]);
                        if ($decoded !== false) {
                            $filename = 'reviews/' . uniqid('rev_', true) . '.png';
                            Storage::disk('public')->put($filename, $decoded);
                            $imagePaths[] = $filename;
                        }
                    }
                } elseif (is_string($img) && !empty(trim($img))) {
                    $imagePaths[] = trim($img);
                }
            }
        }

        $review = Review::create([
            'tenant_id'            => $tenant->id,
            'product_id'           => $request->product_id,
            'user_id'              => Auth::id(),
            'rating'               => $request->rating,
            'title'                => $request->title,
            'body'                 => $request->body,
            'images'               => !empty($imagePaths) ? $imagePaths : null,
            'is_verified_purchase' => $isVerified,
            'is_approved'          => true, // الاعتماد التلقائي للمراجعات
        ]);

        // إرسال إشعار برمجياً عند إضافة مراجعة جديدة
        try {
            Log::info("New review created for product {$request->product_id} by user " . Auth::id());
            if (class_exists(WebhookSender::class)) {
                WebhookSender::trigger('review.created', [
                    'review_id'            => $review->id,
                    'product_id'           => $review->product_id,
                    'user_id'              => $review->user_id,
                    'rating'               => $review->rating,
                    'title'                => $review->title,
                    'body'                 => $review->body,
                    'is_verified_purchase' => $review->is_verified_purchase,
                    'created_at'           => $review->created_at->toIso8601String(),
                ], $tenant->id);
            }
        } catch (\Exception $e) {
            Log::error("Failed to trigger review webhook/notification: " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'شكراً لك! تم إضافة مراجعتك بنجاح.',
            'review'  => $review
        ]);
    }

    public function helpful(HelpfulReviewRequest $request, Review $review)
    {
        $tenant = $request->attributes->get('tenant');
        if ($review->tenant_id !== $tenant->id) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بالوصول لهذه المراجعة',
            ], 403);
        }

        $validated = $request->validated();
        $action = $request->input('action', 'helpful');
        
        if ($action === 'unhelpful') {
            if ($review->helpful_count > 0) {
                $review->decrement('helpful_count');
            }
        } else {
            $review->increment('helpful_count');
        }

        return response()->json([
            'success' => true,
            'count'   => $review->helpful_count,
            'action'  => $action
        ]);
    }

    public function reply(UpdateReviewReplyRequest $request, Review $review)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'غير مصرح لك'], 401);
        }

        $tenant = $request->attributes->get('tenant') ?? session()->get('tenant_id') ?? config('tenant.id');
        $tenantId = is_object($tenant) ? $tenant->id : $tenant;

        if ($review->tenant_id !== $tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بالوصول لهذه المراجعة',
            ], 403);
        }

        $validated = $request->validated();

        $review->update([
            'merchant_reply' => $request->merchant_reply,
            'replied_at'     => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ الرد على المراجعة بنجاح',
            'review'  => $review
        ]);
    }
}
