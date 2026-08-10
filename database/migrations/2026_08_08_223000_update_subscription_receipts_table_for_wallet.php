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
        Schema::table('subscription_receipts', function (Blueprint $table) {
            if (!Schema::hasColumn('subscription_receipts', 'type')) {
                $table->string('type')->default('subscription')->after('tenant_id'); // subscription, wallet
            }
            $table->unsignedBigInteger('plan_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_receipts', function (Blueprint $table) {
            if (Schema::hasColumn('subscription_receipts', 'type')) {
                $table->dropColumn('type');
            }
            $table->unsignedBigInteger('plan_id')->nullable(false)->change();
        });
    }
};
