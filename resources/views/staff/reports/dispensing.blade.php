@extends('layouts.app')
@section('title', 'รายงานเบิก-จ่าย')
@section('page-title', 'รายงานการเบิก-จ่ายเวชภัณฑ์')
@section('sidebar') @include('partials.sidebar-staff') @endsection

@section('content')
<div class="panel mb-3">
    <div class="panel-header"><h5><i class="bi bi-funnel me-2"></i>ตัวกรอง</h5></div>
    <div class="panel-body">
        <form class="row g-2">
            <div class="col-md-4"><input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="จากวันที่"></div>
            <div class="col-md-4"><input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="ถึงวันที่"></div>
            <div class="col-md-4"><button class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>กรองข้อมูล</button></div>
        </form>
    </div>
</div>

@if($summary->isNotEmpty())
<div class="panel mb-3">
    <div class="panel-header"><h5><i class="bi bi-bar-chart me-2"></i>สรุปการเบิกจ่ายตามเวชภัณฑ์</h5></div>
    <div class="table-responsive">
        <table class="table table-modern">
            <thead><tr><th>เวชภัณฑ์</th><th>จำนวนเบิกรวม</th></tr></thead>
            <tbody>
            @foreach($summary as $item)
                <tr><td>{{ $item->supply->name ?? 'N/A' }}</td><td><strong>{{ number_format($item->total_quantity) }}</strong></td></tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div class="panel">
    <div class="panel-header"><h5><i class="bi bi-list me-2"></i>รายละเอียดการเบิกจ่าย</h5></div>
    <div class="table-responsive">
        <table class="table table-modern">
            <thead><tr><th>วันที่</th><th>เวชภัณฑ์</th><th>จำนวน</th><th>ผู้ดำเนินการ</th><th>หมายเหตุ</th></tr></thead>
            <tbody>
            @forelse($transactions as $txn)
                <tr><td>{{ $txn->created_at->format('d/m/Y H:i') }}</td><td>{{ $txn->supply->name ?? '-' }}</td><td>{{ $txn->quantity }}</td><td>{{ $txn->performer->name ?? '-' }}</td><td>{{ $txn->notes ?? '-' }}</td></tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-4">ไม่พบข้อมูล</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $transactions->withQueryString()->links() }}</div>
</div>
@endsection
