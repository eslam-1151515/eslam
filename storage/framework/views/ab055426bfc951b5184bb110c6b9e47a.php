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
            <h2>تقرير الطلبات (<?php echo e(count($orders)); ?> طلب)</h2>
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
                <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="font-weight:bold; color:#4f46e5"><?php echo e($order->reference_number); ?></td>
                    <td><?php echo e($order->customer_name); ?></td>
                    <td dir="ltr"><?php echo e($order->customer_phone); ?></td>
                    <td><?php echo e($order->governorate); ?></td>
                    <td><?php echo e($order->customer_address); ?></td>
                    <td>
                        <?php
                            $items = is_string($order->items) ? json_decode($order->items, true) : $order->items;
                        ?>
                        <?php if(is_array($items)): ?>
                            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div>
                                    - <?php echo e($item['name'] ?? 'منتج'); ?> x<?php echo e($item['quantity'] ?? 1); ?>

                                    <?php if(isset($item['selectedSize'])): ?> | مقاس: <?php echo e($item['selectedSize']); ?> <?php endif; ?>
                                    <?php if(isset($item['selectedColor'])): ?> | لون: <?php echo e($item['selectedColor']); ?> <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e(number_format($order->subtotal)); ?></td>
                    <td><?php echo e(number_format($order->shipping_cost)); ?></td>
                    <td style="font-weight:bold; color:#10b981"><?php echo e(number_format($order->total)); ?></td>
                    <td><?php echo e($order->notes); ?></td>
                    <td dir="ltr"><?php echo e($order->created_at->format('Y/m/d h:i A')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php /**PATH E:\programing\flutter project\fast order\resources\views/orders/export_print.blade.php ENDPATH**/ ?>