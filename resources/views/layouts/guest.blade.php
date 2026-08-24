<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Order Saif — استعادة كلمة السر' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body {
            min-height: 100vh;
            min-height: 100dvh;
        }
        body {
            font-family: 'Cairo', sans-serif;
            background: #0f0c29;
            background: linear-gradient(135deg, #0f0c29 0%, #1a1a4e 50%, #24243e 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px 12px;
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;
            color: #fff;
        }
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 40%, rgba(99,102,241,0.12) 0%, transparent 50%),
                        radial-gradient(circle at 70% 60%, rgba(139,92,246,0.10) 0%, transparent 50%);
            animation: pulse 8s ease-in-out infinite alternate;
            pointer-events: none;
        }
        @keyframes pulse {
            0% { transform: scale(1) rotate(0deg); }
            100% { transform: scale(1.05) rotate(3deg); }
        }

        .card {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 24px;
            padding: 40px 32px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.05);
            position: relative;
            z-index: 1;
            margin: auto;
        }

        @media (max-width: 480px) {
            body {
                padding: 16px 8px;
            }
            .card {
                padding: 28px 18px;
                border-radius: 20px;
            }
        }

        .logo-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 28px;
            text-align: center;
        }
        .logo-icon {
            width: 110px; height: 110px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6, #ec4899);
            border-radius: 50%;
            padding: 3px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 12px 40px rgba(99, 102, 241, 0.25);
            transition: all 0.3s ease;
            flex-shrink: 0;
            text-decoration: none;
        }
        .logo-icon-inner {
            background: #111026;
            border-radius: 50%;
            width: 100%; height: 100%;
            display: flex; align-items: center; justify-content: center;
            padding: 8px;
            overflow: hidden;
        }
        .logo-icon-inner img {
            width: 100%; height: 100%;
            object-fit: contain;
            transform: scale(1.46);
        }
        .logo-text h1 { font-size: 22px; font-weight: 900; color: #fff; line-height: 1.2; }

        label { display: block; font-size: 13px; font-weight: 600; color: #a5b4fc; margin-bottom: 8px; text-align: right; }

        input[type="email"], input[type="password"], input[type="text"] {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 14px 16px;
            font-family: 'Cairo', sans-serif;
            font-size: 14px;
            color: #fff;
            outline: none;
            transition: all 0.2s;
            text-align: right;
        }
        input::placeholder { color: #6b7280; }
        input:focus {
            border-color: #6366f1;
            background: rgba(99,102,241,0.08);
            box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
        }

        .btn-primary {
            width: 100%;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 14px 20px;
            font-family: 'Cairo', sans-serif;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 8px 24px rgba(99,102,241,0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(99,102,241,0.5);
            background: linear-gradient(135deg, #5558e6 0%, #7c4dff 100%);
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo-wrap" style="margin-bottom: 24px;">
            <a href="/" style="display: inline-block;">
                <img src="{{ asset('images/logo2.png') }}?v={{ time() }}" alt="Order Saif" style="max-height: 80px; max-width: 260px; width: auto; height: auto; object-fit: contain; filter: drop-shadow(0 0 20px rgba(99,102,241,0.5));">
            </a>
        </div>

        {{ $slot }}
    </div>
</body>
</html>
