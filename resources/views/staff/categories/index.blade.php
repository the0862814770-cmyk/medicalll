@extends('layouts.app')
@section('title', 'จัดการหมวดหมู่')
@section('page-title', 'จัดการหมวดหมู่เวชภัณฑ์')
@section('sidebar') @include('partials.sidebar-staff') @endsection

@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="panel">
            <div class="panel-header"><h5><i class="bi bi-plus me-2"></i>เพิ่มหมวดหมู่</h5></div>
            <div class="panel-body">
                <form action="{{ route('staff.categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3"><label class="form-label">ชื่อหมวดหมู่ *</label><input type="text" name="name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">คำอธิบาย</label><textarea name="description" class="form-control" rows="2"></textarea></div>
                    <button class="btn btn-primary w-100"><i class="bi bi-check-lg me-1"></i>เพิ่ม</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="panel">
            <div class="panel-header"><h5><i class="bi bi-tags me-2"></i>หมวดหมู่ทั้งหมด</h5></div>
            <div class="panel-body p-0">
                <table class="table table-modern">
                    <thead><tr><th>ชื่อ</th><th>คำอธิบาย</th><th>จำนวนเวชภัณฑ์</th><th class="text-center">จัดการ</th></tr></thead>
                    <tbody>
                    @forelse($categories as $cat)
                        <tr>
                            <td><strong>{{ $cat->name }}</strong></td>
                            <td>{{ $cat->description ?? '-' }}</td>
                            <td>{{ $cat->supplies_count }} รายการ</td>
                            <td class="text-center">
                                <form action="{{ route('staff.categories.destroy', $cat) }}" method="POST" class="d-inline" onsubmit="return confirm('ยืนยันลบ?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">ยังไม่มีหมวดหมู่</td></tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="p-3">{{ $categories->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
