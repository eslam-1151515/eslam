<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'price_before')) {
                $table->decimal('price_before', 10, 2)->default(0)->after('price');
            }
            if (!Schema::hasColumn('products', 'price_after')) {
                $table->decimal('price_after', 10, 2)->default(0)->after('price_before');
            }
            if (!Schema::hasColumn('products', 'main_image_path')) {
                $table->string('main_image_path')->nullable()->after('image_url');
            }
        });

        if (!Schema::hasTable('product_images')) {
            Schema::create('product_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->string('image_path');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('product_images')) {
            Schema::dropIfExists('product_images');
        }
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'main_image_path')) {
                $table->dropColumn('main_image_path');
            }
            if (Schema::hasColumn('products', 'price_after')) {
                $table->dropColumn('price_after');
            }
            if (Schema::hasColumn('products', 'price_before')) {
                $table->dropColumn('price_before');
            }
        });
    }
};
