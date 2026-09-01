@extends('layouts.app')
@section('title', 'แดชบอร์ด - ผู้บริหาร')
@section('page-title', 'แดชบอร์ดผู้บริหาร')
@section('sidebar') @include('partials.sidebar-executive') @endsection

@section('content')
<style>
    /* Dashboard Specific Styles */
    .welcome-banner {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
        border-radius: var(--radius);
        padding: 30px;
        color: white;
        margin-bottom: 25px;
        box-shadow: var(--shadow-md);
        position: relative;
        overflow: hidden;
    }
    .welcome-banner::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
    }
    .welcome-title {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 5px;
    }
    .welcome-subtitle {
        font-size: 15px;
        opacity: 0.9;
        font-weight: 300;
    }
    
    .modern-stat-card {
        background: white;
        border-radius: var(--radius);
        padding: 24px;
        display: flex;
        align-items: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.04);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .modern-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }
    .modern-stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 4px; height: 100%;
    }
    .stat-blue::before { background: #3b82f6; }
    .stat-green::before { background: #10b981; }
    .stat-red::before { background: #ef4444; }
    .stat-purple::before { background: #8b5cf6; }
    .stat-gold::before { background: #f59e0b; }
    .stat-cyan::before { background: #06b6d4; }

    .stat-icon-wrapper {
        width: 54px;
        height: 54px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-right: 16px;
    }
    .stat-blue .stat-icon-wrapper { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .stat-green .stat-icon-wrapper { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .stat-red .stat-icon-wrapper { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .stat-purple .stat-icon-wrapper { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
    .stat-gold .stat-icon-wrapper { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .stat-cyan .stat-icon-wrapper { background: rgba(6, 182, 212, 0.1); color: #06b6d4; }

    .stat-info .stat-value {
        font-size: 26px;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.1;
        margin-bottom: 4px;
    }
    .stat-info .stat-label {
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .premium-panel {
        background: white;
        border-radius: var(--radius);
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        border: none;
        height: 100%;
    }
    .premium-panel-header {
        padding: 20px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .premium-panel-header h5 {
        margin: 0;
        font-weight: 700;
        color: #334155;
        font-size: 17px;
    }
    
    .list-premium-item {
        transition: background-color 0.2s;
    }
    .list-premium-item:hover {
        background-color: #f8fafc;
    }
</style>

<!-- Welcome Banner -->
<div class="welcome-banner animate-in">
    <div class="welcome-title">สวัสดี, {{ Auth::user()->name }}! 👋</div>
    <div class="welcome-subtitle">ภาพรวมบริหารคลังเวชภัณฑ์ประจำวันที่ {{ \Carbon\Carbon::now()->locale('th')->translatedFormat('d M Y') }}</div>
</div>

<!-- Stat Cards (6 stats) -->
<div class="row g-4 mb-4">
    <div class="col-xl-4 col-sm-6 animate-in" style="animation-delay: 0.1s;">
        <div class="modern-stat-card stat-blue">
            <div class="stat-icon-wrapper"><i class="bi bi-capsule"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ number_format($stats['total_supplies']) }}</div>
                <div class="stat-label">รายการเวชภัณฑ์ทั้งหมด</div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6 animate-in" style="animation-delay: 0.15s;">
        <div class="modern-stat-card stat-green">
            <div class="stat-icon-wrapper"><i class="bi bi-box-seam"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ number_format($stats['total_stock_value']) }}</div>
                <div class="stat-label">สต็อกคงเหลือรวม (หน่วย)</div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6 animate-in" style="animation-delay: 0.2s;">
        <div class="modern-stat-card stat-gold">
            <div class="stat-icon-wrapper"><i class="bi bi-graph-down-arrow"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ number_format($stats['total_dispensed_today']) }}</div>
                <div class="stat-label">ยอดเบิกจ่ายวันนี้</div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6 animate-in" style="animation-delay: 0.25s;">
        <div class="modern-stat-card stat-purple">
            <div class="stat-icon-wrapper"><i class="bi bi-calendar-month"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ number_format($stats['total_dispensed_month']) }}</div>
                <div class="stat-label">เบิกจ่ายเดือนนี้</div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6 animate-in" style="animation-delay: 0.3s;">
        <div class="modern-stat-card stat-red">
            <div class="stat-icon-wrapper"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ number_format($stats['low_stock']) }}</div>
                <div class="stat-label">เวชภัณฑ์สต็อกต่ำ</div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6 animate-in" style="animation-delay: 0.35s;">
        <div class="modern-stat-card stat-cyan">
            <div class="stat-icon-wrapper"><i class="bi bi-calendar-x-fill"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ number_format($stats['expired']) }}</div>
                <div class="stat-label">ล็อตหมดอายุแล้ว</div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Report Highlight -->
<div class="row g-4 mb-4">
    <div class="col-12 animate-in" style="animation-delay: 0.4s;">
        <div class="premium-panel" style="background: linear-gradient(to right, #ffffff, #f8fafc);">
            <div class="panel-body d-flex flex-column flex-md-row align-items-center justify-content-between p-4">
                <div class="d-flex align-items-center mb-3 mb-md-0">
                    <div class="stat-icon-wrapper me-3" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6; width: 64px; height: 64px; font-size: 28px;">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-1">รายงานสถานะสต็อกเวชภัณฑ์</h5>
                        <p class="text-muted mb-0">ตรวจสอบความเคลื่อนไหว สต็อกคงเหลือ เวชภัณฑ์ใกล้หมด และล็อตหมดอายุแบบเรียลไทม์</p>
                    </div>
                </div>
                <a href="{{ route('executive.reports.stock') }}" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm">
                    <i class="bi bi-eye me-2"></i>ดูรายงานฉบับเต็ม
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Chart Section -->
    <div class="col-lg-8 animate-in" style="animation-delay: 0.45s;">
        <div class="premium-panel">
            <div class="premium-panel-header">
                <h5><i class="bi bi-graph-up-arrow me-2 text-primary"></i>แนวโน้มการเบิกจ่ายรายเดือน (ย้อนหลัง 6 เดือน)</h5>
            </div>
            <div class="panel-body">
                <canvas id="monthlyChart" style="min-height: 300px; max-height: 350px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Top 10 Ranking -->
    <div class="col-lg-4 animate-in" style="animation-delay: 0.5s;">
        <div class="premium-panel">
            <div class="premium-panel-header">
                <h5><i class="bi bi-trophy-fill me-2 text-warning"></i>Top 10 เวชภัณฑ์เบิกสูงสุดเดือนนี้</h5>
            </div>
            <div class="panel-body p-0">
                @forelse($topSupplies as $i => $item)
                    <div class="d-flex align-items-center justify-content-between p-3 border-bottom list-premium-item">
                        <div class="d-flex align-items-center gap-3">
                            <div class="badge rounded-circle shadow-sm" 
                                 style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-size: 14px;
                                 @if($i == 0) background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); 
                                 @elseif($i == 1) background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%); 
                                 @elseif($i == 2) background: linear-gradient(135deg, #d97706 0%, #b45309 100%); 
                                 @else background: #e2e8f0; color: #475569; @endif">
                                {{ $i+1 }}
                            </div>
                            <span class="fw-semibold text-dark">{{ $item->supply->name ?? 'N/A' }}</span>
                        </div>
                        <strong class="text-primary fs-5">{{ number_format($item->total) }}</strong>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <div class="text-muted mb-3" style="font-size: 48px; opacity: 0.3;"><i class="bi bi-inbox"></i></div>
                        <h6 class="text-muted">ยังไม่มีข้อมูลการเบิกจ่ายในเดือนนี้</h6>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const monthNames = ['ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.'];
    const data = @json($monthlyDispensing);
    const labels = data.map(d => monthNames[d.month - 1] + ' ' + (d.year + 543));
    const values = data.map(d => d.total);
    
    const ctx = document.getElementById('monthlyChart');
    if (ctx) {
        // Create gradient for bars
        const chartCtx = ctx.getContext('2d');
        const gradient = chartCtx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.8)');   // --primary but blueish
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0.2)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'จำนวนเบิกจ่าย',
                    data: values,
                    backgroundColor: gradient,
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                    barPercentage: 0.6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        padding: 12,
                        titleFont: { family: "'Sarabun', sans-serif", size: 14 },
                        bodyFont: { family: "'Sarabun', sans-serif", size: 13 },
                        cornerRadius: 8,
                        displayColors: false,
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { color: '#f1f5f9', drawBorder: false },
                        ticks: { font: { family: "'Sarabun', sans-serif" }, color: '#64748b' }
                    },
                    x: { 
                        grid: { display: false, drawBorder: false },
                        ticks: { font: { family: "'Sarabun', sans-serif" }, color: '#64748b' }
                    }
                },
                animation: {
                    y: {
                        duration: 1000,
                        easing: 'easeOutQuart'
                    }
                }
            }
        });
    }
});
</script>
@endpush
