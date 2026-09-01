@extends('layouts.app')
@section('title', 'แดชบอร์ด - เจ้าหน้าที่')
@section('page-title', 'แดชบอร์ดเจ้าหน้าที่')
@section('sidebar') @include('partials.sidebar-staff') @endsection

@push('styles')
<style>
/* =========================================
   STAFF DASHBOARD — Premium Styles
   ========================================= */

/* Welcome Banner */
.welcome-hero {
    background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #06b6d4 100%);
    border-radius: 18px;
    padding: 32px 36px;
    color: #fff;
    margin-bottom: 28px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(59,130,246,.35);
}
.welcome-hero::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 220px; height: 220px;
    background: radial-gradient(circle, rgba(255,255,255,.18) 0%, transparent 70%);
    border-radius: 50%;
}
.welcome-hero::after {
    content: '';
    position: absolute;
    bottom: -80px; left: 30%;
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(6,182,212,.25) 0%, transparent 70%);
    border-radius: 50%;
}
.welcome-hero-content { position: relative; z-index: 1; }
.welcome-name { font-size: 1.9rem; font-weight: 800; letter-spacing: -.5px; }
.welcome-date { font-size: .95rem; opacity: .85; margin-top: 4px; }
.welcome-badges { display: flex; gap: 10px; margin-top: 18px; flex-wrap: wrap; }
.welcome-badge {
    background: rgba(255,255,255,.18);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,.3);
    border-radius: 30px;
    padding: 6px 16px;
    font-size: .83rem;
    font-weight: 600;
    display: flex; align-items: center; gap: 6px;
    transition: background .2s;
}
.welcome-badge:hover { background: rgba(255,255,255,.28); }
.welcome-badge a { color: #fff; text-decoration: none; }

/* Quick Action Buttons */
.quick-actions { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 20px; }
.quick-btn {
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.35);
    color: #fff;
    border-radius: 10px;
    padding: 9px 20px;
    font-size: .86rem;
    font-weight: 600;
    display: flex; align-items: center; gap: 7px;
    text-decoration: none;
    transition: all .2s;
    backdrop-filter: blur(6px);
}
.quick-btn:hover {
    background: rgba(255,255,255,.28);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0,0,0,.15);
}

/* Stat Cards */
.stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
@media(max-width:1100px){ .stat-grid { grid-template-columns: repeat(2,1fr); } }
@media(max-width:576px){ .stat-grid { grid-template-columns: repeat(2,1fr); } }

.ds-stat-card {
    background: #fff;
    border-radius: 16px;
    padding: 22px 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 2px 12px rgba(0,0,0,.04);
    transition: transform .2s, box-shadow .2s;
    position: relative;
    overflow: hidden;
    text-decoration: none;
    color: inherit;
}
.ds-stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 28px rgba(0,0,0,.09);
    color: inherit;
}
.ds-stat-card .card-strip {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 4px;
    border-radius: 16px 16px 0 0;
}
.ds-stat-card .card-icon {
    width: 52px; height: 52px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}
.ds-stat-card .card-body-inner { flex: 1; min-width: 0; }
.ds-stat-card .card-val {
    font-size: 2rem; font-weight: 800;
    line-height: 1; color: #1e293b;
}
.ds-stat-card .card-lbl {
    font-size: .78rem; color: #6b7280;
    margin-top: 4px; font-weight: 500;
}
.ds-stat-card .card-trend {
    font-size: .75rem; margin-top: 4px;
    display: flex; align-items: center; gap: 3px;
}

/* color themes */
.sc-blue   .card-strip { background: linear-gradient(90deg,#3b82f6,#60a5fa); }
.sc-blue   .card-icon  { background: #eff6ff; color: #3b82f6; }
.sc-green  .card-strip { background: linear-gradient(90deg,#10b981,#34d399); }
.sc-green  .card-icon  { background: #ecfdf5; color: #10b981; }
.sc-red    .card-strip { background: linear-gradient(90deg,#ef4444,#f87171); }
.sc-red    .card-icon  { background: #fef2f2; color: #ef4444; }
.sc-amber  .card-strip { background: linear-gradient(90deg,#f59e0b,#fbbf24); }
.sc-amber  .card-icon  { background: #fffbeb; color: #d97706; }
.sc-purple .card-strip { background: linear-gradient(90deg,#8b5cf6,#a78bfa); }
.sc-purple .card-icon  { background: #f5f3ff; color: #7c3aed; }
.sc-cyan   .card-strip { background: linear-gradient(90deg,#06b6d4,#22d3ee); }
.sc-cyan   .card-icon  { background: #ecfeff; color: #0891b2; }
.sc-rose   .card-strip { background: linear-gradient(90deg,#f43f5e,#fb7185); }
.sc-rose   .card-icon  { background: #fff1f2; color: #e11d48; }
.sc-indigo .card-strip { background: linear-gradient(90deg,#6366f1,#818cf8); }
.sc-indigo .card-icon  { background: #eef2ff; color: #4f46e5; }

/* Dashboard Panel */
.ds-panel {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 2px 12px rgba(0,0,0,.04);
    overflow: hidden;
    height: 100%;
}
.ds-panel-header {
    padding: 18px 22px;
    border-bottom: 1px solid #f1f5f9;
    display: flex; align-items: center; justify-content: space-between;
    gap: 8px;
}
.ds-panel-header h6 {
    font-size: 1rem; font-weight: 700; color: #1e293b; margin: 0;
}

/* Request List */
.req-item {
    padding: 14px 20px;
    border-bottom: 1px solid #f8fafc;
    display: flex; align-items: center; gap: 12px;
    transition: background .15s;
}
.req-item:hover { background: #f8fafc; }
.req-item:last-child { border-bottom: none; }
.req-avatar {
    width: 40px; height: 40px; border-radius: 12px;
    background: linear-gradient(135deg,#ede9fe,#ddd6fe);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
.req-number { font-weight: 700; font-size: .9rem; color: #1e293b; }
.req-meta { font-size: .78rem; color: #6b7280; margin-top: 2px; }
.req-time { font-size: .75rem; color: #9ca3af; white-space: nowrap; }

/* Low Stock Item */
.stock-item {
    padding: 12px 20px;
    border-bottom: 1px solid #f8fafc;
    display: flex; align-items: center; gap: 12px;
    transition: background .15s;
}
.stock-item:hover { background: #fefce8; }
.stock-item:last-child { border-bottom: none; }
.stock-pill {
    font-size: .78rem; font-weight: 700;
    padding: 4px 10px; border-radius: 20px;
    white-space: nowrap; flex-shrink: 0;
}
.stock-bar-mini {
    height: 5px; border-radius: 3px;
    background: #e5e7eb; overflow: hidden; margin-top: 4px;
    width: 80px;
}
.stock-bar-mini-fill { height: 100%; border-radius: 3px; }

/* Near Expiry */
.expiry-item {
    padding: 12px 20px;
    border-bottom: 1px solid #f8fafc;
    display: flex; align-items: center; gap: 12px;
    transition: background .15s;
}
.expiry-item:hover { background: #fff7ed; }
.expiry-item:last-child { border-bottom: none; }
.days-badge {
    font-size: .8rem; font-weight: 700;
    padding: 4px 10px; border-radius: 20px;
    white-space: nowrap; flex-shrink: 0;
}

/* Alert Pulse */
.pulse-dot {
    width: 8px; height: 8px; border-radius: 50%;
    display: inline-block; margin-right: 4px;
    animation: pulse-anim 1.5s ease-in-out infinite;
}
@keyframes pulse-anim {
    0%,100% { opacity: 1; transform: scale(1); }
    50% { opacity: .6; transform: scale(1.3); }
}

/* Empty state */
.ds-empty { text-align: center; padding: 36px 20px; color: #9ca3af; }
.ds-empty i { font-size: 2.5rem; display: block; margin-bottom: 10px; opacity: .4; }
.ds-empty p { font-size: .88rem; margin: 0; }

/* Chart card */
.chart-wrap { padding: 16px; min-height: 220px; }

/* Animate in */
.animate-in { animation: fadeUp .4s ease both; }
@keyframes fadeUp {
    from { opacity:0; transform:translateY(16px); }
    to   { opacity:1; transform:translateY(0); }
}
</style>
@endpush

@section('content')

{{-- ===== WELCOME HERO ===== --}}
<div class="welcome-hero animate-in">
    <div class="welcome-hero-content">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="welcome-name">สวัสดี, {{ Auth::user()->name }}! 👋</div>
                <div class="welcome-date">
                    <i class="bi bi-calendar3 me-2"></i>
                    {{ \Carbon\Carbon::now()->locale('th')->translatedFormat('l, d F Y') }}
                    &nbsp;|&nbsp;<i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::now()->format('H:i') }} น.
                </div>
                <div class="welcome-badges mt-3">
                    @if($stats['pending_medicine_requests'] > 0)
                    <span class="welcome-badge">
                        <span class="pulse-dot" style="background:#fbbf24;"></span>
                        คำร้องรอ {{ $stats['pending_medicine_requests'] }} รายการ
                    </span>
                    @endif
                    @if($stats['critical_stock_count'] > 0)
                    <span class="welcome-badge">
                        <span class="pulse-dot" style="background:#f87171;"></span>
                        ยาใกล้หมด {{ $stats['critical_stock_count'] }} รายการ
                    </span>
                    @endif
                    @if($stats['near_expiry'] > 0)
                    <span class="welcome-badge">
                        <span class="pulse-dot" style="background:#fb923c;"></span>
                        ใกล้หมดอายุ {{ $stats['near_expiry'] }} ล็อต
                    </span>
                    @endif
                    @if($stats['pending_medicine_requests'] == 0 && $stats['critical_stock_count'] == 0 && $stats['near_expiry'] == 0)
                    <span class="welcome-badge"><i class="bi bi-check-circle-fill"></i> ทุกอย่างปกติดี</span>
                    @endif
                </div>
            </div>
            <div class="col-md-4 d-none d-md-flex justify-content-end align-items-center">
                <div style="font-size:6rem;opacity:.25;line-height:1;">🏥</div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="quick-actions">
            <a href="{{ route('staff.reports.stock') }}" class="quick-btn">
                <i class="bi bi-box-seam"></i> ดูรายงานสต็อก
            </a>
            <a href="{{ route('staff.supplies.create') }}" class="quick-btn">
                <i class="bi bi-plus-circle"></i> เพิ่มเวชภัณฑ์
            </a>
            <a href="{{ route('staff.transactions.create') }}" class="quick-btn">
                <i class="bi bi-arrow-left-right"></i> บันทึกธุรกรรม
            </a>
            <a href="{{ route('staff.requests.medicine') }}" class="quick-btn">
                <i class="bi bi-file-earmark-medical"></i> คำร้องขอยา
            </a>
        </div>
    </div>
</div>

{{-- ===== STAT CARDS (ROW 1) ===== --}}
<div class="stat-grid mb-2">
    {{-- รายการเวชภัณฑ์ --}}
    <a href="{{ route('staff.reports.stock') }}" class="ds-stat-card sc-blue animate-in" style="animation-delay:.05s">
        <div class="card-strip"></div>
        <div class="card-icon"><i class="bi bi-capsule"></i></div>
        <div class="card-body-inner">
            <div class="card-val">{{ number_format($stats['total_supplies']) }}</div>
            <div class="card-lbl">รายการเวชภัณฑ์</div>
        </div>
    </a>
    {{-- เบิกจ่ายวันนี้ --}}
    <div class="ds-stat-card sc-green animate-in" style="animation-delay:.1s">
        <div class="card-strip"></div>
        <div class="card-icon"><i class="bi bi-graph-down-arrow"></i></div>
        <div class="card-body-inner">
            <div class="card-val">{{ number_format($stats['dispensed_today']) }}</div>
            <div class="card-lbl">เบิกจ่ายวันนี้ (หน่วย)</div>
        </div>
    </div>
    {{-- คำร้องยารอ --}}
    <a href="{{ route('staff.requests.medicine') }}" class="ds-stat-card sc-amber animate-in" style="animation-delay:.15s">
        <div class="card-strip"></div>
        <div class="card-icon"><i class="bi bi-file-earmark-medical"></i></div>
        <div class="card-body-inner">
            <div class="card-val">{{ number_format($stats['pending_medicine_requests']) }}</div>
            <div class="card-lbl">คำร้องยารอดำเนินการ</div>
        </div>
    </a>
    {{-- คำร้องกระเป๋า --}}
    <a href="{{ route('staff.kits.index') }}" class="ds-stat-card sc-purple animate-in" style="animation-delay:.2s">
        <div class="card-strip"></div>
        <div class="card-icon"><i class="bi bi-bag-heart"></i></div>
        <div class="card-body-inner">
            <div class="card-val">{{ number_format($stats['pending_kit_requests']) }}</div>
            <div class="card-lbl">คำร้องกระเป๋าที่รออนุมัติ</div>
        </div>
    </a>
</div>

<div class="stat-grid mb-4">
    {{-- สต็อกต่ำ --}}
    <a href="{{ route('staff.reports.stock', ['status_filter'=>'low_stock']) }}" class="ds-stat-card sc-red animate-in" style="animation-delay:.25s">
        <div class="card-strip"></div>
        <div class="card-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
        <div class="card-body-inner">
            <div class="card-val">{{ number_format($stats['low_stock_count']) }}</div>
            <div class="card-lbl">สต็อกต่ำกว่าขั้นต่ำ</div>
        </div>
    </a>
    {{-- หมดสต็อก --}}
    <a href="{{ route('staff.reports.stock', ['status_filter'=>'out_of_stock']) }}" class="ds-stat-card sc-rose animate-in" style="animation-delay:.3s">
        <div class="card-strip"></div>
        <div class="card-icon"><i class="bi bi-x-circle-fill"></i></div>
        <div class="card-body-inner">
            <div class="card-val">{{ number_format($stats['out_of_stock_count']) }}</div>
            <div class="card-lbl">หมดสต็อก</div>
        </div>
    </a>
    {{-- ใกล้หมดอายุ --}}
    <a href="{{ route('staff.reports.stock', ['status_filter'=>'near_expiry']) }}" class="ds-stat-card sc-cyan animate-in" style="animation-delay:.35s">
        <div class="card-strip"></div>
        <div class="card-icon"><i class="bi bi-clock-history"></i></div>
        <div class="card-body-inner">
            <div class="card-val">{{ number_format($stats['near_expiry']) }}</div>
            <div class="card-lbl">ใกล้หมดอายุ (90 วัน)</div>
        </div>
    </a>
    {{-- กระเป๋าถูกยืม --}}
    <a href="{{ route('staff.kits.index') }}" class="ds-stat-card sc-indigo animate-in" style="animation-delay:.4s">
        <div class="card-strip"></div>
        <div class="card-icon"><i class="bi bi-briefcase"></i></div>
        <div class="card-body-inner">
            <div class="card-val">{{ number_format($stats['borrowed_kits']) }}/{{ number_format($stats['total_kits']) }}</div>
            <div class="card-lbl">กระเป๋ากำลังถูกยืม</div>
        </div>
    </a>
</div>

{{-- ===== MAIN CONTENT ROW ===== --}}
<div class="row g-4 mb-4">
    {{-- กราฟเบิกจ่าย 7 วัน --}}
    <div class="col-lg-7 animate-in" style="animation-delay:.45s">
        <div class="ds-panel">
            <div class="ds-panel-header">
                <h6><i class="bi bi-bar-chart-line me-2 text-primary"></i>การเบิกจ่าย 7 วันย้อนหลัง</h6>
                <a href="{{ route('staff.transactions.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill" style="font-size:.78rem">
                    ดูทั้งหมด
                </a>
            </div>
            <div class="chart-wrap">
                <canvas id="trendChart" style="min-height:200px;max-height:220px;"></canvas>
            </div>
        </div>
    </div>

    {{-- คำร้องยารอดำเนินการ --}}
    <div class="col-lg-5 animate-in" style="animation-delay:.5s">
        <div class="ds-panel">
            <div class="ds-panel-header">
                <h6><i class="bi bi-clipboard2-pulse me-2 text-warning"></i>คำร้องยารอดำเนินการ</h6>
                <a href="{{ route('staff.requests.medicine') }}" class="btn btn-sm btn-warning rounded-pill text-white" style="font-size:.78rem">
                    ดูทั้งหมด
                </a>
            </div>
            @forelse($recentRequests as $req)
            <div class="req-item">
                <div class="req-avatar">📋</div>
                <div class="flex-1 min-w-0">
                    <div class="req-number">{{ $req->request_number }}</div>
                    <div class="req-meta">
                        <i class="bi bi-person me-1"></i>{{ $req->user->name }}
                        &nbsp;·&nbsp;
                        {{ $req->items_count ?? $req->items->count() ?? 0 }} รายการ
                    </div>
                </div>
                <div class="d-flex flex-column align-items-end gap-1">
                    <div class="req-time">{{ $req->created_at->diffForHumans() }}</div>
                    <a href="{{ route('staff.requests.medicine.show', $req) }}" class="btn btn-xs btn-primary" style="font-size:.75rem;padding:3px 10px;border-radius:20px;">
                        จัดการ
                    </a>
                </div>
            </div>
            @empty
            <div class="ds-empty">
                <i class="bi bi-check-circle-fill text-success"></i>
                <p>ไม่มีคำร้องรอดำเนินการ ✓</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- ===== BOTTOM ROW ===== --}}
<div class="row g-4">
    {{-- ยาใกล้หมด (< 10) --}}
    <div class="col-lg-4 animate-in" style="animation-delay:.55s">
        <div class="ds-panel">
            <div class="ds-panel-header">
                <h6>
                    <span style="font-size:.85rem;background:#fee2e2;color:#dc2626;padding:3px 10px;border-radius:20px;font-weight:700;margin-right:6px;">🚨</span>
                    ยาจวนหมดแล้ว (&lt;10)
                </h6>
                <a href="{{ route('staff.reports.stock', ['status_filter'=>'low_stock']) }}" class="btn btn-sm btn-outline-danger rounded-pill" style="font-size:.78rem">ดูทั้งหมด</a>
            </div>
            @forelse($criticalSupplies as $s)
            @php $stock = (int)$s->total_stock_calc; @endphp
            <div class="stock-item">
                <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#fee2e2,#fecaca);display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;">💊</div>
                <div class="flex-1 min-w-0">
                    <div style="font-weight:700;font-size:.88rem;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $s->name }}</div>
                    <div style="font-size:.75rem;color:#6b7280;">{{ $s->category->name ?? '-' }}</div>
                </div>
                <div class="text-end">
                    <span class="stock-pill" style="background:#fee2e2;color:#dc2626;">
                        {{ $stock }} {{ $s->unit }}
                    </span>
                </div>
            </div>
            @empty
            <div class="ds-empty">
                <i class="bi bi-emoji-smile text-success"></i>
                <p>ยาทุกรายการมีปริมาณเพียงพอ ✓</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- สต็อกต่ำกว่าขั้นต่ำ --}}
    <div class="col-lg-4 animate-in" style="animation-delay:.6s">
        <div class="ds-panel">
            <div class="ds-panel-header">
                <h6><i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i>สต็อกต่ำกว่าขั้นต่ำ</h6>
                <a href="{{ route('staff.reports.stock', ['status_filter'=>'low_stock']) }}" class="btn btn-sm btn-outline-warning rounded-pill" style="font-size:.78rem">ดูทั้งหมด</a>
            </div>
            @forelse($lowStockSupplies as $s)
            @php
                $stock = (int)$s->total_stock_calc;
                $min   = (int)$s->min_stock;
                $pct   = $min > 0 ? min(100, round($stock / max(1,$min*2) * 100)) : 50;
                $barC  = $pct >= 50 ? '#f59e0b' : '#ef4444';
            @endphp
            <div class="stock-item">
                <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#fef9c3,#fde68a);display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;">⚠️</div>
                <div class="flex-1 min-w-0">
                    <div style="font-weight:700;font-size:.88rem;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $s->name }}</div>
                    <div class="stock-bar-mini"><div class="stock-bar-mini-fill" style="width:{{$pct}}%;background:{{$barC}};"></div></div>
                </div>
                <div class="text-end">
                    <span class="stock-pill" style="background:#fef9c3;color:#92400e;">
                        {{ $stock }}/{{ $min }}
                    </span>
                </div>
            </div>
            @empty
            <div class="ds-empty">
                <i class="bi bi-check-circle-fill text-success"></i>
                <p>สต็อกทุกรายการอยู่ในเกณฑ์ดี ✓</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ล็อตใกล้หมดอายุ --}}
    <div class="col-lg-4 animate-in" style="animation-delay:.65s">
        <div class="ds-panel">
            <div class="ds-panel-header">
                <h6><i class="bi bi-clock-history me-2 text-orange"></i>ล็อตใกล้หมดอายุ</h6>
                <a href="{{ route('staff.reports.stock', ['status_filter'=>'near_expiry']) }}" class="btn btn-sm btn-outline-warning rounded-pill" style="font-size:.78rem">ดูทั้งหมด</a>
            </div>
            @forelse($nearExpiryLots as $lot)
            @php
                $daysLeft = (int)now()->diffInDays($lot->expiry_date);
                $badgeBg  = $daysLeft <= 30 ? '#fee2e2' : '#fff7ed';
                $badgeC   = $daysLeft <= 30 ? '#dc2626' : '#c2410c';
            @endphp
            <div class="expiry-item">
                <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#ffedd5,#fed7aa);display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;">📅</div>
                <div class="flex-1 min-w-0">
                    <div style="font-weight:700;font-size:.88rem;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $lot->supply->name ?? 'N/A' }}</div>
                    <div style="font-size:.75rem;color:#6b7280;">
                        ล็อต: {{ $lot->lot_number ?? '-' }} &nbsp;·&nbsp;
                        {{ $lot->expiry_date->format('d/m/Y') }}
                    </div>
                </div>
                <span class="days-badge" style="background:{{$badgeBg}};color:{{$badgeC}};">
                    {{ $daysLeft }} วัน
                </span>
            </div>
            @empty
            <div class="ds-empty">
                <i class="bi bi-calendar-check text-success"></i>
                <p>ไม่มีล็อตใกล้หมดอายุ ✓</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const labels = @json($trendLabels);
    const values = @json($trendValues);

    const ctx = document.getElementById('trendChart');
    if (!ctx) return;

    const chartCtx = ctx.getContext('2d');
    const grad = chartCtx.createLinearGradient(0, 0, 0, 260);
    grad.addColorStop(0, 'rgba(59,130,246,.7)');
    grad.addColorStop(1, 'rgba(59,130,246,.05)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'จำนวนเบิกจ่าย (หน่วย)',
                data: values,
                backgroundColor: grad,
                borderColor: '#3b82f6',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
                barPercentage: 0.55,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(15,23,42,.92)',
                    padding: 12,
                    cornerRadius: 10,
                    titleFont: { family: "'Sarabun', sans-serif", size: 13 },
                    bodyFont:  { family: "'Sarabun', sans-serif", size: 12 },
                    displayColors: false,
                    callbacks: {
                        label: ctx => `เบิกจ่าย: ${ctx.parsed.y.toLocaleString()} หน่วย`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { font: { family: "'Sarabun', sans-serif" }, color: '#64748b' }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { family: "'Sarabun', sans-serif" }, color: '#64748b' }
                }
            },
            animation: { duration: 800, easing: 'easeOutQuart' }
        }
    });
});
</script>
@endpush
