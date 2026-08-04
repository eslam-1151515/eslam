<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\BlacklistRecord;
use App\Http\Requests\StoreBlacklistRecordRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BlacklistController extends Controller
{
    /**
     * Display a listing of blacklist records.
     */
    public function index(Request $request): Response
    {
        $search = trim((string) $request->input('search', ''));
        $type = trim((string) $request->input('type', ''));

        $query = BlacklistRecord::query();

        if ($search !== '') {
            $query->where('value', 'like', "%{$search}%");
        }

        if ($type !== '' && in_array($type, ['ip', 'phone', 'email'])) {
            $query->where('type', $type);
        }

        $records = $query->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Merchant/Blacklist/Index', [
            'records' => $records,
            'filters' => [
                'search' => $search,
                'type' => $type,
            ]
        ]);
    }

    /**
     * Store a newly created blacklist record in storage.
     */
    public function store(StoreBlacklistRecordRequest $request)
    {
        $validated = $request->validated();

        // Check if value already exists for this tenant
        $exists = BlacklistRecord::where('type', $validated['type'])
            ->where('value', $validated['value'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'هذه القيمة مضافة بالفعل في القائمة السوداء');
        }

        BlacklistRecord::create($validated);

        return back()->with('success', 'تم إضافة القيمة للقائمة السوداء بنجاح ✓');
    }

    /**
     * Remove the specified blacklist record from storage.
     */
    public function destroy(BlacklistRecord $blacklistRecord)
    {
        // BlacklistRecord model already uses BelongsToTenant trait, so it's scoped to current tenant
        $blacklistRecord->delete();

        return back()->with('success', 'تم إزالة القيمة من القائمة السوداء بنجاح ✓');
    }
}
