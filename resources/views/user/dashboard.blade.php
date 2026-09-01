@extends('layouts.app')
@section('title', 'แดชบอร์ด')
@section('page-title', 'แดชบอร์ด')
@section('sidebar') @include('partials.sidebar-user') @endsection

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6 animate-in">
        <div class="stat-card blue">
            <div class="stat-icon"><i class="bi bi-capsule"></i></div>
            <div class="stat-value">{{ $stats['total_medicine_requests'] }}</div>
            <div class="stat-label">คำร้องขอยาทั้งหมด</div>
        </div>
    </div>
    <div class="col-md-3 col-6 animate-in">
        <div class="stat-card gold">
            <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
            <div class="stat-value">{{ $stats['pending_medicine_requests'] }}</div>
            <div class="stat-label">รอดำเนินการ</div>
        </div>
    </div>
    <div class="col-md-3 col-6 animate-in">
        <div class="stat-card green">
            <div class="stat-icon"><i class="bi bi-briefcase"></i></div>
            <div class="stat-value">{{ $stats['total_kit_requests'] }}</div>
            <div class="stat-label">คำร้องยืมกระเป๋า</div>
        </div>
    </div>
    <div class="col-md-3 col-6 animate-in">
        <div class="stat-card purple">
            <div class="stat-icon"><i class="bi bi-bag-check"></i></div>
            <div class="stat-value">{{ $stats['active_kit_borrows'] }}</div>
            <div class="stat-label">กำลังยืมอยู่</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="panel">
            <div class="panel-header">
                <h5><i class="bi bi-capsule me-2"></i>คำร้องขอยาล่าสุด</h5>
                <a href="{{ route('user.medicine-requests.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus me-1"></i>ขอรับยา</a>
            </div>
            <div class="panel-body p-0">
                @if($recentMedicineRequests->isEmpty())
                    <div class="empty-state"><i class="bi bi-inbox d-block"></i>ยังไม่มีคำร้อง</div>
                @else
                    <table class="table table-modern">
                        <thead><tr><th>เลขที่</th><th>อาการ</th><th>สถานะ</th><th>วันที่</th></tr></thead>
                        <tbody>
                            @foreach($recentMedicineRequests as $req)
                            <tr>
                                <td><a href="{{ route('user.medicine-requests.show', $req) }}">{{ $req->request_number }}</a></td>
                                <td>{{ Str::limit($req->symptoms, 30) }}</td>
                                <td><span class="badge bg-{{ $req->status_color }} badge-status">{{ $req->status_label }}</span></td>
                                <td>{{ $req->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel">
            <div class="panel-header">
                <h5><i class="bi bi-briefcase me-2"></i>การยืมกระเป๋าล่าสุด</h5>
                <a href="{{ route('user.kit-requests.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus me-1"></i>ขอยืม</a>
            </div>
            <div class="panel-body p-0">
                @if($recentKitRequests->isEmpty())
                    <div class="empty-state"><i class="bi bi-inbox d-block"></i>ยังไม่มีการยืม</div>
                @else
                    <table class="table table-modern">
                        <thead><tr><th>กระเป๋า</th><th>วันที่ยืม</th><th>สถานะ</th><th>จัดการ</th></tr></thead>
                        <tbody>
                            @foreach($recentKitRequests as $req)
                            <tr>
                                <td>{{ $req->kit->name ?? '-' }}</td>
                                <td>{{ $req->borrow_date->format('d/m/Y') }}</td>
                                <td><span class="badge bg-{{ $req->status_color }} badge-status">{{ $req->status_label }}</span></td>
                                <td>
                                    @if($req->status === 'borrowed')
                                        <form action="{{ route('user.kit-requests.return', $req) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-warning" title="แจ้งคืน"><i class="bi bi-arrow-return-left"></i></button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
