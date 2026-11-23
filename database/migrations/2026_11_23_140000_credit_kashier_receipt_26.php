<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\SubscriptionReceipt;
use App\Models\Tenant;
use App\Models\WalletTransaction;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            $receipt = SubscriptionReceipt::find(26);
            if (!$receipt) {
                $receipt = SubscriptionReceipt::where('payment_reference', 'like', '%WALLET_32_26%')->first();
            }
            if (!$receipt) {
                $receipt = SubscriptionReceipt::where('tenant_id', 32)
                    ->where('amount', 600)
                    ->where('status', 'pending')
                    ->latest()
                    ->first();
            }

            if ($receipt && $receipt->status !== 'approved') {
                DB::transaction(function () use ($receipt) {
                    $tenant = Tenant::find($receipt->tenant_id ?: 32);

                    $receipt->update([
                        'status'            => 'approved',
                        'payment_method'    => 'kashier',
                        'payment_reference' => 'Kashier Trans #TX-5201989504 (WALLET_32_26)',
                        'approved_at'       => now(),
                    ]);

                    if ($tenant) {
                        $tenant->increment('wallet_balance', (float) $receipt->amount);

                        WalletTransaction::create([
                            'tenant_id'   => $tenant->id,
                            'amount'      => (float) $receipt->amount,
                            'type'        => 'credit',
                            'description' => '⚡ شحن محفظة لحظي عبر كاشير (Kashier) - رقم العملية #TX-5201989504',
                            'created_by'  => null,
                        ]);
                    }
                });
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Migration credit_kashier_receipt_26 error: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse needed
    }
};
