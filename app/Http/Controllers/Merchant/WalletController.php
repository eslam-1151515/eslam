<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Setting;
use App\Models\SubscriptionReceipt;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class WalletController extends Controller
{
    /**
     * Display the merchant wallet dashboard page.
     */
    public function index(): Response
    {
        $tenant = app(Tenant::class);

        // Platform payment gateway numbers for charging wallet
        $paymentInfo = [
            'vodafone_cash' => Setting::getGlobal('vodafone_cash_number', Setting::get('vodafone_cash_number', '')),
            'instapay'      => Setting::getGlobal('instapay_number', Setting::get('instapay_number', Setting::get('instapay_address', ''))),
            'support_phone' => Setting::getGlobal('support_phone', Setting::get('support_phone', '')),
            'work_hours'    => 'من 10 صباحاً حتى 2 بعد منتصف الليل',
            'min_deposit'   => 300,
        ];

        // Deposit requests (SubscriptionReceipt where type = 'wallet')
        $depositRequests = SubscriptionReceipt::where('tenant_id', $tenant->id)
            ->where('type', 'wallet')
            ->latest()
            ->get()
            ->map(fn($r) => [
                'id'                => $r->id,
                'reference_code'    => $r->reference_code ?: (string) str_pad(100000 + $r->id, 6, '0', STR_PAD_LEFT),
                'amount'            => (float) $r->amount,
                'payment_method'    => $r->payment_method,
                'payment_reference' => $r->payment_reference,
                'receipt_url'       => $r->receipt_path ? asset('storage/' . $r->receipt_path) : null,
                'status'            => $r->status,
                'rejection_reason'  => $r->rejection_reason,
                'date_formatted'    => $r->created_at?->format('Y-m-d'),
                'time_formatted'    => $r->created_at?->format('h:i A'),
            ]);

        // Wallet transactions history (credits/debits)
        $transactions = WalletTransaction::where('tenant_id', $tenant->id)
            ->latest()
            ->get()
            ->map(fn($t) => [
                'id'             => $t->id,
                'amount'         => (float) $t->amount,
                'type'           => $t->type,
                'description'    => $t->description,
                'date_formatted' => $t->created_at?->format('Y-m-d'),
                'time_formatted' => $t->created_at?->format('h:i A'),
            ]);

        $paymobConfigured = app(\App\Services\PaymobService::class)->isConfigured();

        return Inertia::render('Merchant/Wallet/Index', [
            'wallet_balance'       => (float) ($tenant->wallet_balance ?? 0),
            'paymentInfo'          => $paymentInfo,
            'depositRequests'      => $depositRequests,
            'transactions'         => $transactions,
            'is_paymob_configured' => $paymobConfigured,
        ]);
    }

    /**
     * Submit a new wallet top-up deposit request.
     */
    public function deposit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount'            => 'required|numeric|min:300',
            'payment_method'    => 'required|string|in:vodafone_cash,instapay',
            'payment_reference' => 'required|string|max:100',
            'receipt'           => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ], [
            'amount.required'            => 'يرجى إدخال مبلغ الشحن.',
            'amount.numeric'             => 'المبلغ يجب أن يكون قيمة رقمية.',
            'amount.min'                 => 'عفواً، الحد الأدنى لشحن المحفظة هو 300 جنيه.',
            'payment_method.required'    => 'يرجى اختيار طريقة التحويل.',
            'payment_method.in'          => 'طريقة التحويل المحددة غير صالحة.',
            'payment_reference.required' => 'يرجى إدخال الرقم المُنقَل منه.',
            'receipt.required'           => 'يرجى إرفاق صورة إشعار التحويل (إسكرين شوت).',
            'receipt.image'              => 'الملف المرفوع يجب أن يكون صورة.',
            'receipt.max'                => 'حجم الصورة يجب ألا يتجاوز 3 ميجابايت.',
        ]);

        $tenant = app(Tenant::class);

        // Upload receipt image
        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('receipts', 'public');
        } else {
            return redirect()->back()->with('error', 'حدث خطأ أثناء رفع الصورة.');
        }

        // Save wallet deposit request
        SubscriptionReceipt::create([
            'tenant_id'         => $tenant->id,
            'plan_id'           => null,
            'type'              => 'wallet',
            'amount'            => $validated['amount'],
            'payment_method'    => $validated['payment_method'],
            'payment_reference' => $validated['payment_reference'],
            'receipt_path'      => $path,
            'status'            => 'pending',
        ]);

        return redirect()->back()->with('success', 'تم تقديم طلب شحن المحفظة بنجاح! يتم مراجعة الطلب وتغذية الحساب خلال ساعتين عمل.');
    }

    /**
     * Start Instant Paymob Deposit.
     */
    public function instantDeposit(Request $request, \App\Services\PaymobService $paymobService)
    {
        if (!$paymobService->isConfigured()) {
            return redirect()->back()->with('error', 'عفواً، خدمة الدفع الإلكتروني اللحظي غير مفعلة حالياً. يرجى اختيار التحويل اليدوي (إنستاباي / فودافون كاش) ورفع إيصال التحويل ليتم مراجعته وتغذية محفظتك فوراً.');
        }

        $validated = $request->validate([
            'amount'       => 'required|numeric|min:300',
            'method_type'  => 'required|string|in:card,wallet',
            'wallet_phone' => 'nullable|required_if:method_type,wallet|string|regex:/^01[0125][0-9]{8}$/',
        ], [
            'amount.required'            => 'يرجى إدخال مبلغ الشحن.',
            'amount.numeric'             => 'المبلغ يجب أن يكون قيمة رقمية.',
            'amount.min'                 => 'عفواً، الحد الأدنى لشحن المحفظة هو 300 جنيه.',
            'method_type.required'       => 'يرجى اختيار وسيلة الدفع (فيزا أو محفظة).',
            'wallet_phone.required_if'   => 'يرجى كتابة رقم المحفظة الإلكترونية لإتمام الدفع.',
            'wallet_phone.regex'         => 'يرجى إدخال رقم هاتف محفظة مصري صحيح مكون من 11 رقماً (مثال: 01012345678).',
        ]);

        $tenant = app(Tenant::class);

        // Create pending subscription receipt record
        $receipt = SubscriptionReceipt::create([
            'tenant_id'         => $tenant->id,
            'plan_id'           => null,
            'type'              => 'wallet',
            'amount'            => $validated['amount'],
            'payment_method'    => $validated['method_type'] === 'wallet' ? 'paymob_wallet' : 'paymob_card',
            'payment_reference' => 'بانتظار تأكيد الدفع الإلكتروني...',
            'receipt_path'      => null,
            'status'            => 'pending',
        ]);

        $result = $paymobService->initiateDeposit(
            $receipt,
            $tenant,
            $validated['method_type'],
            $validated['wallet_phone'] ?? null
        );

        if (!$result['success']) {
            $receipt->update([
                'status'           => 'rejected',
                'rejection_reason' => $result['message'],
            ]);

            return redirect()->back()->with('error', $result['message'] ?? 'فشل الاتصال ببوابة الدفع.');
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success'      => true,
                'redirect_url' => $result['redirect_url'],
            ]);
        }

        return Inertia::location($result['redirect_url']);
    }

    /**
     * Browser redirect callback after Paymob payment.
     */
    public function paymobCallback(Request $request, \App\Services\PaymobService $paymobService)
    {
        $orderId = $request->query('order');
        $merchantOrderId = $request->query('merchant_order_id');
        $transactionId = $request->query('id');
        $hmac = $request->query('hmac');

        // Check if there is an associated receipt
        $receipt = null;
        if ($merchantOrderId && preg_match('/WALLET_(\d+)_(\d+)/', $merchantOrderId, $m)) {
            $receipt = SubscriptionReceipt::find((int) $m[2]);
        }
        if (!$receipt && $orderId) {
            $receipt = SubscriptionReceipt::where('payment_reference', 'like', "%{$orderId}%")->latest()->first();
        }

        // If webhook already processed and approved the receipt
        if ($receipt && $receipt->status === 'approved') {
            return redirect()->route('merchant.wallet.index')->with('success', '⚡ تم تأكيد شحن المحفظة بنجاح وإضافة الرصيد إلى حسابك!');
        }

        // Validate HMAC only if Paymob is configured and HMAC was supplied
        $hmacValid = false;
        if ($paymobService->isConfigured() && !empty($hmac)) {
            $hmacValid = $paymobService->verifyHmac($request->all(), $hmac);
        }

        $success = (filter_var($request->query('success', false), FILTER_VALIDATE_BOOLEAN)
            || $request->query('txn_response_code') === 'APPROVED') && $hmacValid;

        if ($success && $receipt && $receipt->status !== 'approved') {
            $tenant = $receipt->tenant;
            if ($tenant) {
                $tenant->increment('wallet_balance', $receipt->amount);
                WalletTransaction::create([
                    'tenant_id'   => $tenant->id,
                    'amount'      => $receipt->amount,
                    'type'        => 'credit',
                    'description' => "⚡ شحن محفظة لحظي عبر Paymob (معاملة #{$transactionId})",
                    'created_by'  => null,
                ]);
            }
            $receipt->update([
                'status'            => 'approved',
                'approved_at'       => now(),
                'payment_reference' => "Paymob Trans #{$transactionId}",
            ]);

            return redirect()->route('merchant.wallet.index')->with('success', '⚡ تم شحن المحفظة بنجاح وإضافة الرصيد إلى حسابك فوراً!');
        }

        if ($receipt && $receipt->status === 'pending') {
            $txnResponse = $request->query('txn_response_code');
            if ($txnResponse && $txnResponse !== 'APPROVED') {
                $receipt->update([
                    'status'           => 'rejected',
                    'rejection_reason' => 'فشلت عملية الدفع من البنك أو تم إلغاؤها من المستخدم.',
                ]);
            }
        }

        return redirect()->route('merchant.wallet.index')->with('error', '⚠️ لم يتم تأكيد عملية الدفع. إذا تم الخصم من حسابك، سيتم تحديث رصيدك تلقائياً فور وصول إشعار البنك الرسمي.');
    }

    /**
     * Paymob Sandbox Test Screen (strictly disabled in production).
     */
    public function paymobSandbox(Request $request): Response
    {
        if (!app()->environment('local')) {
            abort(404, 'Sandbox mode is disabled.');
        }

        $receiptId = $request->query('receipt_id');
        $method = $request->query('method', 'card');
        $amount = (float) $request->query('amount', 300);

        $receipt = SubscriptionReceipt::findOrFail($receiptId);

        return Inertia::render('Merchant/Wallet/PaymobSandbox', [
            'receipt' => [
                'id'             => $receipt->id,
                'reference_code' => $receipt->reference_code,
                'amount'         => $receipt->amount,
                'method'         => $method,
            ],
        ]);
    }

    /**
     * Process Sandbox Simulation Action (strictly disabled in production).
     */
    public function completeSandbox(Request $request): RedirectResponse
    {
        if (!app()->environment('local')) {
            abort(404, 'Sandbox simulation is strictly disabled in production.');
        }

        $validated = $request->validate([
            'receipt_id' => 'required|exists:subscription_receipts,id',
            'action'     => 'required|in:success,fail',
        ]);

        $receipt = SubscriptionReceipt::findOrFail($validated['receipt_id']);
        $tenant = $receipt->tenant;

        if ($validated['action'] === 'success') {
            $receipt->update([
                'status'            => 'approved',
                'payment_method'    => $receipt->payment_method ?: 'paymob_card',
                'payment_reference' => 'SANDBOX_TRANS_' . time(),
                'approved_at'       => now(),
            ]);

            if ($tenant) {
                $tenant->increment('wallet_balance', $receipt->amount);
                WalletTransaction::create([
                    'tenant_id'   => $tenant->id,
                    'amount'      => $receipt->amount,
                    'type'        => 'credit',
                    'description' => "⚡ شحن محفظة لحظي عبر Paymob (تجريبي Sandbox)",
                    'created_by'  => null,
                ]);
            }

            return redirect()->route('merchant.wallet.index')->with('success', '⚡ تم محاكاة الدفع بنجاح وإضافة الرصيد إلى محفظتك فوراً!');
        }

        $receipt->update([
            'status'           => 'rejected',
            'rejection_reason' => 'تم رفض عملية الدفع التجريبية (Sandbox Simulation).',
        ]);

        return redirect()->route('merchant.wallet.index')->with('error', 'تمت محاكاة فشل عملية الدفع التجريبية.');
    }
}
