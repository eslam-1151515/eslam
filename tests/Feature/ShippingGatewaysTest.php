<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\ShippingGateway;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShippingGatewaysTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $this->tenant = Tenant::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'name' => 'Test Store',
            'slug' => 'teststore',
            'email' => 'test@store.com',
            'is_active' => true,
            'trial_ends_at' => now()->addMonth(),
        ]);

        $this->user = User::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Test Merchant',
            'email' => 'merchant@test.com',
            'password' => bcrypt('password'),
            'user_type' => 'merchant',
            'is_active' => true,
        ]);

        session()->put('tenant_id', $this->tenant->id);
    }

    public function test_merchant_can_view_shipping_gateways_page()
    {
        $response = $this->actingAs($this->user)
            ->withSession(['tenant_id' => $this->tenant->id])
            ->get('http://teststore.fastorder.test/admin/shipping-gateways');

        $response->assertStatus(200);
    }

    public function test_merchant_can_save_bosta_credentials()
    {
        $response = $this->actingAs($this->user)
            ->withSession(['tenant_id' => $this->tenant->id])
            ->post('http://teststore.fastorder.test/admin/shipping-gateways/connect-account', [
                'provider' => 'bosta',
                'email' => 'bosta@merchant.com',
                'password' => 'bostapass123',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('shipping_gateways', [
            'tenant_id' => $this->tenant->id,
            'provider' => 'bosta',
            'is_active' => true,
        ]);
    }

    public function test_merchant_can_connect_jnt_express_via_account_login()
    {
        $response = $this->actingAs($this->user)
            ->withSession(['tenant_id' => $this->tenant->id])
            ->post('http://teststore.fastorder.test/admin/shipping-gateways/connect-account', [
                'provider' => 'jnt',
                'email' => 'jnt@merchant.com',
                'password' => 'jntpass123',
            ]);

        $response->assertRedirect('http://teststore.fastorder.test/admin/shipping-gateways');
        $this->assertDatabaseHas('shipping_gateways', [
            'tenant_id' => $this->tenant->id,
            'provider' => 'jnt',
            'is_active' => true,
        ]);
    }

    public function test_merchant_can_create_shipment_for_order()
    {
        $order = Order::create([
            'tenant_id' => $this->tenant->id,
            'reference_number' => 'ORD-1001',
            'order_number' => 'ORD-1001',
            'customer_name' => 'John Doe',
            'customer_phone' => '01012345678',
            'customer_address' => '123 Test Street',
            'shipping_address' => '123 Test Street',
            'governorate' => 'Cairo',
            'subtotal' => 500.00,
            'total' => 500.00,
            'status' => 'pending',
            'items' => [
                [
                    'id' => 1,
                    'name' => 'Test Product',
                    'price' => 500.00,
                    'quantity' => 1,
                ]
            ],
        ]);

        $response = $this->actingAs($this->user)
            ->withSession(['tenant_id' => $this->tenant->id])
            ->post("http://teststore.fastorder.test/admin/orders/{$order->id}/shipment", [
                'provider' => 'bosta',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('shipments', [
            'tenant_id' => $this->tenant->id,
            'order_id' => $order->id,
            'provider' => 'bosta',
        ]);

        $this->assertEquals('shipped', $order->fresh()->status);
    }
}
