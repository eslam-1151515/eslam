<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'فاست أوردر'); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Arial, sans-serif; background: #f5f7fa; direction: rtl; }
        .container { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center; color: white; }
        .header h1 { font-size: 28px; font-weight: 700; margin-bottom: 5px; }
        .header p { opacity: 0.9; font-size: 14px; }
        .body { padding: 40px 30px; }
        .body h2 { font-size: 22px; color: #1a1a2e; margin-bottom: 15px; }
        .body p { color: #555; line-height: 1.7; margin-bottom: 15px; font-size: 15px; }
        .btn { display: inline-block; padding: 14px 32px; background: linear-gradient(135deg, #667eea, #764ba2); color: white !important; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; margin: 20px 0; }
        .info-box { background: #f8f9ff; border-right: 4px solid #667eea; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee; font-size: 14px; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #888; }
        .info-value { font-weight: 600; color: #333; }
        .footer { background: #f8f9ff; padding: 25px 30px; text-align: center; border-top: 1px solid #eee; }
        .footer p { color: #999; font-size: 13px; line-height: 1.6; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 600; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚡ فاست أوردر</h1>
            <p>منصة المتاجر الإلكترونية</p>
        </div>
        <div class="body">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
        <div class="footer">
            <p>هذا البريد أُرسل تلقائياً من منصة فاست أوردر.<br>
            إذا لم تكن تتوقع هذا البريد، يمكنك تجاهله بأمان.</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH E:\programing\flutter project\fast order\resources\views\emails\layouts\base.blade.php ENDPATH**/ ?>