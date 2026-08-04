@extends('emails.layouts.base')
@section('content')
<h2>طلب جديد! 🛍️ #{{ $orderData['id'] }}</h2>
<p>لديك طلب جديد في متجرك <strong>{{ $orderData['store_name'] }}</strong>.</p>
<div class="info-box">
    <div class="info-row">
        <span class="info-label">رقم الطلب</span>
        <span class="info-value">#{{ $orderData['id'] }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">العميل</span>
        <span class="info-value">{{ $orderData['customer_name'] ?? 'غير محدد' }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">إجمالي الطلب</span>
        <span class="info-value">{{ $orderData['total'] ?? '' }} {{ $orderData['currency'] ?? 'ج.م' }}</span>
    </div>
</div>
@endsection
