@extends('emails.layouts.base')

@section('title', 'فشل فحص صحة النظام')

@section('content')
<h2>تنبيه: فشل فحص صحة النظام للمنصة ⚠️</h2>
<p>تم إجراء فحص صحة تلقائي للمنصة وتم رصد مشاكل أو فشل في بعض الخدمات الحيوية. يرجى مراجعة التفاصيل أدناه فوراً واتخاذ الإجراءات اللازمة.</p>

<div class="info-box" style="border-right-color: #e3342f;">
    @foreach($checks as $name => $check)
        <div class="info-row" style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #eee;">
            <span class="info-label" style="font-weight: bold; color: #1a1a2e;">{{ $name }}</span>
            <span class="info-value">
                @if($check['ok'])
                    <span class="badge badge-success">سليم</span>
                @else
                    <span class="badge badge-danger">فشل</span>
                @endif
            </span>
        </div>
        @if(!$check['ok'])
            <div style="padding: 8px 10px; background: #fff5f5; border-radius: 4px; color: #e3342f; font-size: 13px; margin-bottom: 15px; border: 1px solid #fed7d7;">
                <strong>سبب الفشل:</strong> {{ $check['message'] }}
            </div>
        @endif
    @endforeach
</div>

<p>تاريخ ووقت الفحص: <strong>{{ now()->toDateTimeString() }}</strong></p>
<p>تم تسجيل تفاصيل الأخطاء في ملف السجل المخصص: <code>storage/logs/fastorder-errors.log</code></p>
@endsection
