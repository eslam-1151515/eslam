<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionReceipt;
use App\Models\Tenant;
use App\Models\WalletTransaction;
use App\Services\KashierService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KashierWebhookController extends Controller
{
    /**
     * Handle Kashier Webhook notifications.
     */
    public function handle(Request $request, KashierService $kashierService): JsonResponse
    {
        $payload = $request->all();
        Log::info('Kashier Webhook received:', $payload);

        // Normalize payload: Kashier may wrap payload in 'data' or send directly at root
        $data = isset($payload['data']) && is_array($payload['data']) ? $payload['data'] : $payload;

        $paymentStatus = strtoupper($data['paymentStatus'] ?? $payload['paymentStatus'] ?? '');
        $merchantOrderId = (string) ($data['merchantOrderId'] ?? $payload['merchantOrderId'] ?? '');
        $orderId = (string) ($data['orderId'] ?? $payload['orderId'] ?? '');
        $transactionId = (string) ($data['transactionId'] ?? $payload['transactionId'] ?? '');
        $amount = (float) ($data['amount'] ?? $payload['amount'] ?? 0);
        $cardBrand = (string) ($data['cardBrand'] ?? $payload['cardBrand'] ?? '');
        $signature = $data['signature'] ?? $payload['signature'] ?? $request->header('x-kashier-signature') ?? null;

        // Verify signature if provided
        if (!empty($signature)) {
            $isValid = $kashierService->verifySignature($data, $signature);
            if (!$isValid) {
                $rawBody = $request->getContent();
                $secretKey = $kashierService->getSecretKey();
                if (!empty($rawBody) && !empty($secretKey)) {
                    $rawHmac = hash_hmac('sha256', $rawBody, $secretKey);
                    if (hash_equals(strtolower($rawHmac), strtolower($signature))) {
                        $isValid = true;
                    }
                }
            }

            if (!$isValid) {
                Log::warning('Kashier Webhook: Invalid signature', [
                    'signature' => $signature,
                    'data'      => $data,
                ]);
                return response()->json(['status' => 'INVALID_SIGNATURE'], 400);
            }
        }

        // Case 1: Store Customer Order (STORE_ORDER_{tenantId}_{orderId})
        $orderMatches = [];
        if (preg_match('/STORE_ORDER[_-](\d+)[_-](\d+)/i', $merchantOrderId, $orderMatches) || preg_match('/STORE_ORDER[_-](\d+)[_-](\d+)/i', $orderId, $orderMatches)) {
            $storeOrderId = (int) $orderMatches[2];
            $storeOrder = \App\Models\Order::find($storeOrderId);
            if ($storeOrder) {
                if (in_array($paymentStatus, ['SUCCESS', 'CAPTURED', 'PAID'])) {
                    $storeOrder->update([
                        'payment_status' => 'paid',
                        'transaction_id' => $transactionId ?: $storeOrder->transaction_id,
                        'status'         => 'processing',
                    ]);

                    // Send WhatsApp auto confirmation if enabled
                    try {
                        $isAutoConfirmEnabled = (bool) \App\Models\Setting::get('auto_confirm_enabled', false, $storeOrder->tenant_id);
                        if ($isAutoConfirmEnabled) {
                            $whatsAppService = new \App\Services\MetaWhatsAppService();
                            $whatsAppService->sendOrderConfirmation($storeOrder);
                        }
                    } catch (\Throwable $e) {
                        Log::warning('WhatsApp auto confirmation failed after Kashier webhook: ' . $e->getMessage());
                    }

                    Log::info("Kashier Webhook: Store Order #{$storeOrder->id} marked as PAID.");
                } else {
                    $storeOrder->update([
                        'payment_status' => 'failed',
                    ]);
                }
                return response()->json(['status' => 'STORE_ORDER_PROCESSED'], 200);
            }
        }

        // Case 2: Wallet Top-Up Receipt (WALLET_{tenantId}_{receiptId})
        $receipt = null;

        // Pattern WALLET_{tenantId}_{receiptId}
        if (preg_match('/WALLET[_-](\d+)[_-](\d+)/i', $merchantOrderId, $matches)) {
            $receiptId = (int) $matches[2];
            $receipt = SubscriptionReceipt::find($receiptId);
        }

        if (!$receipt && !empty($orderId)) {
            $receipt = SubscriptionReceipt::where('payment_reference', 'like', "%{$orderId}%")
                ->where('type', 'wallet')
                ->latest()
                ->first();
        }

        if (!$receipt) {
            Log::warning("Kashier Webhook: No matching receipt found for MerchantOrderId: {$merchantOrderId}, OrderId: {$orderId}");
            return response()->json(['status' => 'RECEIPT_NOT_FOUND'], 200);
        }

        $isSuccess = in_array($paymentStatus, ['SUCCESS', 'CAPTURED', 'PAID']);

        if ($isSuccess) {
            if ($receipt->status !== 'approved') {
                DB::transaction(function () use ($receipt, $amount, $transactionId, $orderId, $cardBrand) {
                    $tenant = $receipt->tenant;

                    $receipt->update([
                        'status'            => 'approved',
                        'payment_method'    => 'kashier',
                        'payment_reference' => "Kashier Trans #{$transactionId} (Order #{$orderId})",
                        'approved_at'       => now(),
                        'approved_by'       => null, // Auto approved by Kashier
                    ]);

                    if ($tenant) {
                        $topUpAmount = $receipt->amount > 0 ? (float) $receipt->amount : $amount;
                        $tenant->increment('wallet_balance', $topUpAmount);

                        $methodDetails = !empty($cardBrand) && $cardBrand !== 'null' ? "كاشير ({$cardBrand})" : 'بوابة كاشير الإلكترونية';

                        WalletTransaction::create([
                            'tenant_id'   => $tenant->id,
                            'amount'      => $topUpAmount,
                            'type'        => 'credit',
                            'description' => "⚡ شحن محفظة لحظي عبر {$methodDetails} - رقم العملية #{$transactionId}",
                            'created_by'  => null,
                        ]);

                        Log::info("Kashier instant top-up of {$topUpAmount} EGP added to Tenant #{$tenant->id} successfully.");
                    }
                });
            }
        } else {
            // Payment failed or cancelled
            if ($receipt->status === 'pending') {
                $receipt->update([
                    'status'            => 'rejected',
                    'payment_method'    => 'kashier',
                    'payment_reference' => "Failed Kashier Trans #{$transactionId}",
                    'rejection_reason'  => "فشلت عملية الدفع عبر كاشير: حالة الدفع ({$paymentStatus})",
                ]);

                Log::info("Kashier payment rejected for Receipt #{$receipt->id}. Status: {$paymentStatus}");
            }
        }

        return response()->json(['status' => 'PROCESSED'], 200);
    }
}
