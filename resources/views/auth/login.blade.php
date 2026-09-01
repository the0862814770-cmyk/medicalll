@extends('layouts.guest')
@section('title', 'เข้าสู่ระบบ')

@section('content')
<style>
    /* Override guest layout styles for modern split login */
    body {
        background: radial-gradient(circle at 15% 20%, rgba(56, 189, 248, 0.15), transparent 45%),
                    radial-gradient(circle at 85% 80%, rgba(99, 102, 241, 0.18), transparent 45%),
                    linear-gradient(160deg, #f0f4f8 0%, #e9edf5 100%) !important;
        overflow: auto !important;
    }

    body::before { display: none; }

    .auth-container {
        max-width: 960px !important;
        padding: 2rem 1rem !important;
    }

    .auth-card {
        padding: 0 !important;
        border-radius: 24px !important;
        overflow: hidden;
        box-shadow: 0 25px 60px -15px rgba(30, 41, 59, 0.25), 0 0 0 1px rgba(255,255,255,0.4) !important;
    }

    /* ---------- Left brand panel ---------- */

.login-brand {
    background:
        radial-gradient(circle at 20% 12%, rgba(255,255,255,0.16), transparent 42%),
        radial-gradient(circle at 88% 85%, rgba(56,189,248,0.35), transparent 50%),
        linear-gradient(155deg, #4338ca 0%, #4f46e5 40%, #4f8ef7 75%, #38bdf8 100%);
    position: relative;
    overflow: hidden;
    min-height: 560px;

    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;

    padding: 2rem;
}

.login-brand > * {
    position: relative;
    z-index: 1;
}

.login-brand-logo {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px;
    margin-bottom: 1.5rem;
    box-shadow: 0 15px 30px rgba(0,0,0,.25);
}

.login-brand-logo img{
    width:100%;
    height:100%;
    object-fit:contain;
}

.login-brand h2{
    color:#fff;
    font-size:1.6rem;
    font-weight:800;
    line-height:1.5;
    margin:0 0 1rem;
    max-width:380px;
}

.login-brand p{
    color:rgba(255,255,255,.9);
    font-size:.95rem;
    line-height:1.8;
    max-width:380px;
    margin:0;
}

    /* ---------- Right form panel ---------- */

    .login-panel {
        padding: 3.25rem 3rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .login-panel h3 {
        color: #1e293b;
        letter-spacing: -0.01em;
        font-size: 1.55rem;
        font-weight: 800;
        margin-bottom: 0.4rem;
    }

    .login-panel .subtitle {
        color: #64748b;
        font-size: 0.92rem;
        margin-bottom: 2rem;
    }

    .field-group {
        margin-bottom: 1.15rem;
    }

    .field-group label {
        font-weight: 600;
        color: #334155;
        font-size: 0.88rem;
        margin-bottom: 0.4rem;
        display: inline-block;
    }

    .input-icon-wrap {
        position: relative;
    }

    .input-icon-wrap i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.95rem;
        pointer-events: none;
        transition: color 0.2s ease;
    }

    .login-panel input[type="email"],
    .login-panel input[type="password"] {
        width: 100%;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.75rem 1rem 0.75rem 2.6rem;
        font-size: 0.95rem;
        background: #f8fafc;
        transition: all 0.2s ease;
        color: #1e293b;
    }

    .login-panel input:focus {
        outline: none;
        border-color: #6366f1;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
    }

    .login-panel input:focus + i,
    .input-icon-wrap:focus-within i {
        color: #6366f1;
    }

    .login-panel .form-check-input {
        width: 1.05rem;
        height: 1.05rem;
        border-radius: 5px;
        border: 1.5px solid #cbd5e1;
        cursor: pointer;
    }

    .login-panel a {
        color: #6366f1;
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .login-panel a:hover {
        color: #4338ca;
    }

    .btn-login-primary {
        background: linear-gradient(135deg, #4338ca, #312e81);
        border: none;
        border-radius: 12px;
        padding: 0.8rem 2rem;
        font-weight: 600;
        font-size: 0.95rem;
        color: #ffffff;
        letter-spacing: 0.01em;
        box-shadow: 0 12px 22px -8px rgba(49, 46, 129, 0.55);
        transition: all 0.2s ease;
        cursor: pointer;
        width: 100%;
        margin-top: 0.5rem;
    }

    .btn-login-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 16px 26px -8px rgba(49, 46, 129, 0.65);
        color: #ffffff;
    }

    .login-back-link {
        text-align: center;
        margin-top: 1.5rem;
        font-size: 0.88rem;
    }

    .login-footer-note {
        text-align: center;
        color: #94a3b8;
        font-size: 0.78rem;
        margin-top: 1.75rem;
    }
    .login-brand h1{
    color:#fff;
    font-size:1.45rem;
    font-weight:700;
    line-height:1.45;
    margin:1.5rem 0 1rem;
    width:100%;
    max-width:440px;
    text-align:left;
    white-space:nowrap;
}

.login-brand p{
    color:rgba(255,255,255,.9);
    font-size:1rem;
    line-height:1.8;
    width:100%;
    max-width:420px;
    margin:0;
    text-align:left;
}

    @media (max-width: 991.98px) {
        .login-brand {
            display: none;
        }
        .login-panel {
            padding: 2.25rem 1.5rem;
        }
    }
</style>
<div class="auth-card">
    <div class="row g-0">
        <div class="col-lg-6 login-brand">
            <div class="login-brand-blob-2"></div>
            <div class="login-brand-logo">
                <img src="{{ asset('images/logo.png') }}" alt="โลโก้มหาวิทยาลัยราชภัฏนครศรีธรรมราช">
            </div>
            <h1><span class="login-brand-line">ระบบสารสนเทศบริหารคลังเวชภัณฑ์</span><br>
                <span class="login-brand-line">มหาวิทยาลัยราชภัฏนครศรีธรรมราช</span>
            </h1>
            <p>
                ระบบสำหรับบริหารจัดการข้อมูลเวชภัณฑ์ การยื่นคำร้องขอรับยา
                และการติดตามสถานะคำร้อง ภายในมหาวิทยาลัยราชภัฏนครศรีธรรมราช
            </p>
        </div>

        <div class="col-lg-6 login-panel">
            <h3>เข้าสู่ระบบ</h3>
            <p class="subtitle">กรุณากรอกอีเมลและรหัสผ่านของคุณ</p>

            @if(session('error'))
                <div class="alert alert-danger mb-3">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field-group">
                    <label for="email" class="form-label">อีเมล</label>
                    <div class="input-icon-wrap">
                        <i class="bi bi-person-fill"></i>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="กรอกอีเมลของคุณ"
                               required autofocus autocomplete="username">
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field-group">
                    <label for="password" class="form-label">รหัสผ่าน</label>
                    <div class="input-icon-wrap">
                        <i class="bi bi-lock-fill"></i>
                        <input id="password" type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="กรอกรหัสผ่าน"
                               required autocomplete="current-password">
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="d-inline-flex align-items-center" style="cursor:pointer;">
                        <input type="checkbox" class="form-check-input me-2" name="remember">
                        <span style="font-size:0.9rem;color:#64748b;">จดจำการเข้าสู่ระบบ</span>
                    </label>
                </div>

                <button type="submit" class="btn-login-primary">
                    <i class="bi bi-box-arrow-in-right me-2"></i>เข้าสู่ระบบ
                </button>
            </form>

            <div class="login-back-link">
                <a href="{{ url('/') }}"><i class="bi bi-arrow-left me-1"></i>กลับหน้าหลัก</a>
            </div>

            <p class="login-footer-note">สำหรับผู้ใช้งานที่ได้รับสิทธิ์เข้าถึงระบบเท่านั้น</p>
        </div>
    </div>
</div>
@endsection