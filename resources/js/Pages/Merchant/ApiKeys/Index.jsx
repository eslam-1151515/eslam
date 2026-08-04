import { useState } from 'react';
import { useForm, router, usePage } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';
import { Head } from '@inertiajs/react';

export default function ApiKeysIndex({ apiKeys }) {
    const { flash } = usePage().props;
    const [showNewKey, setShowNewKey] = useState(flash?.new_key || null);
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('merchant.api-keys.store'), {
            onSuccess: (page) => {
                reset('name');
                if (page.props.flash?.new_key) {
                    setShowNewKey(page.props.flash.new_key);
                }
            },
        });
    };

    const handleRevoke = (id) => {
        if (confirm('هل أنت متأكد من إلغاء هذا المفتاح؟ لن يمكن التراجع عن هذا.')) {
            router.delete(route('merchant.api-keys.destroy', id));
        }
    };

    const copyToClipboard = (text) => {
        navigator.clipboard.writeText(text).then(() => {
            alert('✅ تم نسخ المفتاح بنجاح!');
        });
    };

    return (
        <MerchantLayout>
            <Head title="مفاتيح API" />
            <div className="max-w-5xl mx-auto px-4 py-8">
                <div className="mb-8">
                    <h1 className="text-3xl font-bold text-gray-900 dark:text-white">مفاتيح API</h1>
                    <p className="text-gray-500 mt-1">أدر مفاتيح API الخاصة بمتجرك للتكاملات الخارجية</p>
                </div>

                {/* مفتاح جديد تم إنشاؤه */}
                {showNewKey && (
                    <div className="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                        <p className="text-green-700 font-semibold mb-2">
                            ✅ تم إنشاء مفتاح API بنجاح! انسخه الآن - لن يظهر مرة أخرى:
                        </p>
                        <div className="flex items-center gap-2">
                            <code className="flex-1 bg-white border rounded px-3 py-2 text-sm font-mono text-gray-800 break-all">
                                {showNewKey}
                            </code>
                            <button
                                onClick={() => copyToClipboard(showNewKey)}
                                className="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium transition-colors"
                            >
                                نسخ
                            </button>
                        </div>
                        <button
                            onClick={() => setShowNewKey(null)}
                            className="mt-2 text-sm text-green-600 underline"
                        >
                            إغلاق
                        </button>
                    </div>
                )}

                {/* فورم إنشاء مفتاح */}
                <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-8">
                    <h2 className="text-lg font-semibold mb-4 text-gray-900 dark:text-white">إنشاء مفتاح جديد</h2>
                    <form onSubmit={handleSubmit} className="flex gap-3">
                        <div className="flex-1">
                            <input
                                type="text"
                                placeholder="اسم المفتاح (مثال: تكامل Zapier)"
                                value={data.name}
                                onChange={(e) => setData('name', e.target.value)}
                                className="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            />
                            {errors.name && (
                                <p className="text-red-500 text-sm mt-1">{errors.name}</p>
                            )}
                        </div>
                        <button
                            type="submit"
                            disabled={processing}
                            className="px-6 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 disabled:opacity-50 font-medium transition-colors"
                        >
                            {processing ? 'جاري الإنشاء...' : 'إنشاء مفتاح'}
                        </button>
                    </form>
                </div>

                {/* قائمة المفاتيح */}
                <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <div className="p-6 border-b border-gray-100 dark:border-gray-700">
                        <h2 className="text-lg font-semibold text-gray-900 dark:text-white">
                            المفاتيح الحالية ({apiKeys.length})
                        </h2>
                    </div>
                    {apiKeys.length === 0 ? (
                        <div className="p-12 text-center text-gray-400">
                            <div className="text-5xl mb-3">🔑</div>
                            <p>لا توجد مفاتيح API حتى الآن</p>
                        </div>
                    ) : (
                        <div className="divide-y divide-gray-100 dark:divide-gray-700">
                            {apiKeys.map((key) => (
                                <div
                                    key={key.id}
                                    className="p-6 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors"
                                >
                                    <div className="flex-1">
                                        <div className="flex items-center gap-3 mb-1">
                                            <span className="font-semibold text-gray-900 dark:text-white">
                                                {key.name}
                                            </span>
                                            <span
                                                className={`px-2 py-0.5 text-xs rounded-full font-medium ${
                                                    key.is_active
                                                        ? 'bg-green-100 text-green-700'
                                                        : 'bg-red-100 text-red-700'
                                                }`}
                                            >
                                                {key.is_active ? 'نشط' : 'ملغى'}
                                            </span>
                                        </div>
                                        <p className="text-sm text-gray-500 font-mono">{key.key_preview}</p>
                                        <p className="text-xs text-gray-400 mt-1">
                                            أُنشئ في {key.created_at}
                                            {key.last_used_at && ` · آخر استخدام ${key.last_used_at}`}
                                        </p>
                                    </div>
                                    {key.is_active && (
                                        <button
                                            onClick={() => handleRevoke(key.id)}
                                            className="px-4 py-2 text-red-600 border border-red-200 rounded-lg hover:bg-red-50 text-sm font-medium transition-colors"
                                        >
                                            إلغاء
                                        </button>
                                    )}
                                </div>
                            ))}
                        </div>
                    )}
                </div>

                {/* دليل الاستخدام */}
                <div className="mt-8 bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-6">
                    <h3 className="font-semibold text-blue-900 dark:text-blue-100 mb-3">
                        📖 كيفية استخدام مفتاح API
                    </h3>
                    <p className="text-sm text-blue-700 dark:text-blue-200 mb-3">
                        أضف المفتاح في الـ Authorization header لكل طلب:
                    </p>
                    <code className="block bg-blue-900 text-blue-100 rounded-lg px-4 py-3 text-sm font-mono">
                        Authorization: Bearer YOUR_API_KEY
                    </code>
                    <div className="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div className="bg-white dark:bg-blue-900/40 rounded-lg p-3">
                            <p className="text-xs font-semibold text-blue-800 dark:text-blue-200 mb-1">المنتجات</p>
                            <code className="text-xs text-gray-600 dark:text-gray-300">GET /api/v1/products</code>
                        </div>
                        <div className="bg-white dark:bg-blue-900/40 rounded-lg p-3">
                            <p className="text-xs font-semibold text-blue-800 dark:text-blue-200 mb-1">الطلبات</p>
                            <code className="text-xs text-gray-600 dark:text-gray-300">GET /api/v1/orders</code>
                        </div>
                        <div className="bg-white dark:bg-blue-900/40 rounded-lg p-3">
                            <p className="text-xs font-semibold text-blue-800 dark:text-blue-200 mb-1">الأقسام</p>
                            <code className="text-xs text-gray-600 dark:text-gray-300">GET /api/v1/categories</code>
                        </div>
                        <div className="bg-white dark:bg-blue-900/40 rounded-lg p-3">
                            <p className="text-xs font-semibold text-blue-800 dark:text-blue-200 mb-1">العملاء</p>
                            <code className="text-xs text-gray-600 dark:text-gray-300">GET /api/v1/customers</code>
                        </div>
                    </div>
                </div>
            </div>
        </MerchantLayout>
    );
}
