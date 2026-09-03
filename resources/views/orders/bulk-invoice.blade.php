<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة مجمعة - {{ count($orders) }} فاتورة</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Cairo', sans-serif;
        }

        body {
            direction: rtl;
            color: #1f2937;
            background: #f3f4f6;
            padding-bottom: 60px;
        }

        /* Fixed floating top bar for screen */
        .print-toolbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: #ffffff;
            border-bottom: 2px solid #e5e7eb;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .print-toolbar .info {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
        }

        .print-toolbar .info span {
            color: #4f46e5;
        }

        .print-toolbar .actions {
            display: flex;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #4f46e5;
            color: white;
        }

        .btn-primary:hover {
            background: #4338ca;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }

        /* Invoice Sheet */
        .invoice-page {
            max-width: 800px;
            margin: 24px auto;
            background: #ffffff;
            padding: 32px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            page-break-after: always;
            break-after: page;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 20px;
            margin-bottom: 24px;
        }

        .store-brand h1 {
            color: #4f46e5;
            font-size: 26px;
            font-weight: 800;
        }

        .store-brand p {
            color: #6b7280;
            font-size: 13px;
            margin-top: 4px;
        }

        .invoice-meta {
            text-align: left;
            direction: ltr;
        }

        .invoice-meta h2 {
            font-size: 20px;
            color: #111827;
            font-weight: 800;
        }

        .invoice-meta p {
            font-size: 12px;
            color: #6b7280;
            margin-top: 2px;
        }

        .customer-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            background: #f9fafb;
            padding: 16px;
            border-radius: 8px;
            border: 1px solid #f3f4f6;
            margin-bottom: 24px;
        }

        .customer-box h3 {
            font-size: 13px;
            color: #4f46e5;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .customer-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            margin-bottom: 4px;
        }

        .customer-row .label {
            color: #6b7280;
        }

        .customer-row .value {
            color: #111827;
            font-weight: 600;
        }

        /* Items Table */
        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        table.items-table th {
            background: #f3f4f6;
            color: #374151;
            font-size: 12px;
            font-weight: 700;
            padding: 10px 12px;
            text-align: right;
            border-bottom: 2px solid #e5e7eb;
        }

        table.items-table td {
            padding: 12px;
            font-size: 13px;
            border-bottom: 1px solid #f3f4f6;
            color: #1f2937;
        }

        .item-details {
            font-size: 11px;
            color: #6b7280;
            margin-top: 2px;
        }

        /* Total Section */
        .summary-wrapper {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
        }

        .summary-box {
            width: 260px;
            background: #f9fafb;
            border-radius: 8px;
            padding: 12px 16px;
            border: 1px solid #f3f4f6;
        }

        .summary-line {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            margin-bottom: 6px;
            color: #4b5563;
        }

        .summary-line.total {
            font-size: 16px;
            font-weight: 800;
            color: #111827;
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
            margin-top: 8px;
        }

        .invoice-footer {
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px dashed #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            color: #9ca3af;
        }

        .barcode-box {
            font-family: monospace;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 2px;
            padding: 4px 8px;
            background: #f3f4f6;
            border-radius: 4px;
            color: #111827;
        }

        @media print {
            body {
                background: white;
                padding-bottom: 0;
            }
            .print-toolbar {
                display: none !important;
            }
            .invoice-page {
                margin: 0;
                padding: 24px;
                border: none;
                border-radius: 0;
                box-shadow: none;
                page-break-after: always;
                break-after: page;
            }
        }
    </style>
</head>
<body>

    <!-- Fixed Print Toolbar -->
    <div class="print-toolbar">
        <div class="info">
            جاهز لطباعة <span>{{ count($orders) }}</span> فاتورة
        </div>
        <div class="actions">
            <button onclick="window.print()" class="btn btn-primary">
                <span>🖨️</span>
                <span>بدء الطباعة الآن (Print)</span>
            </button>
            <button onclick="window.close()" class="btn btn-secondary">
                <span>✕</span>
                <span>إغلاق</span>
            </button>
        </div>
    </div>

    @foreach ($orders as $order)
        @php
            $items = is_string($order->items) ? json_decode($order->items, true) : ($order->items ?? []);
        @endphp

        <div class="invoice-page">
            <!-- Header -->
            <div class="invoice-header">
                <div class="store-brand">
                    <h1>{{ $storeName }}</h1>
                    <p>هاتف المتجر: {{ $storePhone }}</p>
                </div>
                <div class="invoice-meta">
                    <h2>#{{ $order->reference_number }}</h2>
                    <p>التاريخ: {{ $order->created_at?->format('Y-m-d H:i') }}</p>
                    @if ($order->shipment)
                        <p style="color: #059669; font-weight: bold; margin-top: 4px;">
                            {{ strtoupper($order->shipment->provider) }}: {{ $order->shipment->tracking_number }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Customer & Shipping Details -->
            <div class="customer-grid">
                <div class="customer-box">
                    <h3>بيانات العميل</h3>
                    <div class="customer-row">
                        <span class="label">الاسم:</span>
                        <span class="value">{{ $order->customer_name }}</span>
                    </div>
                    <div class="customer-row">
                        <span class="label">الهاتف:</span>
                        <span class="value" dir="ltr">{{ $order->customer_phone }}</span>
                    </div>
                </div>

                <div class="customer-box">
                    <h3>بيانات التوصيل</h3>
                    <div class="customer-row">
                        <span class="label">المحافظة:</span>
                        <span class="value">{{ $order->governorate }}</span>
                    </div>
                    <div class="customer-row">
                        <span class="label">العنوان:</span>
                        <span class="value">{{ $order->customer_address }}</span>
                    </div>
                </div>
            </div>

            <!-- Items -->
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">المنتج</th>
                        <th style="width: 15%; text-align: center;">الكمية</th>
                        <th style="width: 15%; text-align: left;">سعر الوحدة</th>
                        <th style="width: 20%; text-align: left;">المجموع</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        @php
                            $pName = $item['name'] ?? $item['product_name'] ?? 'منتج';
                            $qty = $item['quantity'] ?? 1;
                            $price = $item['price'] ?? 0;
                            $lineTotal = $price * $qty;
                            $hasVariants = !empty($item['size']) || !empty($item['color']);
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $pName }}</strong>
                                @if ($hasVariants)
                                    <div class="item-details">
                                        @if (!empty($item['size'])) المقاس: {{ $item['size'] }} @endif
                                        @if (!empty($item['color'])) | اللون: {{ $item['color'] }} @endif
                                    </div>
                                @endif
                            </td>
                            <td style="text-align: center; font-weight: bold;">{{ $qty }}</td>
                            <td style="text-align: left;">{{ number_format($price, 2) }} ج.م</td>
                            <td style="text-align: left; font-weight: bold;">{{ number_format($lineTotal, 2) }} ج.م</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Summary -->
            <div class="summary-wrapper">
                <div class="summary-box">
                    <div class="summary-line">
                        <span>المجموع الفرعي:</span>
                        <span>{{ number_format($order->subtotal ?? 0, 2) }} ج.م</span>
                    </div>
                    <div class="summary-line">
                        <span>سعر الشحن:</span>
                        <span>{{ number_format($order->shipping_cost ?? 0, 2) }} ج.م</span>
                    </div>
                    @if (($order->discount ?? 0) > 0)
                        <div class="summary-line" style="color: #dc2626;">
                            <span>الخصم:</span>
                            <span>-{{ number_format($order->discount, 2) }} ج.م</span>
                        </div>
                    @endif
                    <div class="summary-line total">
                        <span>الإجمالي المطلوب:</span>
                        <span style="color: #4f46e5;">{{ number_format($order->total ?? 0, 2) }} ج.م</span>
                    </div>
                </div>
            </div>

            @if ($order->notes)
                <div style="margin-top: 16px; background: #fffbeb; padding: 10px; border-radius: 6px; border: 1px solid #fef3c7; font-size: 12px; color: #92400e;">
                    <strong>ملاحظات العميل:</strong> {{ $order->notes }}
                </div>
            @endif

            <!-- Footer -->
            <div class="invoice-footer">
                <div class="barcode-box">*{{ $order->reference_number }}*</div>
                <div>شكراً لتعاملكم معنا ❤️</div>
            </div>
        </div>
    @endforeach

    <script>
        // Auto launch print dialog on load
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 600);
        });
    </script>
</body>
</html>
