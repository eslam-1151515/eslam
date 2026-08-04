import React, { useState, useRef } from 'react';
import { Head, useForm, usePage } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

export default function ImportExportIndex() {
    const { flash } = usePage().props;
    const [activeTab, setActiveTab] = useState('export'); // 'export' | 'import'
    const [importSource, setImportSource] = useState('fastorder'); // 'fastorder' | 'shopify' | 'woocommerce'
    const [dragActive, setDragActive] = useState(false);
    const [selectedFile, setSelectedFile] = useState(null);
    const [uploadProgress, setUploadProgress] = useState(0);
    const [isSimulatingProgress, setIsSimulatingProgress] = useState(false);
    const fileInputRef = useRef(null);

    const { data, setData, post, processing, errors, reset } = useForm({
        file: null,
        source: 'fastorder'
    });

    // Handle Drag & Drop
    const handleDrag = (e) => {
        e.preventDefault();
        e.stopPropagation();
        if (e.type === "dragenter" || e.type === "dragover") {
            setDragActive(true);
        } else if (e.type === "dragleave") {
            setDragActive(false);
        }
    };

    const handleDrop = (e) => {
        e.preventDefault();
        e.stopPropagation();
        setDragActive(false);

        if (e.dataTransfer.files && e.dataTransfer.files[0]) {
            const file = e.dataTransfer.files[0];
            if (file.type === "text/csv" || file.name.endsWith('.csv')) {
                setSelectedFile(file);
                setData('file', file);
            } else {
                alert('عذراً، يجب رفع ملف بصيغة CSV فقط.');
            }
        }
    };

    const handleFileChange = (e) => {
        if (e.target.files && e.target.files[0]) {
            const file = e.target.files[0];
            setSelectedFile(file);
            setData('file', file);
        }
    };

    const triggerFileInput = () => {
        fileInputRef.current.click();
    };

    const handleRemoveFile = () => {
        setSelectedFile(null);
        setData('file', null);
        setUploadProgress(0);
        reset('file');
    };

    // Simulated Progress (since Import is fast but feels premium with progress)
    const startProgressSimulation = (callback) => {
        setIsSimulatingProgress(true);
        setUploadProgress(0);
        const interval = setInterval(() => {
            setUploadProgress((oldProgress) => {
                if (oldProgress === 100) {
                    clearInterval(interval);
                    setIsSimulatingProgress(false);
                    callback();
                    return 100;
                }
                const diff = Math.random() * 30;
                return Math.min(oldProgress + diff, 95); // hold at 95 until complete
            });
        }, 150);
        return interval;
    };

    const handleImportSubmit = (e) => {
        e.preventDefault();
        if (!data.file) return;

        setData('source', importSource);

        const interval = startProgressSimulation(() => {
            post('/admin/import-export', {
                preserveScroll: true,
                onSuccess: () => {
                    setUploadProgress(100);
                    setSelectedFile(null);
                    reset('file');
                },
                onError: () => {
                    setUploadProgress(0);
                    setIsSimulatingProgress(false);
                }
            });
        });
    };

    const handleExport = (type) => {
        window.location.href = `/admin/import-export/export?type=${type}`;
    };

    return (
        <MerchantLayout title="نظام الاستيراد والتصدير الشامل">
            <Head title="الاستيراد والتصدير الشامل" />

            <div className="space-y-6 text-right rtl" dir="rtl">
                {/* Heading */}
                <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 className="text-2xl font-extrabold text-gray-900">نظام الاستيراد والتصدير الشامل</h2>
                        <p className="text-sm text-gray-500 mt-1">
                            انقل بياناتك بسهولة وسرعة من وإلى المنصة مع دعم كامل لملفات Shopify و WooCommerce.
                        </p>
                    </div>
                </div>

                {/* Tabs Selector */}
                <div className="flex border-b border-gray-200">
                    <button
                        onClick={() => setActiveTab('export')}
                        className={`py-4 px-6 font-bold text-sm border-b-2 transition-all ${
                            activeTab === 'export'
                                ? 'border-indigo-600 text-indigo-600'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                        }`}
                    >
                        📤 تصدير البيانات (Export)
                    </button>
                    <button
                        onClick={() => setActiveTab('import')}
                        className={`py-4 px-6 font-bold text-sm border-b-2 transition-all ${
                            activeTab === 'import'
                                ? 'border-indigo-600 text-indigo-600'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                        }`}
                    >
                        📥 استيراد المنتجات (Import)
                    </button>
                </div>

                {/* Notifications & Flash Results */}
                {flash?.success && (
                    <div className="p-4 bg-green-50 border-r-4 border-green-500 rounded-xl text-green-800 text-sm font-semibold flex items-center gap-3 shadow-sm">
                        <span className="flex items-center justify-center w-6 h-6 bg-green-100 rounded-full text-green-600 text-xs">✓</span>
                        <div>
                            <p>{flash.success}</p>
                            {flash.import_result && (
                                <p className="text-xs text-green-700 mt-1">
                                    تم استيراد {flash.import_result.imported_count} منتج بنجاح، وتخطي {flash.import_result.failed_count} منتج.
                                </p>
                            )}
                        </div>
                    </div>
                )}

                {flash?.error && (
                    <div className="p-4 bg-red-50 border-r-4 border-red-500 rounded-xl text-red-800 text-sm font-semibold flex items-center gap-3 shadow-sm">
                        <span className="flex items-center justify-center w-6 h-6 bg-red-100 rounded-full text-red-600 text-xs">⚠️</span>
                        {flash.error}
                    </div>
                )}

                {/* Import Failures / Errors Report */}
                {flash?.import_result?.errors && flash.import_result.errors.length > 0 && (
                    <div className="bg-amber-50 border border-amber-200 rounded-2xl p-5 mt-4">
                        <h4 className="text-sm font-bold text-amber-900 mb-2">تقرير الأخطاء أثناء الاستيراد (أول 50 خطأ):</h4>
                        <div className="max-h-48 overflow-y-auto space-y-1 text-xs text-amber-800 font-mono scrollbar-thin">
                            {flash.import_result.errors.map((err, idx) => (
                                <div key={idx} className="bg-amber-100/50 p-2 rounded border border-amber-100">
                                    {err}
                                </div>
                            ))}
                        </div>
                    </div>
                )}

                {/* Tab content: Export */}
                {activeTab === 'export' && (
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {/* Card 1: Products */}
                        <div className="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                            <div>
                                <div className="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 mb-4 text-2xl">
                                    📦
                                </div>
                                <h3 className="text-lg font-bold text-gray-900">تصدير المنتجات</h3>
                                <p className="text-xs text-gray-500 mt-2 leading-relaxed">
                                    تصدير كافة المنتجات الموجودة بالمخزن مع تصنيفاتها، أسعارها، كمياتها المتوفرة، والمقاسات والألوان المضافة كملف CSV متكامل.
                                </p>
                            </div>
                            <button
                                onClick={() => handleExport('products')}
                                className="w-full mt-6 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-4 rounded-xl text-sm transition-all shadow-sm"
                            >
                                تصدير المنتجات (CSV)
                            </button>
                        </div>

                        {/* Card 2: Customers */}
                        <div className="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                            <div>
                                <div className="w-12 h-12 bg-teal-50 rounded-xl flex items-center justify-center text-teal-600 mb-4 text-2xl">
                                    👥
                                </div>
                                <h3 className="text-lg font-bold text-gray-900">تصدير العملاء</h3>
                                <p className="text-xs text-gray-500 mt-2 leading-relaxed">
                                    تجميع وتصدير كافة بيانات العملاء المسجلين أو الذين قاموا بالشراء (الاسم، الهاتف، العنوان، المحافظة، إجمالي الطلبات والمشتريات).
                                </p>
                            </div>
                            <button
                                onClick={() => handleExport('customers')}
                                className="w-full mt-6 bg-teal-600 hover:bg-teal-700 text-white font-bold py-2.5 px-4 rounded-xl text-sm transition-all shadow-sm"
                            >
                                تصدير العملاء (CSV)
                            </button>
                        </div>

                        {/* Card 3: Orders */}
                        <div className="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                            <div>
                                <div className="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 mb-4 text-2xl">
                                    📋
                                </div>
                                <h3 className="text-lg font-bold text-gray-900">تصدير الطلبات</h3>
                                <p className="text-xs text-gray-500 mt-2 leading-relaxed">
                                    تصدير جميع الطلبات بحالاتها وتواريخها، مع سرد تفاصيل المنتجات المشتراة وتكلفة الشحن وطريقة الدفع في ملف CSV منظم.
                                </p>
                            </div>
                            <button
                                onClick={() => handleExport('orders')}
                                className="w-full mt-6 bg-amber-600 hover:bg-amber-700 text-white font-bold py-2.5 px-4 rounded-xl text-sm transition-all shadow-sm"
                            >
                                تصدير الطلبات (CSV)
                            </button>
                        </div>
                    </div>
                )}

                {/* Tab content: Import */}
                {activeTab === 'import' && (
                    <div className="bg-white border border-gray-100 rounded-2xl p-6 md:p-8 shadow-sm">
                        <form onSubmit={handleImportSubmit} className="space-y-6">
                            {/* Step 1: Select Platform Source */}
                            <div>
                                <label className="block text-sm font-bold text-gray-700 mb-3">
                                    1. اختر مصدر البيانات (المنصة المصدّرة للملف):
                                </label>
                                <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    {/* FastOrder */}
                                    <div
                                        onClick={() => setImportSource('fastorder')}
                                        className={`border-2 rounded-2xl p-4 cursor-pointer flex items-center gap-4 transition-all ${
                                            importSource === 'fastorder'
                                                ? 'border-indigo-600 bg-indigo-50/50'
                                                : 'border-gray-200 hover:border-gray-300'
                                        }`}
                                    >
                                        <div className="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-lg">
                                            🚀
                                        </div>
                                        <div>
                                            <h4 className="font-bold text-sm text-gray-900">قالب فاست أوردر</h4>
                                            <p className="text-xxs text-gray-400 mt-0.5">الملف الافتراضي المنسق للنظام</p>
                                        </div>
                                    </div>

                                    {/* Shopify */}
                                    <div
                                        onClick={() => setImportSource('shopify')}
                                        className={`border-2 rounded-2xl p-4 cursor-pointer flex items-center gap-4 transition-all ${
                                            importSource === 'shopify'
                                                ? 'border-green-600 bg-green-50/50'
                                                : 'border-gray-200 hover:border-gray-300'
                                        }`}
                                    >
                                        <div className="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center text-lg">
                                            🛍️
                                        </div>
                                        <div>
                                            <h4 className="font-bold text-sm text-gray-900">شوبيفاي (Shopify)</h4>
                                            <p className="text-xxs text-gray-400 mt-0.5">متوافق مع تصدير المنتجات القياسي</p>
                                        </div>
                                    </div>

                                    {/* WooCommerce */}
                                    <div
                                        onClick={() => setImportSource('woocommerce')}
                                        className={`border-2 rounded-2xl p-4 cursor-pointer flex items-center gap-4 transition-all ${
                                            importSource === 'woocommerce'
                                                ? 'border-purple-600 bg-purple-50/50'
                                                : 'border-gray-200 hover:border-gray-300'
                                        }`}
                                    >
                                        <div className="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center text-lg">
                                            🛒
                                        </div>
                                        <div>
                                            <h4 className="font-bold text-sm text-gray-900">وو كومرس (WooCommerce)</h4>
                                            <p className="text-xxs text-gray-400 mt-0.5">محاذاة تلقائية لأعمدة ووردبريس</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Help & Template Download */}
                            {importSource === 'fastorder' && (
                                <div className="bg-indigo-50/80 rounded-2xl p-4 flex flex-col md:flex-row md:items-center justify-between gap-3 text-sm text-indigo-900">
                                    <div className="flex items-center gap-2">
                                        <span>ℹ️</span>
                                        <span>لضمان عدم حدوث أخطاء، قم بتحميل النموذج الافتراضي، واملأ بياناتك ثم ارفعه هنا.</span>
                                    </div>
                                    <a
                                        href="/admin/import-export/template"
                                        className="inline-flex items-center justify-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-1.5 px-4 rounded-xl text-xs transition-all shadow-sm shrink-0"
                                    >
                                        📥 تحميل القالب الفارغ
                                    </a>
                                </div>
                            )}

                            {/* Step 2: Upload File Drag & Drop */}
                            <div>
                                <label className="block text-sm font-bold text-gray-700 mb-3">
                                    2. قم برفع أو سحب ملف الـ CSV هنا:
                                </label>
                                <div
                                    onDragEnter={handleDrag}
                                    onDragOver={handleDrag}
                                    onDragLeave={handleDrag}
                                    onDrop={handleDrop}
                                    onClick={triggerFileInput}
                                    className={`border-2 border-dashed rounded-3xl p-8 text-center cursor-pointer transition-all flex flex-col items-center justify-center ${
                                        dragActive
                                            ? 'border-indigo-600 bg-indigo-50/20 scale-[0.99]'
                                            : 'border-gray-300 hover:border-indigo-400 hover:bg-gray-50/50'
                                    }`}
                                >
                                    <input
                                        ref={fileInputRef}
                                        type="file"
                                        accept=".csv"
                                        onChange={handleFileChange}
                                        className="hidden"
                                    />
                                    <div className="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-400 mb-4 text-3xl shadow-sm border border-gray-100">
                                        📁
                                    </div>
                                    <p className="text-sm font-bold text-gray-700">اسحب الملف هنا أو انقر للتصفح</p>
                                    <p className="text-xs text-gray-400 mt-1">يدعم فقط ملفات CSV (بترميز UTF-8)، بحد أقصى 8 ميجابايت.</p>
                                </div>
                            </div>

                            {/* Selected File Details & Progress bar */}
                            {selectedFile && (
                                <div className="bg-gray-50 rounded-2xl p-4 border border-gray-100 space-y-3">
                                    <div className="flex items-center justify-between">
                                        <div className="flex items-center gap-3">
                                            <span className="text-xl">📄</span>
                                            <div>
                                                <p className="text-sm font-bold text-gray-800">{selectedFile.name}</p>
                                                <p className="text-xxs text-gray-400 mt-0.5">{(selectedFile.size / 1024).toFixed(1)} KB</p>
                                            </div>
                                        </div>
                                        <button
                                            type="button"
                                            onClick={handleRemoveFile}
                                            disabled={processing || isSimulatingProgress}
                                            className="text-red-500 hover:text-red-700 text-xs font-bold p-1 hover:bg-red-50 rounded-lg transition-all"
                                        >
                                            إلغاء الملف ✕
                                        </button>
                                    </div>

                                    {/* Progress Bar */}
                                    {(processing || isSimulatingProgress || uploadProgress > 0) && (
                                        <div className="space-y-1.5">
                                            <div className="flex items-center justify-between text-xxs text-gray-500">
                                                <span>جاري تحليل ومعالجة البيانات...</span>
                                                <span className="font-bold">{Math.round(uploadProgress)}%</span>
                                            </div>
                                            <div className="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                                <div
                                                    className="bg-indigo-600 h-full rounded-full transition-all duration-300"
                                                    style={{ width: `${uploadProgress}%` }}
                                                />
                                            </div>
                                        </div>
                                    )}
                                </div>
                            )}

                            {/* Submit Import Button */}
                            <div className="flex justify-end pt-2">
                                <button
                                    type="submit"
                                    disabled={!selectedFile || processing || isSimulatingProgress}
                                    className={`px-8 py-3 rounded-2xl font-bold text-sm shadow-sm transition-all ${
                                        selectedFile && !processing && !isSimulatingProgress
                                            ? 'bg-indigo-600 hover:bg-indigo-700 text-white cursor-pointer'
                                            : 'bg-gray-150 text-gray-400 cursor-not-allowed'
                                    }`}
                                >
                                    {processing || isSimulatingProgress ? 'جاري الاستيراد والتسجيل...' : 'بدء عملية الاستيراد الآن 🚀'}
                                </button>
                            </div>
                        </form>
                    </div>
                )}
            </div>
        </MerchantLayout>
    );
}
