@extends('emails.layouts.base')

@section('title', 'سلة تسوقك في انتظارك - ' . ($storeName ?? 'فاست أوردر'))

@section('content')
<div style="text-align: center; margin-bottom: 25px;">
    <div style="font-size: 54px; margin-bottom: 10px;">🛒</div>
    <h2 style="color: #3182ce; font-size: 24px;">هل نسيت شيئاً في سلة تسوقك؟</h2>
    <p style="color: #555; font-size: 16px;">لاحظنا أن لديك منتجات رائعة في سلتك بمتجر <strong>{{ $storeName ?? 'فاست أوردر' }}</strong> لم تكتمل عملية شرائها بعد.</p>
</div>

<div class="info-box" style="border-right-color: #3182ce; background: #ebf8ff;">
    <div class="info-row">
        <span class="info-label">عدد المنتجات في السلة</span>
        <span class="info-value" style="font-weight: bold; font-size: 16px; color: #2b6cb0;">{{ $itemsCount ?? 1 }} منتجات</span>
    </div>
    @if(isset($data['total']))
    <div class="info-row">
        <span class="info-label">الإجمالي التقريبي</span>
        <span class="info-value" style="font-weight: bold; color: #2d3748;">{{ round((float)$data['total'], 2) }} ج.م</span>
    </div>
    @endif
    <div class="info-row">
        <span class="info-label">حالة السلة</span>
        <span class="info-value"><span class="badge badge-warning">محفوظة ومتاحة</span></span>
    </div>
</div>

<p style="text-align: center; color: #4a5568; font-size: 15px;">لا تدع هذه الفرصة تفوتك، فقد تنفد الكمية المتاحة من هذه المنتجات قريباً!</p>

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ $actionUrl ?? url('/checkout') }}" class="btn" style="background: linear-gradient(135deg, #3182ce, #00b4d8); color: white; text-decoration: none; padding: 16px 40px; border-radius: 30px; font-weight: bold; font-size: 18px; display: inline-block; box-shadow: 0 4px 15px rgba(49,130,206,0.3);">أكمل طلبك الآن 🚀</a>
</div>

<p style="font-size: 13px; color: #999; text-align: center;">إذا أتممت طلبك بالفعل مؤخراً، فيرجى تجاهل هذا البريد.</p>
@endsection
