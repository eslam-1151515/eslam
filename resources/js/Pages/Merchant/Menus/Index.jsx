import React, { useState, useEffect } from 'react';
import { Head, useForm, router, usePage } from '@inertiajs/react';
import MerchantLayout from '@/Layouts/MerchantLayout';

export default function MenusIndex({ menus, categories, products, staticPages }) {
    const { flash } = usePage().props;
    const [selectedMenu, setSelectedMenu] = useState(null);
    const [menuItems, setMenuItems] = useState([]);
    
    // State for temporary menu item being added or edited
    const [itemType, setItemType] = useState('custom'); // custom, category, product, page
    const [itemValue, setItemValue] = useState('');
    const [itemTitleAr, setItemTitleAr] = useState('');
    const [itemTitleEn, setItemTitleEn] = useState('');
    const [itemTargetBlank, setItemTargetBlank] = useState(false);
    const [editingItemIndex, setEditingItemIndex] = useState(null); // null means adding, number/string path means editing
    const [editingItemParentIndex, setEditingItemParentIndex] = useState(null); // if editing a child item

    // Main Menu Form
    const { data, setData, post, put, processing, errors, reset, clearErrors } = useForm({
        name: '',
        location: 'header', // header, footer, sidebar, custom
        is_active: true,
        items: [],
    });

    // Load selected menu into form and state
    const handleEditMenu = (menu) => {
        clearErrors();
        setSelectedMenu(menu);
        setData({
            name: menu.name || '',
            location: menu.location || 'header',
            is_active: menu.is_active ?? true,
            items: menu.items || [],
        });
        setMenuItems(JSON.parse(JSON.stringify(menu.items || []))); // deep copy
        resetItemForm();
    };

    const handleCreateNew = () => {
        clearErrors();
        setSelectedMenu(null);
        setData({
            name: '',
            location: 'header',
            is_active: true,
            items: [],
        });
        setMenuItems([]);
        resetItemForm();
    };

    const resetItemForm = () => {
        setItemType('custom');
        setItemValue('');
        setItemTitleAr('');
        setItemTitleEn('');
        setItemTargetBlank(false);
        setEditingItemIndex(null);
        setEditingItemParentIndex(null);
    };

    // Auto-fill titles when selecting category, product or page
    useEffect(() => {
        if (itemType === 'category' && itemValue) {
            const cat = categories.find(c => String(c.id) === String(itemValue));
            if (cat) {
                setItemTitleAr(cat.name || '');
                setItemTitleEn(cat.name_en || '');
            }
        } else if (itemType === 'product' && itemValue) {
            const prod = products.find(p => String(p.id) === String(itemValue));
            if (prod) {
                setItemTitleAr(prod.name || '');
                setItemTitleEn(prod.name || '');
            }
        } else if (itemType === 'page' && itemValue) {
            const page = staticPages.find(p => p.url === itemValue);
            if (page) {
                // simple splitting or clean mapping
                const cleanName = page.name.split(' (')[0];
                setItemTitleAr(cleanName);
                setItemTitleEn(cleanName);
            }
        }
    }, [itemType, itemValue]);

    // Handle adding or updating item in the hierarchy
    const handleSaveItem = (e) => {
        e.preventDefault();
        if (!itemTitleAr && !itemTitleEn) {
            alert('الرجاء إدخال عنوان الرابط بالعربية أو الإنجليزية');
            return;
        }
        if (!itemValue) {
            alert('الرجاء تحديد وجهة الرابط');
            return;
        }

        // Generate target url based on type
        let finalUrl = itemValue;
        if (itemType === 'category') {
            finalUrl = `/category-products.html?id=${itemValue}`;
        } else if (itemType === 'product') {
            finalUrl = `/product.html?id=${itemValue}`;
        }

        const newItem = {
            title_ar: itemTitleAr || itemTitleEn,
            title_en: itemTitleEn || itemTitleAr,
            type: itemType,
            value: itemValue,
            url: finalUrl,
            target_blank: itemTargetBlank,
            children: []
        };

        const updatedItems = [...menuItems];

        if (editingItemIndex !== null) {
            // We are editing an item
            if (editingItemParentIndex !== null) {
                // Editing a child item
                const parent = updatedItems[editingItemParentIndex];
                const oldChildren = parent.children || [];
                newItem.children = oldChildren[editingItemIndex]?.children || [];
                oldChildren[editingItemIndex] = newItem;
                parent.children = oldChildren;
            } else {
                // Editing a parent item
                newItem.children = updatedItems[editingItemIndex].children || [];
                updatedItems[editingItemIndex] = newItem;
            }
        } else {
            // We are adding a new item
            if (editingItemParentIndex !== null) {
                // Adding a child to parent index editingItemParentIndex
                if (!updatedItems[editingItemParentIndex].children) {
                    updatedItems[editingItemParentIndex].children = [];
                }
                updatedItems[editingItemParentIndex].children.push(newItem);
            } else {
                // Adding a top-level item
                updatedItems.push(newItem);
            }
        }

        setMenuItems(updatedItems);
        setData('items', updatedItems);
        resetItemForm();
    };

    // Prepare item for edit
    const startEditItem = (parentIdx, childIdx = null) => {
        setEditingItemParentIndex(parentIdx);
        if (childIdx !== null) {
            // editing a child
            const childItem = menuItems[parentIdx].children[childIdx];
            setEditingItemIndex(childIdx);
            setItemType(childItem.type);
            setItemValue(childItem.value);
            setItemTitleAr(childItem.title_ar);
            setItemTitleEn(childItem.title_en);
            setItemTargetBlank(childItem.target_blank || false);
        } else {
            // editing a parent
            const parentItem = menuItems[parentIdx];
            setEditingItemIndex(parentIdx);
            setEditingItemParentIndex(null); // no parent
            setItemType(parentItem.type);
            setItemValue(parentItem.value);
            setItemTitleAr(parentItem.title_ar);
            setItemTitleEn(parentItem.title_en);
            setItemTargetBlank(parentItem.target_blank || false);
        }
    };

    // Remove item
    const handleDeleteItem = (parentIdx, childIdx = null) => {
        if (!confirm('هل أنت متأكد من حذف هذا الرابط؟')) return;
        const updated = [...menuItems];
        if (childIdx !== null) {
            updated[parentIdx].children.splice(childIdx, 1);
        } else {
            updated.splice(parentIdx, 1);
        }
        setMenuItems(updated);
        setData('items', updated);
        resetItemForm();
    };

    // Move item in order
    const moveItem = (parentIdx, childIdx = null, direction = 'up') => {
        const updated = [...menuItems];
        if (childIdx !== null) {
            const children = [...updated[parentIdx].children];
            const index = childIdx;
            const targetIndex = direction === 'up' ? index - 1 : index + 1;
            
            if (targetIndex >= 0 && targetIndex < children.length) {
                const temp = children[index];
                children[index] = children[targetIndex];
                children[targetIndex] = temp;
                updated[parentIdx].children = children;
            }
        } else {
            const index = parentIdx;
            const targetIndex = direction === 'up' ? index - 1 : index + 1;

            if (targetIndex >= 0 && targetIndex < updated.length) {
                const temp = updated[index];
                updated[index] = updated[targetIndex];
                updated[targetIndex] = temp;
            }
        }
        setMenuItems(updated);
        setData('items', updated);
    };

    // Handle form submit to backend
    const handleSubmitMenuForm = (e) => {
        e.preventDefault();
        if (selectedMenu) {
            put(`/admin/menus/${selectedMenu.id}`, {
                onSuccess: () => {
                    handleCreateNew();
                }
            });
        } else {
            post('/admin/menus', {
                onSuccess: () => {
                    handleCreateNew();
                }
            });
        }
    };

    const handleDeleteMenu = (menu) => {
        if (!confirm(`هل أنت متأكد من حذف القائمة "${menu.name}" بالكامل؟`)) return;
        router.delete(`/admin/menus/${menu.id}`, {
            onSuccess: () => {
                if (selectedMenu?.id === menu.id) {
                    handleCreateNew();
                }
            }
        });
    };

    const handleToggleStatus = (menu) => {
        router.patch(`/admin/menus/${menu.id}/toggle`, {}, {
            preserveScroll: true
        });
    };

    return (
        <MerchantLayout title="نظام القوائم المخصصة">
            <Head title="القوائم المخصصة" />

            <div className="space-y-6">
                {/* Header */}
                <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 className="text-2xl font-extrabold text-gray-900">القوائم المخصصة</h2>
                        <p className="text-sm text-gray-500 mt-1">
                            قم ببناء وإدارة قوائم التنقل للهيدر والفوتر والـ sidebar في متجرك وتخصيص الروابط.
                        </p>
                    </div>
                    {selectedMenu && (
                        <button
                            onClick={handleCreateNew}
                            className="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-800 text-white rounded-xl text-sm font-semibold hover:bg-gray-700 transition-all shadow-md"
                        >
                            إنشاء قائمة جديدة
                        </button>
                    )}
                </div>

                {/* Flash Messages */}
                {flash?.success && (
                    <div className="p-4 bg-green-50 border-r-4 border-green-500 rounded-xl text-green-800 text-sm font-medium flex items-center gap-3 shadow-sm">
                        <span className="flex items-center justify-center w-5 h-5 bg-green-100 rounded-full text-green-600 text-xs">✓</span>
                        {flash.success}
                    </div>
                )}

                <div className="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                    {/* Left Panel: Menus List */}
                    <div className="lg:col-span-4 space-y-4">
                        <div className="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                            <h3 className="text-lg font-bold text-gray-900 mb-4 border-b pb-2">القوائم الحالية</h3>
                            
                            {menus.length > 0 ? (
                                <div className="space-y-3">
                                    {menus.map((menu) => (
                                        <div 
                                            key={menu.id} 
                                            className={`p-4 rounded-xl border transition-all cursor-pointer ${
                                                selectedMenu?.id === menu.id 
                                                    ? 'bg-orange-50 border-orange-400 shadow-sm' 
                                                    : 'bg-gray-50 border-gray-100 hover:border-gray-300'
                                            }`}
                                            onClick={() => handleEditMenu(menu)}
                                        >
                                            <div className="flex justify-between items-start">
                                                <div>
                                                    <h4 className="font-bold text-gray-800">{menu.name}</h4>
                                                    <span className="inline-block mt-1 text-xs px-2 py-0.5 rounded bg-gray-200 text-gray-600">
                                                        {menu.location === 'header' ? 'الهيدر' : 
                                                         menu.location === 'footer' ? 'الفوتر' : 
                                                         menu.location === 'sidebar' ? 'الـ Sidebar' : 'مخصص'}
                                                    </span>
                                                </div>
                                                <div className="flex items-center gap-2" onClick={(e) => e.stopPropagation()}>
                                                    <button
                                                        onClick={() => handleToggleStatus(menu)}
                                                        className={`p-1 rounded transition-colors ${
                                                            menu.is_active 
                                                                ? 'text-green-600 hover:bg-green-50' 
                                                                : 'text-gray-400 hover:bg-gray-100'
                                                        }`}
                                                        title={menu.is_active ? 'نشط' : 'غير نشط'}
                                                    >
                                                        {menu.is_active ? (
                                                            <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                                            </svg>
                                                        ) : (
                                                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        )}
                                                    </button>
                                                    <button
                                                        onClick={() => handleDeleteMenu(menu)}
                                                        className="p-1 text-red-600 hover:bg-red-50 rounded"
                                                        title="حذف"
                                                    >
                                                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            <div className="mt-3 text-xs text-gray-500 flex justify-between">
                                                <span>روابط: {menu.items?.length || 0}</span>
                                                <span>{menu.created_at}</span>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            ) : (
                                <div className="py-6 text-center text-gray-400 text-sm">
                                    لا توجد قوائم حالية، ابدأ بإنشاء واحدة.
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Right Panel: Menu Form and Items Builder */}
                    <div className="lg:col-span-8 space-y-6">
                        <form onSubmit={handleSubmitMenuForm} className="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 space-y-6">
                            <h3 className="text-lg font-bold text-gray-900 border-b pb-2">
                                {selectedMenu ? `تعديل القائمة: ${selectedMenu.name}` : 'إنشاء قائمة جديدة'}
                            </h3>

                            {/* Basic Details */}
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label className="block text-sm font-semibold text-gray-700 mb-1">اسم القائمة *</label>
                                    <input 
                                        type="text" 
                                        value={data.name} 
                                        onChange={e => setData('name', e.target.value)}
                                        className="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-400 focus:border-transparent focus:outline-none"
                                        placeholder="مثال: قائمة الهيدر الرئيسية"
                                    />
                                    {errors.name && <p className="text-red-500 text-xs mt-1">{errors.name}</p>}
                                </div>

                                <div>
                                    <label className="block text-sm font-semibold text-gray-700 mb-1">موقع العرض *</label>
                                    <select 
                                        value={data.location} 
                                        onChange={e => setData('location', e.target.value)}
                                        className="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-400 focus:border-transparent focus:outline-none"
                                    >
                                        <option value="header">الهيدر الرئيسي (Header)</option>
                                        <option value="footer">الفوتر السفلي (Footer)</option>
                                        <option value="sidebar">الـ Sidebar</option>
                                        <option value="custom">موقع مخصص</option>
                                    </select>
                                    {errors.location && <p className="text-red-500 text-xs mt-1">{errors.location}</p>}
                                </div>

                                <div className="flex items-center h-full pt-6">
                                    <label className="inline-flex items-center gap-2 cursor-pointer">
                                        <input 
                                            type="checkbox" 
                                            checked={data.is_active} 
                                            onChange={e => setData('is_active', e.target.checked)}
                                            className="rounded text-orange-600 focus:ring-orange-400 border-gray-300 w-4 h-4"
                                        />
                                        <span className="text-sm font-semibold text-gray-700">تفعيل هذه القائمة فوراً</span>
                                    </label>
                                </div>
                            </div>

                            {/* Menu Items Hierarchy Visualizer */}
                            <div className="border border-gray-200 rounded-xl p-4 bg-gray-50">
                                <h4 className="text-sm font-bold text-gray-700 mb-3 flex items-center justify-between">
                                    <span>هيكلية القائمة ورابطها</span>
                                    <span className="text-xs text-gray-500 font-normal">يمكنك إعادة ترتيب الروابط أو إضافة روابط فرعية</span>
                                </h4>

                                {menuItems.length > 0 ? (
                                    <div className="space-y-3">
                                        {menuItems.map((item, parentIdx) => (
                                            <div key={parentIdx} className="space-y-2">
                                                {/* Parent Item */}
                                                <div className="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-xl shadow-xs group">
                                                    <div className="flex items-center gap-2">
                                                        <span className="text-gray-400 cursor-move">⋮⋮</span>
                                                        <div>
                                                            <div className="font-bold text-gray-800 text-sm flex items-center gap-2">
                                                                <span>{item.title_ar}</span>
                                                                <span className="text-xs text-gray-400 font-normal">/ {item.title_en}</span>
                                                            </div>
                                                            <div className="text-xs text-gray-500 truncate max-w-xs">{item.url}</div>
                                                        </div>
                                                    </div>

                                                    <div className="flex items-center gap-1.5 opacity-90 group-hover:opacity-100 transition-opacity">
                                                        <button 
                                                            type="button" 
                                                            onClick={() => setEditingItemParentIndex(parentIdx)}
                                                            className="px-2 py-1 text-xs bg-orange-50 text-orange-700 border border-orange-100 rounded-lg hover:bg-orange-100"
                                                            title="إضافة رابط فرعي"
                                                        >
                                                            + فرعي
                                                        </button>
                                                        <button 
                                                            type="button" 
                                                            onClick={() => moveItem(parentIdx, null, 'up')}
                                                            disabled={parentIdx === 0}
                                                            className="p-1 text-gray-600 hover:bg-gray-100 rounded disabled:opacity-30"
                                                        >
                                                            ▲
                                                        </button>
                                                        <button 
                                                            type="button" 
                                                            onClick={() => moveItem(parentIdx, null, 'down')}
                                                            disabled={parentIdx === menuItems.length - 1}
                                                            className="p-1 text-gray-600 hover:bg-gray-100 rounded disabled:opacity-30"
                                                        >
                                                            ▼
                                                        </button>
                                                        <button 
                                                            type="button" 
                                                            onClick={() => startEditItem(parentIdx)}
                                                            className="p-1 text-blue-600 hover:bg-blue-50 rounded"
                                                        >
                                                            ✏️
                                                        </button>
                                                        <button 
                                                            type="button" 
                                                            onClick={() => handleDeleteItem(parentIdx)}
                                                            className="p-1 text-red-600 hover:bg-red-50 rounded"
                                                        >
                                                            🗑️
                                                        </button>
                                                    </div>
                                                </div>

                                                {/* Child Items */}
                                                {item.children && item.children.length > 0 && (
                                                    <div className="mr-6 pl-2 border-r-2 border-dashed border-gray-300 space-y-2">
                                                        {item.children.map((child, childIdx) => (
                                                            <div key={childIdx} className="flex items-center justify-between p-2.5 bg-white border border-gray-200 rounded-lg shadow-xs group">
                                                                <div className="flex items-center gap-2">
                                                                    <span className="text-gray-400">└─</span>
                                                                    <div>
                                                                        <div className="font-semibold text-gray-800 text-xs">
                                                                            {child.title_ar} <span className="text-gray-400 font-normal">/ {child.title_en}</span>
                                                                        </div>
                                                                        <div className="text-[10px] text-gray-500 truncate max-w-xs">{child.url}</div>
                                                                    </div>
                                                                </div>

                                                                <div className="flex items-center gap-1 opacity-90 group-hover:opacity-100 transition-opacity">
                                                                    <button 
                                                                        type="button" 
                                                                        onClick={() => moveItem(parentIdx, childIdx, 'up')}
                                                                        disabled={childIdx === 0}
                                                                        className="p-0.5 text-gray-500 hover:bg-gray-100 rounded disabled:opacity-30 text-xs"
                                                                    >
                                                                        ▲
                                                                    </button>
                                                                    <button 
                                                                        type="button" 
                                                                        onClick={() => moveItem(parentIdx, childIdx, 'down')}
                                                                        disabled={childIdx === item.children.length - 1}
                                                                        className="p-0.5 text-gray-500 hover:bg-gray-100 rounded disabled:opacity-30 text-xs"
                                                                    >
                                                                        ▼
                                                                    </button>
                                                                    <button 
                                                                        type="button" 
                                                                        onClick={() => startEditItem(parentIdx, childIdx)}
                                                                        className="p-0.5 text-blue-600 hover:bg-blue-50 rounded text-xs"
                                                                    >
                                                                        ✏️
                                                                    </button>
                                                                    <button 
                                                                        type="button" 
                                                                        onClick={() => handleDeleteItem(parentIdx, childIdx)}
                                                                        className="p-0.5 text-red-600 hover:bg-red-50 rounded text-xs"
                                                                    >
                                                                        🗑️
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        ))}
                                                    </div>
                                                )}
                                            </div>
                                        ))}
                                    </div>
                                ) : (
                                    <div className="py-8 text-center text-gray-400 text-sm bg-white rounded-xl border border-dashed border-gray-300">
                                        لا توجد روابط في القائمة حالياً. استخدم النموذج أدناه لإضافة روابط.
                                    </div>
                                )}
                            </div>

                            {/* Add/Edit Link Item Component Form */}
                            <div className="border border-orange-200 rounded-xl p-4 bg-orange-50/50 space-y-4">
                                <h4 className="text-sm font-bold text-orange-950 flex justify-between items-center">
                                    <span>
                                        {editingItemParentIndex !== null && editingItemIndex === null ? `إضافة رابط فرعي تحت (${menuItems[editingItemParentIndex]?.title_ar})` : 
                                         editingItemIndex !== null ? 'تعديل بيانات الرابط الحالي' : 'إضافة رابط جديد للقائمة'}
                                    </span>
                                    {(editingItemIndex !== null || editingItemParentIndex !== null) && (
                                        <button 
                                            type="button" 
                                            onClick={resetItemForm} 
                                            className="text-xs text-orange-700 hover:underline"
                                        >
                                            إلغاء التعديل / الإضافة للفرع
                                        </button>
                                    )}
                                </h4>

                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {/* Link Type */}
                                    <div>
                                        <label className="block text-xs font-semibold text-gray-700 mb-1">نوع الرابط</label>
                                        <select 
                                            value={itemType} 
                                            onChange={e => {
                                                setItemType(e.target.value);
                                                setItemValue('');
                                            }}
                                            className="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs bg-white focus:outline-none"
                                        >
                                            <option value="custom">رابط مخصص (خارجي أو مخصص)</option>
                                            <option value="category">رابط قسم (Category)</option>
                                            <option value="product">رابط منتج (Product)</option>
                                            <option value="page">رابط صفحة ثابتة (Predefined Page)</option>
                                        </select>
                                    </div>

                                    {/* Link Destination */}
                                    <div>
                                        <label className="block text-xs font-semibold text-gray-700 mb-1">وجهة الرابط *</label>
                                        {itemType === 'custom' && (
                                            <input 
                                                type="text" 
                                                value={itemValue} 
                                                onChange={e => setItemValue(e.target.value)}
                                                className="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs bg-white focus:outline-none"
                                                placeholder="مثال: https://google.com"
                                            />
                                        )}
                                        {itemType === 'category' && (
                                            <select 
                                                value={itemValue} 
                                                onChange={e => setItemValue(e.target.value)}
                                                className="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs bg-white focus:outline-none"
                                            >
                                                <option value="">اختر قسماً...</option>
                                                {categories.map(c => (
                                                    <option key={c.id} value={c.id}>{c.name} ({c.name_en})</option>
                                                ))}
                                            </select>
                                        )}
                                        {itemType === 'product' && (
                                            <select 
                                                value={itemValue} 
                                                onChange={e => setItemValue(e.target.value)}
                                                className="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs bg-white focus:outline-none"
                                            >
                                                <option value="">اختر منتجاً...</option>
                                                {products.map(p => (
                                                    <option key={p.id} value={p.id}>{p.name}</option>
                                                ))}
                                            </select>
                                        )}
                                        {itemType === 'page' && (
                                            <select 
                                                value={itemValue} 
                                                onChange={e => setItemValue(e.target.value)}
                                                className="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs bg-white focus:outline-none"
                                            >
                                                <option value="">اختر صفحة...</option>
                                                {staticPages.map(p => (
                                                    <option key={p.url} value={p.url}>{p.name}</option>
                                                ))}
                                            </select>
                                        )}
                                    </div>
                                </div>

                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {/* Title AR */}
                                    <div>
                                        <label className="block text-xs font-semibold text-gray-700 mb-1">اسم الرابط (بالعربية) *</label>
                                        <input 
                                            type="text" 
                                            value={itemTitleAr} 
                                            onChange={e => setItemTitleAr(e.target.value)}
                                            className="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs bg-white focus:outline-none"
                                            placeholder="مثال: من نحن"
                                        />
                                    </div>

                                    {/* Title EN */}
                                    <div>
                                        <label className="block text-xs font-semibold text-gray-700 mb-1">اسم الرابط (بالإنجليزية) *</label>
                                        <input 
                                            type="text" 
                                            value={itemTitleEn} 
                                            onChange={e => setItemTitleEn(e.target.value)}
                                            className="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs bg-white focus:outline-none"
                                            placeholder="مثال: About Us"
                                        />
                                    </div>
                                </div>

                                <div className="flex items-center justify-between pt-2">
                                    <label className="inline-flex items-center gap-1.5 cursor-pointer">
                                        <input 
                                            type="checkbox" 
                                            checked={itemTargetBlank} 
                                            onChange={e => setItemTargetBlank(e.target.checked)}
                                            className="rounded text-orange-600 focus:ring-orange-400 border-gray-300 w-3.5 h-3.5"
                                        />
                                        <span className="text-xs text-gray-700">فتح الرابط في علامة تبويب جديدة (target="_blank")</span>
                                    </label>

                                    <button 
                                        type="button" 
                                        onClick={handleSaveItem}
                                        className="px-4 py-1.5 bg-orange-600 text-white rounded-lg text-xs font-bold hover:bg-orange-700 transition-colors"
                                    >
                                        {editingItemIndex !== null ? 'تحديث الرابط' : 'إضافة للرابط'}
                                    </button>
                                </div>
                            </div>

                            {/* Submit Button */}
                            <div className="flex justify-end gap-3 border-t pt-4">
                                {selectedMenu && (
                                    <button
                                        type="button"
                                        onClick={handleCreateNew}
                                        className="px-5 py-2.5 bg-gray-200 text-gray-700 hover:bg-gray-300 transition-all rounded-xl text-sm font-semibold"
                                    >
                                        إلغاء
                                    </button>
                                )}
                                <button
                                    type="submit"
                                    disabled={processing || menuItems.length === 0}
                                    className="px-6 py-2.5 bg-orange-600 hover:bg-orange-700 text-white transition-all rounded-xl text-sm font-bold shadow-md hover:shadow-lg focus:outline-none disabled:opacity-50"
                                >
                                    {processing ? 'جاري الحفظ...' : selectedMenu ? 'حفظ التعديلات' : 'حفظ القائمة بالكامل'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </MerchantLayout>
    );
}
