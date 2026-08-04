import React, { useState, useEffect } from 'react';
import { Head, useForm, router, usePage } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

export default function StaffIndex({ staff, rolesList, permissionsList, filters }) {
    const { flash } = usePage().props;
    const [search, setSearch] = useState(filters?.q || '');
    const [showFormModal, setShowFormModal] = useState(false);
    const [showDeleteModal, setShowDeleteModal] = useState(false);
    const [editingStaff, setEditingStaff] = useState(null);
    const [staffToDelete, setStaffToDelete] = useState(null);

    // Form helper
    const { data, setData, post, put, processing, errors, reset, clearErrors } = useForm({
        name: '',
        email: '',
        password: '',
        role: 'staff',
        permissions: [],
        is_active: true,
    });

    // Default permissions mapping for quick selection when changing roles
    const defaultRolePermissions = {
        manager: [
            'view_products', 'create_products', 'edit_products', 'delete_products',
            'view_categories', 'create_categories', 'edit_categories', 'delete_categories',
            'view_orders', 'edit_orders', 'delete_orders',
            'view_settings', 'edit_settings',
            'view_banners', 'edit_banners'
        ],
        product_manager: [
            'view_products', 'create_products', 'edit_products', 'delete_products',
            'view_categories', 'create_categories', 'edit_categories', 'delete_categories'
        ],
        order_manager: [
            'view_orders', 'edit_orders'
        ],
        staff: [
            'view_products', 'view_categories', 'view_orders', 'edit_orders'
        ]
    };

    const handleSearch = (e) => {
        e.preventDefault();
        router.get('/admin/staff', { q: search }, { preserveState: true, replace: true });
    };

    const openCreateModal = () => {
        reset();
        clearErrors();
        setEditingStaff(null);
        // Default permissions for staff role
        setData({
            name: '',
            email: '',
            password: '',
            role: 'staff',
            permissions: defaultRolePermissions.staff,
            is_active: true,
        });
        setShowFormModal(true);
    };

    const openEditModal = (member) => {
        clearErrors();
        setEditingStaff(member);
        setData({
            name: member.name || '',
            email: member.email || '',
            password: '', // Leave blank unless changing
            role: member.role || 'staff',
            permissions: member.permissions || [],
            is_active: member.is_active,
        });
        setShowFormModal(true);
    };

    // Auto-update permissions when role changes
    const handleRoleChange = (roleSlug) => {
        setData(prevData => ({
            ...prevData,
            role: roleSlug,
            permissions: defaultRolePermissions[roleSlug] || []
        }));
    };

    const handlePermissionToggle = (permissionSlug) => {
        const currentPermissions = [...data.permissions];
        const index = currentPermissions.indexOf(permissionSlug);
        if (index > -1) {
            currentPermissions.splice(index, 1);
        } else {
            currentPermissions.push(permissionSlug);
        }
        setData('permissions', currentPermissions);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        if (editingStaff) {
            put(`/admin/staff/${editingStaff.id}`, {
                onSuccess: () => {
                    setShowFormModal(false);
                    reset();
                },
            });
        } else {
            post('/admin/staff', {
                onSuccess: () => {
                    setShowFormModal(false);
                    reset();
                },
            });
        }
    };

    const confirmDelete = (member) => {
        setStaffToDelete(member);
        setShowDeleteModal(true);
    };

    const handleDelete = () => {
        if (!staffToDelete) return;
        router.delete(`/admin/staff/${staffToDelete.id}`, {
            onSuccess: () => {
                setShowDeleteModal(false);
                setStaffToDelete(null);
            }
        });
    };

    // Group permissions by group name for presentation
    const groupedPermissions = permissionsList.reduce((groups, item) => {
        const group = item.group || 'أخرى';
        if (!groups[group]) {
            groups[group] = [];
        }
        groups[group].push(item);
        return groups;
    }, {});

    // Role display names & styles
    const roleMeta = {
        manager: { label: 'مدير النظام', color: 'bg-purple-50 text-purple-700 border-purple-200' },
        product_manager: { label: 'مدير المنتجات', color: 'bg-emerald-50 text-emerald-700 border-emerald-200' },
        order_manager: { label: 'مدير الطلبات', color: 'bg-amber-50 text-amber-700 border-amber-200' },
        staff: { label: 'موظف عام', color: 'bg-gray-50 text-gray-700 border-gray-200' }
    };

    return (
        <MerchantLayout title="إدارة الموظفين والصلاحيات">
            <Head title="الموظفين والصلاحيات" />

            <div className="space-y-6 dir-rtl text-right font-sans">
                {/* Header */}
                <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 className="text-2xl font-bold text-gray-900">الموظفين والصلاحيات</h2>
                        <p className="text-sm text-gray-500 mt-1">
                            إدارة حسابات الموظفين، الأدوار، وصلاحيات الوصول الخاصة بمتجرك.
                        </p>
                    </div>
                    <button
                        onClick={openCreateModal}
                        className="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 active:scale-95 transition-all shadow-sm shadow-indigo-100"
                    >
                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        إضافة موظف جديد
                    </button>
                </div>

                {/* Filters */}
                <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                    <form onSubmit={handleSearch} className="flex flex-col sm:flex-row gap-3">
                        <div className="flex-1 relative">
                            <svg className="absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input
                                type="text"
                                placeholder="ابحث باسم الموظف أو البريد الإلكتروني..."
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                className="w-full pr-10 pl-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                            />
                        </div>
                        <div className="flex gap-2">
                            <button
                                type="submit"
                                className="px-6 py-2.5 bg-gray-900 text-white rounded-xl text-sm font-semibold hover:bg-gray-800 transition-colors shadow-sm"
                            >
                                بحث
                            </button>
                            {filters?.q && (
                                <button
                                    type="button"
                                    onClick={() => {
                                        setSearch('');
                                        router.get('/admin/staff', {}, { replace: true });
                                    }}
                                    className="px-4 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-colors"
                                >
                                    إلغاء التصفية
                                </button>
                            )}
                        </div>
                    </form>
                </div>

                {/* Notifications */}
                {flash?.success && (
                    <div className="p-4 bg-green-50 border-r-4 border-green-500 rounded-xl text-green-800 text-sm font-semibold flex items-center gap-2 shadow-sm animate-fade-in">
                        <svg className="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{flash.success}</span>
                    </div>
                )}
                {flash?.error && (
                    <div className="p-4 bg-red-50 border-r-4 border-red-500 rounded-xl text-red-800 text-sm font-semibold flex items-center gap-2 shadow-sm animate-fade-in">
                        <svg className="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>{flash.error}</span>
                    </div>
                )}

                {/* Staff List Table */}
                <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    {staff.length === 0 ? (
                        <div className="p-12 text-center">
                            <div className="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                                <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h3 className="text-base font-bold text-gray-800">لا يوجد موظفين حالياً</h3>
                            <p className="text-sm text-gray-400 mt-1">اضغط على زر "إضافة موظف جديد" للبدء بالعمل.</p>
                        </div>
                    ) : (
                        <div className="overflow-x-auto">
                            <table className="w-full text-sm text-right">
                                <thead className="bg-gray-50 text-gray-600 font-semibold border-b border-gray-100">
                                    <tr>
                                        <th scope="col" className="px-6 py-4">الموظف</th>
                                        <th scope="col" className="px-6 py-4">الدور الوظيفي</th>
                                        <th scope="col" className="px-6 py-4">تاريخ الإضافة</th>
                                        <th scope="col" className="px-6 py-4 text-center">حالة النشاط</th>
                                        <th scope="col" className="px-6 py-4 text-left">العمليات</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-50 text-gray-700">
                                    {staff.map((member) => {
                                        const role = roleMeta[member.role] || { label: member.role, color: 'bg-gray-100 text-gray-800' };
                                        return (
                                            <tr key={member.id} className="hover:bg-gray-50/50 transition-colors">
                                                <td className="px-6 py-4">
                                                    <div className="flex items-center gap-3">
                                                        <div className="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 font-bold flex items-center justify-center text-sm shadow-inner">
                                                            {member.name.substring(0, 2).toUpperCase()}
                                                        </div>
                                                        <div>
                                                            <div className="font-bold text-gray-900">{member.name}</div>
                                                            <div className="text-xs text-gray-400 mt-0.5">{member.email}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td className="px-6 py-4">
                                                    <span className={`inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold border ${role.color}`}>
                                                        {role.label}
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4 text-gray-500">
                                                    {member.created_at || 'غير متوفر'}
                                                </td>
                                                <td className="px-6 py-4 text-center">
                                                    <span className={`inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold ${
                                                        member.is_active 
                                                            ? 'bg-green-50 text-green-700 border border-green-100' 
                                                            : 'bg-red-50 text-red-700 border border-red-100'
                                                    }`}>
                                                        <span className={`w-1.5 h-1.5 rounded-full ${member.is_active ? 'bg-green-500' : 'bg-red-500'}`} />
                                                        {member.is_active ? 'نشط' : 'معطل'}
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4 text-left">
                                                    <div className="flex items-center justify-end gap-2">
                                                        <button
                                                            onClick={() => openEditModal(member)}
                                                            className="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                                            title="تعديل الصلاحيات والدور"
                                                        >
                                                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                            </svg>
                                                        </button>
                                                        <button
                                                            onClick={() => confirmDelete(member)}
                                                            className="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                                            title="إلغاء ارتباط الموظف وحذفه"
                                                        >
                                                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        );
                                    })}
                                </tbody>
                            </table>
                        </div>
                    )}
                </div>

                {/* Form Modal (Create & Edit) */}
                {showFormModal && (
                    <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-950/60 backdrop-blur-sm transition-opacity duration-300">
                        <div className="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col shadow-2xl animate-scale-up">
                            {/* Modal Header */}
                            <div className="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                                <div>
                                    <h3 className="text-lg font-bold text-gray-900">
                                        {editingStaff ? 'تعديل بيانات الموظف وصلاحياته' : 'إضافة موظف جديد'}
                                    </h3>
                                    <p className="text-xs text-gray-400 mt-1">
                                        قم بتعبئة البيانات التالية وتحديد الصلاحيات المطلوبة للموظف.
                                    </p>
                                </div>
                                <button
                                    onClick={() => setShowFormModal(false)}
                                    className="p-1 text-gray-400 hover:bg-gray-100 rounded-lg transition-colors"
                                >
                                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            {/* Modal Body */}
                            <form onSubmit={handleSubmit} className="flex-1 overflow-y-auto p-6 space-y-6">
                                {/* Basic Info Grid */}
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {/* Name */}
                                    <div className="space-y-1.5">
                                        <label className="text-xs font-bold text-gray-700">الاسم الكامل</label>
                                        <input
                                            type="text"
                                            value={data.name}
                                            onChange={(e) => setData('name', e.target.value)}
                                            className="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all"
                                            placeholder="أحمد محمد"
                                            required
                                        />
                                        {errors.name && <p className="text-xs text-rose-600 font-semibold">{errors.name}</p>}
                                    </div>

                                    {/* Email */}
                                    <div className="space-y-1.5">
                                        <label className="text-xs font-bold text-gray-700">البريد الإلكتروني</label>
                                        <input
                                            type="email"
                                            value={data.email}
                                            onChange={(e) => setData('email', e.target.value)}
                                            className="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-left"
                                            placeholder="name@store.com"
                                            required
                                        />
                                        {errors.email && <p className="text-xs text-rose-600 font-semibold">{errors.email}</p>}
                                    </div>

                                    {/* Password */}
                                    <div className="space-y-1.5">
                                        <label className="text-xs font-bold text-gray-700">
                                            كلمة المرور {editingStaff && <span className="text-gray-400 font-normal">(اتركه فارغاً بعدم التغيير)</span>}
                                        </label>
                                        <input
                                            type="password"
                                            value={data.password}
                                            onChange={(e) => setData('password', e.target.value)}
                                            className="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-left"
                                            placeholder={editingStaff ? '••••••••' : 'كلمة مرور قوية لا تقل عن 8 أحرف'}
                                            required={!editingStaff}
                                        />
                                        {errors.password && <p className="text-xs text-rose-600 font-semibold">{errors.password}</p>}
                                    </div>

                                    {/* Active status */}
                                    <div className="space-y-1.5 flex flex-col justify-end">
                                        <div className="flex items-center gap-3 py-3">
                                            <input
                                                type="checkbox"
                                                id="is_active_toggle"
                                                checked={data.is_active}
                                                onChange={(e) => setData('is_active', e.target.checked)}
                                                className="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                            />
                                            <label htmlFor="is_active_toggle" className="text-sm font-bold text-gray-800 cursor-pointer">
                                                حساب نشط (تمكين الموظف من تسجيل الدخول)
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <hr className="border-gray-100" />

                                {/* Role Selection */}
                                <div className="space-y-3">
                                    <label className="text-xs font-bold text-gray-700 block">الدور الوظيفي الرئيسي</label>
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        {rolesList.map((role) => (
                                            <div
                                                key={role.slug}
                                                onClick={() => handleRoleChange(role.slug)}
                                                className={`p-3.5 border-2 rounded-xl cursor-pointer transition-all flex flex-col gap-1.5 ${
                                                    data.role === role.slug
                                                        ? 'border-indigo-600 bg-indigo-50/30'
                                                        : 'border-gray-100 hover:border-gray-200 hover:bg-gray-50/20'
                                                }`}
                                            >
                                                <div className="flex items-center gap-2">
                                                    <span className={`w-3.5 h-3.5 rounded-full border flex items-center justify-center ${
                                                        data.role === role.slug ? 'border-indigo-600 bg-indigo-600' : 'border-gray-300 bg-white'
                                                    }`}>
                                                        {data.role === role.slug && <span className="w-1.5 h-1.5 rounded-full bg-white" />}
                                                    </span>
                                                    <span className="text-sm font-bold text-gray-900">{role.name}</span>
                                                </div>
                                                <p className="text-xs text-gray-500 leading-normal pr-5">{role.description}</p>
                                            </div>
                                        ))}
                                    </div>
                                    {errors.role && <p className="text-xs text-rose-600 font-semibold">{errors.role}</p>}
                                </div>

                                <hr className="border-gray-100" />

                                {/* Permissions Matrix */}
                                <div className="space-y-4">
                                    <div>
                                        <label className="text-sm font-bold text-gray-800">صلاحيات الوصول التفصيلية</label>
                                        <p className="text-xs text-gray-400 mt-1">
                                            يمكنك تعديل الصلاحيات المخصصة لهذا الموظف بشكل مستقل عن دوره الافتراضي.
                                        </p>
                                    </div>

                                    <div className="space-y-5">
                                        {Object.entries(groupedPermissions).map(([groupName, permissions]) => (
                                            <div key={groupName} className="border border-gray-100 rounded-xl p-4 bg-gray-50/30">
                                                <h4 className="text-xs font-bold text-indigo-600 mb-3 border-r-2 border-indigo-500 pr-2">
                                                    {groupName}
                                                </h4>
                                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                                    {permissions.map((permission) => (
                                                        <label
                                                            key={permission.slug}
                                                            className="flex items-start gap-3 cursor-pointer group"
                                                        >
                                                            <input
                                                                type="checkbox"
                                                                checked={data.permissions.includes(permission.slug)}
                                                                onChange={() => handlePermissionToggle(permission.slug)}
                                                                className="mt-0.5 w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                                            />
                                                            <div className="text-xs">
                                                                <span className="font-semibold text-gray-800 group-hover:text-indigo-600 transition-colors">
                                                                    {permission.name}
                                                                </span>
                                                            </div>
                                                        </label>
                                                    ))}
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                </div>

                                {/* Modal Footer */}
                                <div className="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                                    <button
                                        type="button"
                                        onClick={() => setShowFormModal(false)}
                                        className="px-5 py-2.5 border border-gray-200 text-gray-700 rounded-xl text-sm font-bold hover:bg-gray-50 active:scale-95 transition-all"
                                    >
                                        إلغاء
                                    </button>
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 active:scale-95 transition-all disabled:opacity-50 disabled:pointer-events-none shadow-sm shadow-indigo-100"
                                    >
                                        {processing ? 'جاري الحفظ...' : (editingStaff ? 'تعديل الموظف' : 'إضافة الموظف')}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                )}

                {/* Delete Confirmation Modal */}
                {showDeleteModal && (
                    <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-950/60 backdrop-blur-sm transition-opacity duration-300">
                        <div className="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl animate-scale-up space-y-4">
                            <div className="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mx-auto shadow-inner">
                                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div className="text-center space-y-1.5">
                                <h3 className="text-lg font-bold text-gray-900">هل أنت متأكد من الحذف؟</h3>
                                <p className="text-sm text-gray-500 leading-normal">
                                    سيتم إلغاء ارتباط الموظف <span className="font-bold text-gray-900">"{staffToDelete?.name}"</span> بهذا المتجر نهائياً وتجميد وصوله للبيانات. لا يمكن التراجع عن هذا الإجراء.
                                </p>
                            </div>
                            <div className="flex items-center justify-center gap-3 pt-2">
                                <button
                                    onClick={() => setShowDeleteModal(false)}
                                    className="flex-1 py-2.5 border border-gray-200 text-gray-700 rounded-xl text-sm font-bold hover:bg-gray-50 transition-all"
                                >
                                    تراجع
                                </button>
                                <button
                                    onClick={handleDelete}
                                    className="flex-1 py-2.5 bg-rose-600 text-white rounded-xl text-sm font-bold hover:bg-rose-700 active:scale-95 transition-all shadow-sm shadow-rose-100"
                                >
                                    تأكيد الحذف
                                </button>
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </MerchantLayout>
    );
}
