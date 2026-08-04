<?php

namespace Tests\Feature;

use App\Models\AbandonedCart;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\AbandonedCartRecoveryMail;
use Tests\TestCase;
use Illuminate\Support\Str;

class AbandonedCartTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $user;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $this->tenant = Tenant::create([
            'uuid' => 'tenant-abandoned-uuid',
            'name' => 'Abandoned Test Store',
            'slug' => 'abandonedstore',
            'is_active' => true,
            'trial_ends_at' => now()->addMonth(),
        ]);

        $this->user = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'user_type' => 'merchant',
            'is_active' => true,
        ]);

        $category = Category::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Test Cat',
            'name_ar' => 'قسم تجريبي',
            'main_category' => 'أجهزة عناية شخصية'
        ]);

        $this->product = Product::create([
            'tenant_id' => $this->tenant->id,
            'category_id' => $category->id,
            'name' => 'Test Product',
            'price' => 100.00,
            'price_after' => 100.00,
            'stock' => 10,
            'shipping_type' => 'free',
        ]);
    }

    public function test_guest_can_track_partial_checkout_data()
    {
        // 1. Send initial request to boot the session and get response cookie
        $response1 = $this->get('http://abandonedstore.fastorder.test/');
        $sessionCookie = $response1->getCookie(config('session.cookie'));
        $this->assertNotNull($sessionCookie, 'Session cookie must be present');

        $sessionId = session()->getId();

        // 2. Create the cart in database linked to this session ID
        $cart = Cart::create([
            'tenant_id' => $this->tenant->id,
            'session_id' => $sessionId,
        ]);

        $cart->items()->create([
            'tenant_id' => $this->tenant->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
            'price' => 100.00,
        ]);

        // 3. Send track partial request, passing the cookie we got
        $response = $this->withCookie($sessionCookie->getName(), $sessionCookie->getValue())
        ->post('http://abandonedstore.fastorder.test/shop/checkout/track-partial', [
            'email' => 'guest@example.com',
            'phone' => '01012345678',
        ], [
            'Accept' => 'application/json'
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('abandoned_carts', [
            'tenant_id' => $this->tenant->id,
            'email' => 'guest@example.com',
            'phone' => '01012345678',
        ]);
    }

    public function test_client_can_recover_cart_using_token()
    {
        $token = Str::random(40);
        $cartData = [
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 3,
                ]
            ],
            'subtotal' => 300.00,
            'total' => 300.00,
        ];

        $abandoned = AbandonedCart::create([
            'tenant_id' => $this->tenant->id,
            'recovery_token' => $token,
            'cart_data' => $cartData,
            'session_id' => 'session-abandoned',
        ]);

        $response = $this->get("http://abandonedstore.fastorder.test/shop/cart/recover/{$token}");

        // Asserts redirect to storefront checkout
        $response->assertRedirect(); // Can redirect to route or relative path

        $this->assertDatabaseHas('carts', [
            'tenant_id' => $this->tenant->id,
        ]);
    }

    public function test_merchant_can_view_abandoned_carts_list()
    {
        $response = $this->actingAs($this->user)
            ->get('http://abandonedstore.fastorder.test/admin/abandoned-carts');

        $response->assertOk();
    }

    public function test_merchant_can_send_recovery_reminder_email()
    {
        Mail::fake();

        $abandoned = AbandonedCart::create([
            'tenant_id' => $this->tenant->id,
            'email' => 'recoverme@example.com',
            'phone' => '01012345678',
            'recovery_token' => Str::random(40),
            'cart_data' => [
                'items' => [],
                'total' => 0.00,
            ],
            'session_id' => 'session-abc',
        ]);

        $response = $this->actingAs($this->user)
            ->from('http://abandonedstore.fastorder.test/admin/abandoned-carts')
            ->post("http://abandonedstore.fastorder.test/admin/abandoned-carts/{$abandoned->id}/send-reminder", [
                'locale' => 'ar',
                'discount_code' => 'SAVE10',
                'discount_percentage' => 10,
            ]);

        $response->assertRedirect('http://abandonedstore.fastorder.test/admin/abandoned-carts');
        $response->assertSessionHas('success');

        Mail::assertQueued(AbandonedCartRecoveryMail::class, function ($mail) use ($abandoned) {
            return $mail->abandonedCart->id === $abandoned->id &&
                   $mail->discountCode === 'SAVE10' &&
                   $mail->discountPercentage == 10;
        });

        $abandoned->refresh();
        $this->assertNotNull($abandoned->recovery_email_sent_at);
    }

    public function test_merchant_can_delete_abandoned_cart_record()
    {
        $abandoned = AbandonedCart::create([
            'tenant_id' => $this->tenant->id,
            'email' => 'delete@example.com',
            'recovery_token' => Str::random(40),
            'session_id' => 'session-delete',
            'cart_data' => [
                'items' => [],
                'total' => 0.00,
            ],
        ]);

        $response = $this->actingAs($this->user)
            ->from('http://abandonedstore.fastorder.test/admin/abandoned-carts')
            ->delete("http://abandonedstore.fastorder.test/admin/abandoned-carts/{$abandoned->id}");

        $response->assertRedirect('http://abandonedstore.fastorder.test/admin/abandoned-carts');

        $this->assertDatabaseMissing('abandoned_carts', [
            'id' => $abandoned->id,
        ]);
    }
}
