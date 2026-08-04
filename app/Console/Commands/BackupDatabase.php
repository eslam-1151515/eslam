<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database {--tenant=all : معرّف المتجر أو all لكل المتاجر}';
    protected $description = 'نسخ احتياطي من قاعدة البيانات';

    public function handle(): void
    {
        $this->info('بدء النسخ الاحتياطي لقاعدة البيانات...');

        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $filename  = "backup_db_{$timestamp}.sql";
        $backupPath = "backups/database/{$filename}";

        // استخدام mysqldump
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');
        $dbHost = config('database.connections.mysql.host');

        $command = "mysqldump --host={$dbHost} --user={$dbUser} --password={$dbPass} {$dbName}";

        // في بيئة XAMPP
        $xamppMysqlDump = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';
        if (PHP_OS_FAMILY === 'Windows' && file_exists($xamppMysqlDump)) {
            $command = "\"{$xamppMysqlDump}\" --host={$dbHost} --user={$dbUser}";
            if ($dbPass) {
                $command .= " --password={$dbPass}";
            }
            $command .= " {$dbName}";
        }

        $output     = [];
        $returnCode = 0;
        exec($command, $output, $returnCode);

        if ($returnCode === 0 && !empty($output)) {
            Storage::put($backupPath, implode("\n", $output));
            $this->info("✅ تم حفظ النسخة الاحتياطية في: {$backupPath}");
        } else {
            // حفظ backup بسيط بدون mysqldump
            $this->createSimpleBackup($backupPath, $timestamp);
        }

        // حذف النسخ القديمة (أكبر من 30 يوم)
        $this->cleanupOldBackups();
    }

    private function createSimpleBackup(string $path, string $timestamp): void
    {
        $content  = "-- FastOrder Database Backup\n";
        $content .= "-- Generated at: {$timestamp}\n";
        $content .= "-- Database: " . config('database.connections.mysql.database') . "\n\n";
        $content .= "-- Note: Full mysqldump not available. This is a metadata backup.\n\n";

        $tables = DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            $tableName = array_values((array) $table)[0];
            $count     = DB::table($tableName)->count();
            $content  .= "-- Table: {$tableName} ({$count} records)\n";
        }

        Storage::put($path . '.meta', $content);
        Log::info("[Backup] Metadata backup created at {$path}.meta");
        $this->info('✅ تم حفظ نسخة احتياطية من metadata');
    }

    private function cleanupOldBackups(): void
    {
        $files   = Storage::files('backups/database');
        $cutoff  = now()->subDays(30)->timestamp;
        $deleted = 0;

        foreach ($files as $file) {
            if (Storage::lastModified($file) < $cutoff) {
                Storage::delete($file);
                $deleted++;
            }
        }

        if ($deleted > 0) {
            $this->info("🗑️ تم حذف {$deleted} نسخة احتياطية قديمة.");
        }
    }
}
