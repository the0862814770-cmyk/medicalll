@extends('layouts.app')
@section('title', 'คำร้องยืมกระเป๋า')
@section('page-title', 'อนุมัติคำร้องยืมกระเป๋า')
@section('sidebar') @include('partials.sidebar-executive') @endsection

@section('content')
<div class="panel">
    <div class="panel-header d-flex align-items-center justify-content-between">
        <h5><i class="bi bi-bag-heart me-2"></i>คำร้องยืมกระเป๋าปฐมพยาบาล</h5>
        <form class="d-flex gap-2">
            <select name="status" class="form-select form-select-sm" style="width:auto" onchange="this.form.submit()">
                <option value="" {{ request('status') === null || request('status') === '' ? 'selected' : '' }}>ทุกสถานะ</option>
                <option value="pending" {{ request('status')==='pending' ? 'selected' : '' }}>รอดำเนินการ</option>
                <option value="executive_approved" {{ request('status')==='executive_approved' ? 'selected' : '' }}>อนุมัติโดยผู้บริหาร</option>
                <option value="approved" {{ request('status')==='approved' ? 'selected' : '' }}>อนุมัติโดยพยาบาล</option>
                <option value="borrowed" {{ request('status')==='borrowed' ? 'selected' : '' }}>กำลังยืม</option>
                <option value="return_pending" {{ request('status')==='return_pending' ? 'selected' : '' }}>รอรับคืน</option>
                <option value="returned" {{ request('status')==='returned' ? 'selected' : '' }}>คืนแล้ว</option>
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
                    <th>กระเป๋า</th>
                    <th>วัตถุประสงค์</th>
                    <th>วันยืม-คืน</th>
                    <th>สถานะ</th>
                    <th class="text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody>
            @forelse($requests as $req)
                <tr>
                    <td><strong>{{ $req->request_number }}</strong></td>
                    <td>{{ $req->user->name }}</td>
                    <td>{{ $req->kit->name ?? '-' }}</td>
                    <td>{{ Str::limit($req->purpose, 25) }}</td>
                    <td>{{ $req->borrow_date->format('d/m') }} - {{ $req->expected_return_date->format('d/m/Y') }}</td>
                    <td><span class="badge bg-{{ $req->status_color }} badge-status">{{ $req->status_label }}</span></td>
                    <td class="text-center">
                        <a href="{{ route('executive.requests.kit.show', $req) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye me-1"></i>ดู</a>
                        @if($req->status === 'pending')
                            <form action="{{ route('executive.requests.kit.approve', $req) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-success"><i class="bi bi-check me-1"></i>อนุมัติ</button>
                            </form>
                            <form action="{{ route('executive.requests.kit.reject', $req) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-danger"><i class="bi bi-x me-1"></i>ปฏิเสธ</button>
                            </form>
                        @endif
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
