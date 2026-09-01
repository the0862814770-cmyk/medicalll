@extends('layouts.guest')
@section('title', 'ลงทะเบียน')

@section('content')
<div class="auth-card">
    <div class="auth-logo">
        <div class="logo-icon"><i class="bi bi-hospital"></i></div>
        <h4>ลงทะเบียนผู้ใช้บริการ</h4>
        <p>ระบบบริหารคลังเวชภัณฑ์ มรภ.นครศรีธรรมราช</p>
    </div>

    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div class="form-floating">
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   id="name" name="name" value="{{ old('name') }}" placeholder="ชื่อ-นามสกุล" required>
            <label for="name"><i class="bi bi-person me-1"></i> ชื่อ-นามสกุล</label>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-floating">
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email') }}" placeholder="อีเมล" required>
            <label for="email"><i class="bi bi-envelope me-1"></i> อีเมล</label>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row g-2">
            <div class="col-md-6">
                <div class="form-floating">
                    <input type="text" class="form-control" id="student_id" name="student_id" value="{{ old('student_id') }}" placeholder="รหัสนักศึกษา">
                    <label for="student_id"><i class="bi bi-card-text me-1"></i> รหัสนักศึกษา</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating">
                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" placeholder="เบอร์โทร">
                    <label for="phone"><i class="bi bi-telephone me-1"></i> เบอร์โทร</label>
                </div>
            </div>
        </div>

        <div class="form-floating mt-2">
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                   id="password" name="password" placeholder="รหัสผ่าน" required>
            <label for="password"><i class="bi bi-lock me-1"></i> รหัสผ่าน (อย่างน้อย 8 ตัวอักษร)</label>
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-floating">
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="ยืนยันรหัสผ่าน" required>
            <label for="password_confirmation"><i class="bi bi-lock-fill me-1"></i> ยืนยันรหัสผ่าน</label>
        </div>

        <button type="submit" class="btn btn-login mt-2">
            <i class="bi bi-person-plus me-2"></i>ลงทะเบียน
        </button>
    </form>

    <div class="auth-footer">
        มีบัญชีอยู่แล้ว? <a href="{{ route('login') }}">เข้าสู่ระบบ</a>
    </div>
</div>
@endsection
