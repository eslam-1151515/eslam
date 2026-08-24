import React, { useState } from 'react';
import { Head, Link } from '@inertiajs/react';

export default function Pricing({ plans = [], faqs = [], comparisonCategories = [] }) {
    const [billing, setBilling] = useState('yearly');
    const [activeFaq, setActiveFaq] = useState(null);

    const toggleFaq = (index) => {
        setActiveFaq(activeFaq === index ? null : index);
    };

    return (
        <div className="min-h-screen flex flex-col bg-slate-950 text-slate-100 font-sans selection:bg-indigo-500 selection:text-white relative overflow-x-hidden" dir="rtl">
            <Head title="الباقات والأسعار - فاست أوردر (Order Saif)" />

            {/* Glowing Background Elements */}
            <div className="absolute -top-32 -right-32 w-[450px] h-[450px] bg-indigo-600/15 rounded-full blur-3xl pointer-events-none animate-pulse"></div>
            <div className="absolute top-1/3 -left-32 w-[450px] h-[450px] bg-pink-600/15 rounded-full blur-3xl pointer-events-none animate-pulse" style={{ animationDelay: '2s' }}></div>
            <div className="absolute bottom-10 right-1/4 w-[450px] h-[450px] bg-purple-600/15 rounded-full blur-3xl pointer-events-none animate-pulse" style={{ animationDelay: '1s' }}></div>

            {/* Navigation Header */}
            <header className="sticky top-0 z-50 bg-slate-950/75 backdrop-blur-md border-b border-white/10 transition-all duration-300">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                    <div className="flex items-center gap-3">
                        <Link href="/" className="flex items-center gap-2.5 group">
                            <div className="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-pink-500 flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition-transform">
                                <span className="text-white font-black text-xl">⚡</span>
                            </div>
                            <span className="text-2xl font-black tracking-tight text-white">
                                فاست <span className="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">أوردر</span>
                            </span>
                        </Link>
                    </div>

                    <nav className="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-300">
                        <Link href="/" className="hover:text-white transition-colors">الرئيسية</Link>
                        <a href="#pricing-cards" className="text-indigo-400 font-bold">الباقات والأسعار</a>
                        <a href="#comparison" className="hover:text-white transition-colors">مقارنة المميزات</a>
                        <a href="#faq" className="hover:text-white transition-colors">الأسئلة الشائعة</a>
                    </nav>

                    <div className="flex items-center gap-3">
                        <Link href="/login" className="px-4 py-2 rounded-xl text-slate-300 hover:text-white hover:bg-white/5 font-semibold text-sm transition-all">
                            تسجيل الدخول
                        </Link>
                        <Link href="/register" className="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-pink-600 hover:from-indigo-500 hover:to-pink-500 text-white font-bold text-sm shadow-lg shadow-indigo-500/25 transition-all transform hover:-translate-y-0.5">
                            ابدأ تجربتك المجانية
                        </Link>
                    </div>
                </div>
            </header>

            {/* Hero Section */}
            <section className="relative pt-16 pb-12 text-center px-4 sm:px-6 lg:px-8 z-10">
                <div className="max-w-3xl mx-auto">
                    <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-sm font-bold mb-6 animate-bounce">
                        <span>✨</span> 0% عمولة على مبيعاتك + تجربة مجانية 14 يوماً
                    </div>
                    <h1 className="text-4xl sm:text-5xl lg:text-6xl font-black text-white leading-tight mb-6">
                        باقات وأسعار مصممة <br className="hidden sm:block" />
                        <span className="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent"> لنمو متجرك الإلكتروني بسرعة</span>
                    </h1>
                    <p className="text-lg sm:text-xl text-slate-400 font-medium max-w-2xl mx-auto mb-10 leading-relaxed">
                        اختر الباقة المناسبة لحجم تجارتك وابدأ البيع خلال دقائق. جميع الباقات تشمل تجربة مجانية بدون بطاقة ائتمانية وبدون أي رسوم أو عمولات خفية.
                    </p>

                    {/* Monthly / Annual Toggle Button */}
                    <div className="inline-flex items-center justify-center p-1.5 rounded-2xl bg-slate-900/90 border border-slate-800 shadow-xl max-w-md mx-auto">
                        <button
                            onClick={() => setBilling('monthly')}
                            className={`px-6 py-3 rounded-xl text-sm md:text-base transition-all duration-300 ${
                                billing === 'monthly'
                                    ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30 font-bold'
                                    : 'text-slate-400 hover:text-white font-medium'
                            }`}>
                            دفع شهري
                        </button>
                        <button
                            onClick={() => setBilling('yearly')}
                            className={`px-6 py-3 rounded-xl text-sm md:text-base transition-all duration-300 flex items-center gap-2 ${
                                billing === 'yearly'
                                    ? 'bg-gradient-to-r from-indigo-600 to-pink-600 text-white shadow-lg shadow-pink-500/30 font-bold'
                                    : 'text-slate-400 hover:text-white font-medium'
                            }`}>
                            <span>دفع سنوي</span>
                            <span className="px-2.5 py-0.5 text-xs font-black bg-emerald-500 text-slate-950 rounded-full animate-bounce">
                                وفر 17% 🎁
                            </span>
                        </button>
                    </div>
                    <p className="text-xs text-slate-500 mt-3 font-medium">
                        * عند اختيار الدفع السنوي تحصل على شهرين مجاناً في جميع الباقات
                    </p>
                </div>
            </section>

            {/* Pricing Cards Section */}
            <section id="pricing-cards" className="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto relative z-10 w-full">
                <div className="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
                    {plans.map((plan, index) => {
                        const isPopular = plan.is_popular || plan.slug === 'pro';
                        const priceMonthly = plan.price_monthly || 0;
                        const priceYearly = plan.price_yearly || 0;
                        const trialDays = plan.trial_days || 14;
                        const limits = plan.limits || {};
                        const features = limits.features || [];
                        const maxProducts = limits.max_products;
                        const maxOrders = limits.max_orders;

                        return (
                            <div
                                key={plan.id || index}
                                className={`relative flex flex-col rounded-3xl transition-all duration-300 hover:-translate-y-2 p-8 ${
                                    isPopular
                                        ? 'bg-gradient-to-br from-indigo-950/80 via-slate-900/90 to-purple-950/80 border-2 border-indigo-500 shadow-2xl shadow-indigo-500/25 md:-translate-y-4 md:scale-105 z-20'
                                        : 'bg-slate-900/65 border border-white/10 hover:border-slate-700 z-10'
                                }`}>
                                {isPopular && (
                                    <div className="absolute -top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-indigo-500 to-pink-500 text-white font-black text-xs uppercase px-4 py-1.5 rounded-full shadow-lg shadow-indigo-500/50 tracking-wide flex items-center gap-1.5">
                                        <span>⭐</span> الأكثر طلباً واقتراحاً
                                    </div>
                                )}

                                {/* Plan Header */}
                                <div className="mb-6">
                                    <div className="flex items-center justify-between mb-3">
                                        <h3 className="text-2xl font-black text-white">{plan.name}</h3>
                                        {isPopular && <span className="w-3 h-3 rounded-full bg-emerald-400 animate-ping"></span>}
                                    </div>
                                    <p className="text-sm text-slate-400 min-h-[40px] leading-relaxed">{plan.description}</p>
                                </div>

                                {/* Price Display */}
                                <div className="my-6 p-6 rounded-2xl bg-slate-900/60 border border-slate-800/80 text-center">
                                    <div className="flex items-baseline justify-center gap-1.5">
                                        <span className="text-4xl sm:text-5xl font-black text-white tracking-tight">
                                            {billing === 'monthly'
                                                ? Math.round(priceMonthly).toLocaleString()
                                                : Math.round(priceYearly / 12).toLocaleString()}
                                        </span>
                                        <span className="text-slate-400 font-bold text-lg">ر.س / شهرياً</span>
                                    </div>
                                    <div className="text-xs text-indigo-400 mt-2 font-bold flex items-center justify-center gap-1">
                                        <span>🎁</span>
                                        {billing === 'monthly' ? (
                                            <span>تدفع شهرياً (بدون التزام سنوي)</span>
                                        ) : (
                                            <span>توفير سنوي: تدفع {Math.round(priceYearly).toLocaleString()} ر.س مرة واحدة سنوياً</span>
                                        )}
                                    </div>
                                </div>

                                {/* Key Limits */}
                                <div className="grid grid-cols-2 gap-3 mb-6">
                                    <div className="p-3 rounded-xl bg-white/5 border border-white/5 text-center">
                                        <span className="text-xs text-slate-400 block mb-1">عدد المنتجات</span>
                                        <span className="font-bold text-white text-sm">
                                            {maxProducts >= 9999 ? 'غير محدود 🚀' : `${maxProducts?.toLocaleString() || '0'} منتج`}
                                        </span>
                                    </div>
                                    <div className="p-3 rounded-xl bg-white/5 border border-white/5 text-center">
                                        <span className="text-xs text-slate-400 block mb-1">الطلبات الشهرية</span>
                                        <span className="font-bold text-white text-sm">
                                            {maxOrders >= 9999 ? 'غير محدود 🚀' : `${maxOrders?.toLocaleString() || '0'} طلب`}
                                        </span>
                                    </div>
                                </div>

                                {/* Features List */}
                                <div className="flex-1 mb-8">
                                    <span className="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-4">
                                        مميزات تشملها الباقة:
                                    </span>
                                    <ul className="space-y-3.5">
                                        {features.map((feature, fIndex) => (
                                            <li key={fIndex} className="flex items-start gap-3 text-sm text-slate-300">
                                                <div
                                                    className={`w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 ${
                                                        isPopular
                                                            ? 'bg-indigo-500/20 text-indigo-400'
                                                            : 'bg-emerald-500/10 text-emerald-400'
                                                    }`}>
                                                    ✓
                                                </div>
                                                <span className="leading-snug">{feature}</span>
                                            </li>
                                        ))}
                                    </ul>
                                </div>

                                {/* CTA Button */}
                                <div>
                                    <Link
                                        href={`/register?plan=${plan.slug || ''}`}
                                        className={`w-full py-4 px-6 rounded-2xl font-black text-base text-center block transition-all duration-300 shadow-lg transform hover:-translate-y-0.5 ${
                                            isPopular
                                                ? 'bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 hover:from-indigo-500 hover:to-pink-500 text-white shadow-indigo-500/30'
                                                : 'bg-slate-800 hover:bg-slate-700 text-white hover:text-white border border-slate-700 hover:border-slate-600'
                                        }`}>
                                        ابدأ تجربتك المجانية لمدة {trialDays} يوماً ←
                                    </Link>
                                    <p className="text-center text-[11px] text-slate-500 mt-2.5 font-medium">
                                        🛡️ بدون بطاقة ائتمانية • إلغاء في أي وقت
                                    </p>
                                </div>
                            </div>
                        );
                    })}
                </div>
            </section>

            {/* Feature Comparison Table Section */}
            <section id="comparison" className="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto relative z-10 w-full">
                <div className="text-center max-w-3xl mx-auto mb-12">
                    <h2 className="text-3xl sm:text-4xl font-black text-white mb-4">
                        جدول مقارنة الباقات <span className="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">والخصائص التفصيلية</span>
                    </h2>
                    <p className="text-slate-400 text-lg">
                        قارن بين جميع المميزات والخصائص لتحديد الباقة المثالية التي تلبي تطلعات متجرك وتحقق أهدافك
                    </p>
                </div>

                <div className="bg-slate-900/65 backdrop-blur-md rounded-3xl overflow-hidden border border-slate-800 shadow-2xl">
                    <div className="overflow-x-auto">
                        <table className="w-full text-right border-collapse">
                            <thead>
                                <tr className="border-b border-slate-800 bg-slate-900/80">
                                    <th className="py-6 px-6 text-slate-300 font-bold text-base md:text-lg w-1/3">الخصائص والمميزات</th>
                                    {plans.map((plan, idx) => {
                                        const isPopular = plan.is_popular || plan.slug === 'pro';
                                        return (
                                            <th
                                                key={idx}
                                                className={`py-6 px-6 text-center w-2/9 ${
                                                    isPopular ? 'bg-indigo-950/40 border-x border-indigo-500/30' : ''
                                                }`}>
                                                <div className="text-lg md:text-xl font-black text-white mb-1">{plan.name}</div>
                                                {isPopular ? (
                                                    <span className="inline-block px-2.5 py-0.5 bg-indigo-500 text-white font-bold text-xs rounded-full">
                                                        الأكثر طلباً ⭐
                                                    </span>
                                                ) : (
                                                    <span className="text-xs text-slate-400 font-normal block">
                                                        {plan.slug === 'basic' ? ' للمبتدئين' : 'للشركات'}
                                                    </span>
                                                )}
                                            </th>
                                        );
                                    })}
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-800/60">
                                {comparisonCategories.map((group, gIdx) => (
                                    <React.Fragment key={gIdx}>
                                        <tr className="bg-slate-900/50">
                                            <td colSpan={4} className="py-4 px-6 text-indigo-400 font-black text-base md:text-lg bg-indigo-950/20 border-y border-indigo-500/20">
                                                ❖ {group.category}
                                            </td>
                                        </tr>
                                        {group.features.map((feat, fIdx) => (
                                            <tr key={fIdx} className="hover:bg-slate-900/40 transition-colors">
                                                <td className="py-4 px-6 font-semibold text-slate-300 text-sm md:text-base border-l border-slate-800/40">
                                                    {feat.name}
                                                </td>
                                                {['basic', 'pro', 'enterprise'].map((planSlug, pIdx) => {
                                                    const val = feat[planSlug];
                                                    const isProCol = planSlug === 'pro';
                                                    return (
                                                        <td
                                                            key={pIdx}
                                                            className={`py-4 px-6 text-center font-bold text-sm md:text-base ${
                                                                isProCol
                                                                    ? 'bg-indigo-950/20 font-black text-indigo-300 border-x border-indigo-500/20'
                                                                    : 'text-slate-300'
                                                            }`}>
                                                            {val === true ? (
                                                                <div className="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-400">
                                                                    ✓
                                                                </div>
                                                            ) : val === false ? (
                                                                <div className="inline-flex items-center justify-center w-8 h-8 rounded-full bg-rose-500/10 text-rose-500/60">
                                                                    ✕
                                                                </div>
                                                            ) : (
                                                                <span className={isProCol ? 'text-white' : ''}>{val}</span>
                                                            )}
                                                        </td>
                                                    );
                                                })}
                                            </tr>
                                        ))}
                                    </React.Fragment>
                                ))}
                            </tbody>
                            <tfoot>
                                <tr className="bg-slate-900/90 border-t border-slate-800">
                                    <td className="py-6 px-6 font-bold text-slate-400">اختر الباقة وابدأ الآن</td>
                                    {plans.map((plan, idx) => {
                                        const isPopular = plan.is_popular || plan.slug === 'pro';
                                        return (
                                            <td
                                                key={idx}
                                                className={`py-6 px-6 text-center ${
                                                    isPopular ? 'bg-indigo-950/40 border-x border-indigo-500/30' : ''
                                                }`}>
                                                <Link
                                                    href={`/register?plan=${plan.slug || ''}`}
                                                    className={`inline-block py-2.5 px-6 rounded-xl font-bold text-sm transition-all shadow-md ${
                                                        isPopular
                                                            ? 'bg-indigo-600 hover:bg-indigo-500 text-white shadow-indigo-500/30'
                                                            : 'bg-slate-800 hover:bg-slate-700 text-slate-200'
                                                    }`}>
                                                    ابدأ التجربة مجاناً
                                                </Link>
                                            </td>
                                        );
                                    })}
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </section>

            {/* Pricing FAQ Section */}
            <section id="faq" className="py-16 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto relative z-10 w-full">
                <div className="text-center mb-12">
                    <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-pink-500/10 text-pink-400 text-xs font-bold mb-3">
                        <span>❓</span> إجابات واضحة وشفافة
                    </div>
                    <h2 className="text-3xl sm:text-4xl font-black text-white mb-4">
                        الأسئلة الشائعة <span className="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">حول الباقات والأسعار</span>
                    </h2>
                    <p className="text-slate-400 text-base sm:text-lg">
                        إليك كل ما تحتاج معرفته عن الاشتراك، فترات التجربة، وطرق الدفع في منصة فاست أوردر
                    </p>
                </div>

                <div className="space-y-4">
                    {faqs.map((faq, index) => (
                        <div
                            key={index}
                            className="bg-slate-900/65 backdrop-blur-md rounded-2xl overflow-hidden border border-slate-800 transition-colors hover:border-slate-700">
                            <button
                                onClick={() => toggleFaq(index)}
                                className="w-full py-5 px-6 text-right flex items-center justify-between gap-4 focus:outline-none">
                                <span className="font-bold text-base sm:text-lg text-white flex items-center gap-3">
                                    <span className="w-7 h-7 rounded-lg bg-indigo-500/10 text-indigo-400 flex items-center justify-center text-sm font-black flex-shrink-0">
                                        {index + 1}
                                    </span>
                                    {faq.question}
                                </span>
                                <div
                                    className={`w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 flex-shrink-0 transition-transform duration-300 ${
                                        activeFaq === index ? 'rotate-180 bg-indigo-600 text-white' : ''
                                    }`}>
                                    ▼
                                </div>
                            </button>
                            {activeFaq === index && (
                                <div className="px-6 pb-6 pt-2 text-slate-300 text-sm sm:text-base leading-relaxed border-t border-slate-800/60 font-medium">
                                    <p className="pr-10">{faq.answer}</p>
                                </div>
                            )}
                        </div>
                    ))}
                </div>

                {/* FAQ Help Box */}
                <div className="mt-12 p-8 rounded-3xl bg-gradient-to-r from-indigo-900/40 via-purple-900/40 to-pink-900/40 border border-indigo-500/30 text-center relative overflow-hidden">
                    <h3 className="text-xl font-bold text-white mb-2">هل لديك سؤال آخر لم تجد إجابته هنا؟</h3>
                    <p className="text-slate-300 text-sm mb-6 max-w-xl mx-auto font-medium">
                        فريق الدعم الفني لدينا متاح على مدار الساعة للإجابة على جميع استفساراتك ومساعدتك في اختيار الباقة الأنسب لمتجرك.
                    </p>
                    <div className="flex flex-wrap items-center justify-center gap-4">
                        <a href="https://wa.me/966000000000" target="_blank" rel="noreferrer" className="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm shadow-lg shadow-emerald-600/30 flex items-center gap-2 transition-all">
                            <span>💬</span> تواصل معنا عبر الواتساب
                        </a>
                        <a href="mailto:support@OrderSaif.com" className="px-6 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-sm flex items-center gap-2 transition-all border border-slate-700">
                            <span>✉️</span> راسلنا عبر البريد
                        </a>
                    </div>
                </div>
            </section>

            {/* Bottom CTA Section */}
            <section className="py-16 px-4 sm:px-6 lg:px-8 relative z-10">
                <div className="max-w-6xl mx-auto rounded-3xl bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 p-8 sm:p-14 text-center relative overflow-hidden shadow-2xl shadow-indigo-600/20">
                    <div className="absolute -right-20 -bottom-20 w-80 h-80 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
                    <div className="absolute -left-20 -top-20 w-80 h-80 rounded-full bg-black/10 blur-2xl pointer-events-none"></div>

                    <h2 className="text-3xl sm:text-4xl md:text-5xl font-black text-white mb-4 relative z-10 leading-tight">
                        جاهز لإطلاق متجرك الإلكتروني ومضاعفة مبيعاتك؟ 🚀
                    </h2>
                    <p className="text-indigo-100 text-lg sm:text-xl font-medium max-w-2xl mx-auto mb-8 relative z-10">
                        انضم لآلاف المتاجر الناجحة على فاست أوردر. ابدأ تجربتك المجانية لمدة 14 يوماً الآن دون أي مخاطرة وبدون عمولات على مبيعاتك!
                    </p>
                    <div className="flex flex-wrap items-center justify-center gap-4 relative z-10">
                        <Link href="/register" className="px-8 py-4 rounded-2xl bg-white text-slate-950 hover:bg-slate-100 font-black text-base shadow-xl transition-all transform hover:-translate-y-1">
                            ابدأ تجربتك المجانية الآن ←
                        </Link>
                        <a href="#pricing-cards" className="px-8 py-4 rounded-2xl bg-black/30 hover:bg-black/40 text-white border border-white/20 font-bold text-base backdrop-blur-md transition-all">
                            استعراض الباقات مرة أخرى
                        </a>
                    </div>
                </div>
            </section>

            {/* Footer */}
            <footer className="mt-auto border-t border-slate-900 bg-slate-950/80 py-8 px-4 text-center text-slate-500 text-sm z-10">
                <div className="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div className="flex items-center gap-2">
                        <span className="font-bold text-white">فاست أوردر (Order Saif)</span>
                        <span>• منصة التجارة الإلكترونية الأسرع والأسهل في الوطن العربي</span>
                    </div>
                    <div>
                        &copy; {new Date().getFullYear()} جميع الحقوق محفوظة لمنصة فاست أوردر.
                    </div>
                </div>
            </footer>
        </div>
    );
}
