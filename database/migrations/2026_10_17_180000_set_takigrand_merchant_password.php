<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Tenant;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Search for user by email (case-insensitive)
        $user = User::whereRaw('LOWER(email) = ?', [strtolower('Radwanbisheer73@gmail.com')])->first();

        // 2. Fallback: Search by tenant slug takigrand owner
        if (!$user) {
            $tenant = Tenant::where('slug', 'takigrand')->first();
            if ($tenant && $tenant->owner_id) {
                $user = User::find($tenant->owner_id);
            }
        }

        if ($user) {
            $user->password = Hash::make('Radooreed123');
            $user->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
