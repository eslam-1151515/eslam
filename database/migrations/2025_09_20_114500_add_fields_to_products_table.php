<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'category_id')) {
                // SQLite doesn't support adding FK constraints easily on alter; keep as indexed column
                $table->unsignedBigInteger('category_id')->nullable()->after('id');
                $table->index('category_id');
            }
            if (!Schema::hasColumn('products', 'name')) {
                $table->string('name')->default('')->after('category_id');
            }
            if (!Schema::hasColumn('products', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('products', 'price')) {
                $table->decimal('price', 10, 2)->default(0)->after('description');
            }
            if (!Schema::hasColumn('products', 'stock')) {
                $table->integer('stock')->default(0)->after('price');
            }
            if (!Schema::hasColumn('products', 'image_url')) {
                $table->string('image_url')->nullable()->after('stock');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'image_url')) $table->dropColumn('image_url');
            if (Schema::hasColumn('products', 'stock')) $table->dropColumn('stock');
            if (Schema::hasColumn('products', 'price')) $table->dropColumn('price');
            if (Schema::hasColumn('products', 'description')) $table->dropColumn('description');
            if (Schema::hasColumn('products', 'name')) $table->dropColumn('name');
            if (Schema::hasColumn('products', 'category_id')) {
                $table->dropIndex(['category_id']);
                $table->dropColumn('category_id');
            }
        });
    }
};
