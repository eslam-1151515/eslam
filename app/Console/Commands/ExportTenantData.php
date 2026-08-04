<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExportTenantData extends Command
{
    protected $signature = 'tenant:export {tenant_id : معرّف المتجر}';
    protected $description = 'تصدير كل بيانات متجر معين';

    public function handle(): void
    {
        $tenantId = (int) $this->argument('tenant_id');
        $this->info("تصدير بيانات المتجر #{$tenantId}...");

        $timestamp  = Carbon::now()->format('Y-m-d_H-i-s');
        $exportData = [
            'exported_at' => now()->toISOString(),
            'tenant_id'   => $tenantId,
            'data'        => [],
        ];

        // تصدير الجداول التي تحتوي على tenant_id
        $tables = ['products', 'categories', 'orders', 'coupons'];
        foreach ($tables as $table) {
            try {
                if (
                    DB::getSchemaBuilder()->hasTable($table) &&
                    DB::getSchemaBuilder()->hasColumn($table, 'tenant_id')
                ) {
                    $rows = DB::table($table)
                        ->where('tenant_id', $tenantId)
                        ->get()
                        ->toArray();

                    $exportData['data'][$table] = $rows;
                    $this->info("  ✓ {$table}: " . count($rows) . ' سجل');
                }
            } catch (\Exception $e) {
                $this->warn("  ⚠ لم يمكن تصدير {$table}: " . $e->getMessage());
            }
        }

        $path = "exports/tenant_{$tenantId}_{$timestamp}.json";
        Storage::put($path, json_encode($exportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $this->info("✅ تم التصدير في: {$path}");
    }
}
