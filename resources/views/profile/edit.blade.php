@extends('layouts.app')
@section('title', 'จัดการโปรไฟล์')
@section('page-title', 'จัดการโปรไฟล์')

@section('sidebar')
    @php $role = Auth::user()->role; @endphp
    @if($role === 'admin')
        @include('partials.sidebar-admin')
    @elseif($role === 'staff')
        @include('partials.sidebar-staff')
    @elseif($role === 'executive')
        @include('partials.sidebar-executive')
    @else
        @include('partials.sidebar-user')
    @endif
@endsection

@section('content')
<div class="row g-4">
    {{-- ข้อมูลโปรไฟล์ --}}
    <div class="col-lg-4">
        <div class="panel animate-in">
            <div class="panel-body text-center" style="padding: 32px 22px;">
                <div class="profile-avatar mx-auto mb-3" style="overflow: hidden;">
                    @if($user->profile_photo_path)
                        <img src="{{ Storage::url($user->profile_photo_path) }}" alt="Profile Photo" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        {{ mb_substr($user->name, 0, 1) }}
                    @endif
                </div>
                <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                <p class="text-muted mb-2" style="font-size: 14px;">{{ $user->email }}</p>
                <span class="badge bg-primary-soft">
                    @php
                        $roleLabels = ['user' => 'ผู้ใช้บริการ', 'staff' => 'เจ้าหน้าที่', 'executive' => 'ผู้บริหาร', 'admin' => 'ผู้ดูแลระบบ'];
                    @endphp
                    {{ $roleLabels[$user->role] ?? $user->role }}
                </span>

                <div class="profile-info-list mt-4">
                    <div class="profile-info-item">
                        <i class="bi bi-telephone"></i>
                        <span>{{ $user->phone ?: 'ยังไม่ได้ระบุ' }}</span>
                    </div>
                    <div class="profile-info-item">
                        <i class="bi bi-person-badge"></i>
                        <span>{{ $user->student_id ?: 'ยังไม่ได้ระบุ' }}</span>
                    </div>
                    <div class="profile-info-item">
                        <i class="bi bi-calendar3"></i>
                        <span>สมาชิกตั้งแต่ {{ $user->created_at->locale('th')->translatedFormat('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- แก้ไขข้อมูลส่วนตัว --}}
    <div class="col-lg-8">
        <div class="panel animate-in mb-4">
            <div class="panel-header">
                <h5><i class="bi bi-pencil-square me-2"></i>แก้ไขข้อมูลส่วนตัว</h5>
            </div>
            <div class="panel-body">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-12 mb-2">
                            <label for="profilePhoto" class="form-label">รูปโปรไฟล์</label>
                            <input type="file" class="form-control @error('profile_photo') is-invalid @enderror" 
                                   id="profilePhoto" name="profile_photo" accept="image/jpeg,image/png,image/gif">
                            @error('profile_photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">รองรับไฟล์ JPG, PNG, GIF ขนาดไม่เกิน 2MB</div>
                        </div>

                        <div class="col-md-6">
                            <label for="profileName" class="form-label">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="profileName" name="name" value="{{ old('name', $user->name) }}" required>
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="profileEmail" class="form-label">อีเมล <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="profileEmail" name="email" value="{{ old('email', $user->email) }}" required>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="profilePhone" class="form-label">เบอร์โทรศัพท์</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                       id="profilePhone" name="phone" value="{{ old('phone', $user->phone) }}"
                                       placeholder="0xx-xxx-xxxx">
                            </div>
                            @error('phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="profileStudentId" class="form-label">รหัสนักศึกษา</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                <input type="text" class="form-control @error('student_id') is-invalid @enderror"
                                       id="profileStudentId" name="student_id" value="{{ old('student_id', $user->student_id) }}"
                                       placeholder="รหัสนักศึกษา">
                            </div>
                            @error('student_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>บันทึกข้อมูล
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- เปลี่ยนรหัสผ่าน --}}
        <div class="panel animate-in">
            <div class="panel-header">
                <h5><i class="bi bi-shield-lock me-2"></i>เปลี่ยนรหัสผ่าน</h5>
            </div>
            <div class="panel-body">
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="currentPassword" class="form-label">รหัสผ่านปัจจุบัน <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                       id="currentPassword" name="current_password" required>
                            </div>
                            @error('current_password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="newPassword" class="form-label">รหัสผ่านใหม่ <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="newPassword" name="password" required>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="form-text">อย่างน้อย 8 ตัวอักษร</div>
                        </div>

                        <div class="col-md-6">
                            <label for="confirmNewPassword" class="form-label">ยืนยันรหัสผ่านใหม่ <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                                <input type="password" class="form-control"
                                       id="confirmNewPassword" name="password_confirmation" required>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-outline-custom">
                            <i class="bi bi-shield-check me-1"></i>เปลี่ยนรหัสผ่าน
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .profile-avatar {
        width: 96px;
        height: 96px;
        border-radius: 50%;
        background: var(--gradient-primary);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 36px;
        box-shadow: 0 8px 24px rgba(91,79,224,0.35), 0 0 0 5px rgba(91,79,224,0.1);
        letter-spacing: -0.5px;
    }

    .bg-primary-soft {
        background: rgba(91,79,224,0.12);
        color: var(--primary);
        font-weight: 600;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
    }

    .profile-info-list {
        text-align: left;
        border-top: 1px solid #eef2f1;
        padding-top: 16px;
    }

    .profile-info-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        font-size: 14px;
        color: #555;
        border-bottom: 1px solid #f5f5f7;
    }

    .profile-info-item:last-child {
        border-bottom: none;
    }

    .profile-info-item i {
        font-size: 16px;
        width: 20px;
        text-align: center;
        color: var(--primary);
    }

    .input-group-text {
        background: #f8f9fb;
        border: 1.5px solid #e0e0e0;
        border-right: none;
        color: #888;
    }

    .input-group .form-control {
        border-left: none;
    }

    .input-group .form-control:focus {
        border-color: var(--primary-light);
    }

    .input-group:focus-within .input-group-text {
        border-color: var(--primary-light);
        color: var(--primary);
    }
</style>
@endpush
