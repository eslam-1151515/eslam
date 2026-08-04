import React, { useState, useRef, useEffect } from 'react';
import { Head, Link, usePage, useForm } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

export default function BulkUpload() {
    const { flash } = usePage().props;
    const fileInputRef = useRef(null);
    const [previewData, setPreviewData] = useState(null);
    const [isDragOver, setIsDragOver] = useState(false);
    const [simulatedProgress, setSimulatedProgress] = useState(0);

    const { data, setData, post, processing, progress, errors, reset } = useForm({
        file: null,
    });

    // إعادة ضبط الحقول عند نجاح العملية أو فشلها
    useEffect(() => {
        if (flash?.success || flash?.error || flash?.import_result) {
            if (flash?.success || flash?.import_result?.success) {
                setPreviewData(null);
                reset();
                if (fileInputRef.current) {
                    fileInputRef.current.value = '';
                }
            }
        }
    }, [flash]);

    // محاكاة شريط التقدم أثناء معالجة الطلب في الخلفية
    useEffect(() => {
        let interval;
        if (processing) {
            setSimulatedProgress(10);
            interval = setInterval(() => {
                setSimulatedProgress(prev => {
                    if (prev >= 95) return prev;
                    return prev + (prev < 60 ? 15 : prev < 85 ? 5 : 2);
                });
            }, 400);
        } else {
            setSimulatedProgress(0);
        }
        return () => clearInterval(interval);
    }, [processing]);

    // دالة بسيطة لتحليل محتويات ملف الـ CSV لعرض معاينة أولية
    const parseCSV = (text) => {
        const lines = text.split(/\r?\n/).filter(line => line.trim() !== '');
        if (lines.length === 0) return { headers: [], rows: [] };

        const parseLine = (line) => {
            const result = [];
            let current = '';
            let inQuotes = false;

            for (let i = 0; i < line.length; i++) {
                const char = line[i];
                if (char === '"') {
                    inQuotes = !inQuotes;
                } else if (char === ',' && !inQuotes) {
                    result.push(current.trim());
                    current = '';
                } else {
                    current += char;
                }
            }
            result.push(current.trim());
            return result;
        };

        const headers = parseLine(lines[0]);
        // جلب أول 5 صفوف فقط للمعاينة
        const rows = lines.slice(1, 6).map(line => {
            const values = parseLine(line);
            const obj = {};
            headers.forEach((h, index) => {
                obj[h] = values[index] || '';
            });
            return obj;
        });

        return { headers, rows };
    };

    const handleFileChange = (e) => {
        const file = e.target.files[0];
        if (!file) return;

        if (!file.name.endsWith('.csv')) {
            alert('يرجى اختيار ملف بصيغة CSV فقط');
            return;
        }

        setData('file', file);

        const reader = new FileReader();
        reader.onload = (event) => {
            const text = event.target.result;
            const parsed = parseCSV(text);
            setPreviewData(parsed);
        };
        reader.readAsText(file, 'UTF-8');
    };

    const handleDragOver = (e) => {
        e.preventDefault();
        setIsDragOver(true);
    };

    const handleDragLeave = () => {
        setIsDragOver(false);
    };

    const handleDrop = (e) => {
        e.preventDefault();
        setIsDragOver(false);
        const file = e.dataTransfer.files[0];
        if (!file) return;

        if (!file.name.endsWith('.csv')) {
            alert('يرجى اختيار ملف بصيغة CSV فقط');
            return;
        }

        setData('file', file);

        const reader = new FileReader();
        reader.onload = (event) => {
            const text = event.target.result;
            const parsed = parseCSV(text);
            setPreviewData(parsed);
        };
        reader.readAsText(file, 'UTF-8');
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        if (!data.file) return;

        post(route('merchant.products.bulk.import'), {
            forceFormData: true,
        });
    };

    const triggerFileSelect = () => {
        fileInputRef.current.click();
    };

    const removeSelectedFile = () => {
        setData('file', null);
        setPreviewData(null);
        if (fileInputRef.current) {
            fileInputRef.current.value = '';
        }
    };

    const formatBytes = (bytes, decimals = 2) => {
        if (!+bytes) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`;
    };

    const importResult = flash?.import_result || usePage().props.import_result;

    return (
        <MerchantLayout title="الرفع الجماعي للمنتجات">
            <Head title="الرفع الجماعي للمنتجات" />

            <div className="space-y-6 text-right" dir="rtl">
                {/* العنوان */}
                <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 className="text-2xl font-bold text-gray-900">الرفع الجماعي للمنتجات</h2>
                        <p className="text-sm text-gray-500 mt-1">
                            قم بإضافة مجموعة كبيرة من المنتجات دفعة واحدة عن طريق رفع ملف CSV معد مسبقاً.
                        </p>
                    </div>
                    <Link
                        href="/admin/products"
                        className="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-300 text-gray-700 bg-white rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors shadow-sm"
                    >
                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5l7 7-7 7" />
                        </svg>
                        العودة للمنتجات
                    </Link>
                </div>

                {/* التنبيهات والرسائل الفلاشية */}
                {flash?.success && (
                    <div className="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3">
                        <svg className="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span className="text-sm font-medium">{flash.success}</span>
                    </div>
                )}

                {flash?.error && (
                    <div className="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl flex items-center gap-3">
                        <svg className="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span className="text-sm font-medium">{flash.error}</span>
                    </div>
                )}

                {/* نتائج الاستيراد المفصلة */}
                {importResult && (
                    <div className="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-4">
                        <h3 className="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg className="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            إحصائيات عملية الاستيراد الأخيرة
                        </h3>
                        
                        <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div className="p-4 bg-emerald-50 border border-emerald-100 rounded-lg text-center">
                                <span className="block text-2xl font-extrabold text-emerald-600">{importResult.imported}</span>
                                <span className="text-xs font-semibold text-emerald-800">منتجات تم استيرادها بنجاح</span>
                            </div>
                            <div className="p-4 bg-amber-50 border border-amber-100 rounded-lg text-center">
                                <span className="block text-2xl font-extrabold text-amber-600">{importResult.ignored}</span>
                                <span className="text-xs font-semibold text-amber-800">صفوف تم تجاهلها أو بها أخطاء</span>
                            </div>
                            <div className="p-4 bg-blue-50 border border-blue-100 rounded-lg text-center">
                                <span className="block text-2xl font-extrabold text-blue-600">
                                    {importResult.imported + importResult.ignored}
                                </span>
                                <span className="text-xs font-semibold text-blue-800">إجمالي الصفوف المعالجة</span>
                            </div>
                        </div>

                        {/* قائمة أخطاء التحقق */}
                        {importResult.errors && importResult.errors.length > 0 && (
                            <div className="mt-4 border border-rose-100 rounded-lg bg-rose-50/50 p-4">
                                <span className="text-sm font-bold text-rose-800 block mb-2">تفاصيل الأخطاء المكتشفة:</span>
                                <div className="max-h-60 overflow-y-auto space-y-2 pr-1">
                                    {importResult.errors.map((err, i) => (
                                        <div key={i} className="text-xs bg-white border border-rose-100 p-2.5 rounded-md shadow-2xs">
                                            <div className="flex justify-between items-center mb-1">
                                                <span className="font-bold text-gray-700">الصف {err.row}</span>
                                                <span className="text-gray-500 font-medium">المنتج: {err.name}</span>
                                            </div>
                                            <ul className="list-disc list-inside text-rose-600 space-y-0.5">
                                                {err.messages.map((msg, idx) => (
                                                    <li key={idx}>{msg}</li>
                                                ))}
                                            </ul>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        )}
                    </div>
                )}

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {/* عمود التعليمات وتحميل القالب */}
                    <div className="lg:col-span-1 space-y-6">
                        <div className="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-5">
                            <div>
                                <h3 className="text-md font-bold text-gray-900">1. تحميل قالب البيانات</h3>
                                <p className="text-xs text-gray-500 mt-1">
                                    قم بتحميل نموذج الـ CSV لملئه بالمنتجات لتجنب أي أخطاء في التنسيق.
                                </p>
                            </div>
                            <a
                                href={route('merchant.products.bulk.template')}
                                className="w-full inline-flex justify-center items-center gap-2 px-4 py-3 bg-orange-50 text-orange-700 border border-orange-200 rounded-lg text-sm font-medium hover:bg-orange-100 transition-colors shadow-2xs"
                            >
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                تحميل قالب CSV الجاهز
                            </a>

                            <hr className="border-gray-100" />

                            <div>
                                <h3 className="text-md font-bold text-gray-900 mb-3">إرشادات هامة للملف:</h3>
                                <ul className="space-y-2.5 text-xs text-gray-600">
                                    <li className="flex items-start gap-1.5">
                                        <span className="inline-block w-1.5 h-1.5 rounded-full bg-orange-500 mt-1.5 flex-shrink-0"></span>
                                        <span>يجب حفظ الملف بترميز <strong>UTF-8</strong> لضمان دعم الحروف العربية.</span>
                                    </li>
                                    <li className="flex items-start gap-1.5">
                                        <span className="inline-block w-1.5 h-1.5 rounded-full bg-orange-500 mt-1.5 flex-shrink-0"></span>
                                        <span>الحقول الإلزامية هي: <strong>الاسم، السعر، الكمية، واسم القسم</strong>.</span>
                                    </li>
                                    <li className="flex items-start gap-1.5">
                                        <span className="inline-block w-1.5 h-1.5 rounded-full bg-orange-500 mt-1.5 flex-shrink-0"></span>
                                        <span>إذا كان القسم (category_name) غير موجود، فسيقوم النظام بإنشائه تلقائياً وربطه بالمنتج.</span>
                                    </li>
                                    <li className="flex items-start gap-1.5">
                                        <span className="inline-block w-1.5 h-1.5 rounded-full bg-orange-500 mt-1.5 flex-shrink-0"></span>
                                        <span>الـ SKU يجب أن يكون فريداً إذا تم تعيينه.</span>
                                    </li>
                                    <li className="flex items-start gap-1.5">
                                        <span className="inline-block w-1.5 h-1.5 rounded-full bg-orange-500 mt-1.5 flex-shrink-0"></span>
                                        <span>لفصل المقاسات أو الألوان المتعددة في نفس المنتج، استخدم <strong>الفاصلة العادية (,)</strong>، مثل: <code className="bg-gray-100 px-1 rounded">S,M,L</code>.</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {/* عمود رفع الملف والمعاينة */}
                    <div className="lg:col-span-2 space-y-6">
                        <form onSubmit={handleSubmit} className="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-5">
                            <div>
                                <h3 className="text-md font-bold text-gray-900">2. اختيار ورفع الملف</h3>
                                <p className="text-xs text-gray-500 mt-1">
                                    اسحب ملف الـ CSV المعبأ بالمنتجات وأفلته هنا للرفع.
                                </p>
                            </div>

                            <input
                                type="file"
                                ref={fileInputRef}
                                onChange={handleFileChange}
                                accept=".csv"
                                className="hidden"
                            />

                            {/* منطقة السحب والإفلات */}
                            {!data.file ? (
                                <div
                                    onDragOver={handleDragOver}
                                    onDragLeave={handleDragLeave}
                                    onDrop={handleDrop}
                                    onClick={triggerFileSelect}
                                    className={`border-2 border-dashed rounded-xl p-8 flex flex-col items-center justify-center gap-3 cursor-pointer transition-all ${
                                        isDragOver
                                            ? 'border-orange-500 bg-orange-50/50 scale-[0.99]'
                                            : 'border-gray-300 hover:border-orange-400 hover:bg-gray-50/50'
                                    }`}
                                >
                                    <div className="p-3.5 bg-orange-50 rounded-full text-orange-600">
                                        <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div className="text-center space-y-1">
                                        <p className="text-sm font-semibold text-gray-800">اسحب وأفلت ملف الـ CSV هنا</p>
                                        <p className="text-xs text-gray-400">أو انقر لتصفح الملفات من جهازك</p>
                                    </div>
                                    <span className="text-[10px] text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">
                                        الحجم الأقصى: 5 ميجابايت
                                    </span>
                                </div>
                            ) : (
                                <div className="border border-orange-200 bg-orange-50/20 rounded-xl p-4 flex items-center justify-between gap-4">
                                    <div className="flex items-center gap-3">
                                        <div className="p-2.5 bg-orange-100 rounded-lg text-orange-600">
                                            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p className="text-sm font-bold text-gray-800 truncate max-w-[250px] sm:max-w-xs">{data.file.name}</p>
                                            <p className="text-xs text-gray-400 mt-0.5">{formatBytes(data.file.size)}</p>
                                        </div>
                                    </div>
                                    <button
                                        type="button"
                                        onClick={removeSelectedFile}
                                        className="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all"
                                        title="إلغاء الملف"
                                        disabled={processing}
                                    >
                                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            )}

                            {/* شاشة المعاينة ما قبل الاستيراد */}
                            {previewData && previewData.rows.length > 0 && (
                                <div className="space-y-2 border border-gray-100 rounded-xl p-4 bg-gray-50/50">
                                    <span className="text-xs font-bold text-gray-700 flex items-center gap-1.5">
                                        <svg className="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        معاينة ما قبل الاستيراد (أول 5 صفوف)
                                    </span>
                                    <div className="overflow-x-auto border border-gray-200 rounded-lg">
                                        <table className="w-full text-xs text-right text-gray-500">
                                            <thead className="text-[10px] text-gray-700 bg-gray-100 uppercase font-bold border-b border-gray-200">
                                                <tr>
                                                    {previewData.headers.map((h, i) => (
                                                        <th key={i} className="px-3 py-2 text-center whitespace-nowrap">
                                                            {h}
                                                        </th>
                                                    ))}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {previewData.rows.map((row, i) => (
                                                    <tr key={i} className="bg-white border-b border-gray-100 last:border-0 hover:bg-gray-50">
                                                        {previewData.headers.map((h, idx) => (
                                                            <td key={idx} className="px-3 py-2 text-center whitespace-nowrap font-medium text-gray-800">
                                                                {row[h] !== undefined ? row[h] : '-'}
                                                            </td>
                                                        ))}
                                                    </tr>
                                                ))}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            )}

                            {/* شريط التقدم أثناء الاستيراد */}
                            {processing && (
                                <div className="space-y-2 mt-4 bg-orange-50/50 p-4 border border-orange-100 rounded-xl">
                                    <div className="flex justify-between items-center">
                                        <span className="text-xs font-bold text-orange-800">
                                            {simulatedProgress < 50 
                                                ? 'جاري رفع الملف وقراءته...' 
                                                : simulatedProgress < 85 
                                                ? 'يتم الآن التحقق من صحة البيانات وتطابق الأقسام...' 
                                                : 'حفظ المنتجات وتحديث قاعدة البيانات...'}
                                        </span>
                                        <span className="text-xs font-bold text-orange-600">{simulatedProgress}%</span>
                                    </div>
                                    <div className="w-full bg-gray-200 rounded-full h-2">
                                        <div
                                            className="bg-orange-500 h-2 rounded-full transition-all duration-300 ease-out shadow-sm"
                                            style={{ width: `${simulatedProgress}%` }}
                                        ></div>
                                    </div>
                                </div>
                            )}

                            {/* أخطاء الرفع العامة */}
                            {errors.file && (
                                <p className="text-xs text-rose-600 font-semibold">{errors.file}</p>
                            )}

                            {/* زر بدء الاستيراد */}
                            {data.file && (
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="w-full inline-flex justify-center items-center gap-2 px-5 py-3 bg-orange-600 text-white rounded-lg text-sm font-semibold hover:bg-orange-700 transition-colors shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    {processing ? (
                                        <>
                                            <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                            </svg>
                                            جاري معالجة الاستيراد...
                                        </>
                                    ) : (
                                        <>
                                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            بدء عملية الاستيراد الفعلي
                                        </>
                                    )}
                                </button>
                            )}
                        </form>
                    </div>
                </div>
            </div>
        </MerchantLayout>
    );
}
