<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Setting;
use App\Http\Requests\UpdateSettingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SettingController extends Controller
{
    /**
     * Display the merchant settings.
     */
    public function index()
    {
        $settings = [
            'logo' => Setting::get('logo'),
            'logo_url' => Setting::get('logo') ? asset('storage/' . Setting::get('logo')) : null,
            'phone' => Setting::get('phone', '01012027705'),
            'whatsapp' => Setting::get('whatsapp', '201012027705'),
            'store_name' => Setting::get('store_name', 'Store'),
            'facebook_pixel_id' => Setting::get('facebook_pixel_id', ''),
            'tiktok_pixel_id' => Setting::get('tiktok_pixel_id', ''),
            'snapchat_pixel_id' => Setting::get('snapchat_pixel_id', ''),
            'google_analytics_id' => Setting::get('google_analytics_id', ''),
            'facebook_page' => Setting::get('facebook_page', ''),
            'instagram_page' => Setting::get('instagram_page', ''),
            'tiktok_page' => Setting::get('tiktok_page', ''),
            'google_maps_url' => Setting::get('google_maps_url', ''),
            'address' => Setting::get('address', ''),
            'main_categories' => Category::getMainCategories(),
        ];

        return Inertia::render('Merchant/Settings/Index', [
            'settings' => $settings,
        ]);
    }

    /**
     * Update the merchant settings.
     */
    public function update(UpdateSettingRequest $request)
    {
        $validated = $request->validated();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $oldLogo = Setting::get('logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            $path = \App\Services\ImageCompressionService::compressAndStore($request->file('logo'), 'settings', 'public');
            Setting::set('logo', $path);
        }

        // Save scalar settings
        $keys = [
            'phone', 'whatsapp', 'store_name', 
            'facebook_pixel_id', 'tiktok_pixel_id', 'snapchat_pixel_id', 'google_analytics_id',
            'facebook_page', 'instagram_page', 'tiktok_page', 'google_maps_url', 'address'
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                $val = $request->input($key);
                if (is_bool($val)) {
                    $val = $val ? '1' : '0';
                }
                Setting::set($key, $val ?? '');
            }
        }

        // Save main categories (array of strings)
        if ($request->has('main_categories')) {
            $cats = $request->input('main_categories', []);
            // Filter empty ones
            $cats = array_values(array_filter(array_map('trim', (array) $cats)));
            Category::saveMainCategories($cats);
        }

        return redirect()->route('settings.index')->with('success', 'تم حفظ الإعدادات بنجاح ✓');
    }
}
