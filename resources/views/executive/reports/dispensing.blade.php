@extends('layouts.app')
@section('title', 'รายงานเบิก-จ่าย')
@section('page-title', 'รายงานเบิก-จ่ายเวชภัณฑ์ย้อนหลัง')
@section('sidebar') @include('partials.sidebar-executive') @endsection

@section('content')
<div class="panel mb-3">
    <div class="panel-header"><h5><i class="bi bi-funnel me-2"></i>ตัวกรอง</h5></div>
    <div class="panel-body">
        <form class="row g-2">
            <div class="col-md-3">
                <select name="period" class="form-select">
                    <option value="daily" {{ $period=='daily'?'selected':'' }}>รายวัน</option>
                    <option value="monthly" {{ $period=='monthly'?'selected':'' }}>รายเดือน</option>
                    <option value="yearly" {{ $period=='yearly'?'selected':'' }}>รายปี</option>
                </select>
            </div>
            <div class="col-md-3"><input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}"></div>
            <div class="col-md-3"><input type="date" name="date_to" class="form-control" value="{{ $dateTo }}"></div>
            <div class="col-md-3"><button class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>ดูรายงาน</button></div>
        </form>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-8">
        <div class="panel">
            <div class="panel-header"><h5><i class="bi bi-graph-up me-2"></i>กราฟเบิก-จ่าย</h5></div>
            <div class="panel-body"><canvas id="dispensingChart" height="300"></canvas></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel">
            <div class="panel-header"><h5><i class="bi bi-calculator me-2"></i>สรุป</h5></div>
            <div class="panel-body">
                <div class="text-center">
                    <div class="stat-value text-primary">{{ number_format($data->sum('total')) }}</div>
                    <div class="stat-label">เบิก-จ่ายรวม ({{ $period === 'daily' ? 'รายวัน' : ($period === 'monthly' ? 'รายเดือน' : 'รายปี') }})</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-header"><h5><i class="bi bi-list me-2"></i>รายละเอียด</h5></div>
    <div class="table-responsive">
        <table class="table table-modern">
            <thead><tr><th>วันที่</th><th>เวชภัณฑ์</th><th>จำนวน</th><th>ผู้ดำเนินการ</th></tr></thead>
            <tbody>
            @forelse($details as $d)
                <tr><td>{{ $d->created_at->format('d/m/Y H:i') }}</td><td>{{ $d->supply->name ?? '-' }}</td><td>{{ $d->quantity }}</td><td>{{ $d->performer->name ?? '-' }}</td></tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-4">ไม่พบข้อมูล</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $details->withQueryString()->links() }}</div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const monthNames = ['ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.'];
const data = @json($data);
let labels, values;
@if($period === 'daily')
    labels = data.map(d => d.date);
    values = data.map(d => d.total);
@elseif($period === 'monthly')
    labels = data.map(d => monthNames[d.month - 1] + ' ' + (d.year + 543));
    values = data.map(d => d.total);
@else
    labels = data.map(d => (d.year + 543).toString());
    values = data.map(d => d.total);
@endif

new Chart(document.getElementById('dispensingChart'), {
    type: 'line',
    data: { labels, datasets: [{ label: 'จำนวนเบิกจ่าย', data: values, borderColor: '#1a3c6e', backgroundColor: 'rgba(26,60,110,0.1)', fill: true, tension: 0.4, borderWidth: 2 }] },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});
</script>
@endpush
