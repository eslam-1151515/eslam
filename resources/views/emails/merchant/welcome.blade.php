@extends('emails.layouts.base')
@section('content')
<h2>مرحباً {{ $merchantName }}! 🎉</h2>
<p>يسعدنا انضمامك إلى منصة <strong>فاست أوردر</strong>. متجرك <strong>{{ $storeName }}</strong> جاهز الآن ويمكنك البدء في إضافة منتجاتك.</p>
<div class="info-box">
    <div class="info-row">
        <span class="info-label">اسم المتجر</span>
        <span class="info-value">{{ $storeName }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">رابط المتجر</span>
        <span class="info-value">{{ $storeUrl }}</span>
    </div>
</div>
<div style="text-align: center;">
    <a href="{{ $storeUrl }}" class="btn">زيارة متجري الآن</a>
</div>
<p>إذا احتجت أي مساعدة، فريق الدعم موجود دائماً.</p>
@endsection
