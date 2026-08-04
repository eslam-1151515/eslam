import React, { useState, useEffect } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

// Mock Component for live rendering of the preview
import LivePreview from '@/Components/LandingPageBuilder/LivePreview';
import SectionsList from '@/Components/LandingPageBuilder/SectionsList';
import SectionEditor from '@/Components/LandingPageBuilder/SectionEditor';
import AddSectionModal from '@/Components/LandingPageBuilder/AddSectionModal';

export default function LandingPagesEdit({ landingPage, defaultSections, products, templates }) {
    const [sections, setSections] = useState(landingPage.sections || defaultSections);
    const [selectedSectionIndex, setSelectedSectionIndex] = useState(0);
    const [activeTab, setActiveTab] = useState('sections'); // 'sections' | 'settings' | 'seo'
    const [isMobilePreview, setIsMobilePreview] = useState(true);
    const [showAddModal, setShowAddModal] = useState(false);

    const { data, setData, put, processing, errors } = useForm({
        title: landingPage.title || '',
        slug: landingPage.slug || '',
        template: landingPage.template || 'classic',
        color_theme: landingPage.color_theme || 'light',
        facebook_pixel_id: landingPage.facebook_pixel_id || '',
        tiktok_pixel_id: landingPage.tiktok_pixel_id || '',
        sections: sections,
        custom_css: landingPage.custom_css || '',
        seo_title: landingPage.seo_title || '',
        seo_description: landingPage.seo_description || '',
        featured_image: landingPage.featured_image || '',
    });

    // Update form sections when local sections state changes
    useEffect(() => {
        setData('sections', sections);
    }, [sections]);

    const handleUpdateSection = (index, updatedSection) => {
        const updated = [...sections];
        updated[index] = updatedSection;
        setSections(updated);
    };

    const handleMoveSection = (index, direction) => {
        const targetIndex = direction === 'up' ? index - 1 : index + 1;
        if (targetIndex < 0 || targetIndex >= sections.length) return;

        const updated = [...sections];
        const temp = updated[index];
        updated[index] = updated[targetIndex];
        updated[targetIndex] = temp;

        setSections(updated);
        setSelectedSectionIndex(targetIndex);
    };

    const handleDeleteSection = (index) => {
        if (sections.length <= 1) {
            alert('يجب أن تحتوي الصفحة على قسم واحد على الأقل.');
            return;
        }
        if (confirm('هل أنت متأكد من حذف هذا القسم؟')) {
            const updated = sections.filter((_, i) => i !== index);
            setSections(updated);
            setSelectedSectionIndex(0);
        }
    };

    const handleAddSection = (type) => {
        let newSection = { type };
        
        // Find default config from defaults if available
        const defaultSec = defaultSections.find(s => s.type === type);
        if (defaultSec) {
            newSection = { ...defaultSec };
        } else {
            // General defaults
            if (type === 'hero') {
                newSection = { type, title: 'عنوان رئيسي جذاب للمنتج', subtitle: 'وصف فرعي للمنتج وأهم مميزاته لحث الزوار على الطلب', button_text: 'اطلب الآن', bg_color: '#1e1b4b', text_color: '#ffffff' };
            } else if (type === 'countdown') {
                newSection = { type, title: 'ينتهي هذا العرض الحصري خلال:', end_date: new Date(Date.now() + 86400000 * 2).toISOString(), bg_color: '#f97316' };
            } else if (type === 'cta') {
                newSection = { type, title: 'احصل على منتجك الآن قبل نفاد الكمية', subtitle: 'ضمان 100% - دفع عند الاستلام - شحن مجاني وسريع', button_text: 'اطلب الآن واضغط هنا', bg_color: '#4f46e5' };
            }
        }

        setSections([...sections, newSection]);
        setSelectedSectionIndex(sections.length); // select the newly added section
        setShowAddModal(false);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        put(`/admin/landing-pages/${landingPage.id}`);
    };

    return (
        <MerchantLayout title={`تعديل صفحة: ${landingPage.title}`}>
            <Head title={`تعديل صفحة الهبوط: ${landingPage.title}`} />

            <div className="flex flex-col h-[calc(100vh-64px)] -m-6 overflow-hidden">
                {/* Visual Builder Header */}
                <div className="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between z-10 shadow-sm flex-shrink-0">
                    <div className="flex items-center gap-3">
                        <Link href="/admin/landing-pages" className="p-2 hover:bg-gray-100 rounded-xl transition-colors text-gray-500 hover:text-gray-900">
                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </Link>
                        <div>
                            <h2 className="text-lg font-extrabold text-gray-900 leading-none">{data.title}</h2>
                            <a href={landingPage.url} target="_blank" rel="noreferrer" className="text-xs text-orange-600 hover:underline mt-1 block" dir="ltr">
                                {landingPage.url}
                            </a>
                        </div>
                    </div>

                    {/* Viewport Switcher */}
                    <div className="hidden md:flex items-center bg-gray-100 p-1 rounded-xl gap-1">
                        <button
                            onClick={() => setIsMobilePreview(false)}
                            className={`p-2 rounded-lg transition-all ${!isMobilePreview ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-900'}`}
                            title="عرض الكمبيوتر"
                        >
                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </button>
                        <button
                            onClick={() => setIsMobilePreview(true)}
                            className={`p-2 rounded-lg transition-all ${isMobilePreview ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-900'}`}
                            title="عرض الموبايل"
                        >
                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </button>
                    </div>

                    {/* Save Buttons */}
                    <div className="flex items-center gap-2">
                        <button
                            onClick={handleSubmit}
                            disabled={processing}
                            className="px-5 py-2 bg-orange-600 text-white text-sm font-semibold rounded-xl hover:bg-orange-700 transition-colors shadow-md disabled:opacity-50 inline-flex items-center gap-1.5"
                        >
                            {processing ? (
                                <>جاري الحفظ...</>
                            ) : (
                                <>
                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                    </svg>
                                    حفظ التغييرات
                                </>
                            )}
                        </button>
                    </div>
                </div>

                {/* Main Workspace Panels */}
                <div className="flex-1 flex overflow-hidden bg-gray-50">
                    {/* Left Panel: Settings Sidebar */}
                    <div className="w-full md:w-[380px] bg-white border-l border-gray-200 flex flex-col overflow-hidden flex-shrink-0 shadow-sm">
                        {/* Tab Switcher */}
                        <div className="flex border-b border-gray-100 flex-shrink-0 bg-gray-50/50">
                            <button
                                onClick={() => setActiveTab('sections')}
                                className={`flex-1 py-3 text-center text-xs font-bold border-b-2 transition-all ${
                                    activeTab === 'sections' ? 'border-orange-500 text-orange-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-900'
                                }`}
                            >
                                الأقسام والمحتوى
                            </button>
                            <button
                                onClick={() => setActiveTab('settings')}
                                className={`flex-1 py-3 text-center text-xs font-bold border-b-2 transition-all ${
                                    activeTab === 'settings' ? 'border-orange-500 text-orange-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-900'
                                }`}
                            >
                                إعدادات الصفحة
                            </button>
                            <button
                                onClick={() => setActiveTab('seo')}
                                className={`flex-1 py-3 text-center text-xs font-bold border-b-2 transition-all ${
                                    activeTab === 'seo' ? 'border-orange-500 text-orange-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-900'
                                }`}
                            >
                                SEO ومشاركات
                            </button>
                        </div>

                        {/* Sidebar Scrollable Panel */}
                        <div className="flex-1 overflow-y-auto p-5 space-y-6">
                            {activeTab === 'sections' && (
                                <div className="space-y-6">
                                    {/* Sections list */}
                                    <div>
                                        <div className="flex items-center justify-between mb-3">
                                            <h3 className="text-sm font-bold text-gray-900">أقسام صفحة الهبوط</h3>
                                            <button
                                                type="button"
                                                onClick={() => setShowAddModal(true)}
                                                className="text-xs text-orange-600 hover:text-orange-700 font-bold flex items-center gap-1 bg-orange-50 px-2.5 py-1.5 rounded-lg hover:bg-orange-100 transition-colors"
                                            >
                                                <span>➕ إضافة قسم</span>
                                            </button>
                                        </div>

                                        <SectionsList
                                            sections={sections}
                                            selectedSectionIndex={selectedSectionIndex}
                                            setSelectedSectionIndex={setSelectedSectionIndex}
                                            handleMoveSection={handleMoveSection}
                                            handleDeleteSection={handleDeleteSection}
                                        />
                                    </div>

                                    {/* Selected Section Editor */}
                                    {sections[selectedSectionIndex] && (
                                        <div className="pt-6 border-t border-gray-100">
                                            <div className="flex items-center justify-between mb-4">
                                                <h3 className="text-sm font-extrabold text-gray-900">
                                                    تعديل قسم: {sections[selectedSectionIndex].type.toUpperCase()}
                                                </h3>
                                                <span className="text-[10px] bg-orange-100 text-orange-800 font-bold px-2 py-0.5 rounded-md uppercase">
                                                    الترتيب: {selectedSectionIndex + 1}
                                                </span>
                                            </div>
                                            <SectionEditor
                                                section={sections[selectedSectionIndex]}
                                                onChange={(updated) => handleUpdateSection(selectedSectionIndex, updated)}
                                                products={products}
                                            />
                                        </div>
                                    )}
                                </div>
                            )}

                            {activeTab === 'settings' && (
                                <div className="space-y-5">
                                    <div>
                                        <label className="block text-xs font-bold text-gray-700 mb-1.5">العنوان الرئيسي</label>
                                        <input
                                            type="text"
                                            value={data.title}
                                            onChange={(e) => setData('title', e.target.value)}
                                            className="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 text-sm focus:outline-none"
                                        />
                                        {errors.title && <p className="text-red-500 text-xs mt-1">{errors.title}</p>}
                                    </div>

                                    <div>
                                        <label className="block text-xs font-bold text-gray-700 mb-1.5">الرابط المخصص (Slug)</label>
                                        <input
                                            type="text"
                                            value={data.slug}
                                            onChange={(e) => setData('slug', e.target.value)}
                                            className="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 text-sm focus:outline-none text-left"
                                            dir="ltr"
                                        />
                                        {errors.slug && <p className="text-red-500 text-xs mt-1">{errors.slug}</p>}
                                    </div>

                                    <div>
                                        <label className="block text-xs font-bold text-gray-700 mb-1.5">قالب الصفحة الأساسي</label>
                                        <select
                                            value={data.template}
                                            onChange={(e) => setData('template', e.target.value)}
                                            className="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 text-sm focus:outline-none"
                                        >
                                            {Object.entries(templates).map(([key, template]) => (
                                                <option key={key} value={key}>{template.name}</option>
                                            ))}
                                        </select>
                                    </div>

                                    <div>
                                        <label className="block text-xs font-bold text-gray-700 mb-1.5">مظهر صفحة الهبوط (Color Theme)</label>
                                        <select
                                            value={data.color_theme}
                                            onChange={(e) => setData('color_theme', e.target.value)}
                                            className="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 text-sm focus:outline-none"
                                        >
                                            <option value="light">الوضع الفاتح (Light Mode) - افتراضي</option>
                                            <option value="dark">الوضع الداكن (Dark Mode)</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label className="block text-xs font-bold text-gray-700 mb-1.5">Facebook Pixel ID (اختياري)</label>
                                        <input
                                            type="text"
                                            value={data.facebook_pixel_id}
                                            onChange={(e) => setData('facebook_pixel_id', e.target.value)}
                                            placeholder="مثال: 123456789012345"
                                            className="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 text-sm focus:outline-none"
                                        />
                                        {errors.facebook_pixel_id && <p className="text-red-500 text-xs mt-1">{errors.facebook_pixel_id}</p>}
                                    </div>

                                    <div>
                                        <label className="block text-xs font-bold text-gray-700 mb-1.5">TikTok Pixel ID (اختياري)</label>
                                        <input
                                            type="text"
                                            value={data.tiktok_pixel_id}
                                            onChange={(e) => setData('tiktok_pixel_id', e.target.value)}
                                            placeholder="مثال: C1234567890ABCDE"
                                            className="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 text-sm focus:outline-none"
                                        />
                                        {errors.tiktok_pixel_id && <p className="text-red-500 text-xs mt-1">{errors.tiktok_pixel_id}</p>}
                                    </div>

                                    <div>
                                        <label className="block text-xs font-bold text-gray-700 mb-1.5">أكواد CSS مخصصة (اختياري)</label>
                                        <textarea
                                            value={data.custom_css}
                                            onChange={(e) => setData('custom_css', e.target.value)}
                                            placeholder="/* اكتب كود CSS مخصص هنا لتغيير مظهر الصفحة */"
                                            className="w-full h-32 px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 text-xs focus:outline-none font-mono"
                                            dir="ltr"
                                        />
                                    </div>
                                </div>
                            )}

                            {activeTab === 'seo' && (
                                <div className="space-y-5">
                                    <div>
                                        <label className="block text-xs font-bold text-gray-700 mb-1.5">عنوان السيو (SEO Title)</label>
                                        <input
                                            type="text"
                                            value={data.seo_title}
                                            onChange={(e) => setData('seo_title', e.target.value)}
                                            placeholder="العنوان الظاهر بمحركات البحث"
                                            className="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 text-sm focus:outline-none"
                                        />
                                    </div>

                                    <div>
                                        <label className="block text-xs font-bold text-gray-700 mb-1.5">وصف السيو (SEO Description)</label>
                                        <textarea
                                            value={data.seo_description}
                                            onChange={(e) => setData('seo_description', e.target.value)}
                                            placeholder="وصف مختصر ومحفز للظهور بمحركات البحث ومشاركات السوشيال ميديا"
                                            className="w-full h-24 px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 text-sm focus:outline-none"
                                        />
                                    </div>

                                    <div>
                                        <label className="block text-xs font-bold text-gray-700 mb-1.5">رابط الصورة المصغرة لمشاركات السوشيال ميديا</label>
                                        <input
                                            type="text"
                                            value={data.featured_image}
                                            onChange={(e) => setData('featured_image', e.target.value)}
                                            placeholder="https://example.com/image.jpg"
                                            className="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 text-sm focus:outline-none text-left"
                                            dir="ltr"
                                        />
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Right Panel: Live Storefront Mockup Preview */}
                    <div className="flex-1 hidden md:flex items-center justify-center p-8 overflow-y-auto">
                        <div className={`transition-all duration-300 ${isMobilePreview ? 'w-[375px] h-[760px] border-[12px] border-gray-900 rounded-[48px] shadow-2xl' : 'w-full h-full border border-gray-200 rounded-2xl shadow-md'} bg-white overflow-hidden flex flex-col relative`}>
                            {/* Device camera notch for mobile */}
                            {isMobilePreview && (
                                <div className="absolute top-0 left-1/2 -translate-x-1/2 w-32 h-6 bg-gray-900 rounded-b-2xl z-20 flex items-center justify-center">
                                    <div className="w-3 h-3 rounded-full bg-gray-800"></div>
                                </div>
                            )}

                            {/* Frame content */}
                            <div className="flex-1 overflow-y-auto custom-scrollbar bg-gray-900">
                                <LivePreview sections={sections} products={products} template={data.template} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Modals */}
            {showAddModal && (
                <AddSectionModal
                    onClose={() => setShowAddModal(false)}
                    onAdd={handleAddSection}
                />
            )}
        </MerchantLayout>
    );
}
