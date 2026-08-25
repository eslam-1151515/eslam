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

        // Search filter (reference_code, payment_reference, amount, tenant name/email/phone)
        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_code', 'like', "%{$search}%")
                  ->orWhere('payment_reference', 'like', "%{$search}%")
                  ->orWhere('amount', 'like', "%{$search}%")
                  ->orWhereHas('tenant', function ($tq) use ($search) {
                      $tq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // Date filter
        if ($date = $request->input('date')) {
            $query->whereDate('created_at', $date);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        if ($tenantId = $request->input('tenant_id')) {
            $query->where('tenant_id', $tenantId);
        }

        $receipts = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $tenants = Tenant::orderBy('name', 'asc')->get(['id', 'name', 'slug']);
        $plans = SubscriptionPlan::orderBy('price_monthly', 'asc')->get(['id', 'name', 'price_monthly']);

        $paymentSettings = [
            'vodafone_cash_number'         => \App\Models\Setting::getGlobal('vodafone_cash_number', \App\Models\Setting::get('vodafone_cash_number', '')),
            'instapay_number'              => \App\Models\Setting::getGlobal('instapay_number', \App\Models\Setting::get('instapay_number', \App\Models\Setting::get('instapay_address', ''))),
            'paymob_api_key'               => \App\Models\Setting::getGlobal('paymob_api_key', \App\Models\Setting::get('paymob_api_key', '')),
            'paymob_secret_key'            => \App\Models\Setting::getGlobal('paymob_secret_key', \App\Models\Setting::get('paymob_secret_key', '')),
            'paymob_public_key'            => \App\Models\Setting::getGlobal('paymob_public_key', \App\Models\Setting::get('paymob_public_key', '')),
            'paymob_card_integration_id'   => \App\Models\Setting::getGlobal('paymob_card_integration_id', \App\Models\Setting::get('paymob_card_integration_id', '')),
            'paymob_wallet_integration_id' => \App\Models\Setting::getGlobal('paymob_wallet_integration_id', \App\Models\Setting::get('paymob_wallet_integration_id', '')),
            'paymob_iframe_id'             => \App\Models\Setting::getGlobal('paymob_iframe_id', \App\Models\Setting::get('paymob_iframe_id', '')),
            'paymob_hmac_secret'           => \App\Models\Setting::getGlobal('paymob_hmac_secret', \App\Models\Setting::get('paymob_hmac_secret', '')),
            'paymob_webhook_url'           => url('/api/webhooks/paymob'),
        ];

        return Inertia::render('SuperAdmin/Subscriptions/Receipts', [
            'receipts'        => $receipts,
            'tenants'         => $tenants,
            'plans'           => $plans,
            'paymentSettings' => $paymentSettings,
            'filters'         => $request->only(['search', 'date', 'status', 'type', 'tenant_id']),
        ]);
    }

    /**
     * Update platform payment numbers and Paymob settings.
     */
    public function updatePaymentSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'vodafone_cash_number'         => ['nullable', 'string', 'max:50'],
            'instapay_number'              => ['nullable', 'string', 'max:50'],
            'paymob_api_key'               => ['nullable', 'string', 'max:500'],
            'paymob_secret_key'            => ['nullable', 'string', 'max:500'],
            'paymob_public_key'            => ['nullable', 'string', 'max:500'],
            'paymob_card_integration_id'   => ['nullable', 'string', 'max:100'],
            'paymob_wallet_integration_id' => ['nullable', 'string', 'max:100'],
            'paymob_iframe_id'             => ['nullable', 'string', 'max:100'],
            'paymob_hmac_secret'           => ['nullable', 'string', 'max:500'],
        ]);

        if ($request->has('vodafone_cash_number')) {
            \App\Models\Setting::setGlobal('vodafone_cash_number', $request->vodafone_cash_number, 'payment');
        }
        if ($request->has('instapay_number')) {
            \App\Models\Setting::setGlobal('instapay_number', $request->instapay_number, 'payment');
        }
        if ($request->has('paymob_api_key')) {
            \App\Models\Setting::setGlobal('paymob_api_key', $request->paymob_api_key, 'payment');
        }
        if ($request->has('paymob_secret_key')) {
            \App\Models\Setting::setGlobal('paymob_secret_key', $request->paymob_secret_key, 'payment');
        }
        if ($request->has('paymob_public_key')) {
            \App\Models\Setting::setGlobal('paymob_public_key', $request->paymob_public_key, 'payment');
        }
        if ($request->has('paymob_card_integration_id')) {
            \App\Models\Setting::setGlobal('paymob_card_integration_id', $request->paymob_card_integration_id, 'payment');
        }
        if ($request->has('paymob_wallet_integration_id')) {
            \App\Models\Setting::setGlobal('paymob_wallet_integration_id', $request->paymob_wallet_integration_id, 'payment');
        }
        if ($request->has('paymob_iframe_id')) {
            \App\Models\Setting::setGlobal('paymob_iframe_id', $request->paymob_iframe_id, 'payment');
        }
        if ($request->has('paymob_hmac_secret')) {
            \App\Models\Setting::setGlobal('paymob_hmac_secret', $request->paymob_hmac_secret, 'payment');
        }

        return redirect()->back()->with('success', 'تم تحديث إعدادات الدفع وبوابة Paymob بنجاح.');
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
     * Approve a subscription or wallet receipt.
     */
    public function approveReceipt(Request $request, SubscriptionReceipt $receipt): RedirectResponse
    {
        if ($receipt->status !== 'pending') {
            return redirect()->back()->with('error', 'هذا الطلب تم معالجته بالفعل.');
        }

        DB::transaction(function () use ($receipt) {
            $tenant = $receipt->tenant;

            // 1. Approve receipt
            $receipt->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => Auth::id(),
            ]);

            // If this is a wallet top-up receipt
            if ($receipt->type === 'wallet') {
                if ($tenant) {
                    $tenant->increment('wallet_balance', $receipt->amount);
                    \App\Models\WalletTransaction::create([
                        'tenant_id'   => $tenant->id,
                        'amount'      => $receipt->amount,
                        'type'        => 'credit',
                        'description' => 'شحن محفظة عبر ' . ($receipt->payment_method === 'vodafone_cash' ? 'فودافون كاش' : ($receipt->payment_method === 'instapay' ? 'إنستا باي' : $receipt->payment_method)) . ($receipt->payment_reference ? ' (الرقم المحول منه: ' . $receipt->payment_reference . ')' : ''),
                        'created_by'  => Auth::id(),
                    ]);
                }
                return;
            }

            // Otherwise, subscription plan assignment
            $plan = $receipt->plan;
            if ($plan && $tenant) {
                // Determine duration based on amount vs plan prices
                $months = 1;
                if ($plan->price_yearly > 0 && $receipt->amount >= $plan->price_yearly) {
                    $months = 12;
                }

                // Create or update tenant subscription
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

                // Update tenant model fields
                $tenant->update([
                    'subscription_status' => 'active',
                    'subscription_ends_at' => $endsAt,
                ]);
            }
        });

        if ($receipt->tenant_id) {
            \App\Services\CacheService::invalidateDashboardStats($receipt->tenant_id);
        }

        $msg = $receipt->type === 'wallet'
            ? 'تم تأكيد وصول المبلغ وإضافته إلى محفظة التاجر بنجاح.'
            : 'تم اعتماد إيصال الدفع وتفعيل الاشتراك بنجاح.';

        return redirect()->back()->with('success', $msg);
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

        if ($receipt->tenant_id) {
            \App\Services\CacheService::invalidateDashboardStats($receipt->tenant_id);
        }

        return redirect()->back()->with('success', 'تم رفض إيصال الدفع.');
    }

    /**
     * Delete a subscription receipt.
     */
    public function destroyReceipt(SubscriptionReceipt $receipt): RedirectResponse
    {
        $receipt->delete();

        if ($receipt->tenant_id) {
            \App\Services\CacheService::invalidateDashboardStats($receipt->tenant_id);
        }

        return redirect()->back()->with('success', 'تم حذف إيصال الدفع بنجاح.');
    }
}
