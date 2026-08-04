<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Webhook;
use App\Models\WebhookLog;
use App\Http\Requests\StoreWebhookRequest;
use App\Http\Requests\UpdateWebhookRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class WebhookController extends Controller
{
    /**
     * عرض قائمة الـ Webhooks
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q', ''));

        $webhooks = Webhook::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('url', 'like', '%' . $q . '%');
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => Webhook::count(),
            'active' => Webhook::where('is_active', true)->count(),
            'logs_count' => WebhookLog::whereIn('webhook_id', Webhook::pluck('id'))->count(),
        ];

        return Inertia::render('Merchant/Webhooks/Index', [
            'webhooks' => $webhooks,
            'filters' => [
                'q' => $q
            ],
            'stats' => $stats,
        ]);
    }

    /**
     * حفظ Webhook جديد
     */
    public function store(StoreWebhookRequest $request)
    {
        $validated = $request->validated();

        $validated['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : true;

        Webhook::create($validated);

        return redirect()->route('merchant.webhooks.index')
            ->with('success', 'تم إنشاء الـ Webhook بنجاح ✓');
    }

    /**
     * تحديث Webhook
     */
    public function update(UpdateWebhookRequest $request, Webhook $webhook)
    {
        $validated = $request->validated();

        $validated['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : $webhook->is_active;

        $webhook->update($validated);

        return redirect()->route('merchant.webhooks.index')
            ->with('success', 'تم تحديث الـ Webhook بنجاح ✓');
    }

    /**
     * تفعيل/تعطيل الـ Webhook
     */
    public function toggle(Webhook $webhook)
    {
        $webhook->update([
            'is_active' => !$webhook->is_active
        ]);

        $message = $webhook->is_active ? 'تم تفعيل الـ Webhook بنجاح ✓' : 'تم تعطيل الـ Webhook بنجاح ✓';
        return redirect()->route('merchant.webhooks.index')->with('success', $message);
    }

    /**
     * حذف الـ Webhook
     */
    public function destroy(Webhook $webhook)
    {
        $webhook->delete();

        return redirect()->route('merchant.webhooks.index')
            ->with('success', 'تم حذف الـ Webhook بنجاح ✓');
    }

    /**
     * عرض سجل المكالمات الأخيرة
     */
    public function logs(Webhook $webhook)
    {
        $logs = $webhook->logs()->latest()->take(50)->get()->map(function ($log) {
            return [
                'id' => $log->id,
                'event' => $log->event,
                'payload' => $log->payload,
                'response_status' => $log->response_status,
                'response_body' => $log->response_body,
                'duration_ms' => $log->duration_ms,
                'created_at' => $log->created_at->toDateTimeString(),
            ];
        });

        return response()->json($logs);
    }
}
