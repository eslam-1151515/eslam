<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير الطلبات</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 100%;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: right;
        }
        th {
            background-color: #4f46e5;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .header-actions {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .btn-print {
            background-color: #10b981;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        @media print {
            .btn-print {
                display: none;
            }
            body {
                background: white;
                padding: 0;
            }
            .container {
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-actions">
            <h2>تقرير الطلبات ({{ count($orders) }} طلب)</h2>
            <button class="btn-print" onclick="window.print()">🖨️ طباعة / حفظ كـ PDF</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>الرقم المرجعي</th>
                    <th>العميل</th>
                    <th>الهاتف</th>
                    <th>المحافظة</th>
                    <th>العنوان</th>
                    <th>المنتجات</th>
                    <th>المجموع الفرعي</th>
                    <th>الشحن</th>
                    <th>الإجمالي</th>
                    <th>ملاحظات</th>
                    <th>التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td style="font-weight:bold; color:#4f46e5">{{ $order->reference_number }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td dir="ltr">{{ $order->customer_phone }}</td>
                    <td>{{ $order->governorate }}</td>
                    <td>{{ $order->customer_address }}</td>
                    <td>
                        @php
                            $items = is_string($order->items) ? json_decode($order->items, true) : $order->items;
                        @endphp
                        @if(is_array($items))
                            @foreach($items as $item)
                                <div>
                                    - {{ $item['name'] ?? 'منتج' }} x{{ $item['quantity'] ?? 1 }}
                                    @if(isset($item['selectedSize'])) | مقاس: {{ $item['selectedSize'] }} @endif
                                    @if(isset($item['selectedColor'])) | لون: {{ $item['selectedColor'] }} @endif
                                </div>
                            @endforeach
                        @endif
                    </td>
                    <td>{{ number_format($order->subtotal) }}</td>
                    <td>{{ number_format($order->shipping_cost) }}</td>
                    <td style="font-weight:bold; color:#10b981">{{ number_format($order->total) }}</td>
                    <td>{{ $order->notes }}</td>
                    <td dir="ltr">{{ $order->created_at->format('Y/m/d h:i A') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script>
        // Auto trigger print dialogue when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
