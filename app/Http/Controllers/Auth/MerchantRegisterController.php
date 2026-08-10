<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class MerchantRegisterController extends Controller
{
    /**
     * Display the merchant registration wizard view.
     */
    public function showRegistrationForm(Request $request): View
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();
        $googleUser = session('google_user', null);

        return view('platform.auth.register', [
            'plans' => $plans,
            'googleUser' => $googleUser,
        ]);
    }

    /**
     * AJAX endpoint to check store domain/slug availability.
     */
    public function checkSlug(Request $request)
    {
        $slug = strtolower(trim($request->input('slug', '')));

        if (empty($slug) || !preg_match('/^[a-z0-9_-]+$/', $slug)) {
            return response()->json([
                'available' => false,
                'message' => 'رابط غير صالح. يرجى استخدام حروف إنجليزية وأرقام وشرطات فقط بدون مسافات.'
            ]);
        }

        if (in_array($slug, ['admin', 'superadmin', 'api', 'www', 'mail', 'ftp', 'dashboard', 'app', 'platform', 'auth', 'test', 'demo', 'blog', 'support', 'help', 'status'])) {
            return response()->json([
                'available' => false,
                'message' => 'هذا الرابط محجوز للنظام، يرجى اختيار رابط آخر.'
            ]);
        }

        $exists = Tenant::where('slug', $slug)->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'رابط المتجر مستخدم بالفعل، يرجى اختيار رابط آخر.' : 'رابط المتجر متاح للاستخدام!'
        ]);
    }

    /**
     * Handle an incoming merchant registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request): RedirectResponse
    {
        $isGoogle = session()->has('google_user') || $request->boolean('is_google');

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'store_name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'alpha_dash', 'max:255', 'unique:tenants,slug'],
            'activity' => ['nullable', 'string', 'max:255'],
            'plan_id' => ['nullable', 'integer', 'exists:subscription_plans,id'],
        ];

        if (!$isGoogle) {
            $rules['email'] = ['required', 'string', 'email', 'max:255', 'unique:users,email'];
            $rules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
        } else {
            $rules['email'] = ['required', 'string', 'email', 'max:255'];
            $rules['password'] = ['nullable', 'string', 'min:8'];
        }

        $request->validate($rules, [
            'slug.unique' => 'رابط المتجر (Slug) مستخدم بالفعل، يرجى اختيار رابط آخر.',
            'slug.alpha_dash' => 'رابط المتجر يجب أن يحتوي على حروف إنجليزية وأرقام وشرطات فقط بدون مسافات.',
            'email.unique' => 'البريد الإلكتروني مسجل لدينا بالفعل.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
        ]);

        $user = DB::transaction(function () use ($request, $isGoogle) {
            $email = $isGoogle ? session('google_user.email', $request->email) : $request->email;
            $password = $request->filled('password')
                ? Hash::make($request->password)
                : Hash::make(Str::random(24));

            // 1. Create Tenant (Store)
            $tenant = Tenant::create([
                'uuid' => (string) Str::uuid(),
                'name' => $request->store_name,
                'slug' => strtolower($request->slug),
                'phone' => $request->phone,
                'email' => $email,
                'subscription_status' => 'trial',
                'trial_ends_at' => now()->addDays(7),
                'is_active' => true,
                'settings' => [
                    'activity' => $request->activity ?? 'تجارة عامة',
                    'phone' => $request->phone,
                ],
            ]);

            // Save phone and whatsapp to settings table if provided
            if (!empty($request->phone)) {
                $whatsapp = $request->phone;
                if (str_starts_with($whatsapp, '0')) {
                    $whatsapp = '2' . substr($whatsapp, 1);
                } elseif (!str_starts_with($whatsapp, '20') && strlen($whatsapp) === 10) {
                    $whatsapp = '2' . $whatsapp;
                }
                \App\Models\Setting::set('phone', $request->phone, 'general', $tenant->id);
                \App\Models\Setting::set('whatsapp', $whatsapp, 'general', $tenant->id);
            }

            // 2. Create or Update User
            $user = User::where('email', $email)->first();
            if (!$user) {
                $user = User::create([
                    'tenant_id' => $tenant->id,
                    'name' => $request->name,
                    'email' => $email,
                    'password' => $password,
                    'user_type' => 'merchant',
                    'is_active' => true,
                    'phone' => $request->phone,
                ]);
            } else {
                $user->update([
                    'tenant_id' => $tenant->id,
                    'user_type' => 'merchant',
                ]);
            }

            // 3. Link owner on Tenant
            $tenant->update(['owner_id' => $user->id]);

            // 4. Link in tenant_users pivot table
            if (!$user->tenants()->where('tenant_id', $tenant->id)->exists()) {
                $user->tenants()->attach($tenant->id, [
                    'role' => 'owner',
                    'permissions' => json_encode(['*']),
                ]);
            }

            // 5. Activate Automatic Trial Subscription
            $plan = null;
            if ($request->filled('plan_id')) {
                $plan = SubscriptionPlan::find($request->plan_id);
            }
            if (!$plan) {
                $plan = SubscriptionPlan::where('is_active', true)->first();
            }

            $trialDays = $plan ? ($plan->trial_days ?: 7) : 7;

            Subscription::create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan ? $plan->id : null,
                'status' => 'trial',
                'billing_cycle' => 'monthly',
                'price' => 0,
                'starts_at' => now(),
                'trial_ends_at' => now()->addDays($trialDays),
                'ends_at' => now()->addDays($trialDays),
            ]);

            return $user;
        });

        event(new Registered($user));

        if (session()->has('google_user')) {
            session()->forget('google_user');
        }

        Auth::login($user);

        // Store active tenant in session
        if ($user->currentTenant) {
            session(['tenant_id' => $user->currentTenant->id]);
        }

        // Redirect to the merchant's dashboard on their subdomain or fallback to local dashboard
        $host = parse_url(config('app.url', 'http://localhost'), PHP_URL_HOST) ?: 'localhost';
        if ($host !== 'localhost' && $host !== '127.0.0.1' && !str_contains($host, 'localhost')) {
            $subdomainUrl = $request->getScheme() . '://' . strtolower($request->slug) . '.' . $host;
            return redirect()->away($subdomainUrl . '/dashboard');
        }

        return redirect('/dashboard');
    }
}
