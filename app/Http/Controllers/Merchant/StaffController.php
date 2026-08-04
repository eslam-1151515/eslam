<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class StaffController extends Controller
{
    /**
     * عرض قائمة الموظفين المرتبطين بالمتجر الحالي
     */
    public function index(Request $request): Response
    {
        $tenantId = session()->get('tenant_id') ?? config('tenant.id');
        $q = trim((string) $request->get('q', ''));

        $staff = User::whereHas('tenants', function ($query) use ($tenantId) {
            $query->where('tenants.id', $tenantId);
        })
        ->where('user_type', 'staff')
        ->when($q !== '', function ($query) use ($q) {
            $query->where(function ($qq) use ($q) {
                $qq->where('name', 'like', "%{$q}%")
                   ->orWhere('email', 'like', "%{$q}%");
            });
        })
        ->latest()
        ->get()
        ->map(function ($user) use ($tenantId) {
            $tenantPivot = $user->tenants->firstWhere('id', $tenantId)?->pivot;
            
            $permissions = $tenantPivot?->permissions;
            if (is_string($permissions)) {
                $permissions = json_decode($permissions, true) ?? [];
            } elseif (!is_array($permissions)) {
                $permissions = [];
            }

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'user_type' => $user->user_type,
                'is_active' => (bool) $user->is_active,
                'created_at' => $user->created_at?->format('Y-m-d H:i'),
                'role' => $tenantPivot?->role ?? 'staff',
                'permissions' => $permissions,
            ];
        });

        // قائمة الأدوار المتاحة للاختيار في الواجهة
        $rolesList = [
            [
                'slug' => 'manager',
                'name' => 'مدير النظام (Manager)',
                'description' => 'يمتلك كافة الصلاحيات لإدارة المتجر والمنتجات والطلبات.',
            ],
            [
                'slug' => 'product_manager',
                'name' => 'مدير المنتجات (Product Manager)',
                'description' => 'إدارة المنتجات والتصنيفات فقط.',
            ],
            [
                'slug' => 'order_manager',
                'name' => 'مدير الطلبات (Order Manager)',
                'description' => 'إدارة الطلبات والشحن فقط.',
            ],
            [
                'slug' => 'staff',
                'name' => 'موظف عام (Staff)',
                'description' => 'عرض المنتجات وإدارة محدودة للطلبات.',
            ],
        ];

        // قائمة الصلاحيات التفصيلية التي يمكن تخصيصها للموظف
        $permissionsList = [
            // Products
            ['slug' => 'view_products', 'name' => 'عرض المنتجات', 'group' => 'المنتجات'],
            ['slug' => 'create_products', 'name' => 'إضافة منتجات جديدة', 'group' => 'المنتجات'],
            ['slug' => 'edit_products', 'name' => 'تعديل المنتجات', 'group' => 'المنتجات'],
            ['slug' => 'delete_products', 'name' => 'حذف المنتجات', 'group' => 'المنتجات'],

            // Categories
            ['slug' => 'view_categories', 'name' => 'عرض التصنيفات', 'group' => 'التصنيفات'],
            ['slug' => 'create_categories', 'name' => 'إضافة تصنيفات جديدة', 'group' => 'التصنيفات'],
            ['slug' => 'edit_categories', 'name' => 'تعديل التصنيفات', 'group' => 'التصنيفات'],
            ['slug' => 'delete_categories', 'name' => 'حذف التصنيفات', 'group' => 'التصنيفات'],

            // Orders
            ['slug' => 'view_orders', 'name' => 'عرض الطلبات', 'group' => 'الطلبات'],
            ['slug' => 'edit_orders', 'name' => 'تعديل وتحديث الطلبات', 'group' => 'الطلبات'],
            ['slug' => 'delete_orders', 'name' => 'حذف الطلبات', 'group' => 'الطلبات'],

            // Settings & Banners
            ['slug' => 'view_settings', 'name' => 'عرض الإعدادات', 'group' => 'الإعدادات'],
            ['slug' => 'edit_settings', 'name' => 'تعديل الإعدادات', 'group' => 'الإعدادات'],
            ['slug' => 'view_banners', 'name' => 'عرض البانرات', 'group' => 'البانرات'],
            ['slug' => 'edit_banners', 'name' => 'تعديل وإدارة البانرات', 'group' => 'البانرات'],
        ];

        return Inertia::render('Merchant/Staff/Index', [
            'staff' => $staff,
            'rolesList' => $rolesList,
            'permissionsList' => $permissionsList,
            'filters' => [
                'q' => $q,
            ],
        ]);
    }

    /**
     * إضافة موظف جديد وربطه بالمتجر
     */
    public function store(StoreStaffRequest $request)
    {
        $validated = $request->validated();

        $tenantId = session()->get('tenant_id') ?? config('tenant.id');

        DB::transaction(function () use ($validated, $tenantId) {
            // إنشاء المستخدم في جدول users كـ staff
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'user_type' => 'staff',
                'tenant_id' => $tenantId,
                'is_active' => $validated['is_active'] ?? true,
            ]);

            // ربط المستخدم بالمتجر في جدول tenant_users
            $user->tenants()->attach($tenantId, [
                'role' => $validated['role'],
                'permissions' => json_encode($validated['permissions'] ?? []),
            ]);
        });

        return redirect()->back()->with('success', 'تم إضافة الموظف بنجاح.');
    }

    /**
     * تعديل بيانات الموظف ودوره وصلاحياته
     */
    public function update(UpdateStaffRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $tenantId = session()->get('tenant_id') ?? config('tenant.id');

        // التحقق من أن الموظف مرتبط بالمتجر الحالي
        $isLinked = $user->tenants()->where('tenants.id', $tenantId)->exists();
        if (!$isLinked) {
            abort(403, 'غير مصرح بإجراء هذه العملية.');
        }

        $validated = $request->validated();

        DB::transaction(function () use ($user, $validated, $tenantId) {
            // تحديث بيانات الموظف الشخصية وحالته
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'is_active' => $validated['is_active'] ?? true,
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $user->update($userData);

            // تحديث دور الموظف وصلاحياته في جدول tenant_users
            $user->tenants()->updateExistingPivot($tenantId, [
                'role' => $validated['role'],
                'permissions' => json_encode($validated['permissions'] ?? []),
            ]);
        });

        return redirect()->back()->with('success', 'تم تحديث بيانات الموظف بنجاح.');
    }

    /**
     * إلغاء ارتباط الموظف بالمتجر وحذفه
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $tenantId = session()->get('tenant_id') ?? config('tenant.id');

        // التحقق من أن الموظف مرتبط بالمتجر الحالي
        $isLinked = $user->tenants()->where('tenants.id', $tenantId)->exists();
        if (!$isLinked) {
            abort(403, 'غير مصرح بإجراء هذه العملية.');
        }

        DB::transaction(function () use ($user, $tenantId) {
            // إلغاء ارتباط الموظف بالمتجر
            $user->tenants()->detach($tenantId);

            // إذا كان الموظف غير مرتبط بأي متجر آخر وليس مالكاً أو مشرفاً عاماً، نقوم بحذف حسابه تماماً
            if ($user->tenants()->count() === 0 && !$user->isSuperAdmin() && !$user->isMerchant()) {
                $user->delete();
            }
        });

        return redirect()->back()->with('success', 'تم إلغاء ارتباط الموظف وحذفه من المتجر بنجاح.');
    }
}
