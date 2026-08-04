<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'name_ar')) {
                $table->string('name_ar')->nullable()->after('name');
            }
            if (!Schema::hasColumn('categories', 'name_en')) {
                $table->string('name_en')->nullable()->after('name_ar');
            }
            if (!Schema::hasColumn('categories', 'parent_id')) {
                $table->unsignedBigInteger('parent_id')->nullable()->index()->after('image_path');
            }
        });

        // Backfill name_ar from existing name if empty
        try {
            DB::statement('UPDATE categories SET name_ar = COALESCE(name_ar, name)');
        } catch (\Throwable $e) {
            // Fallback for SQLite older versions without COALESCE issues
            $rows = DB::table('categories')->get(['id', 'name_ar', 'name']);
            foreach ($rows as $row) {
                if (empty($row->name_ar) && !empty($row->name)) {
                    DB::table('categories')->where('id', $row->id)->update(['name_ar' => $row->name]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'parent_id')) {
                $table->dropColumn('parent_id');
            }
            if (Schema::hasColumn('categories', 'name_en')) {
                $table->dropColumn('name_en');
            }
            if (Schema::hasColumn('categories', 'name_ar')) {
                $table->dropColumn('name_ar');
            }
        });
    }
};
