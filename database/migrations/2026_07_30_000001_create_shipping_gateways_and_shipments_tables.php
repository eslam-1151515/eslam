<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipping_gateways', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->index();
            $table->string('provider', 50); // bosta, jnt, egypt_post
            $table->boolean('is_active')->default(false);
            $table->text('credentials')->nullable(); // Encrypted/JSON API keys / Tokens
            $table->json('settings')->nullable(); // Default pickup address, shipping type, etc.
            $table->timestamps();

            $table->unique(['tenant_id', 'provider']);
        });

        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->index();
            $table->unsignedBigInteger('order_id')->index();
            $table->string('provider', 50); // bosta, jnt, egypt_post
            $table->string('tracking_number')->nullable()->index();
            $table->string('airway_bill_url')->nullable();
            $table->string('status', 50)->default('created'); // created, picked_up, in_transit, delivered, cancelled
            $table->decimal('cost', 10, 2)->default(0.00);
            $table->json('raw_response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('shipping_gateways');
    }
};
