<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;
use App\Models\SubscriptionReceipt;
use App\Models\WalletTransaction;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Revert Receipt #18 (Tenant 30 - Radwan Bisheer)
        $receipt = SubscriptionReceipt::find(18);
        if ($receipt && $receipt->status === 'approved' && str_contains($receipt->payment_reference ?? '', 'SANDBOX')) {
            $receipt->update([
                'status'           => 'rejected',
                'rejection_reason' => 'تم إلغاء شحن تجريبي غير حقيقي (Sandbox Simulation) لعدم وجود سداد مالي فعلي.',
            ]);
        }

        // 2. Adjust Tenant 30 Wallet Balance
        $tenant = Tenant::find(30);
        if ($tenant) {
            $currentBalance = (float) $tenant->wallet_balance;
            if ($currentBalance > 0) {
                // Record the debit transaction for clear accounting
                WalletTransaction::create([
                    'tenant_id'   => $tenant->id,
                    'amount'      => $currentBalance,
                    'type'        => 'debit',
                    'description' => '⚠️ تسوية نظام: إلغاء واسترداد رصيد تجريبي غير مدفوع (إيصال #18)',
                    'created_by'  => null,
                ]);

                // Reset balance to 0.00
                $tenant->update([
                    'wallet_balance' => 0.00,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback needed for security cleanup
    }
};
