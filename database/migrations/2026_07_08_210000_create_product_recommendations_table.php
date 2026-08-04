<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('recommended_id')->constrained('products')->onDelete('cascade');
            $table->string('type'); // 'upsell' or 'cross-sell'
            $table->timestamps();

            $table->unique(['tenant_id', 'product_id', 'recommended_id', 'type'], 'prod_rec_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_recommendations');
    }
};
