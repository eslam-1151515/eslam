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
            'vodafone_cash' => Setting::get('vodafone_cash_number', '01012345678'),
            'instapay'      => Setting::get('instapay_number', Setting::get('instapay_address', '01012345678')),
            'support_phone' => Setting::get('support_phone', Setting::get('phone', '01012345678')),
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

        return Inertia::render('Merchant/Wallet/Index', [
            'wallet_balance'  => (float) ($tenant->wallet_balance ?? 0),
            'paymentInfo'     => $paymentInfo,
            'depositRequests' => $depositRequests,
            'transactions'    => $transactions,
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
}
