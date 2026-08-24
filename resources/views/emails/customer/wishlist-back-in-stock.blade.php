@extends('emails.layouts.base')

@section('title', 'منتج متاح الآن في المخزون! - ' . ($storeName ?? 'أوردر سيف'))

@section('content')
<div style="text-align: center; margin-bottom: 25px;">
    <div style="font-size: 54px; margin-bottom: 10px;">💖</div>
    <h2 style="color: #e53e3e; font-size: 24px;">خبر سار جداً لك!</h2>
    <p style="color: #555; font-size: 16px;">المنتج الذي قمت بإضافته سابقاً إلى قائمة أمنياتك أصبح متاحاً في المخزون الآن.</p>
</div>

<div class="info-box" style="border-right-color: #e53e3e; background: #fff5f5;">
    <div class="info-row">
        <span class="info-label">اسم المنتج</span>
        <span class="info-value" style="font-size: 16px; color: #c53030;">{{ $productName ?? (is_object($product) ? ($product->name ?? 'منتجك المفضل') : 'منتجك المفضل') }}</span>
    </div>
    @if(isset($product) && is_object($product) && !empty($product->price))
    <div class="info-row">
        <span class="info-label">السعر الحالي</span>
        <span class="info-value" style="font-weight: bold; color: #2d3748;">{{ round((float)$product->price, 2) }} ج.م</span>
    </div>
    @elseif(isset($product['price']))
    <div class="info-row">
        <span class="info-label">السعر الحالي</span>
        <span class="info-value" style="font-weight: bold; color: #2d3748;">{{ round((float)$product['price'], 2) }} ج.م</span>
    </div>
    @endif
    <div class="info-row">
        <span class="info-label">حالة المخزون</span>
        <span class="info-value"><span class="badge badge-success">متوفر الآن ✅</span></span>
    </div>
</div>

<p style="text-align: center; color: #4a5568; font-size: 15px;">سارع بطلبه الآن قبل أن تنفد الكمية مرة أخرى!</p>

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ $actionUrl ?? url('/products') }}" class="btn" style="background: linear-gradient(135deg, #e53e3e, #dd6b20); color: white; text-decoration: none; padding: 16px 38px; border-radius: 30px; font-weight: bold; font-size: 17px; display: inline-block;">اشترِ المنتج الآن 🛍️</a>
</div>

<p style="font-size: 13px; color: #999; text-align: center;">يمكنك إزالة هذا المنتج من قائمة أمنياتك أو إدارة تفضيلات التنبيهات من خلال حسابك.</p>
@endsection
