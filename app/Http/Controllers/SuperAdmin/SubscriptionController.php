<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionReceipt;
use App\Models\Subscription;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    /**
     * List subscription plans.
     */
    public function plans(): Response
    {
        $plans = SubscriptionPlan::orderBy('price_monthly', 'asc')->get();

        return Inertia::render('SuperAdmin/Subscriptions/Plans', [
            'plans' => $plans
        ]);
    }

    /**
     * View manual subscription receipts.
     */
    public function receipts(Request $request): Response
    {
        $query = SubscriptionReceipt::with(['tenant', 'plan', 'approvedBy']);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $receipts = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $tenants = Tenant::orderBy('name', 'asc')->get(['id', 'name', 'slug']);
        $plans = SubscriptionPlan::orderBy('price_monthly', 'asc')->get(['id', 'name', 'price_monthly']);

        return Inertia::render('SuperAdmin/Subscriptions/Receipts', [
            'receipts' => $receipts,
            'tenants' => $tenants,
            'plans' => $plans,
            'filters' => $request->only(['status']),
        ]);
    }

    /**
     * Store/attach a new manual receipt for a tenant.
     */
    public function storeReceipt(Request $request): RedirectResponse
    {
        $request->validate([
            'tenant_id' => ['required', 'exists:tenants,id'],
            'plan_id' => ['required', 'exists:subscription_plans,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'max:255'],
            'payment_reference' => ['nullable', 'string', 'max:255'],
            'receipt_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $receiptPath = null;
        if ($request->hasFile('receipt_file')) {
            $receiptPath = $request->file('receipt_file')->store('receipts', 'public');
        }

        $receipt = SubscriptionReceipt::create([
            'tenant_id' => $request->tenant_id,
            'plan_id' => $request->plan_id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_reference' => $request->payment_reference,
            'receipt_path' => $receiptPath,
            'status' => 'pending',
        ]);

        return $this->approveReceipt($request, $receipt);
    }

    /**
     * Approve a subscription receipt.
     */
    public function approveReceipt(Request $request, SubscriptionReceipt $receipt): RedirectResponse
    {
        if ($receipt->status !== 'pending') {
            return redirect()->back()->with('error', 'هذا الطلب تم معالجته بالفعل.');
        }

        DB::transaction(function () use ($receipt) {
            $plan = $receipt->plan;
            $tenant = $receipt->tenant;

            // 1. Approve receipt
            $receipt->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => Auth::id(),
            ]);

            // 2. Determine duration based on amount vs plan prices
            $months = 1;
            if ($plan->price_yearly > 0 && $receipt->amount >= $plan->price_yearly) {
                $months = 12;
            }

            // 3. Create or update tenant subscription
            $endsAt = now()->addMonths($months);
            Subscription::updateOrCreate(
                ['tenant_id' => $tenant->id, 'status' => 'active'],
                [
                    'plan_id' => $plan->id,
                    'price' => $receipt->amount,
                    'starts_at' => now(),
                    'ends_at' => $endsAt,
                    'billing_cycle' => $months == 12 ? 'yearly' : 'monthly',
                ]
            );

            // 4. Update tenant model fields
            $tenant->update([
                'subscription_status' => 'active',
                'subscription_ends_at' => $endsAt,
            ]);
        });

        return redirect()->back()->with('success', 'تم اعتماد إيصال الدفع وتفعيل الاشتراك بنجاح.');
    }

    /**
     * Update an existing subscription plan.
     */
    public function updatePlan(Request $request, SubscriptionPlan $plan): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price_monthly' => ['required', 'numeric', 'min:0'],
            'price_yearly' => ['required', 'numeric', 'min:0'],
            'trial_days' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        $plan->update([
            'name' => $request->name,
            'price_monthly' => $request->price_monthly,
            'price_yearly' => $request->price_yearly,
            'trial_days' => $request->trial_days,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'تم تحديث خطة الاشتراك بنجاح.');
    }

    /**
     * Reject a subscription receipt.
     */
    public function rejectReceipt(Request $request, SubscriptionReceipt $receipt): RedirectResponse
    {
        if ($receipt->status !== 'pending') {
            return redirect()->back()->with('error', 'هذا الطلب تم معالجته بالفعل.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ]);

        $receipt->update([
            'status' => 'rejected',
            'rejection_reason' => $request->input('rejection_reason'),
        ]);

        return redirect()->back()->with('success', 'تم رفض إيصال الدفع.');
    }
}
