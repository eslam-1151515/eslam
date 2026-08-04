<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderReturn;
use App\Models\StockMovement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderReturnTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $merchant;
    protected $customer;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $this->tenant = Tenant::create([
            'uuid' => 'tenant-return-uuid',
            'name' => 'Returns Test Store',
            'slug' => 'returnstore',
            'is_active' => true,
            'trial_ends_at' => now()->addMonth(),
        ]);

        $this->merchant = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'user_type' => 'merchant',
            'is_active' => true,
        ]);

        $this->customer = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'user_type' => 'customer',
            'is_active' => true,
        ]);

        $category = Category::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Cat',
            'name_ar' => 'قسم',
            'main_category' => 'أجهزة عناية شخصية'
        ]);

        $this->product = Product::create([
            'tenant_id' => $this->tenant->id,
            'category_id' => $category->id,
            'name' => 'Returnable Item',
            'price' => 150.00,
            'price_after' => 150.00,
            'stock' => 5,
            'shipping_type' => 'free',
        ]);

        // Bind tenant
        session(['tenant_id' => $this->tenant->id]);
        config(['tenant.id' => $this->tenant->id]);
        app()->bind(Tenant::class, function () {
            return $this->tenant;
        });
    }

    public function test_customer_can_request_return_for_delivered_order()
    {
        $order = Order::create([
            'tenant_id' => $this->tenant->id,
            'reference_number' => '11111',
            'customer_name' => $this->customer->name,
            'customer_email' => $this->customer->email,
            'customer_phone' => '01012345678',
            'customer_address' => 'Cairo',
            'governorate' => 'القاهرة',
            'payment_method' => 'cod',
            'subtotal' => 150.00,
            'total' => 150.00,
            'status' => 'delivered',
            'items' => [
                [
                    'id' => $this->product->id,
                    'name' => $this->product->name,
                    'price' => 150.00,
                    'quantity' => 1,
                ]
            ]
        ]);

        $response = $this->actingAs($this->customer)
            ->withSession(['tenant_id' => $this->tenant->id])
            ->post('http://returnstore.fastorder.test/api/account/returns', [
                'order_id' => $order->id,
                'items' => [
                    [
                        'id' => $this->product->id,
                        'quantity' => 1,
                    ]
                ],
                'reason' => 'Defected item',
            ]);

        $response->assertOk();
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('order_returns', [
            'tenant_id' => $this->tenant->id,
            'order_id' => $order->id,
            'status' => 'pending',
            'reason' => 'Defected item',
        ]);
    }

    public function test_customer_cannot_request_return_for_non_delivered_order()
    {
        $order = Order::create([
            'tenant_id' => $this->tenant->id,
            'reference_number' => '22222',
            'customer_name' => $this->customer->name,
            'customer_email' => $this->customer->email,
            'customer_phone' => '01012345678',
            'customer_address' => 'Cairo',
            'governorate' => 'القاهرة',
            'payment_method' => 'cod',
            'subtotal' => 150.00,
            'total' => 150.00,
            'status' => 'pending', // Not delivered
            'items' => [
                [
                    'id' => $this->product->id,
                    'name' => $this->product->name,
                    'price' => 150.00,
                    'quantity' => 1,
                ]
            ]
        ]);

        $response = $this->actingAs($this->customer)
            ->withSession(['tenant_id' => $this->tenant->id])
            ->post('http://returnstore.fastorder.test/api/account/returns', [
                'order_id' => $order->id,
                'items' => [
                    [
                        'id' => $this->product->id,
                        'quantity' => 1,
                    ]
                ],
                'reason' => 'Changed my mind',
            ]);

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
    }

    public function test_merchant_can_view_returns_and_manage_them()
    {
        $order = Order::create([
            'tenant_id' => $this->tenant->id,
            'reference_number' => '33333',
            'customer_name' => $this->customer->name,
            'customer_email' => $this->customer->email,
            'customer_phone' => '01012345678',
            'customer_address' => 'Cairo',
            'governorate' => 'القاهرة',
            'payment_method' => 'cod',
            'subtotal' => 150.00,
            'total' => 150.00,
            'status' => 'delivered',
            'items' => [
                [
                    'id' => $this->product->id,
                    'name' => $this->product->name,
                    'price' => 150.00,
                    'quantity' => 1,
                ]
            ]
        ]);

        $orderReturn = OrderReturn::create([
            'tenant_id' => $this->tenant->id,
            'order_id' => $order->id,
            'user_id' => $this->customer->id,
            'items' => [
                [
                    'id' => $this->product->id,
                    'name' => $this->product->name,
                    'price' => 150.00,
                    'quantity' => 1,
                ]
            ],
            'reason' => 'Broken part',
            'status' => 'pending',
            'refund_amount' => 150.00,
        ]);

        // 1. Merchant Index
        $responseIndex = $this->actingAs($this->merchant)
            ->get('http://returnstore.fastorder.test/admin/returns');
        $responseIndex->assertOk();

        // 2. Merchant Show
        $responseShow = $this->actingAs($this->merchant)
            ->get("http://returnstore.fastorder.test/admin/returns/{$orderReturn->id}");
        $responseShow->assertOk();

        // 3. Merchant Approve (Initial Approval)
        $responseApprove = $this->actingAs($this->merchant)
            ->from("http://returnstore.fastorder.test/admin/returns/{$orderReturn->id}")
            ->post("http://returnstore.fastorder.test/admin/returns/{$orderReturn->id}/approve", [
                'notes' => 'Approved initial check',
            ]);
        $responseApprove->assertRedirect();
        $this->assertDatabaseHas('order_returns', [
            'id' => $orderReturn->id,
            'status' => 'approved',
        ]);

        // 4. Merchant Reject
        $responseReject = $this->actingAs($this->merchant)
            ->from("http://returnstore.fastorder.test/admin/returns/{$orderReturn->id}")
            ->post("http://returnstore.fastorder.test/admin/returns/{$orderReturn->id}/reject", [
                'notes' => 'Not matching conditions',
            ]);
        $responseReject->assertRedirect();
        $this->assertDatabaseHas('order_returns', [
            'id' => $orderReturn->id,
            'status' => 'rejected',
        ]);

        // Reset status to approved to complete it
        $orderReturn->update(['status' => 'approved']);

        // 5. Merchant Complete (Final restock and completion)
        $initialStock = $this->product->stock; // 5

        $responseComplete = $this->actingAs($this->merchant)
            ->from("http://returnstore.fastorder.test/admin/returns/{$orderReturn->id}")
            ->post("http://returnstore.fastorder.test/admin/returns/{$orderReturn->id}/complete", [
                'notes' => 'Refund processed',
            ]);

        $responseComplete->assertRedirect();
        
        // Assert return is completed
        $this->assertDatabaseHas('order_returns', [
            'id' => $orderReturn->id,
            'status' => 'completed',
        ]);

        // Assert stock incremented (5 + 1 = 6)
        $this->product->refresh();
        $this->assertEquals($initialStock + 1, $this->product->stock);

        // Assert StockMovement registered
        $this->assertDatabaseHas('stock_movements', [
            'tenant_id' => $this->tenant->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
            'type' => 'return',
        ]);

        // Assert original order notes updated
        $order->refresh();
        $this->assertStringContainsString('مرتجع مكتمل', $order->notes);
    }
}
