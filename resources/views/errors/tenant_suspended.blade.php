<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المتجر غير متاح حالياً — Order Saif</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Cairo', sans-serif; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            color: #ffffff;
        }
        .card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.5rem;
            padding: 3rem 2rem;
            max-width: 480px;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .icon-box {
            width: 80px;
            height: 80px;
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.2rem;
        }
        h1 { font-size: 1.5rem; font-weight: 800; margin-bottom: 0.75rem; color: #ffffff; }
        p { font-size: 0.95rem; color: #cbd5e1; line-height: 1.7; margin-bottom: 2rem; }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #ffffff;
            text-decoration: none;
            padding: 0.85rem 2rem;
            border-radius: 0.75rem;
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.4); }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon-box">⚠️</div>
        <h1>المتجر غير متاح حالياً</h1>
        <p>عذراً، هذا المتجر متوقف مؤقتاً لانتهاء مدة الاشتراك الخاصة به. إذا كنت صاحب المتجر، يرجى تسجيل الدخول إلى لوحة التحكم لتجديد الاشتراك وإعادة تفعيل متجرك أمام العملاء.</p>
        <a href="/admin/login" class="btn">تسجيل دخول التاجر</a>
    </div>
</body>
</html>
