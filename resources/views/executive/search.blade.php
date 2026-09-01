@extends('layouts.app')
@section('title', 'ค้นหาเวชภัณฑ์')
@section('page-title', 'ค้นหาเวชภัณฑ์และประวัติ')
@section('sidebar') @include('partials.sidebar-executive') @endsection

@section('content')
<div class="panel mb-3">
    <div class="panel-header"><h5><i class="bi bi-search me-2"></i>ค้นหา</h5></div>
    <div class="panel-body">
        <form class="row g-2">
            <div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="ค้นหาชื่อหรือรหัสเวชภัณฑ์..." value="{{ request('search') }}"></div>
            <div class="col-md-3"><input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="จากวันที่"></div>
            <div class="col-md-3"><input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="ถึงวันที่"></div>
            <div class="col-md-2"><button class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>ค้นหา</button></div>
        </form>
    </div>
</div>

@if(request('search'))
    @if($supplies->isNotEmpty())
    <div class="panel mb-3">
        <div class="panel-header"><h5><i class="bi bi-capsule me-2"></i>เวชภัณฑ์ที่พบ ({{ $supplies->count() }})</h5></div>
        <div class="table-responsive">
            <table class="table table-modern">
                <thead><tr><th>รหัส</th><th>ชื่อ</th><th>หมวดหมู่</th><th>คงเหลือ</th></tr></thead>
                <tbody>
                @foreach($supplies as $s)
                    <tr><td>{{ $s->code }}</td><td><strong>{{ $s->name }}</strong></td><td>{{ $s->category->name ?? '-' }}</td><td>{{ $s->total_stock }} {{ $s->unit }}</td></tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="panel">
        <div class="panel-header"><h5><i class="bi bi-clock-history me-2"></i>ประวัติธุรกรรม</h5></div>
        <div class="table-responsive">
            <table class="table table-modern">
                <thead><tr><th>วันที่</th><th>เวชภัณฑ์</th><th>ประเภท</th><th>จำนวน</th><th>ผู้ดำเนินการ</th></tr></thead>
                <tbody>
                @forelse($transactions as $txn)
                    <tr><td>{{ $txn->created_at->format('d/m/Y H:i') }}</td><td>{{ $txn->supply->name ?? '-' }}</td><td><span class="badge bg-{{ $txn->type_color }} badge-status">{{ $txn->type_label }}</span></td><td>{{ $txn->quantity }}</td><td>{{ $txn->performer->name ?? '-' }}</td></tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">ไม่พบข้อมูล</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="p-3">{{ $transactions->withQueryString()->links() }}</div>
        @endif
    </div>
@endif
@endsection
