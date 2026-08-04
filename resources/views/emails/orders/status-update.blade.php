@extends('emails.layouts.base')
@section('content')
<h2>تحديث على طلبك #{{ $orderData['id'] }}</h2>
<p>تم تحديث حالة طلبك من متجر <strong>{{ $orderData['store_name'] }}</strong>.</p>
<div class="info-box">
    <div class="info-row">
        <span class="info-label">رقم الطلب</span>
        <span class="info-value">#{{ $orderData['id'] }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">الحالة الجديدة</span>
        <span class="info-value"><span class="badge badge-success">{{ $statusLabel }}</span></span>
    </div>
</div>
@endsection
