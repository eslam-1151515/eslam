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
            if (!Schema::hasColumn('orders', 'is_unlocked')) {
                $table->boolean('is_unlocked')->default(false)->after('status');
                $table->timestamp('unlocked_at')->nullable()->after('is_unlocked');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'is_unlocked')) {
                $table->dropColumn(['is_unlocked', 'unlocked_at']);
            }
        });
    }
};
