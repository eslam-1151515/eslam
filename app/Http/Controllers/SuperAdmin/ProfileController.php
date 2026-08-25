<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the Super Admin profile and admin management page.
     */
    public function index(): Response
    {
        $currentUser = Auth::user();

        // Retrieve all super admin accounts
        $admins = User::where('user_type', 'super_admin')
            ->orderBy('created_at', 'asc')
            ->get(['id', 'name', 'email', 'phone', 'user_type', 'is_active', 'created_at', 'last_login_at']);

        return Inertia::render('SuperAdmin/Profile/Index', [
            'admin' => [
                'id'            => $currentUser->id,
                'name'          => $currentUser->name,
                'email'         => $currentUser->email,
                'phone'         => $currentUser->phone,
                'last_login_at' => $currentUser->last_login_at ? $currentUser->last_login_at->format('Y-m-d h:i A') : null,
                'created_at'    => $currentUser->created_at ? $currentUser->created_at->format('Y-m-d') : null,
            ],
            'admins' => $admins,
        ]);
    }

    /**
     * Update the Super Admin's personal profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:50'],
        ], [
            'name.required'  => 'يرجى إدخال اسم المدير.',
            'email.required' => 'يرجى إدخال البريد الإلكتروني.',
            'email.email'    => 'البريد الإلكتروني المدخل غير صالح.',
            'email.unique'   => 'هذا البريد الإلكتروني مسجل بالفعل لمستخدم آخر.',
        ]);

        $user->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ]);

        return redirect()->back()->with('success', 'تم تحديث بيانات الملف الشخصي بنجاح.');
    }

    /**
     * Update the Super Admin's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required'         => 'يرجى كتابة كلمة المرور الحالية.',
            'current_password.current_password' => 'كلمة المرور الحالية غير صحيحة.',
            'password.required'                 => 'يرجى إدخال كلمة المرور الجديدة.',
            'password.min'                      => 'يجب أن لا تقل كلمة المرور عن 8 خانات.',
            'password.confirmed'                => 'تأكيد كلمة المرور الجديدة غير متطابق.',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->back()->with('success', 'تم تغيير كلمة المرور بنجاح.');
    }

    /**
     * Create a new Super Admin account.
     */
    public function storeAdmin(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone'    => ['nullable', 'string', 'max:50'],
            'password' => ['required', Password::min(8)],
        ], [
            'name.required'     => 'يرجى كتابة اسم المدير الجديد.',
            'email.required'    => 'يرجى كتابة البريد الإلكتروني.',
            'email.email'       => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.unique'      => 'هذا البريد الإلكتروني مستخدم بالفعل.',
            'password.required' => 'يرجى تعيين كلمة مرور للمدير.',
            'password.min'      => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل.',
        ]);

        User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'phone'     => $validated['phone'] ?? null,
            'password'  => Hash::make($validated['password']),
            'user_type' => 'super_admin',
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'تمت إضافة حساب المدير الجديد بنجاح ويمكنه تسجيل الدخول الآن.');
    }

    /**
     * Delete an extra Super Admin account.
     */
    public function destroyAdmin(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'عفواً، لا يمكنك حذف حسابك الحالي المسجل به الدخول.');
        }

        if ($user->user_type !== 'super_admin') {
            return redirect()->back()->with('error', 'هذا المستخدم ليس مديراً عاماً.');
        }

        $superAdminsCount = User::where('user_type', 'super_admin')->count();
        if ($superAdminsCount <= 1) {
            return redirect()->back()->with('error', 'لا يمكن حذف المدير الأخير في النظام.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'تم حذف حساب المدير بنجاح.');
    }
}
