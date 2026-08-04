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
        Schema::table('landing_pages', function (Blueprint $table) {
            $table->string('template')->default('classic')->after('slug');
            $table->text('custom_css')->nullable()->after('sections');
            $table->string('seo_title')->nullable()->after('custom_css');
            $table->text('seo_description')->nullable()->after('seo_title');
            $table->string('featured_image')->nullable()->after('seo_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('landing_pages', function (Blueprint $table) {
            $table->dropColumn(['template', 'custom_css', 'seo_title', 'seo_description', 'featured_image']);
        });
    }
};
