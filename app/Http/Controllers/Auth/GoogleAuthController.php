<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        $query = http_build_query([
            'client_id' => config('services.google.client_id'),
            'redirect_uri' => config('services.google.redirect'),
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => csrf_token(),
        ]);

        return redirect('https://accounts.google.com/o/oauth2/v2/auth?' . $query);
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback(Request $request)
    {
        $code = $request->code;
        if (!$code) {
            return redirect()->route('merchant.login')->withErrors(['error' => 'Google authentication failed.']);
        }

        // Exchange auth code for access token using PHP cURL (zero dependencies)
        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'code' => $code,
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'redirect_uri' => config('services.google.redirect'),
            'grant_type' => 'authorization_code',
        ]));
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        if (!isset($data['access_token'])) {
            return redirect()->route('merchant.login')->withErrors(['error' => 'Failed to obtain access token.']);
        }

        // Fetch user info from Google Info Endpoint
        $ch = curl_init('https://openidconnect.googleapis.com/v1/userinfo');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $data['access_token']
        ]);
        $userInfoResponse = curl_exec($ch);
        curl_close($ch);

        $userInfo = json_decode($userInfoResponse, true);
        if (!isset($userInfo['email'])) {
            return redirect()->route('merchant.login')->withErrors(['error' => 'Failed to obtain user info.']);
        }

        // Check if user already exists in database
        $user = User::where('email', $userInfo['email'])->first();

        if ($user) {
            Auth::login($user);
            
            // Redirect to their subdomain if they have a tenant
            if ($user->isMerchant() && $user->currentTenant) {
                $host = parse_url(config('app.url'), PHP_URL_HOST);
                $subdomainUrl = $request->getScheme() . '://' . $user->currentTenant->slug . '.' . $host;
                return redirect()->away($subdomainUrl . '/admin/dashboard');
            }

            return redirect()->intended('/dashboard');
        }

        // For new users, store details in session and redirect to complete registration (asking for store slug)
        session([
            'google_user' => [
                'name' => $userInfo['name'] ?? '',
                'email' => $userInfo['email'],
                'google_id' => $userInfo['sub'] ?? null,
            ]
        ]);

        return redirect()->route('auth.google.complete');
    }

    /**
     * Show form to complete registration for Google users.
     */
    public function showCompleteRegistration()
    {
        if (!session()->has('google_user')) {
            return redirect()->route('merchant.login');
        }

        return Inertia::render('Auth/GoogleCompleteRegistration', [
            'email' => session('google_user.email'),
            'name' => session('google_user.name'),
        ]);
    }

    /**
     * Process completing registration for Google users.
     */
    public function completeRegistration(Request $request)
    {
        if (!session()->has('google_user')) {
            return redirect()->route('merchant.login');
        }

        $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'subdomain' => ['required', 'string', 'alpha_dash', 'max:255', 'unique:tenants,slug'],
        ]);

        $googleUser = session('google_user');

        $user = DB::transaction(function () use ($request, $googleUser) {
            // 1. Create Tenant (Store)
            $tenant = Tenant::create([
                'uuid' => (string) \Illuminate\Support\Str::uuid(),
                'name' => $request->store_name,
                'slug' => strtolower($request->subdomain),
                'is_active' => true,
            ]);

            // 2. Create User
            $user = User::create([
                'tenant_id' => $tenant->id,
                'name' => $googleUser['name'],
                'email' => $googleUser['email'],
                'password' => Hash::make(\Illuminate\Support\Str::random(24)), // secure random password for OAuth users
                'user_type' => 'merchant',
                'is_active' => true,
            ]);

            // 3. Link owner on Tenant
            $tenant->update(['owner_id' => $user->id]);

            // 4. Create pivot role entry
            $user->tenants()->attach($tenant->id, [
                'role' => 'owner',
                'permissions' => json_encode(['*']),
            ]);

            return $user;
        });

        session()->forget('google_user');

        Auth::login($user);

        // Redirect to the merchant's dashboard on their subdomain
        $host = parse_url(config('app.url'), PHP_URL_HOST);
        $subdomainUrl = $request->getScheme() . '://' . strtolower($request->subdomain) . '.' . $host;
        
        return redirect()->away($subdomainUrl . '/dashboard');
    }
}
