@extends('layouts.app')
@section('title', 'แก้ไขผู้ใช้')
@section('page-title', 'แก้ไขผู้ใช้: ' . $user->name)
@section('sidebar') @include('partials.sidebar-admin') @endsection

@section('content')
<div class="panel">
    <div class="panel-header"><h5><i class="bi bi-pencil me-2"></i>แก้ไขข้อมูลผู้ใช้</h5></div>
    <div class="panel-body">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">ชื่อ-นามสกุล *</label><input type="text" name="name" class="form-control" required value="{{ old('name', $user->name) }}"></div>
                <div class="col-md-6"><label class="form-label">อีเมล *</label><input type="email" name="email" class="form-control" required value="{{ old('email', $user->email) }}"></div>
                <div class="col-md-4"><label class="form-label">บทบาท *</label><select name="role" class="form-select" required><option value="user" {{ $user->role=='user'?'selected':'' }}>ผู้ใช้บริการ</option><option value="staff" {{ $user->role=='staff'?'selected':'' }}>เจ้าหน้าที่</option><option value="executive" {{ $user->role=='executive'?'selected':'' }}>ผู้บริหาร</option><option value="admin" {{ $user->role=='admin'?'selected':'' }}>ผู้ดูแลระบบ</option></select></div>
                <div class="col-md-4"><label class="form-label">รหัสนักศึกษา</label><input type="text" name="student_id" class="form-control" value="{{ old('student_id', $user->student_id) }}"></div>
                <div class="col-md-4"><label class="form-label">เบอร์โทร</label><input type="tel" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}"></div>
                <div class="col-md-6"><label class="form-label">รหัสผ่านใหม่ <small class="text-muted">(เว้นว่างถ้าไม่เปลี่ยน)</small></label><input type="password" name="password" class="form-control"></div>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>บันทึก</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </form>
    </div>
</div>
@endsection
