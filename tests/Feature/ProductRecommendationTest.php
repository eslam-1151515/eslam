<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductRecommendation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductRecommendationTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $user;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $this->tenant = Tenant::create([
            'uuid' => 'tenant-recomm-uuid',
            'name' => 'Recomm Test Store',
            'slug' => 'recommstore',
            'is_active' => true,
            'trial_ends_at' => now()->addMonth(),
        ]);

        $this->user = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'user_type' => 'merchant',
            'is_active' => true,
        ]);

        $this->category = Category::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Gadgets',
            'name_ar' => 'أجهزة',
            'main_category' => 'أجهزة عناية شخصية'
        ]);

        // Bind tenant
        session(['tenant_id' => $this->tenant->id]);
        config(['tenant.id' => $this->tenant->id]);
        app()->bind(Tenant::class, function () {
            return $this->tenant;
        });
    }

    public function test_merchant_can_configure_upsell_and_cross_sell_recommendations()
    {
        $productMain = Product::create([
            'tenant_id' => $this->tenant->id,
            'category_id' => $this->category->id,
            'name' => 'Main Product',
            'price' => 100.00,
            'price_after' => 100.00,
            'stock' => 10,
            'shipping_type' => 'free',
        ]);

        $productUpsell = Product::create([
            'tenant_id' => $this->tenant->id,
            'category_id' => $this->category->id,
            'name' => 'Upsell Product',
            'price' => 150.00,
            'price_after' => 150.00,
            'stock' => 10,
            'shipping_type' => 'free',
        ]);

        $productCross = Product::create([
            'tenant_id' => $this->tenant->id,
            'category_id' => $this->category->id,
            'name' => 'Cross-sell Product',
            'price' => 20.00,
            'price_after' => 20.00,
            'stock' => 10,
            'shipping_type' => 'free',
        ]);

        // Put request to update product recommendations
        $response = $this->actingAs($this->user)
            ->from("http://recommstore.fastorder.test/admin/products/{$productMain->id}/edit")
            ->put("http://recommstore.fastorder.test/admin/products/{$productMain->id}", [
                'name' => 'Main Product Updated',
                'category_id' => $this->category->id,
                'price_after' => 100.00,
                'stock' => 10,
                'shipping_type' => 'free',
                'upsell_ids' => [$productUpsell->id],
                'cross_sell_ids' => [$productCross->id],
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('product_recommendations', [
            'tenant_id' => $this->tenant->id,
            'product_id' => $productMain->id,
            'recommended_id' => $productUpsell->id,
            'type' => 'upsell',
        ]);

        $this->assertDatabaseHas('product_recommendations', [
            'tenant_id' => $this->tenant->id,
            'product_id' => $productMain->id,
            'recommended_id' => $productCross->id,
            'type' => 'cross-sell',
        ]);
    }

    public function test_storefront_can_retrieve_cross_sell_recommendations()
    {
        $productMain = Product::create([
            'tenant_id' => $this->tenant->id,
            'category_id' => $this->category->id,
            'name' => 'Main Product',
            'price' => 100.00,
            'price_after' => 100.00,
            'stock' => 10,
            'shipping_type' => 'free',
        ]);

        $productCross = Product::create([
            'tenant_id' => $this->tenant->id,
            'category_id' => $this->category->id,
            'name' => 'Cross-sell Product',
            'price' => 20.00,
            'price_after' => 20.00,
            'stock' => 10,
            'shipping_type' => 'free',
        ]);

        ProductRecommendation::create([
            'tenant_id' => $this->tenant->id,
            'product_id' => $productMain->id,
            'recommended_id' => $productCross->id,
            'type' => 'cross-sell',
        ]);

        $response = $this->withSession(['tenant_id' => $this->tenant->id])
            ->get("http://recommstore.fastorder.test/public-api/recommendations?ids={$productMain->id}&type=cross-sell");

        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $productCross->id,
            'name' => 'Cross-sell Product',
        ]);
    }

    public function test_storefront_can_retrieve_upsell_recommendations()
    {
        $productMain = Product::create([
            'tenant_id' => $this->tenant->id,
            'category_id' => $this->category->id,
            'name' => 'Main Product',
            'price' => 100.00,
            'price_after' => 100.00,
            'stock' => 10,
            'shipping_type' => 'free',
        ]);

        $productUpsell = Product::create([
            'tenant_id' => $this->tenant->id,
            'category_id' => $this->category->id,
            'name' => 'Upsell Product',
            'price' => 150.00,
            'price_after' => 150.00,
            'stock' => 5,
            'shipping_type' => 'free',
        ]);

        ProductRecommendation::create([
            'tenant_id' => $this->tenant->id,
            'product_id' => $productMain->id,
            'recommended_id' => $productUpsell->id,
            'type' => 'upsell',
        ]);

        $response = $this->withSession(['tenant_id' => $this->tenant->id])
            ->get("http://recommstore.fastorder.test/public-api/recommendations?ids={$productMain->id}&type=upsell");

        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $productUpsell->id,
            'name' => 'Upsell Product',
        ]);
    }

    public function test_storefront_excludes_out_of_stock_and_duplicate_view_recommendations()
    {
        $productMain = Product::create([
            'tenant_id' => $this->tenant->id,
            'category_id' => $this->category->id,
            'name' => 'Main Product',
            'price' => 100.00,
            'price_after' => 100.00,
            'stock' => 10,
            'shipping_type' => 'free',
        ]);

        $productOos = Product::create([
            'tenant_id' => $this->tenant->id,
            'category_id' => $this->category->id,
            'name' => 'Out of Stock Product',
            'price' => 50.00,
            'price_after' => 50.00,
            'stock' => 0, // Out of stock
            'shipping_type' => 'free',
        ]);

        ProductRecommendation::create([
            'tenant_id' => $this->tenant->id,
            'product_id' => $productMain->id,
            'recommended_id' => $productOos->id,
            'type' => 'cross-sell',
        ]);

        // Request with out of stock product recommendation -> should be filtered out
        $response1 = $this->withSession(['tenant_id' => $this->tenant->id])
            ->get("http://recommstore.fastorder.test/public-api/recommendations?ids={$productMain->id}&type=cross-sell");

        $response1->assertOk();
        $this->assertCount(0, $response1->json('data'));

        // Request with self recommendation or already listed query ids -> should be filtered out
        $productInCart = Product::create([
            'tenant_id' => $this->tenant->id,
            'category_id' => $this->category->id,
            'name' => 'In Cart Product',
            'price' => 30.00,
            'price_after' => 30.00,
            'stock' => 5,
            'shipping_type' => 'free',
        ]);

        ProductRecommendation::create([
            'tenant_id' => $this->tenant->id,
            'product_id' => $productMain->id,
            'recommended_id' => $productInCart->id,
            'type' => 'cross-sell',
        ]);

        // Send query with both productMain and productInCart -> productInCart should be filtered because it is in query 'ids'
        $response2 = $this->withSession(['tenant_id' => $this->tenant->id])
            ->get("http://recommstore.fastorder.test/public-api/recommendations?ids={$productMain->id},{$productInCart->id}&type=cross-sell");

        $response2->assertOk();
        $this->assertCount(0, $response2->json('data'));
    }
}
