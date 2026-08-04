<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $locale === 'ar' ? 'أكمل شراء منتجاتك' : 'Complete your purchase' }}</title>
    <style>
        body {
            font-family: 'Cairo', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f7fafc;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: none;
            text-size-adjust: none;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff;
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 30px 20px;
        }
        .intro-text {
            font-size: 16px;
            color: #4a5568;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        .discount-banner {
            background-color: #f0fdf4;
            border: 1px dashed #22c55e;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin-bottom: 30px;
        }
        .discount-title {
            color: #15803d;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 5px;
        }
        .discount-code {
            display: inline-block;
            background-color: #22c55e;
            color: #ffffff;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 2px;
            padding: 8px 20px;
            border-radius: 6px;
            margin-top: 10px;
        }
        .item-list {
            margin-bottom: 30px;
        }
        .item-row {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #edf2f7;
        }
        .item-row:last-child {
            border-bottom: none;
        }
        .item-image {
            width: 70px;
            height: 70px;
            border-radius: 8px;
            object-fit: cover;
            margin-right: 15px;
            margin-left: 15px;
            border: 1px solid #edf2f7;
        }
        .item-details {
            flex: 1;
        }
        .item-name {
            font-weight: 600;
            font-size: 15px;
            color: #2d3748;
            margin-bottom: 4px;
        }
        .item-qty-price {
            font-size: 13px;
            color: #718096;
        }
        .item-total {
            font-weight: 700;
            font-size: 15px;
            color: #4f46e5;
        }
        .summary {
            background-color: #f7fafc;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 35px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
            color: #4a5568;
        }
        .summary-row:last-child {
            margin-bottom: 0;
            font-weight: 700;
            font-size: 16px;
            color: #2d3748;
            border-top: 1px solid #edf2f7;
            padding-top: 10px;
        }
        .cta-container {
            text-align: center;
            margin-bottom: 30px;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 15px 35px;
            font-size: 16px;
            font-weight: 700;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
            transition: all 0.3s ease;
        }
        .footer {
            background-color: #f7fafc;
            padding: 25px 20px;
            text-align: center;
            font-size: 12px;
            color: #a0aec0;
            border-top: 1px solid #edf2f7;
        }
        .footer p {
            margin: 5px 0;
        }
        .footer a {
            color: #4f46e5;
            text-decoration: none;
        }
        .rtl-align {
            text-align: right;
        }
        .ltr-align {
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            @if($locale === 'ar')
                <h1>سلتك في انتظارك بمتجر {{ $storeName }}</h1>
                <p>لقد احتفظنا بمنتجاتك المفضلة لتتمكن من إكمال طلبك بسهولة</p>
            @else
                <h1>Your cart is waiting at {{ $storeName }}</h1>
                <p>We've saved your favorite items so you can complete your order easily</p>
            @endif
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Intro -->
            <div class="intro-text {{ $locale === 'ar' ? 'rtl-align' : 'ltr-align' }}">
                @if($locale === 'ar')
                    <p>أهلاً بك،</p>
                    <p>لقد لاحظنا أنك تركت بعض المنتجات في سلتك ولم تكمل عملية الدفع بعد. لتسهيل عملية العودة، يمكنك الضغط على الزر أدناه واستكمال طلبك في ثوانٍ معدودة.</p>
                @else
                    <p>Hello,</p>
                    <p>We noticed you left some items in your cart and didn't complete the checkout. To make it easy to return, just click the button below to restore your cart and place your order in seconds.</p>
                @endif
            </div>

            <!-- Discount Banner -->
            @if($discountCode)
                <div class="discount-banner">
                    @if($locale === 'ar')
                        <div class="discount-title">🎁 عرض خاص لك فقط!</div>
                        <p style="margin: 5px 0; color: #4a5568;">استخدم هذا الكود للحصول على خصم <strong>{{ $discountPercentage }}%</strong> إضافي على طلبك:</p>
                        <div class="discount-code">{{ $discountCode }}</div>
                    @else
                        <div class="discount-title">🎁 Special offer just for you!</div>
                        <p style="margin: 5px 0; color: #4a5568;">Use this coupon code to get an extra <strong>{{ $discountPercentage }}%</strong> off your order:</p>
                        <div class="discount-code">{{ $discountCode }}</div>
                    @endif
                </div>
            @endif

            <!-- Items -->
            <div class="item-list">
                <h3 class="{{ $locale === 'ar' ? 'rtl-align' : 'ltr-align' }}" style="color: #2d3748; border-bottom: 2px solid #edf2f7; padding-bottom: 8px;">
                    {{ $locale === 'ar' ? 'محتويات السلة:' : 'Your Items:' }}
                </h3>
                @foreach($items as $item)
                    <div class="item-row" style="direction: {{ $locale === 'ar' ? 'rtl' : 'ltr' }};">
                        @if($item['image'])
                            <img class="item-image" src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
                        @else
                            <div class="item-image" style="background-color: #f7fafc; display: flex; align-items: center; justify-content: center; font-size: 24px;">🛒</div>
                        @endif
                        <div class="item-details {{ $locale === 'ar' ? 'rtl-align' : 'ltr-align' }}">
                            <div class="item-name">{{ $item['name'] }}</div>
                            <div class="item-qty-price">
                                @if($locale === 'ar')
                                    الكمية: {{ $item['quantity'] }} × {{ number_format($item['price'], 0) }} ج.م
                                @else
                                    Qty: {{ $item['quantity'] }} × {{ number_format($item['price'], 0) }} EGP
                                @endif
                            </div>
                        </div>
                        <div class="item-total">
                            {{ number_format($item['total'], 0) }} {{ $locale === 'ar' ? 'ج.م' : 'EGP' }}
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Summary -->
            <div class="summary" style="direction: {{ $locale === 'ar' ? 'rtl' : 'ltr' }};">
                <div class="summary-row">
                    <span>{{ $locale === 'ar' ? 'المجموع الفرعي:' : 'Subtotal:' }}</span>
                    <span>{{ number_format($subtotal, 0) }} {{ $locale === 'ar' ? 'ج.م' : 'EGP' }}</span>
                </div>
                <div class="summary-row">
                    <span>{{ $locale === 'ar' ? 'الإجمالي التقريبي:' : 'Estimated Total:' }}</span>
                    <span>{{ number_format($total, 0) }} {{ $locale === 'ar' ? 'ج.م' : 'EGP' }}</span>
                </div>
            </div>

            <!-- CTA -->
            <div class="cta-container">
                <a href="{{ $recoveryUrl }}" class="cta-button" target="_blank">
                    {{ $locale === 'ar' ? 'العودة واستكمال الطلب الآن 🛍️' : 'Restore Cart & Complete Checkout 🛍️' }}
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>{{ $locale === 'ar' ? 'هذه الرسالة مرسلة تلقائياً من متجر ' : 'This is an automated email from ' }} {{ $storeName }}</p>
            <p>&copy; {{ date('Y') }} {{ $storeName }}. {{ $locale === 'ar' ? 'جميع الحقوق محفوظة.' : 'All rights reserved.' }}</p>
        </div>
    </div>
</body>
</html>
