@extends('layouts.app')
@section('title', 'เพิ่มผู้ใช้')
@section('page-title', 'เพิ่มผู้ใช้งานใหม่')
@section('sidebar') @include('partials.sidebar-admin') @endsection

@section('content')
<div class="panel">
    <div class="panel-header"><h5><i class="bi bi-person-plus me-2"></i>เพิ่มผู้ใช้งานใหม่</h5></div>
    <div class="panel-body">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">ชื่อ-นามสกุล *</label><input type="text" name="name" class="form-control" required value="{{ old('name') }}"></div>
                <div class="col-md-6"><label class="form-label">อีเมล *</label><input type="email" name="email" class="form-control" required value="{{ old('email') }}"></div>
                <div class="col-md-4"><label class="form-label">บทบาท *</label><select name="role" class="form-select" required><option value="user">ผู้ใช้บริการ</option><option value="staff">เจ้าหน้าที่</option><option value="executive">ผู้บริหาร</option><option value="admin">ผู้ดูแลระบบ</option></select></div>
                <div class="col-md-4"><label class="form-label">รหัสนักศึกษา</label><input type="text" name="student_id" class="form-control" value="{{ old('student_id') }}"></div>
                <div class="col-md-4"><label class="form-label">เบอร์โทร</label><input type="tel" name="phone" class="form-control" value="{{ old('phone') }}"></div>
                <div class="col-md-6"><label class="form-label">รหัสผ่าน *</label><input type="password" name="password" class="form-control" required></div>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>บันทึก</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </form>
    </div>
</div>
@endsection
