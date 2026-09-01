@extends('layouts.app')
@section('title', 'ประวัติธุรกรรม')
@section('page-title', 'ประวัติธุรกรรมเวชภัณฑ์')
@section('sidebar') @include('partials.sidebar-staff') @endsection

@section('content')
<div class="panel">
    <div class="panel-header">
        <h5><i class="bi bi-clock-history me-2"></i>ประวัติธุรกรรม</h5>
        <a href="{{ route('staff.transactions.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus me-1"></i>บันทึกธุรกรรม</a>
    </div>
    <div class="panel-body">
        <form class="row g-2 mb-3">
            <div class="col-md-3"><input type="text" name="search" class="form-control" placeholder="ค้นหาเวชภัณฑ์..." value="{{ request('search') }}"></div>
            <div class="col-md-2"><select name="type" class="form-select"><option value="">ทุกประเภท</option><option value="receive" {{ request('type')=='receive'?'selected':'' }}>รับเข้า</option><option value="dispense" {{ request('type')=='dispense'?'selected':'' }}>เบิกจ่าย</option><option value="return" {{ request('type')=='return'?'selected':'' }}>รับคืน</option></select></div>
            <div class="col-md-2"><input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="จากวันที่"></div>
            <div class="col-md-2"><input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="ถึงวันที่"></div>
            <div class="col-md-3"><button class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>ค้นหา</button></div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-modern">
            <thead><tr><th>วันที่</th><th>เวชภัณฑ์</th><th>ประเภท</th><th>จำนวน</th><th>ล็อต</th><th>ผู้ดำเนินการ</th><th>หมายเหตุ</th></tr></thead>
            <tbody>
            @forelse($transactions as $txn)
                <tr>
                    <td>{{ $txn->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $txn->supply->name ?? '-' }}</td>
                    <td><span class="badge bg-{{ $txn->type_color }} badge-status">{{ $txn->type_label }}</span></td>
                    <td><strong>{{ $txn->quantity }}</strong></td>
                    <td>{{ $txn->lot->lot_number ?? '-' }}</td>
                    <td>{{ $txn->performer->name ?? '-' }}</td>
                    <td>{{ Str::limit($txn->notes, 30) }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">ไม่พบข้อมูล</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $transactions->withQueryString()->links() }}</div>
</div>
@endsection
