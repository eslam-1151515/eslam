@extends('emails.layouts.base')
@section('content')
<h2>شكراً لطلبك! 🎊</h2>
<p>تم استلام طلبك بنجاح من متجر <strong>{{ $orderData['store_name'] }}</strong>.</p>
<div class="info-box">
    <div class="info-row">
        <span class="info-label">رقم الطلب</span>
        <span class="info-value">#{{ $orderData['id'] }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">الحالة</span>
        <span class="info-value"><span class="badge badge-warning">قيد المعالجة</span></span>
    </div>
    <div class="info-row">
        <span class="info-label">الإجمالي</span>
        <span class="info-value">{{ $orderData['total'] ?? '' }} {{ $orderData['currency'] ?? 'ج.م' }}</span>
    </div>
</div>
@endsection
