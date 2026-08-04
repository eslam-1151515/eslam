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
        $tables = [
            'products',
            'categories',
            'orders',
            'banners',
            'shipping_governorates'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                // Add tenant_id column after id
                $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
                
                // Define the foreign key constraint
                $table->foreign('tenant_id')
                      ->references('id')
                      ->on('tenants')
                      ->onDelete('cascade');
            });
        }

        // Special handling for settings table to change unique constraint
        Schema::table('settings', function (Blueprint $table) {
            $table->dropUnique(['key']);
            
            $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
            $table->foreign('tenant_id')
                  ->references('id')
                  ->on('tenants')
                  ->onDelete('cascade');
                  
            $table->unique(['tenant_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Special handling for settings table
        Schema::table('settings', function (Blueprint $table) {
            $table->dropUnique(['tenant_id', 'key']);
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
            $table->unique(['key']);
        });

        $tables = [
            'shipping_governorates',
            'banners',
            'orders',
            'categories',
            'products'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                // Drop foreign key first
                $table->dropForeign(['tenant_id']);
                // Drop column
                $table->dropColumn('tenant_id');
            });
        }
    }
};
