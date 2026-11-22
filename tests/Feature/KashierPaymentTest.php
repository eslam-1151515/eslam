<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\SubscriptionReceipt;
use App\Models\Tenant;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\KashierService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class KashierPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::setGlobal('kashier_active', '1', 'payment');
        Setting::setGlobal('kashier_mode', 'test', 'payment');
        Setting::setGlobal('kashier_merchant_id', 'MID-52019-895', 'payment');
        Setting::setGlobal('kashier_payment_api_key', '19a348ba-f4b5-4b5b-a998-deb66d1406a9', 'payment');
        Setting::setGlobal('kashier_secret_key', 'test_secret_key_123', 'payment');
    }

    public function test_kashier_service_is_configured_and_detects_modes()
    {
        $service = app(KashierService::class);
        $this->assertTrue($service->isConfigured());
        $this->assertFalse($service->isLiveMode());
        $this->assertEquals('https://test-api.kashier.io', $service->getBaseUrl());

        Setting::setGlobal('kashier_mode', 'live', 'payment');
        $liveService = new KashierService();
        $this->assertTrue($liveService->isLiveMode());
        $this->assertEquals('https://api.kashier.io', $liveService->getBaseUrl());
    }

    public function test_kashier_signature_verification_succeeds_for_valid_payload()
    {
        $service = new KashierService(
            'MID-52019-895',
            'test_payment_api_key',
            'test_secret',
            'test'
        );

        $params = [
            'paymentStatus'   => 'SUCCESS',
            'cardDataToken'   => 'token_123',
            'maskedCard'      => '411111******1111',
            'merchantOrderId' => 'WALLET_1_10',
            'orderId'         => 'KSH_ORDER_99',
            'cardBrand'       => 'VISA',
            'orderReference'  => 'REF_100',
            'transactionId'   => 'TX_555',
            'amount'          => '300.00',
            'currency'        => 'EGP',
        ];

        // Construct expected message
        $fields = [
            'paymentStatus', 'cardDataToken', 'maskedCard', 'merchantOrderId', 'orderId',
            'cardBrand', 'orderReference', 'transactionId', 'amount', 'currency'
        ];
        $parts = [];
        foreach ($fields as $f) {
            $parts[] = "{$f}={$params[$f]}";
        }
        $validSignature = hash_hmac('sha256', implode('&', $parts), 'test_payment_api_key');

        $this->assertTrue($service->verifySignature($params, $validSignature));
        $this->assertFalse($service->verifySignature($params, 'invalid_signature_hex'));
    }

    public function test_kashier_initiate_deposit_creates_session_successfully()
    {
        Http::fake([
            'https://test-api.kashier.io/v3/payment/sessions' => Http::response([
                'sessionUrl' => 'https://payments.kashier.io/session/test_session_123',
                '_id'        => 'test_session_123',
                'order'      => 'WALLET_1_50',
            ], 200),
        ]);

        $tenant = Tenant::create([
            'uuid'           => (string) \Illuminate\Support\Str::uuid(),
            'name'           => 'متجر السيف التجريبي',
            'slug'           => 'saif-store',
            'email'          => 'saif@ordersaif.com',
            'wallet_balance' => 0,
        ]);

        $receipt = SubscriptionReceipt::create([
            'tenant_id'         => $tenant->id,
            'type'              => 'wallet',
            'amount'            => 500,
            'payment_method'    => 'kashier',
            'payment_reference' => 'Pending Kashier',
            'status'            => 'pending',
        ]);

        $service = app(KashierService::class);
        $result = $service->initiateDeposit($receipt, $tenant);

        $this->assertTrue($result['success']);
        $this->assertEquals('https://payments.kashier.io/session/test_session_123', $result['redirect_url']);
        $this->assertEquals('test_session_123', $result['session_id']);
    }

    public function test_kashier_webhook_processes_success_and_credits_wallet_idempotently()
    {
        $tenant = Tenant::create([
            'uuid'           => (string) \Illuminate\Support\Str::uuid(),
            'name'           => 'متجر التجربة',
            'slug'           => 'test-store',
            'email'          => 'test@ordersaif.com',
            'wallet_balance' => 100.00,
        ]);

        $receipt = SubscriptionReceipt::create([
            'tenant_id'         => $tenant->id,
            'type'              => 'wallet',
            'amount'            => 300.00,
            'payment_method'    => 'kashier',
            'payment_reference' => 'Pending...',
            'status'            => 'pending',
        ]);

        $payload = [
            'paymentStatus'   => 'SUCCESS',
            'merchantOrderId' => "WALLET_{$tenant->id}_{$receipt->id}",
            'orderId'         => 'KSH_12345',
            'transactionId'   => 'TX_987654',
            'amount'          => '300.00',
            'currency'        => 'EGP',
            'cardBrand'       => 'VISA',
        ];

        // First webhook delivery
        $response = $this->postJson(route('api.webhooks.kashier'), $payload);
        $response->assertStatus(200);
        $response->assertJson(['status' => 'PROCESSED']);

        $receipt->refresh();
        $tenant->refresh();

        $this->assertEquals('approved', $receipt->status);
        $this->assertEquals(400.00, (float) $tenant->wallet_balance);

        $this->assertDatabaseHas('wallet_transactions', [
            'tenant_id' => $tenant->id,
            'amount'    => 300.00,
            'type'      => 'credit',
        ]);

        // Duplicate delivery (Idempotency test)
        $responseDuplicate = $this->postJson(route('api.webhooks.kashier'), $payload);
        $responseDuplicate->assertStatus(200);

        $tenant->refresh();
        // Balance must remain 400, NOT 700!
        $this->assertEquals(400.00, (float) $tenant->wallet_balance);
        $this->assertEquals(1, WalletTransaction::where('tenant_id', $tenant->id)->count());
    }

    public function test_kashier_webhook_processes_failure_and_rejects_receipt()
    {
        $tenant = Tenant::create([
            'uuid'           => (string) \Illuminate\Support\Str::uuid(),
            'name'           => 'متجر التجربة',
            'slug'           => 'test-store-fail',
            'email'          => 'testfail@ordersaif.com',
            'wallet_balance' => 50.00,
        ]);

        $receipt = SubscriptionReceipt::create([
            'tenant_id'         => $tenant->id,
            'type'              => 'wallet',
            'amount'            => 300.00,
            'payment_method'    => 'kashier',
            'payment_reference' => 'Pending...',
            'status'            => 'pending',
        ]);

        $payload = [
            'paymentStatus'   => 'FAILED',
            'merchantOrderId' => "WALLET_{$tenant->id}_{$receipt->id}",
            'orderId'         => 'KSH_FAIL_123',
            'transactionId'   => 'TX_FAILED_001',
            'amount'          => '300.00',
            'currency'        => 'EGP',
        ];

        $response = $this->postJson(route('api.webhooks.kashier'), $payload);
        $response->assertStatus(200);

        $receipt->refresh();
        $tenant->refresh();

        $this->assertEquals('rejected', $receipt->status);
        $this->assertEquals(50.00, (float) $tenant->wallet_balance);
    }
}
