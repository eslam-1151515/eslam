<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Setting;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Setting::setGlobal('kashier_active', '1', 'payment');
        Setting::setGlobal('kashier_mode', 'live', 'payment');
        Setting::setGlobal('kashier_merchant_id', 'MID-52019-895', 'payment');
        Setting::setGlobal('kashier_payment_api_key', '0b01aadf-0df2-4d90-864c-913dc3462b1c', 'payment');
        Setting::setGlobal('kashier_secret_key', '20b46d7dd22d75e195c8d0f0f6ed6e41$b47fdd473e076b8e035662ea2fd217bf96c7196524de12cf5c1dbfebd85ef6276888f3a833443645a0a29441f243590e', 'payment');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
