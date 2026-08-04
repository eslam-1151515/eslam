<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $user = $request->user();
        if (empty($user->phone)) {
            $user->phone = \App\Models\Setting::where('key', 'phone')->value('value') ?: $user->tenant?->phone;
        }

        return Inertia::render('Merchant/Profile/Edit', [
            'status' => session('status'),
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $phone = $request->input('phone');
        $user->phone = $phone;
        $user->save();

        // التزامن التام مع المكان المخصص لهاتف المتجر والتينانت تماماً كالتسجيل لأول مرة
        if (!empty($phone)) {
            $whatsapp = $phone;
            if (str_starts_with($whatsapp, '0')) {
                $whatsapp = '2' . substr($whatsapp, 1);
            } elseif (!str_starts_with($whatsapp, '20') && strlen($whatsapp) === 10) {
                $whatsapp = '2' . $whatsapp;
            }

            $tenantId = $user->tenant_id;
            \App\Models\Setting::set('phone', $phone, 'general', $tenantId);
            \App\Models\Setting::set('whatsapp', $whatsapp, 'general', $tenantId);

            if ($user->tenant) {
                $user->tenant->update(['phone' => $phone]);
            }
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
