@extends('emails.layouts.base')

@section('title', ($title ?? 'عروض جديدة') . ' - ' . ($storeName ?? 'فاست أوردر'))

@section('content')
<div style="text-align: center; margin-bottom: 25px;">
    <div style="font-size: 54px; margin-bottom: 10px;">🏷️</div>
    <h2 style="color: #667eea; font-size: 26px;">{{ $title ?? 'عروض حصرية وخصومات رائعة!' }}</h2>
    <p style="color: #555; font-size: 16px; line-height: 1.8;">{{ $offer['description'] ?? 'لا تفوت أحدث عروضنا وخصوماتنا الحصرية لفترة محدودة على منتجاتنا المميزة.' }}</p>
</div>

@if(!empty($offer['image']))
<div style="text-align: center; margin: 20px 0;">
    <img src="{{ $offer['image'] }}" alt="صورة العرض" style="max-width: 100%; height: auto; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
</div>
@endif

<div class="info-box" style="text-align: center; background: #fffaf0; border: 2px dashed #f6ad55; border-radius: 12px; padding: 25px;">
    @if(!empty($offer['discount']))
    <div style="font-size: 32px; font-weight: bold; color: #dd6b20; margin-bottom: 10px;">
        خصم {{ $offer['discount'] }}% 💥
    </div>
    @endif

    @if(!empty($offer['coupon_code']))
    <p style="color: #718096; font-size: 15px; margin-bottom: 8px;">استخدم كود الخصم الحصري عند إتمام الطلب:</p>
    <div style="display: inline-block; background: #2d3748; color: #fff; font-family: monospace; font-size: 22px; font-weight: bold; padding: 10px 25px; border-radius: 8px; letter-spacing: 2px;">
        {{ $offer['coupon_code'] }}
    </div>
    @endif
</div>

<div style="text-align: center; margin: 35px 0;">
    <a href="{{ $actionUrl ?? url('/products') }}" class="btn" style="background: linear-gradient(135deg, #ff6584, #ff3b6a); color: white; text-decoration: none; padding: 16px 40px; border-radius: 30px; font-weight: bold; font-size: 18px; display: inline-block; box-shadow: 0 4px 15px rgba(255,101,132,0.4);">تسوق العرض الآن 🚀</a>
</div>

<p style="font-size: 13px; color: #999; text-align: center;">العروض سارية لفترة محدودة أو حتى نفاد المخزون. تطبق الشروط والأحكام.</p>
@endsection
