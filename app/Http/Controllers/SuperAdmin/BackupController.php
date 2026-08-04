<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BackupController extends Controller
{
    public function index()
    {
        $backups = [];

        try {
            $files = Storage::files('backups/database');
            foreach ($files as $file) {
                $backups[] = [
                    'name'       => basename($file),
                    'path'       => $file,
                    'size'       => $this->formatBytes(Storage::size($file)),
                    'created_at' => date('Y-m-d H:i', Storage::lastModified($file)),
                ];
            }
            // ترتيب تنازلي حسب التاريخ
            usort($backups, fn ($a, $b) => strcmp($b['created_at'], $a['created_at']));
        } catch (\Exception $e) {
            // مجلد backups غير موجود بعد — لا شيء
        }

        return Inertia::render('SuperAdmin/Backups/Index', [
            'backups' => $backups,
        ]);
    }

    public function create()
    {
        Artisan::call('backup:database');
        return back()->with('success', 'تم إنشاء النسخة الاحتياطية بنجاح.');
    }

    public function download(Request $request)
    {
        $file = $request->input('file');

        if (!$file || !Storage::exists($file)) {
            abort(404, 'الملف غير موجود');
        }

        return Storage::download($file);
    }

    public function destroy(Request $request)
    {
        $file = $request->input('file');

        if ($file && Storage::exists($file)) {
            Storage::delete($file);
        }

        return back()->with('success', 'تم حذف النسخة الاحتياطية.');
    }

    // ─────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1_073_741_824) {
            return round($bytes / 1_073_741_824, 2) . ' GB';
        }
        if ($bytes >= 1_048_576) {
            return round($bytes / 1_048_576, 2) . ' MB';
        }
        if ($bytes >= 1_024) {
            return round($bytes / 1_024, 2) . ' KB';
        }
        return $bytes . ' B';
    }
}
