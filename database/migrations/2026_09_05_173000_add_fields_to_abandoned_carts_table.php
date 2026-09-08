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
        Schema::table('abandoned_carts', function (Blueprint $table) {
            if (!Schema::hasColumn('abandoned_carts', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('abandoned_carts', 'governorate')) {
                $table->string('governorate')->nullable()->after('customer_name');
            }
            if (!Schema::hasColumn('abandoned_carts', 'customer_address')) {
                $table->text('customer_address')->nullable()->after('governorate');
            }
            if (!Schema::hasColumn('abandoned_carts', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->default(0)->after('cart_data');
            }
            if (!Schema::hasColumn('abandoned_carts', 'total')) {
                $table->decimal('total', 10, 2)->default(0)->after('subtotal');
            }
            if (!Schema::hasColumn('abandoned_carts', 'status')) {
                $table->string('status', 30)->default('abandoned')->after('total');
            }
            if (!Schema::hasColumn('abandoned_carts', 'converted_order_id')) {
                $table->unsignedBigInteger('converted_order_id')->nullable()->after('status');
                $table->foreign('converted_order_id')
                      ->references('id')
                      ->on('orders')
                      ->nullOnDelete();
            }
            if (!Schema::hasColumn('abandoned_carts', 'last_contacted_at')) {
                $table->timestamp('last_contacted_at')->nullable()->after('converted_order_id');
            }
            if (!Schema::hasColumn('abandoned_carts', 'notes')) {
                $table->text('notes')->nullable()->after('last_contacted_at');
            }

            // Indexes
            try {
                $table->index(['tenant_id', 'status']);
                $table->index(['tenant_id', 'phone']);
            } catch (\Throwable $e) {}
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('abandoned_carts', function (Blueprint $table) {
            try {
                $table->dropForeign(['converted_order_id']);
                $table->dropIndex(['tenant_id', 'status']);
                $table->dropIndex(['tenant_id', 'phone']);
            } catch (\Throwable $e) {}

            $cols = [
                'customer_name',
                'governorate',
                'customer_address',
                'subtotal',
                'total',
                'status',
                'converted_order_id',
                'last_contacted_at',
                'notes',
            ];
            foreach ($cols as $c) {
                if (Schema::hasColumn('abandoned_carts', $c)) {
                    $table->dropColumn($c);
                }
            }
        });
    }
};
