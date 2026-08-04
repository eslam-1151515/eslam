import React from 'react';

const sectionTypes = [
    {
        type: 'hero',
        title: '🎯 البانر الرئيسي (Hero)',
        description: 'عنوان جذاب، وصف قصير، وصورة مع زر شراء رئيسي.',
    },
    {
        type: 'countdown',
        title: '⏱ عداد تنازلي (Countdown)',
        description: 'عداد تنازلي ترويجي لخلق حالة من الاستعجال والندرة.',
    },
    {
        type: 'product_showcase',
        title: '📦 عرض المنتج الرئيسي (Showcase)',
        description: 'شرح تفاصيل المنتج، صوره، ومميزاته مع إمكانية الشراء المباشر.',
    },
    {
        type: 'features',
        title: '✨ مزايا وخصائص المنتج (Features)',
        description: 'شبكة تعرض مميزات وفوائد المنتج مع أيقونات معبرة.',
    },
    {
        type: 'testimonials',
        title: '⭐ آراء وتقييمات العملاء (Reviews)',
        description: 'عرض تعليقات وصور وتقييمات العملاء لزيادة المصداقية.',
    },
    {
        type: 'cta',
        title: '📢 دعوة اتخاذ قرار شراء (CTA)',
        description: 'بانر ملفت للانتباه لطلب المنتج الآن مع تفاصيل الشحن والضمان.',
    },
];

export default function AddSectionModal({ onClose, onAdd }) {
    return (
        <div className="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div className="bg-white rounded-3xl w-full max-w-2xl max-h-[85vh] overflow-hidden flex flex-col shadow-2xl animate-in zoom-in-95 duration-200">
                {/* Header */}
                <div className="p-6 border-b border-gray-100 flex items-center justify-between flex-shrink-0">
                    <h3 className="text-lg font-extrabold text-gray-900">إضافة قسم جديد لصفحة الهبوط</h3>
                    <button
                        onClick={onClose}
                        className="p-2 hover:bg-gray-100 rounded-xl text-gray-400 hover:text-gray-900 transition-colors"
                    >
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {/* Grid Content */}
                <div className="p-6 overflow-y-auto grid grid-cols-1 sm:grid-cols-2 gap-4 flex-1">
                    {sectionTypes.map((item) => (
                        <div
                            key={item.type}
                            onClick={() => onAdd(item.type)}
                            className="p-5 border border-gray-200 rounded-2xl hover:border-orange-500 hover:bg-orange-50/10 cursor-pointer transition-all flex flex-col justify-between group shadow-sm hover:shadow-md"
                        >
                            <div>
                                <h4 className="font-bold text-gray-900 text-sm group-hover:text-orange-600 transition-colors">{item.title}</h4>
                                <p className="text-xs text-gray-500 mt-1.5 leading-relaxed">{item.description}</p>
                            </div>
                            <div className="mt-4 flex items-center justify-end text-xs text-orange-600 font-bold gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <span>إضافة القسم</span>
                                <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
}
