import SuperAdminLayout from '@/Layouts/SuperAdminLayout';
import { Head, router, usePage } from '@inertiajs/react';
import { useState } from 'react';

export default function BackupsIndex({ backups }) {
    const { flash } = usePage().props;
    const [loading, setLoading] = useState(false);

    const handleCreate = () => {
        if (!confirm('هل تريد إنشاء نسخة احتياطية الآن؟')) return;
        setLoading(true);
        router.post(route('superadmin.backups.create'), {}, {
            onFinish: () => setLoading(false),
        });
    };

    const handleDelete = (file) => {
        if (!confirm('هل تريد حذف هذه النسخة الاحتياطية؟')) return;
        router.delete(route('superadmin.backups.destroy'), { data: { file } });
    };

    return (
        <SuperAdminLayout>
            <Head title="النسخ الاحتياطية" />

            <div className="max-w-5xl mx-auto px-4 py-8">

                {/* ── Header ── */}
                <div className="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
                    <div>
                        <h1 className="text-3xl font-bold text-gray-900 dark:text-white">النسخ الاحتياطية</h1>
                        <p className="text-gray-500 dark:text-gray-400 mt-1">
                            إدارة النسخ الاحتياطية لقاعدة البيانات
                        </p>
                    </div>
                    <button
                        onClick={handleCreate}
                        disabled={loading}
                        className="flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white rounded-xl font-medium transition-colors shadow-sm"
                    >
                        {loading ? (
                            <>
                                <svg className="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none">
                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                </svg>
                                جارٍ الإنشاء…
                            </>
                        ) : (
                            <>💾 إنشاء نسخة احتياطية</>
                        )}
                    </button>
                </div>

                {/* ── Flash message ── */}
                {flash?.success && (
                    <div className="mb-6 px-4 py-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-400 rounded-xl text-sm">
                        ✅ {flash.success}
                    </div>
                )}

                {/* ── Stats bar ── */}
                <div className="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6">
                    <StatCard label="إجمالي النسخ" value={backups.length} icon="📦" />
                    <StatCard label="آخر نسخة" value={backups[0]?.created_at ?? '—'} icon="🕐" />
                    <StatCard label="أحدث حجم" value={backups[0]?.size ?? '—'} icon="📊" />
                </div>

                {/* ── Backups list ── */}
                <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    {backups.length === 0 ? (
                        <div className="p-16 text-center text-gray-400 dark:text-gray-500">
                            <div className="text-6xl mb-4">💾</div>
                            <p className="text-lg font-medium">لا توجد نسخ احتياطية بعد</p>
                            <p className="text-sm mt-1">اضغط على "إنشاء نسخة احتياطية" للبدء</p>
                        </div>
                    ) : (
                        <div className="divide-y divide-gray-100 dark:divide-gray-700">
                            {backups.map((backup) => (
                                <div
                                    key={backup.name}
                                    className="p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors"
                                >
                                    <div className="flex items-center gap-3">
                                        <span className="text-2xl">🗄️</span>
                                        <div>
                                            <p className="font-medium text-gray-900 dark:text-white text-sm">
                                                {backup.name}
                                            </p>
                                            <p className="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                {backup.created_at} &middot; {backup.size}
                                            </p>
                                        </div>
                                    </div>

                                    <div className="flex gap-2 self-end sm:self-auto">
                                        <a
                                            href={route('superadmin.backups.download', { file: backup.path })}
                                            className="inline-flex items-center gap-1 px-4 py-2 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 rounded-lg text-xs font-medium hover:bg-emerald-100 dark:hover:bg-emerald-900/40 transition-colors border border-emerald-200 dark:border-emerald-700"
                                        >
                                            ⬇ تحميل
                                        </a>
                                        <button
                                            onClick={() => handleDelete(backup.path)}
                                            className="inline-flex items-center gap-1 px-4 py-2 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-lg text-xs font-medium hover:bg-red-100 dark:hover:bg-red-900/40 transition-colors border border-red-200 dark:border-red-700"
                                        >
                                            🗑 حذف
                                        </button>
                                    </div>
                                </div>
                            ))}
                        </div>
                    )}
                </div>

                {/* ── Info note ── */}
                <p className="mt-4 text-xs text-gray-400 dark:text-gray-500 text-center">
                    يتم حذف النسخ الاحتياطية تلقائياً بعد 30 يوماً · النسخ اليومية تتم تلقائياً في 2:00 صباحاً
                </p>
            </div>
        </SuperAdminLayout>
    );
}

// ── Sub-component ─────────────────────────────────────────────────────────────
function StatCard({ label, value, icon }) {
    return (
        <div className="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 px-4 py-3 shadow-sm flex items-center gap-3">
            <span className="text-2xl">{icon}</span>
            <div>
                <p className="text-xs text-gray-500 dark:text-gray-400">{label}</p>
                <p className="text-sm font-semibold text-gray-800 dark:text-white">{value}</p>
            </div>
        </div>
    );
}
