@extends('emails.layouts.base')
@section('content')
<h2>⚠️ اشتراكك ينتهي قريباً</h2>
<p>عزيزي <strong>{{ $merchantName }}</strong>،</p>
<p>اشتراكك في منصة فاست أوردر لمتجر <strong>{{ $storeName }}</strong> سينتهي خلال <strong>{{ $daysRemaining }} أيام</strong> فقط.</p>
<p>لتجنب انقطاع الخدمة، يرجى تجديد اشتراكك الآن.</p>
<div style="text-align: center;">
    <a href="{{ $renewUrl }}" class="btn">تجديد الاشتراك الآن</a>
</div>
@endsection
