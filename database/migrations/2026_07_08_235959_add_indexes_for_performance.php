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
        Schema::table('orders', function (Blueprint $table) {
            $table->index(['tenant_id', 'status']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->index(['tenant_id', 'category_id']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index(['tenant_id', 'parent_id']);
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->index(['tenant_id', 'is_active']);
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->index(['tenant_id', 'status']);
        });

        Schema::table('order_returns', function (Blueprint $table) {
            $table->index(['tenant_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_returns', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'status']);
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'status']);
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'is_active']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'parent_id']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'category_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'status']);
        });
    }
};
