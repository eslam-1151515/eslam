<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Jobs\QueueHeartbeatJob;
use App\Mail\SystemHealthFailedMail;
use Carbon\Carbon;

class MonitorHealth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:health';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'فحص حالة النظام (سعة القرص، قاعدة البيانات، والـ queue worker) وإرسال تنبيهات في حال حدوث مشاكل';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('بدء فحص صحة النظام للمنصة...');

        $checks = [
            'سعة القرص (Disk Space)' => [
                'ok' => true,
                'message' => 'Disk space is OK.',
            ],
            'قاعدة البيانات (Database Connection)' => [
                'ok' => true,
                'message' => 'Database is connected.',
            ],
            'منفذ الطوابير (Queue Worker)' => [
                'ok' => true,
                'message' => 'Queue worker is active.',
            ],
        ];

        // 1. فحص سعة القرص
        try {
            $diskPath = base_path();
            $free = disk_free_space($diskPath);
            $total = disk_total_space($diskPath);
            
            if ($free !== false && $total !== false && $total > 0) {
                $used = $total - $free;
                $usedPercent = ($used / $total) * 100;
                $freeGB = round($free / (1024 * 1024 * 1024), 2);
                $totalGB = round($total / (1024 * 1024 * 1024), 2);
                
                $checks['سعة القرص (Disk Space)']['message'] = sprintf(
                    'مستغل: %d%% (%s جيجابايت فارغ من إجمالي %s جيجابايت)',
                    round($usedPercent),
                    $freeGB,
                    $totalGB
                );
                
                if ($usedPercent >= 90) {
                    $checks['سعة القرص (Disk Space)']['ok'] = false;
                }
            } else {
                $checks['سعة القرص (Disk Space)']['message'] = 'عاجز عن قراءة مساحة القرص بشكل صحيح.';
            }
        } catch (\Exception $e) {
            $checks['سعة القرص (Disk Space)']['ok'] = false;
            $checks['سعة القرص (Disk Space)']['message'] = 'فشل فحص القرص: ' . $e->getMessage();
        }

        // 2. فحص قاعدة البيانات
        try {
            DB::connection()->getPdo();
            DB::select('SELECT 1');
            $checks['قاعدة البيانات (Database Connection)']['message'] = 'الاتصال بقاعدة البيانات سليم ونشط.';
        } catch (\Exception $e) {
            $checks['قاعدة البيانات (Database Connection)']['ok'] = false;
            $checks['قاعدة البيانات (Database Connection)']['message'] = 'فشل الاتصال بقاعدة البيانات: ' . $e->getMessage();
        }

        // 3. فحص منفذ الطوابير (Queue Worker)
        try {
            $lastHeartbeat = Cache::get('queue_last_heartbeat');
            if (!$lastHeartbeat) {
                $checks['منفذ الطوابير (Queue Worker)']['ok'] = false;
                $checks['منفذ الطوابير (Queue Worker)']['message'] = 'لم يتم العثور على أي نبض طوابير (Heartbeat). قد يكون منفذ الطوابير متوقفاً أو تم مسح الكاش.';
            } else {
                $lastHeartbeatTime = Carbon::parse($lastHeartbeat);
                $diffInMinutes = now()->diffInMinutes($lastHeartbeatTime);
                
                $checks['منفذ الطوابير (Queue Worker)']['message'] = sprintf(
                    'آخر نبض طوابير كان في %s (قبل %d دقيقة).',
                    $lastHeartbeatTime->toDateTimeString(),
                    $diffInMinutes
                );
                
                if ($diffInMinutes > 15) {
                    $checks['منفذ الطوابير (Queue Worker)']['ok'] = false;
                    $checks['منفذ الطوابير (Queue Worker)']['message'] = sprintf(
                        'منفذ الطوابير متأخر أو متوقف. آخر معالجة كانت قبل %d دقيقة.',
                        $diffInMinutes
                    );
                }
            }
            
            // إرسال نبض جديد للتجربة القادمة
            QueueHeartbeatJob::dispatch();
        } catch (\Exception $e) {
            $checks['منفذ الطوابير (Queue Worker)']['ok'] = false;
            $checks['منفذ الطوابير (Queue Worker)']['message'] = 'فشل فحص الطوابير: ' . $e->getMessage();
        }

        // طباعة النتائج في الـ Console
        $hasFailure = false;
        foreach ($checks as $name => $result) {
            if ($result['ok']) {
                $this->info("✔ {$name}: {$result['message']}");
            } else {
                $this->error("✖ {$name}: {$result['message']}");
                $hasFailure = true;
            }
        }

        // إذا وجد أي فشل، أرسل بريداً إلكترونياً وسجل الخطأ في سجل فاست أوردر المخصص
        if ($hasFailure) {
            Log::channel('fastorder-errors')->error('فشل فحص صحة النظام للمنصة!', $checks);
            
            try {
                $adminEmail = env('ADMIN_EMAIL', config('mail.from.address') ?? 'admin@fastorder.com');
                Mail::to($adminEmail)->send(new SystemHealthFailedMail($checks));
                $this->info("تم إرسال بريد تنبيه إداري إلى: {$adminEmail}");
            } catch (\Exception $e) {
                $this->error("فشل إرسال البريد الإلكتروني للتنبيه: " . $e->getMessage());
                Log::channel('fastorder-errors')->error("فشل إرسال بريد تنبيه إداري لصحة النظام: " . $e->getMessage());
            }
        } else {
            $this->info('فحص صحة النظام اكتمل بنجاح دون مشاكل.');
        }
    }
}
