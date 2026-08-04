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
        Schema::create('theme_marketplaces', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('author')->default('Fast Order Team');
            $table->string('version')->default('1.0.0');
            $table->string('type')->default('free'); // free, paid
            $table->decimal('price', 10, 2)->default(0.00);
            $table->string('currency', 10)->default('EGP');
            $table->string('preview_url')->nullable();
            $table->string('thumbnail')->nullable();
            $table->json('features')->nullable();
            $table->json('compatibility')->nullable();
            $table->decimal('rating', 3, 2)->default(5.00);
            $table->unsignedInteger('reviews_count')->default(0);
            $table->json('reviews')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['type', 'is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_marketplaces');
    }
};
