<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'เข้าสู่ระบบ') - ระบบบริหารคลังเวชภัณฑ์ มรภ.นครศรีธรรมราช</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <meta name="description" content="ระบบสารสนเทศบริหารคลังเวชภัณฑ์ มหาวิทยาลัยราชภัฏนครศรีธรรมราช">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Sarabun', sans-serif;
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #0e2244 0%, #1a3c6e 30%, #2a5298 60%, #1a3c6e 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(ellipse at 30% 50%, rgba(232,168,56,0.08) 0%, transparent 50%),
                        radial-gradient(ellipse at 70% 20%, rgba(42,82,152,0.15) 0%, transparent 40%),
                        radial-gradient(ellipse at 50% 80%, rgba(232,168,56,0.05) 0%, transparent 40%);
            animation: bgFloat 20s ease-in-out infinite;
        }

        @keyframes bgFloat {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -20px) rotate(1deg); }
            66% { transform: translate(-20px, 10px) rotate(-1deg); }
        }

        .auth-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 460px;
            padding: 20px;
        }

        .auth-card {
            background: rgba(255,255,255,0.97);
            border-radius: 20px;
            padding: 40px 36px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3), 0 0 0 1px rgba(255,255,255,0.1);
            backdrop-filter: blur(20px);
        }

        .auth-logo {
            text-align: center;
            margin-bottom: 28px;
        }

        .auth-logo .logo-icon {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #1a3c6e, #2a5298);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: #fff;
            margin-bottom: 16px;
            box-shadow: 0 8px 24px rgba(26,60,110,0.25);
            position: relative;
        }

        .auth-logo .logo-icon::after {
            content: '';
            position: absolute;
            inset: -3px;
            border-radius: 22px;
            background: linear-gradient(135deg, #e8a838, #f5c563);
            z-index: -1;
            opacity: 0.6;
        }

        .auth-logo h4 {
            font-size: 18px;
            font-weight: 700;
            color: #1a3c6e;
            margin: 0 0 4px;
        }

        .auth-logo p {
            font-size: 13px;
            color: #888;
            margin: 0;
        }

        .form-floating {
            margin-bottom: 16px;
        }

        .form-floating .form-control {
            border-radius: 12px;
            border: 1.5px solid #e0e0e0;
            padding: 16px 14px 8px;
            font-size: 15px;
            height: 56px;
        }

        .form-floating .form-control:focus {
            border-color: #2a5298;
            box-shadow: 0 0 0 3px rgba(42,82,152,0.12);
        }

        .form-floating label {
            font-size: 14px;
            color: #888;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #1a3c6e, #2a5298);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(26,60,110,0.35);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .divider {
            text-align: center;
            margin: 20px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e0e0e0;
        }

        .divider span {
            background: #fff;
            padding: 0 16px;
            position: relative;
            font-size: 13px;
            color: #999;
        }

        .auth-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }

        .auth-footer a {
            color: #2a5298;
            font-weight: 600;
            text-decoration: none;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 12px;
            border: none;
            font-size: 14px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
