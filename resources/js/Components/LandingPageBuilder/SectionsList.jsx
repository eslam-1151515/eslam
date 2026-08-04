import React from 'react';

export default function SectionsList({
    sections,
    selectedSectionIndex,
    setSelectedSectionIndex,
    handleMoveSection,
    handleDeleteSection,
}) {
    return (
        <div className="space-y-2">
            {sections.map((section, index) => {
                const isSelected = selectedSectionIndex === index;

                return (
                    <div
                        key={index}
                        onClick={() => setSelectedSectionIndex(index)}
                        className={`flex items-center justify-between p-3 rounded-xl border cursor-pointer transition-all ${
                            isSelected
                                ? 'bg-orange-50/50 border-orange-400 shadow-sm'
                                : 'bg-white border-gray-200 hover:border-gray-300'
                        }`}
                    >
                        <div className="flex items-center gap-3 min-w-0">
                            {/* Drag Indicator/Index */}
                            <span className={`w-5 h-5 flex items-center justify-center rounded-lg text-[10px] font-extrabold ${
                                isSelected ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-500'
                            }`}>
                                {index + 1}
                            </span>
                            
                            {/* Title & Icon representation */}
                            <div className="truncate">
                                <span className={`text-xs font-bold ${isSelected ? 'text-orange-950' : 'text-gray-900'}`}>
                                    {section.type === 'hero' && '🎯 البانر الرئيسي (Hero)'}
                                    {section.type === 'countdown' && '⏱ عداد تنازلي (Countdown)'}
                                    {section.type === 'product_showcase' && '📦 عرض منتج واحد (Showcase)'}
                                    {section.type === 'features' && '✨ مزايا المنتج (Features)'}
                                    {section.type === 'testimonials' && '⭐ آراء العملاء (Reviews)'}
                                    {section.type === 'cta' && '📢 دعوة اتخاذ قرار (CTA)'}
                                </span>
                            </div>
                        </div>

                        {/* Reorder/Delete Actions */}
                        <div className="flex items-center gap-1.5" onClick={(e) => e.stopPropagation()}>
                            {/* Move Up */}
                            <button
                                type="button"
                                disabled={index === 0}
                                onClick={() => handleMoveSection(index, 'up')}
                                className="p-1 hover:bg-gray-100 rounded-md text-gray-400 hover:text-gray-900 disabled:opacity-30 disabled:hover:bg-transparent"
                                title="تحريك للأعلى"
                            >
                                <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 15l7-7 7 7" />
                                </svg>
                            </button>

                            {/* Move Down */}
                            <button
                                type="button"
                                disabled={index === sections.length - 1}
                                onClick={() => handleMoveSection(index, 'down')}
                                className="p-1 hover:bg-gray-100 rounded-md text-gray-400 hover:text-gray-900 disabled:opacity-30 disabled:hover:bg-transparent"
                                title="تحريك للأسفل"
                            >
                                <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            {/* Delete */}
                            <button
                                type="button"
                                onClick={() => handleDeleteSection(index)}
                                className="p-1 hover:bg-red-50 text-gray-400 hover:text-red-600 rounded-md transition-colors ml-1"
                                title="حذف القسم"
                            >
                                <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                );
            })}
        </div>
    );
}
