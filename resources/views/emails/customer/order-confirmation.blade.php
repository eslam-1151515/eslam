@extends('emails.layouts.base')

@section('title', 'تأكيد طلبك #' . ($orderNumber ?? '') . ' - ' . ($storeName ?? 'أوردر سيف'))

@section('content')
<div style="text-align: center; margin-bottom: 25px;">
    <div style="font-size: 48px; margin-bottom: 10px;">📦</div>
    <h2>شكراً لطلبك من {{ $storeName ?? 'أوردر سيف' }}!</h2>
    <p style="color: #666; font-size: 16px;">تم استلام طلبك بنجاح وهو الآن قيد التجهيز والمراجعة.</p>
</div>

<div class="info-box">
    <div class="info-row">
        <span class="info-label">رقم الطلب</span>
        <span class="info-value">#{{ $orderNumber ?? ($order->reference_number ?: ($order->id ?? '')) }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">حالة الطلب</span>
        <span class="info-value"><span class="badge badge-warning">قيد المراجعة</span></span>
    </div>
    @if(isset($order) && is_object($order))
    <div class="info-row">
        <span class="info-label">طريقة الدفع</span>
        <span class="info-value">{{ $order->payment_method === 'cod' ? '💵 الدفع عند الاستلام' : ($order->payment_method === 'transfer' ? '🏦 تحويل بنكي / إنستاباي' : '💳 بطاقة ائتمان') }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">إجمالي الطلب</span>
        <span class="info-value" style="color: #667eea; font-weight: bold;">{{ round((float)($order->total ?? 0), 2) }} ج.م</span>
    </div>
    @elseif(isset($data['total']))
    <div class="info-row">
        <span class="info-label">إجمالي الطلب</span>
        <span class="info-value" style="color: #667eea; font-weight: bold;">{{ round((float)$data['total'], 2) }} ج.م</span>
    </div>
    @endif
</div>

@if(isset($order) && is_object($order) && !empty($order->items))
    @php
        $items = is_string($order->items) ? json_decode($order->items, true) : $order->items;
    @endphp
    @if(is_array($items) && count($items) > 0)
    <div style="margin: 25px 0;">
        <h3 style="font-size: 18px; color: #1a1a2e; margin-bottom: 12px; border-bottom: 2px solid #eee; padding-bottom: 8px;">🛒 محتويات الطلب:</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f9ff; text-align: right; font-size: 14px; color: #555;">
                    <th style="padding: 10px; border-bottom: 1px solid #ddd;">المنتج</th>
                    <th style="padding: 10px; border-bottom: 1px solid #ddd; text-align: center;">الكمية</th>
                    <th style="padding: 10px; border-bottom: 1px solid #ddd; text-align: left;">السعر</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr style="border-bottom: 1px solid #f0f0f0; font-size: 14px;">
                    <td style="padding: 10px; font-weight: 600; color: #333;">{{ $item['name'] ?? $item['product_name'] ?? 'منتج' }}</td>
                    <td style="padding: 10px; text-align: center; color: #666;">{{ $item['quantity'] ?? $item['qty'] ?? 1 }}</td>
                    <td style="padding: 10px; text-align: left; font-weight: bold; color: #667eea;">{{ round((float)($item['price'] ?? 0), 2) }} ج.م</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
@endif

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ $actionUrl ?? url('/account') }}" class="btn" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; text-decoration: none; padding: 14px 35px; border-radius: 30px; font-weight: bold; display: inline-block;">تتبع طلبك الآن 🚚</a>
</div>

<p style="font-size: 14px; color: #888; text-align: center;">سوف نرسل لك تحديثاً فور تحرك طلبك نحو الشحن والتوصيل.</p>
@endsection
