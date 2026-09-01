@extends('layouts.app')
@section('title', 'คำร้องขอรับยา')
@section('page-title', 'อนุมัติคำร้องขอรับยา')
@section('sidebar') @include('partials.sidebar-executive') @endsection

@section('content')
<div class="panel">
    <div class="panel-header d-flex align-items-center justify-content-between">
        <h5><i class="bi bi-file-earmark-medical me-2"></i>คำร้องขอรับยา</h5>
        <form class="d-flex gap-2">
            <select name="status" class="form-select form-select-sm" style="width:auto" onchange="this.form.submit()">
                <option value="" {{ request('status') === null || request('status') === '' ? 'selected' : '' }}>ทุกสถานะ</option>
                <option value="pending" {{ request('status')==='pending' ? 'selected' : '' }}>รอดำเนินการ</option>
                <option value="executive_approved" {{ request('status')==='executive_approved' ? 'selected' : '' }}>อนุมัติแล้วโดยผู้บริหาร</option>
                <option value="approved" {{ request('status')==='approved' ? 'selected' : '' }}>อนุมัติแล้ว</option>
                <option value="dispensed" {{ request('status')==='dispensed' ? 'selected' : '' }}>จ่ายแล้ว</option>
                <option value="rejected" {{ request('status')==='rejected' ? 'selected' : '' }}>ปฏิเสธ</option>
            </select>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-modern">
            <thead>
                <tr>
                    <th>เลขที่</th>
                    <th>ผู้ขอ</th>
                    <th>อาการ</th>
                    <th>รายการ</th>
                    <th>สถานะ</th>
                    <th>วันที่</th>
                    <th class="text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody>
            @forelse($requests as $req)
                <tr>
                    <td><strong>{{ $req->request_number }}</strong></td>
                    <td>{{ $req->user->name }}<br><small class="text-muted">{{ $req->user->student_id ?? '-' }}</small></td>
                    <td>{{ Str::limit($req->symptoms, 30) }}</td>
                    <td>{{ $req->items->count() }} รายการ</td>
                    <td><span class="badge bg-{{ $req->status_color }} badge-status">{{ $req->status_label }}</span></td>
                    <td>{{ $req->created_at->format('d/m/Y H:i') }}</td>
                    <td class="text-center">
                        <a href="{{ route('executive.requests.medicine.show', $req) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye me-1"></i>ดู</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">ไม่มีคำร้อง</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $requests->withQueryString()->links() }}</div>
</div>
@endsection
