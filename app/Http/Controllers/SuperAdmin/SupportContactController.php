<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SupportContact;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SupportContactController extends Controller
{
    public function index()
    {
        $contacts = SupportContact::orderBy('sort_order', 'asc')->orderBy('id', 'desc')->get()->map(function($c) {
            return [
                'id' => $c->id,
                'type' => $c->type,
                'title' => $c->title,
                'phone_number' => $c->phone_number,
                'whatsapp_message' => $c->whatsapp_message,
                'is_active' => (bool) $c->is_active,
                'sort_order' => $c->sort_order,
                'action_url' => $c->action_url,
            ];
        });

        return Inertia::render('SuperAdmin/SupportContacts/Index', [
            'contacts' => $contacts,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:whatsapp,phone',
            'title' => 'required|string|max:255',
            'phone_number' => 'required|string|max:50',
            'whatsapp_message' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        SupportContact::create($validated);

        return redirect()->back()->with('success', 'تم إضافة رقم الدعم الفني بنجاح ✓');
    }

    public function update(Request $request, SupportContact $supportContact)
    {
        $validated = $request->validate([
            'type' => 'required|in:whatsapp,phone',
            'title' => 'required|string|max:255',
            'phone_number' => 'required|string|max:50',
            'whatsapp_message' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $supportContact->update($validated);

        return redirect()->back()->with('success', 'تم تحديث رقم الدعم الفني بنجاح ✓');
    }

    public function toggle(SupportContact $supportContact)
    {
        $supportContact->update(['is_active' => !$supportContact->is_active]);
        return redirect()->back()->with('success', 'تم تغيير حالة الرقم بنجاح ✓');
    }

    public function destroy(SupportContact $supportContact)
    {
        $supportContact->delete();
        return redirect()->back()->with('success', 'تم حذف الرقم بنجاح ✓');
    }
}
