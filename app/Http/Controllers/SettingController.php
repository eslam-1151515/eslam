<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'logo' => Setting::get('logo'),
            'favicon' => Setting::get('favicon'),
            'phone' => Setting::get('phone', '01146520922'),
            'whatsapp' => Setting::get('whatsapp', '201146520922'),
            'facebook_page' => Setting::get('facebook_page', ''),
            'store_name' => Setting::get('store_name', 'Store'),
            'facebook_pixel_id' => Setting::get('facebook_pixel_id', ''),
            'tiktok_pixel_id' => Setting::get('tiktok_pixel_id', ''),
            'google_analytics_id' => Setting::get('google_analytics_id', ''),
            'primary_color' => Setting::get('primary_color', '#4f46e5'),
            'secondary_color' => Setting::get('secondary_color', '#64748b'),
            'font_family' => Setting::get('font_family', 'Cairo'),
            'main_categories' => Category::getMainCategories(),
        ];

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'phone' => ['nullable', 'string', 'max:20'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'facebook_page' => ['nullable', 'url', 'max:500'],
            'store_name' => ['nullable', 'string', 'max:100'],
            'facebook_pixel_id' => ['nullable', 'string', 'max:50'],
            'tiktok_pixel_id' => ['nullable', 'string', 'max:50'],
            'google_analytics_id' => ['nullable', 'string', 'max:50'],
            'primary_color' => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'secondary_color' => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'font_family' => ['nullable', 'string', 'max:50'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif,svg,ico', 'max:1024'],
        ], [
            'logo.image' => 'يجب أن يكون الملف صورة',
            'logo.mimes' => 'الصيغ المسموح بها: jpg, jpeg, png, webp, gif, svg',
            'logo.max' => 'الحد الأقصى لحجم الصورة 2 ميجابايت',
            'favicon.image' => 'يجب أن يكون ملف الأيقونة صورة',
            'favicon.mimes' => 'الصيغ المسموح بها للأيقونة: jpg, jpeg, png, webp, gif, svg, ico',
            'favicon.max' => 'الحد الأقصى لحجم الأيقونة 1 ميجابايت',
            'facebook_page.url' => 'يجب أن يكون الرابط صحيح',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $oldLogo = Setting::get('logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            $path = $request->file('logo')->store('settings', 'public');
            Setting::set('logo', $path);
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $oldFavicon = Setting::get('favicon');
            if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                Storage::disk('public')->delete($oldFavicon);
            }
            $path = $request->file('favicon')->store('settings', 'public');
            Setting::set('favicon', $path);
        }

        // Save scalar settings
        foreach (['phone', 'whatsapp', 'facebook_page', 'store_name', 'facebook_pixel_id', 'tiktok_pixel_id', 'google_analytics_id', 'primary_color', 'secondary_color', 'font_family'] as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key, ''));
            }
        }

        if ($request->filled('store_name')) {
            try {
                $tenant = app(\App\Models\Tenant::class);
                if ($tenant) {
                    $tenant->update(['name' => $request->input('store_name')]);
                }
            } catch (\Exception $e) {}
        }

        // Save main categories (array of strings)
        if ($request->has('main_categories')) {
            $cats = $request->input('main_categories', []);
            // Filter empty ones
            $cats = array_values(array_filter(array_map('trim', (array) $cats)));
            if (count($cats)) {
                Category::saveMainCategories($cats);
            }
        }

        return redirect()->route('settings.index')->with('status', 'تم حفظ الإعدادات بنجاح');
    }
}
