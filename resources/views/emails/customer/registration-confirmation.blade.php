@extends('emails.layouts.base')

@section('title', 'تأكيد التسجيل - ' . ($storeName ?? 'أوردر سيف'))

@section('content')
<div style="text-align: center; margin-bottom: 25px;">
    <div style="font-size: 48px; margin-bottom: 10px;">🎉</div>
    <h2>مرحباً بك في {{ $storeName ?? 'أوردر سيف' }}!</h2>
    <p style="color: #666; font-size: 16px;">سعداء بانضمامك لعائلتنا، تم تأكيد حسابك بنجاح.</p>
</div>

<div class="info-box">
    <div class="info-row">
        <span class="info-label">اسم العميل</span>
        <span class="info-value">{{ $userName ?? ($user->name ?? 'عميلنا العزيز') }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">البريد الإلكتروني</span>
        <span class="info-value">{{ $user->email ?? ($data['email'] ?? '') }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">تاريخ الانضمام</span>
        <span class="info-value">{{ now()->format('Y-m-d') }}</span>
    </div>
</div>

<p>يمكنك الآن تصفح أحدث المنتجات، إضافة عناصر إلى قائمة أمنياتك، وتتبع طلباتك بكل سهولة من خلال حسابك الشخصي.</p>

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ $actionUrl ?? url('/account') }}" class="btn" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; text-decoration: none; padding: 14px 35px; border-radius: 30px; font-weight: bold; display: inline-block;">الذهاب إلى حسابي 🚀</a>
</div>

<p style="font-size: 14px; color: #888; text-align: center;">إذا كانت لديك أي أسئلة أو استفسارات، فريق الدعم الفني جاهز لمساعدتك في أي وقت.</p>
@endsection
