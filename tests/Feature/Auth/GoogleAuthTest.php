<?php

namespace Tests\Feature\Auth;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirect_to_google_initiates_oauth_flow(): void
    {
        config(['services.google.client_id' => 'test-google-client-id']);
        config(['services.google.redirect' => 'https://ordersaif.com/auth/google/callback']);

        $response = $this->get('/auth/google');

        $response->assertRedirect();
        $targetUrl = $response->headers->get('Location');
        $this->assertStringContainsString('accounts.google.com', $targetUrl);
        $this->assertStringContainsString('client_id=test-google-client-id', $targetUrl);
        $this->assertStringContainsString('response_type=code', $targetUrl);
    }

    public function test_google_entry_authenticates_merchant_with_valid_token(): void
    {
        $tenant = Tenant::create([
            'uuid' => 'tenant-uuid-1',
            'name' => 'Test Store',
            'slug' => 'teststore',
            'is_active' => true,
        ]);

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'user_type' => 'merchant',
        ]);

        $token = 'test_valid_google_token_12345';
        Cache::put('google_login_token_' . $token, [
            'user_id' => $user->id,
            'tenant_id' => $tenant->id,
        ], now()->addSeconds(60));

        $response = $this->get('/admin/google-entry?token=' . $token);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/admin/dashboard');
    }

    public function test_google_entry_rejects_invalid_or_expired_token(): void
    {
        $response = $this->get('/admin/google-entry?token=non_existent_token');

        $this->assertGuest();
        $response->assertRedirect('/admin/login');
        $response->assertSessionHasErrors(['error']);
    }
}
