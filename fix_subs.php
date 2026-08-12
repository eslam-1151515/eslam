<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$freePlan = \App\Models\SubscriptionPlan::where('slug', 'free')->first() ?? \App\Models\SubscriptionPlan::first();
if ($freePlan) {
    foreach (\App\Models\Tenant::all() as $tenant) {
        if ($tenant->subscriptions()->count() === 0) {
            $endsAt = $tenant->subscription_ends_at ?: now()->addDays(7);
            \App\Models\Subscription::create([
                'tenant_id'     => $tenant->id,
                'plan_id'       => $freePlan->id,
                'status'        => 'active',
                'billing_cycle' => 'monthly',
                'price'         => 0,
                'starts_at'     => $tenant->created_at ?: now(),
                'ends_at'       => $endsAt,
                'trial_ends_at' => $endsAt,
            ]);
            if (!$tenant->subscription_ends_at) {
                $tenant->update([
                    'subscription_status' => 'trial',
                    'subscription_ends_at' => $endsAt,
                    'trial_ends_at' => $endsAt,
                ]);
            }
        }
    }
}
echo "Subscriptions synced successfully.\n";
