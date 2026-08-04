<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionReceipt;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class SubscriptionController extends Controller
{
    /**
     * Display the merchant's subscription index page.
     */
    public function index(): Response
    {
        $tenant = app(Tenant::class);

        // Get the active/latest subscription
        $subscription = Subscription::where('tenant_id', $tenant->id)
            ->with('plan')
            ->latest()
            ->first();

        // Get all active subscription plans
        $plans = SubscriptionPlan::where('is_active', true)
            ->orderBy('price_monthly', 'asc')
            ->get();

        // Get receipt history
        $receipts = SubscriptionReceipt::where('tenant_id', $tenant->id)
            ->with('plan')
            ->latest()
            ->get()
            ->map(fn($receipt) => [
                'id' => $receipt->id,
                'plan_name' => $receipt->plan?->name,
                'amount' => $receipt->amount,
                'payment_method' => $receipt->payment_method,
                'payment_reference' => $receipt->payment_reference,
                'receipt_url' => asset('storage/' . $receipt->receipt_path),
                'status' => $receipt->status,
                'rejection_reason' => $receipt->rejection_reason,
                'created_at' => $receipt->created_at?->format('Y-m-d H:i'),
            ]);

        // Get limits and current usage
        $currentPlan = $subscription?->plan;
        $limits = $currentPlan?->limits;
        
        $maxProducts = $limits['max_products'] ?? 0;
        $maxOrders = $limits['max_orders'] ?? 0;

        $usage = [
            'products' => [
                'current' => Product::count(),
                'max' => (int) $maxProducts,
            ],
            'orders' => [
                'current' => Order::count(),
                'max' => (int) $maxOrders,
            ],
        ];

        return Inertia::render('Merchant/Subscription/Index', [
            'subscription' => $subscription ? [
                'id' => $subscription->id,
                'plan' => $subscription->plan,
                'status' => $subscription->status,
                'billing_cycle' => $subscription->billing_cycle,
                'price' => $subscription->price,
                'starts_at' => $subscription->starts_at?->format('Y-m-d'),
                'ends_at' => $subscription->ends_at?->format('Y-m-d'),
                'trial_ends_at' => $subscription->trial_ends_at?->format('Y-m-d'),
                'is_active' => $subscription->isActive(),
            ] : null,
            'plans' => $plans,
            'receipts' => $receipts,
            'usage' => $usage,
            'tenant' => [
                'trial_ends_at' => $tenant->trial_ends_at?->format('Y-m-d'),
                'subscription_ends_at' => $tenant->subscription_ends_at?->format('Y-m-d'),
                'subscription_status' => $tenant->subscription_status,
            ],
        ]);
    }

    /**
     * Submit a new subscription payment receipt.
     */
    public function subscribe(Request $request): RedirectResponse
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'payment_method' => 'required|string|in:vodafone_cash,instapay',
            'payment_reference' => 'required|string|max:100',
            'receipt' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'amount' => 'required|numeric|min:0',
        ], [
            'plan_id.required' => 'يرجى اختيار الباقة المطلوبة.',
            'plan_id.exists' => 'الباقة المحددة غير موجودة.',
            'payment_method.required' => 'يرجى اختيار طريقة الدفع.',
            'payment_method.in' => 'طريقة الدفع المحددة غير صالحة.',
            'payment_reference.required' => 'يرجى إدخال رقم العملية أو رقم المحفظة المحول منها.',
            'receipt.required' => 'يرجى رفع صورة إيصال التحويل.',
            'receipt.image' => 'يجب أن يكون الملف المرفوع صورة.',
            'receipt.max' => 'حجم الصورة لا يجب أن يتعدى 2 ميجابايت.',
            'amount.required' => 'يرجى إدخال المبلغ المدفوع.',
            'amount.numeric' => 'يجب أن يكون المبلغ قيمة رقمية.',
        ]);

        $tenant = app(Tenant::class);

        // Upload receipt image
        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('receipts', 'public');
        } else {
            return redirect()->back()->with('error', 'حدث خطأ أثناء رفع الصورة.');
        }

        // Create subscription receipt record
        SubscriptionReceipt::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $request->plan_id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_reference' => $request->payment_reference,
            'receipt_path' => $path,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'تم رفع إيصال الدفع بنجاح! سيتم مراجعة طلبك وتفعيل الباقة خلال 24 ساعة.');
    }
}
