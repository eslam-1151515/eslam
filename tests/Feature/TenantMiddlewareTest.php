<?php

namespace Tests\Feature;

use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class TenantMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Register test routes with the middleware
        Route::middleware(['web', 'tenant.identify', 'tenant.active'])->group(function () {
            Route::get('/_test/tenant-check', function () {
                $tenant = app(Tenant::class);
                return response()->json([
                    'id' => $tenant->id,
                    'slug' => $tenant->slug,
                    'is_active' => $tenant->is_active,
                ]);
            });
        });

        // Register a public/central test route with only identity check
        Route::middleware(['web', 'tenant.identify'])->group(function () {
            Route::get('/_test/tenant-identify-only', function () {
                $tenantBound = app()->bound(Tenant::class);
                return response()->json([
                    'bound' => $tenantBound,
                    'tenant' => $tenantBound ? app(Tenant::class)->slug : null,
                ]);
            });
        });
    }

    public function test_main_domain_does_not_require_or_bind_tenant()
    {
        $response = $this->get('/_test/tenant-identify-only');

        $response->assertStatus(200);
        $response->assertJson([
            'bound' => false,
            'tenant' => null,
        ]);
    }

    public function test_valid_tenant_subdomain_binds_tenant()
    {
        $tenant = Tenant::create([
            'uuid' => 'tenant-1-uuid',
            'name' => 'Merchant One',
            'slug' => 'merchant1',
            'is_active' => true,
        ]);

        $response = $this->get('http://merchant1.fastorder.test/_test/tenant-check');

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $tenant->id,
            'slug' => 'merchant1',
        ]);
    }

    public function test_invalid_tenant_subdomain_returns_404()
    {
        // Visit non-existent tenant subdomain
        $response = $this->get('http://nonexistent.fastorder.test/_test/tenant-check');

        $response->assertStatus(404);
    }

    public function test_inactive_tenant_returns_403()
    {
        Tenant::create([
            'uuid' => 'tenant-2-uuid',
            'name' => 'Merchant Two',
            'slug' => 'merchant2',
            'is_active' => false,
        ]);

        $response = $this->get('http://merchant2.fastorder.test/_test/tenant-check');

        $response->assertStatus(403);
        $response->assertSee('المتجر معطل حالياً');
    }

    public function test_expired_subscription_tenant_returns_403()
    {
        Tenant::create([
            'uuid' => 'tenant-3-uuid',
            'name' => 'Merchant Three',
            'slug' => 'merchant3',
            'is_active' => true,
            'trial_ends_at' => now()->subDay(), // Expired trial
            'subscription_ends_at' => now()->subDay(), // Expired subscription
        ]);

        $response = $this->get('http://merchant3.fastorder.test/_test/tenant-check');

        $response->assertStatus(403);
        $response->assertSee('المتجر غير متاح حالياً');
    }

    public function test_active_trial_tenant_is_allowed()
    {
        Tenant::create([
            'uuid' => 'tenant-4-uuid',
            'name' => 'Merchant Four',
            'slug' => 'merchant4',
            'is_active' => true,
            'trial_ends_at' => now()->addDay(), // Active trial
            'subscription_ends_at' => null,
        ]);

        $response = $this->get('http://merchant4.fastorder.test/_test/tenant-check');

        $response->assertStatus(200);
        $response->assertJson([
            'slug' => 'merchant4',
        ]);
    }
}
