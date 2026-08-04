<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Setting;
use App\Models\BlacklistRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class GhostOrderBlockerTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        Cache::flush();

        $this->tenant = Tenant::create([
            'uuid' => 'tenant-ghost-uuid',
            'name' => 'Ghost Blocker Store',
            'slug' => 'ghoststore',
            'is_active' => true,
            'trial_ends_at' => now()->addMonth(),
        ]);

        $this->user = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'user_type' => 'merchant',
            'is_active' => true,
        ]);

        // Bind tenant
        session(['tenant_id' => $this->tenant->id]);
        config(['tenant.id' => $this->tenant->id]);
        app()->bind(Tenant::class, function () {
            return $this->tenant;
        });
    }

    public function test_blocker_is_ignored_when_disabled()
    {
        // 1. Blocker is disabled
        Setting::set('ghost_blocker_enabled', '0', 'general', $this->tenant->id);

        // Add a blocked IP
        BlacklistRecord::create([
            'tenant_id' => $this->tenant->id,
            'type' => 'ip',
            'value' => '127.0.0.1',
            'reason' => 'Spam',
        ]);

        // Request should pass (will not be blocked by middleware with 403 or redirect with validation errors)
        $response = $this->withSession(['tenant_id' => $this->tenant->id])
            ->post('http://ghoststore.fastorder.test/checkout', [
                'customer_phone' => '01012345678',
                'customer_email' => 'normal@example.com',
            ]);

        // It should NOT redirect with custom_phone validation errors from middleware
        $response->assertSessionDoesntHaveErrors(['customer_phone']);
    }

    public function test_blocker_blocks_blacklisted_ip()
    {
        Setting::set('ghost_blocker_enabled', '1', 'general', $this->tenant->id);

        BlacklistRecord::create([
            'tenant_id' => $this->tenant->id,
            'type' => 'ip',
            'value' => '192.168.1.100',
            'reason' => 'Spam IP',
        ]);

        $response = $this->withSession(['tenant_id' => $this->tenant->id])
            ->withServerVariables(['REMOTE_ADDR' => '192.168.1.100'])
            ->post('http://ghoststore.fastorder.test/checkout', [
                'customer_phone' => '01012345678',
                'customer_email' => 'normal@example.com',
            ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['customer_phone']);
    }

    public function test_blocker_blocks_blacklisted_phone()
    {
        Setting::set('ghost_blocker_enabled', '1', 'general', $this->tenant->id);

        BlacklistRecord::create([
            'tenant_id' => $this->tenant->id,
            'type' => 'phone',
            'value' => '01099999999',
            'reason' => 'Fake Customer',
        ]);

        $response = $this->withSession(['tenant_id' => $this->tenant->id])
            ->post('http://ghoststore.fastorder.test/checkout', [
                'customer_phone' => '01099999999',
                'customer_email' => 'normal@example.com',
            ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['customer_phone']);
    }

    public function test_blocker_blocks_blacklisted_email()
    {
        Setting::set('ghost_blocker_enabled', '1', 'general', $this->tenant->id);

        BlacklistRecord::create([
            'tenant_id' => $this->tenant->id,
            'type' => 'email',
            'value' => 'spammer@example.com',
            'reason' => 'Spammer email',
        ]);

        $response = $this->withSession(['tenant_id' => $this->tenant->id])
            ->post('http://ghoststore.fastorder.test/checkout', [
                'customer_phone' => '01012345678',
                'customer_email' => 'spammer@example.com',
            ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['customer_phone']);
    }

    public function test_blocker_blocks_by_settings_email_patterns()
    {
        Setting::set('ghost_blocker_enabled', '1', 'general', $this->tenant->id);
        Setting::set('blocked_emails', '*@spam.com, test@ghost.com', 'general', $this->tenant->id);

        // Wildcard match
        $response = $this->withSession(['tenant_id' => $this->tenant->id])
            ->post('http://ghoststore.fastorder.test/checkout', [
                'customer_phone' => '01012345678',
                'customer_email' => 'anything@spam.com',
            ]);
        $response->assertSessionHasErrors(['customer_phone']);

        // Specific match
        $response2 = $this->withSession(['tenant_id' => $this->tenant->id])
            ->post('http://ghoststore.fastorder.test/checkout', [
                'customer_phone' => '01012345678',
                'customer_email' => 'test@ghost.com',
            ]);
        $response2->assertSessionHasErrors(['customer_phone']);
    }

    public function test_blocker_checks_phone_sanity()
    {
        Setting::set('ghost_blocker_enabled', '1', 'general', $this->tenant->id);

        // Repeated digits
        $response = $this->withSession(['tenant_id' => $this->tenant->id])
            ->post('http://ghoststore.fastorder.test/checkout', [
                'customer_phone' => '1111111111',
                'customer_email' => 'normal@example.com',
            ]);
        $response->assertSessionHasErrors(['customer_phone']);

        // Sequential digits
        $response2 = $this->withSession(['tenant_id' => $this->tenant->id])
            ->post('http://ghoststore.fastorder.test/checkout', [
                'customer_phone' => '1234567890',
                'customer_email' => 'normal@example.com',
            ]);
        $response2->assertSessionHasErrors(['customer_phone']);
    }

    public function test_blocker_checks_egyptian_phone_format_if_enabled()
    {
        Setting::set('ghost_blocker_enabled', '1', 'general', $this->tenant->id);
        Setting::set('phone_verification_enabled', '1', 'general', $this->tenant->id);
        Setting::set('phone_verification_min_order', '50', 'general', $this->tenant->id);

        // Order subtotal above min order (we simulate by passing items in request)
        $response = $this->withSession(['tenant_id' => $this->tenant->id])
            ->post('http://ghoststore.fastorder.test/checkout', [
                'customer_phone' => '01912345678', // Invalid carrier prefix
                'customer_email' => 'normal@example.com',
                'items' => [
                    ['price' => 30, 'qty' => 2] // 60 total > 50 min
                ]
            ]);
        $response->assertSessionHasErrors(['customer_phone']);

        // Valid Egypt format
        $response2 = $this->withSession(['tenant_id' => $this->tenant->id])
            ->post('http://ghoststore.fastorder.test/checkout', [
                'customer_phone' => '01012345678', // Valid (starts with 010 and 11 digits)
                'customer_email' => 'normal@example.com',
                'items' => [
                    ['price' => 30, 'qty' => 2]
                ]
            ]);
        // The middleware should not block it (should proceed to checkout controller validation)
        $response2->assertSessionDoesntHaveErrors(['customer_phone']);
    }

    public function test_merchant_can_manage_blacklist()
    {
        // 1. View blacklist
        $response = $this->actingAs($this->user)
            ->get('http://ghoststore.fastorder.test/admin/blacklist');
        $response->assertOk();

        // 2. Add record
        $responseStore = $this->actingAs($this->user)
            ->from('http://ghoststore.fastorder.test/admin/blacklist')
            ->post('http://ghoststore.fastorder.test/admin/blacklist', [
                'type' => 'email',
                'value' => 'newblocked@example.com',
                'reason' => 'Abusive',
            ]);
        $responseStore->assertRedirect('http://ghoststore.fastorder.test/admin/blacklist');
        $this->assertDatabaseHas('blacklist_records', [
            'tenant_id' => $this->tenant->id,
            'type' => 'email',
            'value' => 'newblocked@example.com',
        ]);

        // 3. Delete record
        $record = BlacklistRecord::where('value', 'newblocked@example.com')->first();
        $responseDestroy = $this->actingAs($this->user)
            ->from('http://ghoststore.fastorder.test/admin/blacklist')
            ->delete("http://ghoststore.fastorder.test/admin/blacklist/{$record->id}");
        $responseDestroy->assertRedirect('http://ghoststore.fastorder.test/admin/blacklist');
        $this->assertDatabaseMissing('blacklist_records', [
            'id' => $record->id,
        ]);
    }
}
