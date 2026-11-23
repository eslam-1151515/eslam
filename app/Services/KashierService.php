<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\SubscriptionReceipt;
use App\Models\Tenant;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KashierService
{
    protected string $merchantId;
    protected string $paymentApiKey;
    protected string $secretKey;
    protected string $mode;
    protected bool $isActive;
    protected bool $isConfigured;

    public function __construct(
        ?string $merchantId = null,
        ?string $paymentApiKey = null,
        ?string $secretKey = null,
        ?string $mode = null
    ) {
        $this->merchantId = $merchantId ?: (Setting::getGlobal('kashier_merchant_id', Setting::get('kashier_merchant_id', 'MID-52019-895')) ?: 'MID-52019-895');
        $this->paymentApiKey = $paymentApiKey ?: (Setting::getGlobal('kashier_payment_api_key', Setting::get('kashier_payment_api_key', '0b01aadf-0df2-4d90-864c-913dc3462b1c')) ?: '0b01aadf-0df2-4d90-864c-913dc3462b1c');
        $this->secretKey = $secretKey ?: (Setting::getGlobal('kashier_secret_key', Setting::get('kashier_secret_key', '20b46d7dd22d75e195c8d0f0f6ed6e41$b47fdd473e076b8e035662ea2fd217bf96c7196524de12cf5c1dbfebd85ef6276888f3a833443645a0a29441f243590e')) ?: '20b46d7dd22d75e195c8d0f0f6ed6e41$b47fdd473e076b8e035662ea2fd217bf96c7196524de12cf5c1dbfebd85ef6276888f3a833443645a0a29441f243590e');
        $this->mode = $mode ?: (Setting::getGlobal('kashier_mode', Setting::get('kashier_mode', 'live')) ?: 'live');
        $this->isActive = (bool) Setting::getGlobal('kashier_active', Setting::get('kashier_active', 1));

        $this->isConfigured = !empty($this->merchantId) && !empty($this->paymentApiKey) && !empty($this->secretKey);
    }

    /**
     * Check if Kashier credentials are fully configured and enabled.
     */
    public function isConfigured(): bool
    {
        return $this->isConfigured && $this->isActive;
    }

    /**
     * Check if running in Live mode.
     */
    public function isLiveMode(): bool
    {
        return strtolower($this->mode) === 'live';
    }

    /**
     * Get Base URL according to environment mode.
     */
    public function getBaseUrl(): string
    {
        return $this->isLiveMode()
            ? 'https://api.kashier.io'
            : 'https://test-api.kashier.io';
    }

    /**
     * Initiate instant wallet deposit checkout via Kashier API v3.
     *
     * @param SubscriptionReceipt $receipt
     * @param Tenant $tenant
     * @param string $returnUrl Optional custom redirect URL
     * @return array ['success' => bool, 'redirect_url' => string, 'session_id' => string, 'message' => string]
     */
    public function initiateDeposit(SubscriptionReceipt $receipt, Tenant $tenant, ?string $returnUrl = null): array
    {
        if (!$this->isConfigured()) {
            Log::warning("Kashier credentials not configured or gateway inactive. Instant deposit rejected for Receipt #{$receipt->reference_code}");

            return [
                'success' => false,
                'message' => 'عفواً، خدمة الدفع الإلكتروني اللحظي عبر كاشير غير مفعلة حالياً. يرجى اختيار التحويل اليدوي أو مراجعة إدارة المنصة.',
            ];
        }

        $owner = $tenant->owner;
        $email = $owner?->email ?: $tenant->email ?: 'merchant@ordersaif.com';
        $customerRef = 'tenant-' . $tenant->id;
        $orderId = "WALLET_{$tenant->id}_{$receipt->id}";

        $redirectUrl = $returnUrl ?: route('merchant.wallet.kashier.callback');
        $webhookUrl = url('/api/webhooks/kashier');

        $endpoint = $this->getBaseUrl() . '/v3/payment/sessions';

        $payload = [
            'amount'           => number_format((float) $receipt->amount, 2, '.', ''),
            'currency'         => 'EGP',
            'order'            => $orderId,
            'merchantId'       => $this->merchantId,
            'merchantRedirect' => $redirectUrl,
            'serverWebhook'    => $webhookUrl,
            'display'          => 'ar',
            'type'             => 'one-time',
            'allowedMethods'   => 'card,wallet',
            'customer'         => [
                'email'     => $email,
                'reference' => $customerRef,
            ],
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->secretKey,
                'api-key'       => $this->paymentApiKey,
                'Content-Type'  => 'application/json',
            ])->timeout(30)->post($endpoint, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $sessionUrl = $data['sessionUrl'] ?? null;
                $sessionId = $data['_id'] ?? null;

                if (!empty($sessionUrl)) {
                    $receipt->update([
                        'payment_reference' => 'Kashier Order: ' . $orderId . ($sessionId ? ' (Session: ' . $sessionId . ')' : ''),
                    ]);

                    return [
                        'success'      => true,
                        'redirect_url' => $sessionUrl,
                        'session_id'   => $sessionId,
                        'message'      => 'تم إنشاء جلسة الدفع بنجاح.',
                    ];
                }
            }

            Log::error('Kashier session creation failed', [
                'status'   => $response->status(),
                'response' => $response->body(),
                'payload'  => $payload,
            ]);

            $errorMsg = $response->json('messages.ar')
                ?? $response->json('messages.en')
                ?? $response->json('message')
                ?? 'فشل الاتصال ببوابة كاشير.';

            return [
                'success' => false,
                'message' => 'حدث خطأ أثناء التواصل مع كاشير: ' . $errorMsg,
            ];

        } catch (\Throwable $e) {
            Log::error('Kashier initiate deposit exception: ' . $e->getMessage(), [
                'receipt_id' => $receipt->id,
                'trace'      => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'تعذر الاتصال ببوابة الدفع: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Initiate checkout session for a customer order in a merchant's store.
     *
     * @param \App\Models\Order $order
     * @return array ['success' => bool, 'redirect_url' => string, 'session_id' => string, 'message' => string]
     */
    public function createStoreOrderCheckout(\App\Models\Order $order): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'بوابة كاشير غير مهيأة بالشكل الصحيح في هذا المتجر.',
            ];
        }

        $email = $order->customer_email ?: 'customer@store.com';
        $orderId = "STORE_ORDER_{$order->tenant_id}_{$order->id}";
        $redirectUrl = url("/checkout/payment-callback/kashier?order_id={$order->id}");
        $webhookUrl = url('/api/webhooks/kashier');

        $endpoint = $this->getBaseUrl() . '/v3/payment/sessions';

        $payload = [
            'amount'           => number_format((float) $order->total, 2, '.', ''),
            'currency'         => 'EGP',
            'order'            => $orderId,
            'merchantId'       => $this->merchantId,
            'merchantRedirect' => $redirectUrl,
            'serverWebhook'    => $webhookUrl,
            'display'          => 'ar',
            'type'             => 'one-time',
            'allowedMethods'   => 'card,wallet',
            'customer'         => [
                'email'     => $email,
                'reference' => "order-{$order->id}",
            ],
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->secretKey,
                'api-key'       => $this->paymentApiKey,
                'Content-Type'  => 'application/json',
            ])->timeout(30)->post($endpoint, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $sessionUrl = $data['sessionUrl'] ?? null;
                $sessionId = $data['_id'] ?? null;

                if (!empty($sessionUrl)) {
                    $order->update([
                        'transaction_id'  => $sessionId ?: $orderId,
                        'payment_details' => array_merge($order->payment_details ?? [], [
                            'kashier_session_id' => $sessionId,
                            'kashier_order_id'   => $orderId,
                        ]),
                    ]);

                    return [
                        'success'      => true,
                        'redirect_url' => $sessionUrl,
                        'session_id'   => $sessionId,
                    ];
                }
            }

            Log::error('Kashier store order session creation failed', [
                'status'   => $response->status(),
                'response' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => 'تعذر بدء جلسة الدفع عبر كاشير: ' . ($response->json('messages.ar') ?? $response->json('message') ?? 'خطأ غير متوقع'),
            ];
        } catch (\Throwable $e) {
            Log::error('Kashier store order exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'حدث خطأ أثناء الاتصال ببوابة الدفع: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Validate the HMAC-SHA256 signature sent by Kashier (for redirect or webhook).
     *
     * Kashier signs an ordered list of 10 fields:
     * paymentStatus, cardDataToken, maskedCard, merchantOrderId, orderId,
     * cardBrand, orderReference, transactionId, amount, currency.
     *
     * @param array $params
     * @param string|null $signature
     * @return bool
     */
    public function verifySignature(array $params, ?string $signature): bool
    {
        if (empty($signature)) {
            return false;
        }

        $fields = [
            'paymentStatus',
            'cardDataToken',
            'maskedCard',
            'merchantOrderId',
            'orderId',
            'cardBrand',
            'orderReference',
            'transactionId',
            'amount',
            'currency',
        ];

        $dataParts = [];
        foreach ($fields as $field) {
            $val = isset($params[$field]) && $params[$field] !== '' && $params[$field] !== null
                ? (string) $params[$field]
                : 'null';
            $dataParts[] = "{$field}={$val}";
        }

        $message = implode('&', $dataParts);
        $cleanSig = strtolower(trim($signature));

        $sigApi = strtolower(hash_hmac('sha256', $message, $this->paymentApiKey));
        $sigSec = strtolower(hash_hmac('sha256', $message, $this->secretKey));

        return hash_equals($sigApi, $cleanSig) || hash_equals($sigSec, $cleanSig);
    }

    /**
     * Get secret key.
     */
    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    /**
     * Get webhook URL for admin display.
     */
    public function getWebhookUrl(): string
    {
        return url('/api/webhooks/kashier');
    }
}
