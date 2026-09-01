@extends('layouts.app')
@section('title', 'จัดการผู้ใช้')
@section('page-title', 'จัดการผู้ใช้งาน')
@section('sidebar') @include('partials.sidebar-admin') @endsection

@section('content')
<div class="panel">
    <div class="panel-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-people me-2"></i>ผู้ใช้งานทั้งหมด</h5>
        <div>
            <button type="button" class="btn btn-success btn-sm me-2" data-bs-toggle="modal" data-bs-target="#importUsersModal">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i>นำเข้าผู้ใช้ (Excel/CSV)
            </button>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-person-plus me-1"></i>เพิ่มผู้ใช้</a>
        </div>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="importUsersModal" tabindex="-1" aria-labelledby="importUsersModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="importUsersModalLabel"><i class="bi bi-file-earmark-spreadsheet me-2"></i>นำเข้าผู้ใช้งานจากไฟล์</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="import_file" class="form-label">เลือกไฟล์ Excel หรือ CSV</label>
                            <input class="form-control" type="file" id="import_file" name="import_file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
                            <div class="form-text mt-2">
                                กรุณาใช้ไฟล์ตามเทมเพลตที่กำหนด หากยังไม่มีสามารถ <a href="{{ route('admin.users.template') }}">ดาวน์โหลดเทมเพลตได้ที่นี่</a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-success"><i class="bi bi-upload me-1"></i>เริ่มนำเข้าข้อมูล</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <form class="row g-2 mb-3">
            <div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="ค้นหาชื่อ, อีเมล, รหัสนักศึกษา..." value="{{ request('search') }}"></div>
            <div class="col-md-3"><select name="role" class="form-select"><option value="">ทุกบทบาท</option><option value="user" {{ request('role')=='user'?'selected':'' }}>ผู้ใช้บริการ</option><option value="staff" {{ request('role')=='staff'?'selected':'' }}>เจ้าหน้าที่</option><option value="executive" {{ request('role')=='executive'?'selected':'' }}>ผู้บริหาร</option><option value="admin" {{ request('role')=='admin'?'selected':'' }}>ผู้ดูแลระบบ</option></select></div>
            <div class="col-md-2"><select name="status" class="form-select"><option value="">ทุกสถานะ</option><option value="active" {{ request('status')=='active'?'selected':'' }}>ใช้งาน</option><option value="suspended" {{ request('status')=='suspended'?'selected':'' }}>ระงับ</option></select></div>
            <div class="col-md-3"><button class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>ค้นหา</button></div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-modern">
            <thead><tr><th>ชื่อ</th><th>อีเมล</th><th>รหัสนักศึกษา</th><th>เบอร์โทร</th><th>บทบาท</th><th>สถานะ</th><th class="text-center">จัดการ</th></tr></thead>
            <tbody>
            @php $roleLabels = ['user'=>'ผู้ใช้บริการ','staff'=>'เจ้าหน้าที่','executive'=>'ผู้บริหาร','admin'=>'ผู้ดูแลระบบ']; @endphp
            @forelse($users as $user)
                <tr>
                    <td><strong>{{ $user->name }}</strong></td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->student_id ?? '-' }}</td>
                    <td>{{ $user->phone ?? '-' }}</td>
                    <td><span class="badge bg-primary badge-status">{{ $roleLabels[$user->role] ?? $user->role }}</span></td>
                    <td><span class="badge bg-{{ $user->status === 'active' ? 'success' : 'danger' }} badge-status">{{ $user->status === 'active' ? 'ใช้งาน' : 'ระงับ' }}</span></td>
                    <td class="text-center">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary" title="แก้ไข"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline">@csrf
                            <button class="btn btn-sm {{ $user->status === 'active' ? 'btn-outline-warning' : 'btn-outline-success' }}" title="{{ $user->status === 'active' ? 'ระงับ' : 'เปิดใช้งาน' }}">
                                <i class="bi {{ $user->status === 'active' ? 'bi-pause-circle' : 'bi-play-circle' }}"></i>
                            </button>
                        </form>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('ยืนยันลบผู้ใช้?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="ลบ"><i class="bi bi-trash"></i></button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">ไม่พบผู้ใช้</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $users->withQueryString()->links() }}</div>
</div>
@endsection
