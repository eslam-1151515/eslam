import React, { useState } from 'react';
import { Head, useForm, usePage, Link } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

export default function SettingsIndex({ settings }) {
    const { flash } = usePage().props;
    const queryParams = typeof window !== 'undefined' ? new URLSearchParams(window.location.search) : null;
    const initialTab = queryParams ? (queryParams.get('tab') || 'general') : 'general';
    const [activeTab, setActiveTab] = useState(initialTab);
    const [logoPreview, setLogoPreview] = useState(settings.logo_url);

    // Form data setup
    const { data, setData, post, processing, errors } = useForm({
        _method: 'PUT',
        store_name: settings.store_name || '',
        phone: settings.phone || '',
        whatsapp: settings.whatsapp || '',
        facebook_pixel_id: settings.facebook_pixel_id || '',
        tiktok_pixel_id: settings.tiktok_pixel_id || '',
        snapchat_pixel_id: settings.snapchat_pixel_id || '',
        google_analytics_id: settings.google_analytics_id || '',
        facebook_page: settings.facebook_page || '',
        instagram_page: settings.instagram_page || '',
        tiktok_page: settings.tiktok_page || '',
        google_maps_url: settings.google_maps_url || '',
        address: settings.address || '',
        logo: null,
        main_categories: settings.main_categories || [],
    });

    const handleLogoChange = (e) => {
        const file = e.target.files[0];
        if (file) {
            setData('logo', file);
            const reader = new FileReader();
            reader.onloadend = () => {
                setLogoPreview(reader.result);
            };
            reader.readAsDataURL(file);
        }
    };

    const handleRemoveLogo = () => {
        setLogoPreview(null);
        setData('logo', null);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        post('/admin/settings', {
            forceFormData: true,
            preserveScroll: true,
        });
    };

    // Category handlers
    const handleCategoryChange = (index, value) => {
        const updated = [...data.main_categories];
        updated[index] = value;
        setData('main_categories', updated);
    };

    const handleAddCategory = () => {
        setData('main_categories', [...data.main_categories, '']);
    };

    const handleRemoveCategory = (index) => {
        const updated = data.main_categories.filter((_, i) => i !== index);
        setData('main_categories', updated);
    };

    // Test WhatsApp helper
    const handleTestWhatsapp = () => {
        const rawPhone = data.whatsapp.replace(/[^0-9]/g, '');
        if (!rawPhone || rawPhone.length < 10) {
            alert('برجاء كتابة رقم الواتساب بشكل صحيح أولاً لتجربته.');
            return;
        }
        let clean = rawPhone;
        if (clean.startsWith('01') && clean.length === 11) {
            clean = '2' + clean;
        }
        window.open(`https://wa.me/${clean}?text=${encodeURIComponent('اختبار رقم الواتساب من لوحة التحكم - متجر فاست أوردر')}`, '_blank');
    };

    return (
        <MerchantLayout title="إعدادات المتجر">
            <Head title="إعدادات المتجر" />

            <div className="space-y-6 rtl text-right" dir="rtl">
                {/* Header */}
                <div>
                    <h2 className="text-2xl font-extrabold text-gray-900">إعدادات المتجر</h2>
                    <p className="text-sm text-gray-500 mt-1">
                        تعديل وإدارة الإعدادات الأساسية لمتجرك، الشعار، البيكسلات وروابط التواصل الاجتماعي.
                    </p>
                </div>

                {/* Flash Messages */}
                {flash?.success && (
                    <div className="p-4 bg-emerald-50 border-r-4 border-emerald-500 rounded-xl text-emerald-800 text-sm font-medium flex items-center gap-3 shadow-sm animate-in fade-in slide-in-from-top-4 duration-200">
                        <span className="flex items-center justify-center w-5 h-5 bg-emerald-100 rounded-full text-emerald-600 text-xs">✓</span>
                        {flash.success}
                    </div>
                )}
                {flash?.error && (
                    <div className="p-4 bg-red-50 border-r-4 border-red-500 rounded-xl text-red-800 text-sm font-medium flex items-center gap-3 shadow-sm animate-in fade-in slide-in-from-top-4 duration-200">
                        <span className="flex items-center justify-center w-5 h-5 bg-red-100 rounded-full text-red-600 text-xs">⚠️</span>
                        {flash.error}
                    </div>
                )}

                {/* Main Settings Grid */}
                <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
                    {/* Navigation Tabs */}
                    <div className="md:col-span-1 bg-white rounded-2xl border border-gray-200 p-4 shadow-sm h-fit">
                        <nav className="flex flex-col gap-2">
                            <button
                                type="button"
                                onClick={() => setActiveTab('general')}
                                className={`flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all text-right w-full cursor-pointer ${
                                    activeTab === 'general'
                                        ? 'bg-orange-50 text-orange-600 shadow-sm'
                                        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'
                                }`}
                            >
                                <span>⚙️</span>
                                الإعدادات العامة
                            </button>

                            <button
                                type="button"
                                onClick={() => setActiveTab('integrations')}
                                className={`flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all text-right w-full cursor-pointer ${
                                    activeTab === 'integrations'
                                        ? 'bg-orange-50 text-orange-600 shadow-sm'
                                        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'
                                }`}
                            >
                                <span>🎯</span>
                                ربط البيكسل
                            </button>

                            <button
                                type="button"
                                onClick={() => setActiveTab('social')}
                                className={`flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all text-right w-full cursor-pointer ${
                                    activeTab === 'social'
                                        ? 'bg-orange-50 text-orange-600 shadow-sm'
                                        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'
                                }`}
                            >
                                <span>🌐</span>
                                روابط التواصل والوسائط
                            </button>

                            <Link
                                href="/admin/banners"
                                className="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all text-right w-full cursor-pointer text-gray-600 hover:bg-orange-50 hover:text-orange-600"
                            >
                                <span>🖼️</span>
                                البانرات
                            </Link>

                            <Link
                                href="/admin/shipping"
                                className="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all text-right w-full cursor-pointer text-gray-600 hover:bg-orange-50 hover:text-orange-600"
                            >
                                <span>🚚</span>
                                الشحن
                            </Link>

                            <button
                                type="button"
                                onClick={() => setActiveTab('logo')}
                                className={`flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all text-right w-full cursor-pointer ${
                                    activeTab === 'logo'
                                        ? 'bg-orange-50 text-orange-600 shadow-sm'
                                        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'
                                }`}
                            >
                                <span>🎨</span>
                                شعار المتجر
                            </button>

                            <button
                                type="button"
                                onClick={() => setActiveTab('categories')}
                                className={`flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all text-right w-full cursor-pointer ${
                                    activeTab === 'categories'
                                        ? 'bg-orange-50 text-orange-600 shadow-sm'
                                        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'
                                }`}
                            >
                                <span>🗂️</span>
                                أقسام المنصة
                            </button>
                        </nav>
                    </div>

                    {/* Settings Form Content */}
                    <div className="md:col-span-3 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <form onSubmit={handleSubmit} className="p-6 space-y-6">
                            {/* Tab 1: General Settings */}
                            {activeTab === 'general' && (
                                <div className="space-y-5 animate-in fade-in duration-200">
                                    <h3 className="text-lg font-bold text-gray-900 border-b pb-3 mb-4 flex items-center gap-2">
                                        <span>📞</span> بيانات المتجر الأساسية
                                    </h3>
                                    
                                    <div>
                                        <label className="block text-sm font-bold text-gray-700 mb-1.5">اسم المتجر</label>
                                        <input
                                            type="text"
                                            value={data.store_name}
                                            onChange={(e) => setData('store_name', e.target.value)}
                                            placeholder="مثال: متجر الأناقة"
                                            className={`w-full px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all ${
                                                errors.store_name ? 'border-red-400 bg-red-50/50 text-red-900' : 'border-gray-300'
                                            }`}
                                        />
                                        <p className="text-xs text-gray-400 mt-1">يظهر اسم المتجر في جميع صفحات الموقع والفواتير.</p>
                                        {errors.store_name && <p className="text-xs text-red-600 mt-1">{errors.store_name}</p>}
                                    </div>

                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div>
                                            <label className="block text-sm font-bold text-gray-700 mb-1.5">رقم الهاتف (للاتصال)</label>
                                            <input
                                                type="text"
                                                value={data.phone}
                                                onChange={(e) => setData('phone', e.target.value)}
                                                placeholder="01xxxxxxxxx"
                                                className={`w-full px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-left font-mono ${
                                                    errors.phone ? 'border-red-400 bg-red-50/50 text-red-900' : 'border-gray-300'
                                                }`}
                                                dir="ltr"
                                            />
                                            <p className="text-xs text-gray-400 mt-1 text-right">أرقام فقط بدون مسافات أو حروف (مثال: 01012345678).</p>
                                            {errors.phone && <p className="text-xs text-red-600 font-bold mt-1 text-right">{errors.phone}</p>}
                                        </div>

                                        <div>
                                            <div className="flex items-center justify-between mb-1.5">
                                                <label className="block text-sm font-bold text-gray-700">رقم الواتساب</label>
                                                <button
                                                    type="button"
                                                    onClick={handleTestWhatsapp}
                                                    className="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 px-2.5 py-1 rounded-lg transition-colors border border-emerald-200"
                                                    title="اختبار رقم الواتساب في نافذة جديدة"
                                                >
                                                    <span>🧪</span> اختبار الواتس
                                                </button>
                                            </div>
                                            <input
                                                type="text"
                                                value={data.whatsapp}
                                                onChange={(e) => setData('whatsapp', e.target.value)}
                                                placeholder="201xxxxxxxxx"
                                                className={`w-full px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-left font-mono ${
                                                    errors.whatsapp ? 'border-red-400 bg-red-50/50 text-red-900' : 'border-gray-300'
                                                }`}
                                                dir="ltr"
                                            />
                                            <p className="text-xs text-gray-400 mt-1 text-right">أدخل أرقام فقط بدون مسافات (مثال: 201012345678 أو 01012345678).</p>
                                            {errors.whatsapp && <p className="text-xs text-red-600 font-bold mt-1 text-right">{errors.whatsapp}</p>}
                                        </div>
                                    </div>
                                </div>
                            )}

                            {/* Tab 2: Pixel Links */}
                            {activeTab === 'integrations' && (
                                <div className="space-y-5 animate-in fade-in duration-200">
                                    <h3 className="text-lg font-bold text-gray-900 border-b pb-3 mb-4 flex items-center gap-2">
                                        <span>🎯</span> ربط البيكسل
                                    </h3>

                                    <div>
                                        <label className="block text-sm font-bold text-gray-700 mb-1.5">معرف فيسبوك بكسل (Facebook Pixel ID)</label>
                                        <input
                                            type="text"
                                            value={data.facebook_pixel_id}
                                            onChange={(e) => setData('facebook_pixel_id', e.target.value)}
                                            placeholder="مثال: 123456789012345"
                                            className={`w-full px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-left font-mono ${
                                                errors.facebook_pixel_id ? 'border-red-400 bg-red-50/50 text-red-900' : 'border-gray-300'
                                            }`}
                                            dir="ltr"
                                        />
                                        <p className="text-xs text-gray-400 mt-1 text-right">أدخل أرقام الـ Pixel ID فقط وسيتم تفعيل تتبع الأحداث تلقائياً.</p>
                                        {errors.facebook_pixel_id && <p className="text-xs text-red-600 mt-1 text-right">{errors.facebook_pixel_id}</p>}
                                    </div>

                                    <div>
                                        <label className="block text-sm font-bold text-gray-700 mb-1.5">معرف تيك توك بكسل (TikTok Pixel ID)</label>
                                        <input
                                            type="text"
                                            value={data.tiktok_pixel_id}
                                            onChange={(e) => setData('tiktok_pixel_id', e.target.value)}
                                            placeholder="مثال: CD8Q..."
                                            className={`w-full px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-left font-mono ${
                                                errors.tiktok_pixel_id ? 'border-red-400 bg-red-50/50 text-red-900' : 'border-gray-300'
                                            }`}
                                            dir="ltr"
                                        />
                                        <p className="text-xs text-gray-400 mt-1 text-right">أدخل الـ Pixel ID المكون من حروف وأرقام.</p>
                                        {errors.tiktok_pixel_id && <p className="text-xs text-red-600 mt-1 text-right">{errors.tiktok_pixel_id}</p>}
                                    </div>

                                    <div>
                                        <label className="block text-sm font-bold text-gray-700 mb-1.5">معرف سناب شات بكسل (Snapchat Pixel ID)</label>
                                        <input
                                            type="text"
                                            value={data.snapchat_pixel_id}
                                            onChange={(e) => setData('snapchat_pixel_id', e.target.value)}
                                            placeholder="مثال: 9a8b7c6d-5e4f-3a2b..."
                                            className={`w-full px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-left font-mono ${
                                                errors.snapchat_pixel_id ? 'border-red-400 bg-red-50/50 text-red-900' : 'border-gray-300'
                                            }`}
                                            dir="ltr"
                                        />
                                        <p className="text-xs text-gray-400 mt-1 text-right">أدخل معرف Snapchat Pixel لتتبع الإعلانات على سناب شات.</p>
                                        {errors.snapchat_pixel_id && <p className="text-xs text-red-600 mt-1 text-right">{errors.snapchat_pixel_id}</p>}
                                    </div>

                                    <div>
                                        <label className="block text-sm font-bold text-gray-700 mb-1.5">معرف جوجل أناليتكس (Google Analytics ID)</label>
                                        <input
                                            type="text"
                                            value={data.google_analytics_id}
                                            onChange={(e) => setData('google_analytics_id', e.target.value)}
                                            placeholder="مثال: G-XXXXXXXXXX"
                                            className={`w-full px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-left font-mono ${
                                                errors.google_analytics_id ? 'border-red-400 bg-red-50/50 text-red-900' : 'border-gray-300'
                                            }`}
                                            dir="ltr"
                                        />
                                        <p className="text-xs text-gray-400 mt-1 text-right">أدخل معرف التتبع لجوجل مثل G-XXXXXXXXXX.</p>
                                        {errors.google_analytics_id && <p className="text-xs text-red-600 mt-1 text-right">{errors.google_analytics_id}</p>}
                                    </div>
                                </div>
                            )}

                            {/* Tab 3: Social & Location Links */}
                            {activeTab === 'social' && (
                                <div className="space-y-5 animate-in fade-in duration-200">
                                    <h3 className="text-lg font-bold text-gray-900 border-b pb-3 mb-4 flex items-center gap-2">
                                        <span>🌐</span> روابط التواصل والوسائط والعنوان
                                    </h3>

                                    <div>
                                        <label className="block text-sm font-bold text-gray-700 mb-1.5">رابط صفحة فيسبوك (Facebook)</label>
                                        <input
                                            type="url"
                                            value={data.facebook_page}
                                            onChange={(e) => setData('facebook_page', e.target.value)}
                                            placeholder="https://facebook.com/yourpage"
                                            className={`w-full px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-left ${
                                                errors.facebook_page ? 'border-red-400 bg-red-50/50 text-red-900' : 'border-gray-300'
                                            }`}
                                            dir="ltr"
                                        />
                                        {errors.facebook_page && <p className="text-xs text-red-600 mt-1 text-right">{errors.facebook_page}</p>}
                                    </div>

                                    <div>
                                        <label className="block text-sm font-bold text-gray-700 mb-1.5">رابط الانستجرام (Instagram)</label>
                                        <input
                                            type="url"
                                            value={data.instagram_page}
                                            onChange={(e) => setData('instagram_page', e.target.value)}
                                            placeholder="https://instagram.com/yourprofile"
                                            className={`w-full px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-left ${
                                                errors.instagram_page ? 'border-red-400 bg-red-50/50 text-red-900' : 'border-gray-300'
                                            }`}
                                            dir="ltr"
                                        />
                                        {errors.instagram_page && <p className="text-xs text-red-600 mt-1 text-right">{errors.instagram_page}</p>}
                                    </div>

                                    <div>
                                        <label className="block text-sm font-bold text-gray-700 mb-1.5">رابط التيك توك (TikTok)</label>
                                        <input
                                            type="url"
                                            value={data.tiktok_page}
                                            onChange={(e) => setData('tiktok_page', e.target.value)}
                                            placeholder="https://tiktok.com/@yourprofile"
                                            className={`w-full px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-left ${
                                                errors.tiktok_page ? 'border-red-400 bg-red-50/50 text-red-900' : 'border-gray-300'
                                            }`}
                                            dir="ltr"
                                        />
                                        {errors.tiktok_page && <p className="text-xs text-red-600 mt-1 text-right">{errors.tiktok_page}</p>}
                                    </div>

                                    <div>
                                        <label className="block text-sm font-bold text-gray-700 mb-1.5">رابط موقع الخريطة (Google Maps Location URL)</label>
                                        <input
                                            type="url"
                                            value={data.google_maps_url}
                                            onChange={(e) => setData('google_maps_url', e.target.value)}
                                            placeholder="https://maps.google.com/..."
                                            className={`w-full px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-left ${
                                                errors.google_maps_url ? 'border-red-400 bg-red-50/50 text-red-900' : 'border-gray-300'
                                            }`}
                                            dir="ltr"
                                        />
                                        {errors.google_maps_url && <p className="text-xs text-red-600 mt-1 text-right">{errors.google_maps_url}</p>}
                                    </div>

                                    <div>
                                        <label className="block text-sm font-bold text-gray-700 mb-1.5">العنوان التفصيلي للمتجر</label>
                                        <input
                                            type="text"
                                            value={data.address}
                                            onChange={(e) => setData('address', e.target.value)}
                                            placeholder="مثال: القاهرة، مدينة نصر، شارع الطيران (إذا تُرِكَ فارغاً يظهر: مصر)"
                                            className={`w-full px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all ${
                                                errors.address ? 'border-red-400 bg-red-50/50 text-red-900' : 'border-gray-300'
                                            }`}
                                        />
                                        <p className="text-xs text-gray-400 mt-1">يظهر هذا العنوان في صفحة تواصل معنا بالموقع.</p>
                                        {errors.address && <p className="text-xs text-red-600 mt-1">{errors.address}</p>}
                                    </div>
                                </div>
                            )}

                            {/* Tab 4: Logo Upload */}
                            {activeTab === 'logo' && (
                                <div className="space-y-5 animate-in fade-in duration-200">
                                    <h3 className="text-lg font-bold text-gray-900 border-b pb-3 mb-4 flex items-center gap-2">
                                        <span>🖼️</span> شعار المتجر (Logo)
                                    </h3>

                                    <div className="flex flex-col md:flex-row gap-6 items-start">
                                        <div className="flex-1 w-full">
                                            <label className="block text-sm font-bold text-gray-700 mb-2">رفع شعار جديد</label>
                                            
                                            <div className="relative border-2 border-dashed border-gray-300 rounded-2xl p-6 text-center hover:border-orange-500 transition-colors bg-gray-50/50 cursor-pointer">
                                                <input
                                                    type="file"
                                                    accept="image/*"
                                                    onChange={handleLogoChange}
                                                    className="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                                />
                                                <svg className="w-10 h-10 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span className="block text-sm font-semibold text-gray-700">اضغط لرفع صورة الشعار</span>
                                                <span className="block text-xs text-gray-400 mt-1">الصيغ المسموح بها: JPG, PNG, WEBP, SVG - الحد الأقصى: 20MB</span>
                                            </div>
                                            {errors.logo && <p className="text-xs text-red-600 mt-2">{errors.logo}</p>}
                                        </div>

                                        <div className="flex flex-col items-center gap-2 bg-gray-50 border border-gray-100 rounded-2xl p-4 w-full md:w-fit min-w-[150px]">
                                            <p className="text-xs text-gray-500 font-bold">معاينة الشعار:</p>
                                            {logoPreview ? (
                                                <div className="relative group">
                                                    <img
                                                        src={logoPreview}
                                                        alt="الشعار"
                                                        className="h-28 w-28 object-contain rounded-xl border border-gray-200 bg-white p-2 shadow-sm"
                                                    />
                                                    <button
                                                        type="button"
                                                        onClick={handleRemoveLogo}
                                                        className="absolute -top-2 -right-2 bg-red-600 text-white rounded-full p-1 shadow-md hover:bg-red-700 transition-colors cursor-pointer"
                                                        title="إلغاء الصورة المحددة"
                                                    >
                                                        <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            ) : (
                                                <div className="h-28 w-28 rounded-xl border-2 border-dashed border-gray-200 flex items-center justify-center text-gray-300 text-xs text-center p-2 bg-white">
                                                    لا يوجد شعار حالياً
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            )}

                            {/* Tab 5: Platform Main Categories */}
                            {activeTab === 'categories' && (
                                <div className="space-y-5 animate-in fade-in duration-200">
                                    <div className="flex justify-between items-center border-b pb-3 mb-4">
                                        <h3 className="text-lg font-bold text-gray-900 flex items-center gap-2">
                                            <span>🗂️</span> الأقسام الرئيسية
                                        </h3>
                                        <button
                                            type="button"
                                            onClick={handleAddCategory}
                                            className="px-3.5 py-1.5 bg-orange-50 hover:bg-orange-100 text-orange-600 rounded-xl text-xs font-bold transition-colors flex items-center gap-1.5 cursor-pointer"
                                        >
                                            <span>+</span> إضافة قسم رئيسي
                                        </button>
                                    </div>

                                    <div className="space-y-3">
                                        {data.main_categories.map((cat, idx) => (
                                            <div key={idx} className="flex items-center gap-2">
                                                <input
                                                    type="text"
                                                    value={cat}
                                                    onChange={(e) => handleCategoryChange(idx, e.target.value)}
                                                    placeholder={`اسم القسم الرئيسي #${idx + 1}`}
                                                    className="flex-1 px-3.5 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                                                />
                                                <button
                                                    type="button"
                                                    onClick={() => handleRemoveCategory(idx)}
                                                    className="p-2.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors cursor-pointer"
                                                >
                                                    ✕
                                                </button>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            )}

                            {/* Submit Button */}
                            <div className="pt-6 border-t border-gray-100 flex justify-end">
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="px-6 py-2.5 bg-orange-600 hover:bg-orange-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all cursor-pointer disabled:opacity-50"
                                >
                                    {processing ? 'جاري الحفظ...' : 'حفظ الإعدادات'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </MerchantLayout>
    );
}
