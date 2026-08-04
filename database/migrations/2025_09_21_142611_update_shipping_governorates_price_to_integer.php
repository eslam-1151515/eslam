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
        Schema::table('shipping_governorates', function (Blueprint $table) {
            $table->integer('price')->default(0)->change(); // تغيير من decimal إلى integer
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_governorates', function (Blueprint $table) {
            $table->decimal('price', 8, 2)->default(0)->change(); // العودة إلى decimal
        });
    }
};
