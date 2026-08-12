import React, { useState } from 'react';
import { Link, usePage, router } from '@inertiajs/react';

export default function SuperAdminLayout({ children }) {
    const { auth } = usePage().props;
    const [isSidebarOpen, setIsSidebarOpen] = useState(true);

    return (
        <div className="min-h-screen bg-gray-100 flex flex-row" dir="rtl">
            {/* Sidebar (Fixed position sticky to screen height) */}
            <aside className={`${isSidebarOpen ? 'w-64' : 'w-20'} bg-indigo-900 text-white transition-all duration-300 flex flex-col shrink-0 sticky top-0 h-screen overflow-y-auto`}>
                {/* Logo Area */}
                <div className="h-16 flex items-center justify-between px-4 border-b border-indigo-800 shrink-0">
                    <span className={`font-bold text-lg whitespace-nowrap overflow-hidden ${!isSidebarOpen && 'hidden'}`}>
                        لوحة التحكم السوبر
                    </span>
                    <button 
                        onClick={() => setIsSidebarOpen(!isSidebarOpen)} 
                        className="text-white hover:text-indigo-200 focus:outline-none"
                    >
                        <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                {/* Nav Links */}
                <nav className="flex-1 py-4 space-y-1">
                    <Link href="/dashboard" className="flex items-center px-4 py-2.5 text-indigo-100 hover:bg-indigo-800 hover:text-white transition-colors">
                        <svg className="w-6 h-6 mr-3 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span className={`${!isSidebarOpen && 'hidden'} transition-opacity duration-300`}>الرئيسية</span>
                    </Link>

                    <Link href="/tenants" className="flex items-center px-4 py-2.5 text-indigo-100 hover:bg-indigo-800 hover:text-white transition-colors">
                        <svg className="w-6 h-6 mr-3 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span className={`${!isSidebarOpen && 'hidden'} transition-opacity duration-300`}>المتاجر</span>
                    </Link>

                    <Link href="/subscriptions/plans" className="flex items-center px-4 py-2.5 text-indigo-100 hover:bg-indigo-800 hover:text-white transition-colors">
                        <svg className="w-6 h-6 mr-3 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span className={`${!isSidebarOpen && 'hidden'} transition-opacity duration-300`}>خطط الاشتراك</span>
                    </Link>

                    <Link href="/subscriptions/receipts" className="flex items-center px-4 py-2.5 text-indigo-100 hover:bg-indigo-800 hover:text-white transition-colors">
                        <svg className="w-6 h-6 mr-3 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4M7.833 8.667H14.17m-6.337 3.5h6.337m-6.337 3.5h6.337M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span className={`${!isSidebarOpen && 'hidden'} transition-opacity duration-300`}>إيصالات الدفع</span>
                    </Link>

                    <Link href="/backups" className="flex items-center px-4 py-2.5 text-indigo-100 hover:bg-indigo-800 hover:text-white transition-colors">
                        <svg className="w-6 h-6 mr-3 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        <span className={`${!isSidebarOpen && 'hidden'} transition-opacity duration-300`}>النسخ الاحتياطي</span>
                    </Link>

                    <Link href="/support-contacts" className="flex items-center px-4 py-2.5 text-indigo-100 hover:bg-indigo-800 hover:text-white transition-colors">
                        <svg className="w-6 h-6 mr-3 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span className={`${!isSidebarOpen && 'hidden'} transition-opacity duration-300`}>أرقام الدعم الفني</span>
                    </Link>

                    <Link href="/tutorials" className="flex items-center px-4 py-2.5 text-indigo-100 hover:bg-indigo-800 hover:text-white transition-colors">
                        <svg className="w-6 h-6 mr-3 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <span className={`${!isSidebarOpen && 'hidden'} transition-opacity duration-300`}>الشروحات والدروس</span>
                    </Link>
                </nav>

                {/* User Profile */}
                <div className="p-4 border-t border-indigo-800 flex items-center justify-start mt-auto shrink-0">
                    <div className="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-center overflow-hidden font-bold">
                        {auth?.user?.name ? auth.user.name.substring(0, 2).toUpperCase() : 'AD'}
                    </div>
                    <div className={`mr-3 ${!isSidebarOpen && 'hidden'}`}>
                        <p className="text-sm font-semibold whitespace-nowrap overflow-hidden text-ellipsis">{auth?.user?.name || 'Super Admin'}</p>
                        <button
                            onClick={(e) => {
                                e.preventDefault();
                                router.post('/logout', {}, {
                                    onSuccess: () => { window.location.href = '/login'; },
                                    onError: () => { window.location.href = '/login'; }
                                });
                            }}
                            className="text-xs text-indigo-300 hover:text-white block text-right"
                        >
                            تسجيل الخروج
                        </button>
                    </div>
                </div>
            </aside>

            {/* Main Content Area */}
            <div className="flex-1 flex flex-col min-w-0">
                {/* Header */}
                <header className="h-16 bg-white border-b flex items-center justify-between px-6 shrink-0 sticky top-0 z-10 shadow-xs">
                    <h1 className="text-xl font-semibold text-gray-800">لوحة تحكم المدير العام</h1>
                    <div className="flex items-center space-x-4 space-x-reverse">
                        <span className="text-sm text-gray-500">{auth?.user?.email}</span>
                    </div>
                </header>

                {/* Main Content */}
                <main className="flex-1 p-6 bg-gray-50">
                    {children}
                </main>
            </div>
        </div>
    );
}
