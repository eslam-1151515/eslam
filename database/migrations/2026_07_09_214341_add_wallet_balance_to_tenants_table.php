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
        Schema::table('tenants', function (Blueprint $table) {
            $table->decimal('wallet_balance', 10, 2)->default(0)->after('is_active');
        });

        Schema::table('subscription_receipts', function (Blueprint $table) {
            $table->unsignedBigInteger('plan_id')->nullable()->change();
            $table->string('type')->default('subscription')->after('id'); // subscription or wallet
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_receipts', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn('wallet_balance');
        });
    }
};
