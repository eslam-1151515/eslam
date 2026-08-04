<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\ThemeMarketplace;
use App\Services\ThemeService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class ThemeMarketplaceController extends Controller
{
    /**
     * @var ThemeService
     */
    protected ThemeService $themeService;

    /**
     * Inject ThemeService into the controller.
     */
    public function __construct(ThemeService $themeService)
    {
        $this->themeService = $themeService;
    }

    /**
     * Display the Theme Marketplace page with filtering, search, and categorization.
     */
    public function index(Request $request)
    {
        $typeFilter = $request->query('type');
        $searchQuery = $request->query('search');

        $themes = ThemeMarketplace::getAvailableThemes($typeFilter, $searchQuery);
        $activeThemeSlug = $this->themeService->getActiveTheme();

        // حساب الإحصائيات العامة للسوق
        $allThemes = ThemeMarketplace::getAvailableThemes();
        $stats = [
            'total' => $allThemes->count(),
            'free' => $allThemes->where('type', 'free')->count(),
            'paid' => $allThemes->where('type', 'paid')->count(),
        ];

        $data = [
            'themes' => $themes,
            'activeTheme' => $activeThemeSlug,
            'filters' => [
                'type' => $typeFilter ?? 'all',
                'search' => $searchQuery ?? '',
            ],
            'stats' => $stats,
        ];

        // Return JSON if requested explicitly via API/AJAX
        if ($request->wantsJson() && !$request->header('X-Inertia')) {
            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
        }

        // Render Inertia component if Inertia is available and blade view not explicitly requested
        if (class_exists(\Inertia\Inertia::class) && ($request->header('X-Inertia') || $request->query('view') !== 'blade')) {
            return Inertia::render('Merchant/Themes/Marketplace', $data);
        }

        // Fallback to Blade view
        return view('merchant.themes.marketplace', $data);
    }

    /**
     * Display detailed information, compatibility, and reviews for a specific theme.
     */
    public function show(Request $request, string $slug)
    {
        $theme = ThemeMarketplace::findBySlug($slug);

        if (!$theme) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'الثيم المطلوب غير موجود في السوق.'], 404);
            }
            return redirect()->route('merchant.themes.marketplace')->with('error', 'الثيم المطلوب غير موجود في السوق.');
        }

        $activeThemeSlug = $this->themeService->getActiveTheme();

        $data = [
            'theme' => $theme,
            'activeTheme' => $activeThemeSlug,
        ];

        if ($request->wantsJson() && !$request->header('X-Inertia')) {
            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
        }

        if (class_exists(\Inertia\Inertia::class) && ($request->header('X-Inertia') || $request->query('view') !== 'blade')) {
            return Inertia::render('Merchant/Themes/MarketplaceShow', $data);
        }

        return view('merchant.themes.marketplace_show', $data);
    }

    /**
     * Activate or install a theme for the current merchant store.
     */
    public function install(Request $request, string $slug)
    {
        $theme = ThemeMarketplace::findBySlug($slug);

        if (!$theme) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'الثيم المطلوب غير صالح أو غير متاح.'], 404);
            }
            return redirect()->back()->with('error', 'الثيم المطلوب غير صالح أو غير متاح.');
        }

        // في الثيمات المدفوعة يمكن إضافة منطق التحقق من شراء الثيم أو الاشتراك
        // يتم هنا تفعيل الثيم في إعدادات المتجر عبر ThemeService
        $success = $this->themeService->setActiveTheme($slug);

        if (!$success && $slug !== 'default') {
            // إذا كان الثيم في السوق ولكنه ليس مثبتاً في المجلدات بعد، نقوم بتفعيله في الإعدادات على أي حال
            \App\Models\Setting::set('active_theme', $slug, 'theme', session()->get('tenant_id'));
            $this->themeService->clearThemeCache(session()->get('tenant_id'));
        }

        $themeName = is_array($theme) ? ($theme['name'] ?? $slug) : $theme->name;

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => "تم تفعيل ثيم ({$themeName}) بنجاح وأصبح هو الثيم النشط لمتجرك ✓",
                'activeTheme' => $slug,
            ]);
        }

        return redirect()->back()->with('success', "تم تفعيل ثيم ({$themeName}) بنجاح وأصبح هو الثيم النشط لمتجرك ✓");
    }

    /**
     * Store a merchant review and rating for a theme.
     */
    public function storeReview(Request $request, string $slug)
    {
        $request->validate([
            'rating' => ['required', 'numeric', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:1000'],
            'reviewer' => ['nullable', 'string', 'max:100'],
        ], [
            'rating.required' => 'يرجى تحديد التقييم من 1 إلى 5 نجوم',
            'rating.min' => 'التقييم لا يمكن أن يقل عن 1',
            'rating.max' => 'التقييم لا يمكن أن يزيد عن 5 نجوم',
            'comment.required' => 'يرجى كتابة تعليق أو مراجعة للثيم',
            'comment.max' => 'التعليق يجب ألا يتجاوز 1000 حرف',
        ]);

        $theme = ThemeMarketplace::where('slug', $slug)->first();

        // إذا لم يكن الثيم في قاعدة البيانات بعد (يستخدم البيانات الافتراضية)، نقوم بتغذية الجدول أولاً
        if (!$theme) {
            ThemeMarketplace::seedDefaultThemes();
            $theme = ThemeMarketplace::where('slug', $slug)->first();
        }

        if (!$theme) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'تعذر العثور على الثيم في قاعدة البيانات.'], 404);
            }
            return redirect()->back()->with('error', 'تعذر العثور على الثيم.');
        }

        $reviewerName = $request->input('reviewer') ?: (auth()->user() ? auth()->user()->name : 'تاجر فاست أوردر');

        $theme->addReview([
            'reviewer' => $reviewerName,
            'rating' => $request->input('rating'),
            'comment' => $request->input('comment'),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'تم إضافة تقييمك ومراجعتك للثيم بنجاح، شكراً لمشاركتنا رأيك ✓',
                'theme' => $theme,
            ]);
        }

        return redirect()->back()->with('success', 'تم إضافة تقييمك ومراجعتك للثيم بنجاح ✓');
    }

    /**
     * Preview theme directly from marketplace.
     */
    public function preview(Request $request, string $slug)
    {
        // Redirect to standard interactive theme customizer preview
        return redirect()->route('merchant.themes.preview', ['slug' => $slug]);
    }
}
