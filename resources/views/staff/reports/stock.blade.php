@extends('layouts.app')
@section('title', 'รายงานสต็อกเวชภัณฑ์')
@section('page-title', 'รายงานสต็อกเวชภัณฑ์')
@section('sidebar') @include('partials.sidebar-staff') @endsection

@push('styles')
<style>
/* ======= Stock Report Premium Styles ======= */
.stock-page { font-family: 'Sarabun', 'Inter', sans-serif; }

/* Alert Banner */
.alert-stock-banner {
    background: linear-gradient(135deg, #fff3cd 0%, #fff8e1 100%);
    border-left: 4px solid #f59e0b;
    border-radius: 10px;
    padding: 12px 18px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: .92rem;
}
.alert-stock-banner.danger {
    background: linear-gradient(135deg, #fee2e2 0%, #fff5f5 100%);
    border-left-color: #ef4444;
}

/* Critical Low Stock Banner (< 10) */
.alert-critical-banner {
    background: linear-gradient(135deg, #fef3c7 0%, #fef9ee 100%);
    border: 1.5px solid #f59e0b;
    border-left: 5px solid #dc2626;
    border-radius: 12px;
    padding: 14px 18px;
    box-shadow: 0 2px 12px rgba(220,38,38,.10);
    animation: pulse-red 2s ease-in-out infinite;
}
@keyframes pulse-red {
    0%, 100% { box-shadow: 0 2px 12px rgba(220,38,38,.10); }
    50% { box-shadow: 0 4px 20px rgba(220,38,38,.22); }
}
.alert-critical-banner .banner-header {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    user-select: none;
}
.alert-critical-banner .banner-title {
    font-weight: 700;
    color: #991b1b;
    font-size: 1rem;
    flex: 1;
}
.alert-critical-badge {
    background: #dc2626;
    color: #fff;
    font-size: .8rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    white-space: nowrap;
}
.critical-item-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px dashed #fca5a5;
}
.critical-item-chip {
    background: #fff;
    border: 1.5px solid #fca5a5;
    border-radius: 8px;
    padding: 5px 12px;
    font-size: .82rem;
    display: flex;
    align-items: center;
    gap: 6px;
    color: #374151;
}
.critical-item-chip .chip-stock {
    font-weight: 800;
    color: #dc2626;
    font-size: .9rem;
}


/* Summary Cards */
.stat-cards { display: grid; grid-template-columns: repeat(6, 1fr); gap: 12px; margin-bottom: 20px; }
@media(max-width:1100px){ .stat-cards { grid-template-columns: repeat(3,1fr); } }
@media(max-width:600px){ .stat-cards { grid-template-columns: repeat(2,1fr); } }
.stat-card {
    background: #fff;
    border-radius: 12px;
    padding: 14px 16px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 4px rgba(0,0,0,.05);
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    transition: box-shadow .15s, transform .15s;
}
.stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.10); transform: translateY(-2px); }
.stat-card.active { outline: 2.5px solid var(--card-color, #3b82f6); }
.stat-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; }
.stat-info .count { font-size: 1.6rem; font-weight: 800; line-height: 1; }
.stat-info .label { font-size: .78rem; color: #6b7280; margin-top: 2px; }

/* Search/Filter Bar */
.filter-bar {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 16px 18px;
    margin-bottom: 18px;
}
.filter-bar input, .filter-bar select {
    border-radius: 8px;
    border: 1.5px solid #d1d5db;
    font-size: .88rem;
    transition: border-color .15s, box-shadow .15s;
}
.filter-bar input:focus, .filter-bar select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,.12);
    outline: none;
}

/* Table */
.table-stock { font-size: .86rem; }
.table-stock thead th {
    background: #f1f5f9;
    color: #374151;
    font-weight: 700;
    border-bottom: 2px solid #e2e8f0;
    white-space: nowrap;
    padding: 10px 12px;
    user-select: none;
}
.table-stock thead th.sortable { cursor: pointer; }
.table-stock thead th.sortable:hover { background: #e2e8f0; }
.table-stock tbody tr { transition: background .1s; }
.table-stock tbody tr:hover { background: #f8fafc !important; }
.table-stock td { padding: 8px 12px; vertical-align: middle; }

/* Stock Progress Bar */
.stock-bar-wrap { min-width: 80px; }
.stock-bar {
    height: 7px;
    border-radius: 4px;
    background: #e5e7eb;
    overflow: hidden;
    margin-top: 3px;
}
.stock-bar-fill {
    height: 100%;
    border-radius: 4px;
    transition: width .4s;
}

/* Expiry Badge */
.expiry-badge { font-size: .78rem; padding: 3px 8px; border-radius: 20px; font-weight: 600; }
.expiry-ok  { background: #dcfce7; color: #166534; }
.expiry-warn { background: #fef3c7; color: #92400e; }
.expiry-danger { background: #fee2e2; color: #991b1b; }

/* Status Badge */
.status-badge { font-size: .78rem; padding: 4px 10px; border-radius: 20px; font-weight: 700; white-space: nowrap; }

/* Action Dropdown */
.action-btn { border: none; background: #f3f4f6; border-radius: 8px; padding: 5px 10px; cursor: pointer; transition: background .15s; }
.action-btn:hover { background: #e5e7eb; }

/* Supply avatar */
.supply-avatar {
    width: 36px; height: 36px; border-radius: 8px;
    background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}

/* Pagination wrapper */
.pag-wrapper { 
    display: flex; 
    flex-direction: column;
    gap: 12px;
    padding: 14px;
    background: #f9fafb;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
}
.pag-wrapper-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}
.pag-wrapper .pagination-info {
    background: #fff;
    padding: 8px 14px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    font-size: .95rem;
    font-weight: 600;
    color: #1e293b;
    white-space: nowrap;
    box-shadow: 0 1px 3px rgba(0,0,0,.05);
}
.pag-wrapper-stats {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.pag-wrapper .stat-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: .85rem;
    font-weight: 600;
    white-space: nowrap;
    transition: transform .2s, box-shadow .2s;
}
.pag-wrapper .stat-badge:hover { 
    transform: translateY(-2px); 
    box-shadow: 0 2px 6px rgba(0,0,0,.1);
}
.pag-wrapper .stat-badge.normal { 
    background: linear-gradient(135deg, #d1fae5, #ecfdf5);
    color: #047857;
    border: 1px solid #a7f3d0;
}
.pag-wrapper .stat-badge.low { 
    background: linear-gradient(135deg, #fef9c3, #fffbeb);
    color: #b45309;
    border: 1px solid #fcd34d;
}
.pag-wrapper .stat-badge.out { 
    background: linear-gradient(135deg, #fee2e2, #fef2f2);
    color: #991b1b;
    border: 1px solid #fca5a5;
}
.pag-wrapper .stat-badge.expiry { 
    background: linear-gradient(135deg, #ffedd5, #fffbf0);
    color: #b45309;
    border: 1px solid #fed7aa;
}
.pag-wrapper .stat-badge.expired { 
    background: linear-gradient(135deg, #f3f4f6, #f9fafb);
    color: #374151;
    border: 1px solid #d1d5db;
}
@media(max-width: 768px) {
    .pag-wrapper {
        padding: 12px;
    }
    .pag-wrapper-row {
        flex-direction: column;
        align-items: stretch;
    }
    .pag-wrapper .pagination-info {
        text-align: center;
    }
    .pag-wrapper-stats {
        justify-content: center;
    }
}

/* Row highlight */
tr.row-out-of-stock td { background: #fee2e2 !important; }
tr.row-low-stock td { background: #fef9c3 !important; }
tr.row-expired td { background: #f1f5f9 !important; }
tr.row-near-expiry td { background: #fff7ed !important; }

/* Chart card */
.chart-card {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    padding: 18px;
    box-shadow: 0 1px 4px rgba(0,0,0,.05);
}
</style>
@endpush

@section('content')
<div class="stock-page">

{{-- ===== CRITICAL LOW STOCK BANNER (stock < 10) ===== --}}
@if($criticalLowCount > 0)
<div class="alert-critical-banner mb-3" id="criticalBannerWrap">
    <div class="banner-header" onclick="toggleCriticalList()" aria-expanded="true" aria-controls="criticalList">
        <span style="font-size:1.4rem;">🚨</span>
        <span class="banner-title">
            แจ้งเตือน: ยาใกล้หมดแล้ว!
            <span class="alert-critical-badge ms-2">{{ $criticalLowCount }} รายการ ที่เหลือน้อยกว่า 10</span>
        </span>
        <span class="text-muted small" id="criticalToggleText">คลิกเพื่อดูรายละเอียด ▼</span>
    </div>
    <div class="critical-item-list" id="criticalList">
        @foreach($criticalLowItems as $ci)
        <a href="{{ route('staff.supplies.edit', $ci->id) }}" class="critical-item-chip text-decoration-none" title="คลิกเพื่อแก้ไข">
            <span>💊</span>
            <span>
                <span class="fw-semibold">{{ $ci->name }}</span>
                <span class="text-muted" style="font-size:.76rem;"> ({{ $ci->code }})</span>
            </span>
            <span class="chip-stock">{{ (int)$ci->total_stock_calc }}</span>
            <span class="text-muted" style="font-size:.76rem;">{{ $ci->unit }}</span>
        </a>
        @endforeach
    </div>
</div>
@endif

{{-- ===== ALERT BANNERS ===== --}}
@if($alertLowStock > 0 || $alertNearExpiry > 0)
<div class="mb-3 d-flex flex-column gap-2">
    @if($alertLowStock > 0)
    <div class="alert-stock-banner danger">
        <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
        <div>
            <strong>⚠ แจ้งเตือนสต็อก:</strong>
            มี <strong>{{ $alertLowStock }}</strong> รายการที่สต็อกต่ำหรือหมดแล้ว — กรุณาตรวจสอบและเติมสต็อก
        </div>
    </div>
    @endif
    @if($alertNearExpiry > 0)
    <div class="alert-stock-banner">
        <i class="bi bi-clock-history text-warning fs-5"></i>
        <div>
            <strong>🕐 แจ้งเตือนวันหมดอายุ:</strong>
            มี <strong>{{ $alertNearExpiry }}</strong> รายการที่ใกล้หมดอายุหรือหมดอายุแล้ว — กรุณาตรวจสอบล็อตยา
        </div>
    </div>
    @endif
</div>
@endif

{{-- ===== SUMMARY STAT CARDS ===== --}}
<div class="stat-cards mb-3">
    <a href="{{ route('staff.reports.stock', array_merge(request()->except('status_filter','page'), [])) }}" class="stat-card text-decoration-none {{ !$statusFilter ? 'active' : '' }}" style="--card-color:#6366f1">
        <div class="stat-icon" style="background:#ede9fe;color:#6366f1">
            <i class="bi bi-boxes"></i>
        </div>
        <div class="stat-info">
            <div class="count" style="color:#6366f1">{{ $stats['total'] }}</div>
            <div class="label">รายการทั้งหมด</div>
        </div>
    </a>
    <a href="{{ route('staff.reports.stock', array_merge(request()->except('status_filter','page'), ['status_filter'=>'normal'])) }}" class="stat-card text-decoration-none {{ $statusFilter==='normal' ? 'active' : '' }}" style="--card-color:#22c55e">
        <div class="stat-icon" style="background:#dcfce7;color:#22c55e">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div class="stat-info">
            <div class="count" style="color:#22c55e">{{ $stats['normal'] }}</div>
            <div class="label">ปกติ</div>
        </div>
    </a>
    <a href="{{ route('staff.reports.stock', array_merge(request()->except('status_filter','page'), ['status_filter'=>'low_stock'])) }}" class="stat-card text-decoration-none {{ $statusFilter==='low_stock' ? 'active' : '' }}" style="--card-color:#eab308">
        <div class="stat-icon" style="background:#fef9c3;color:#ca8a04">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div class="stat-info">
            <div class="count" style="color:#ca8a04">{{ $stats['low_stock'] }}</div>
            <div class="label">ใกล้หมด</div>
        </div>
    </a>
    <a href="{{ route('staff.reports.stock', array_merge(request()->except('status_filter','page'), ['status_filter'=>'out_of_stock'])) }}" class="stat-card text-decoration-none {{ $statusFilter==='out_of_stock' ? 'active' : '' }}" style="--card-color:#ef4444">
        <div class="stat-icon" style="background:#fee2e2;color:#ef4444">
            <i class="bi bi-x-circle-fill"></i>
        </div>
        <div class="stat-info">
            <div class="count" style="color:#ef4444">{{ $stats['out_of_stock'] }}</div>
            <div class="label">หมดสต็อก</div>
        </div>
    </a>
    <a href="{{ route('staff.reports.stock', array_merge(request()->except('status_filter','page'), ['status_filter'=>'near_expiry'])) }}" class="stat-card text-decoration-none {{ $statusFilter==='near_expiry' ? 'active' : '' }}" style="--card-color:#f97316">
        <div class="stat-icon" style="background:#ffedd5;color:#f97316">
            <i class="bi bi-clock-history"></i>
        </div>
        <div class="stat-info">
            <div class="count" style="color:#f97316">{{ $stats['near_expiry'] }}</div>
            <div class="label">ใกล้หมดอายุ</div>
        </div>
    </a>
    <a href="{{ route('staff.reports.stock', array_merge(request()->except('status_filter','page'), ['status_filter'=>'expired'])) }}" class="stat-card text-decoration-none {{ $statusFilter==='expired' ? 'active' : '' }}" style="--card-color:#6b7280">
        <div class="stat-icon" style="background:#f3f4f6;color:#374151">
            <i class="bi bi-slash-circle-fill"></i>
        </div>
        <div class="stat-info">
            <div class="count" style="color:#374151">{{ $stats['expired'] }}</div>
            <div class="label">หมดอายุ</div>
        </div>
    </a>
</div>

{{-- ===== PANEL ===== --}}
<div class="panel">
    <div class="panel-header d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h5 class="mb-0"><i class="bi bi-box-seam me-2 text-primary"></i>สต็อกเวชภัณฑ์ทั้งหมด</h5>
        <div class="d-flex gap-2 flex-wrap">
            {{-- Export --}}
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-download me-1"></i>Export
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('staff.reports.stock.export-xls', request()->all()) }}">
                            <i class="bi bi-file-earmark-spreadsheet text-success me-2"></i>📊 Export Excel
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('staff.reports.stock.export-pdf', request()->all()) }}">
                            <i class="bi bi-file-earmark-pdf text-danger me-2"></i>Export PDF
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('staff.reports.stock.export-csv', request()->all()) }}">
                            <i class="bi bi-filetype-csv text-secondary me-2"></i>Export CSV (Raw)
                        </a>
                    </li>

                </ul>
            </div>
            {{-- Add Supply --}}
            <a href="{{ route('staff.supplies.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>+ เพิ่มเวชภัณฑ์
            </a>
        </div>
    </div>

    {{-- ===== FILTER BAR ===== --}}
    <div class="panel-body pb-0">
        <form method="GET" action="{{ route('staff.reports.stock') }}" class="filter-bar">
            <div class="row g-2 align-items-end">
                {{-- Search --}}
                <div class="col-md-4">
                    <label class="form-label small fw-semibold mb-1">🔍 ค้นหา</label>
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="รหัส / ชื่อยา / หมวดหมู่ / ผู้ผลิต / ตำแหน่ง..."
                        value="{{ request('search') }}">
                </div>
                {{-- Category --}}
                <div class="col-md-2">
                    <label class="form-label small fw-semibold mb-1">หมวดหมู่</label>
                    <select name="category_id" class="form-select form-select-sm">
                        <option value="">ทั้งหมด</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Unit --}}
                <div class="col-md-2">
                    <label class="form-label small fw-semibold mb-1">หน่วย</label>
                    <select name="unit" class="form-select form-select-sm">
                        <option value="">ทุกหน่วย</option>
                        @foreach($units as $u)
                        <option value="{{ $u }}" {{ request('unit') == $u ? 'selected' : '' }}>{{ $u }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Status --}}
                <div class="col-md-2">
                    <label class="form-label small fw-semibold mb-1">สถานะ</label>
                    <select name="status_filter" class="form-select form-select-sm">
                        <option value="">ทุกสถานะ</option>
                        <option value="normal" {{ request('status_filter')=='normal' ? 'selected' : '' }}>ปกติ</option>
                        <option value="low_stock" {{ request('status_filter')=='low_stock' ? 'selected' : '' }}>ใกล้หมด</option>
                        <option value="out_of_stock" {{ request('status_filter')=='out_of_stock' ? 'selected' : '' }}>หมดสต็อก</option>
                        <option value="near_expiry" {{ request('status_filter')=='near_expiry' ? 'selected' : '' }}>ใกล้หมดอายุ</option>
                        <option value="expired" {{ request('status_filter')=='expired' ? 'selected' : '' }}>หมดอายุ</option>
                    </select>
                </div>
                {{-- Per Page --}}
                <div class="col-md-2">
                    <label class="form-label small fw-semibold mb-1">แสดง</label>
                    <select name="per_page" class="form-select form-select-sm">
                        @foreach([10,20,50,100] as $pp)
                        <option value="{{ $pp }}" {{ $perPage == $pp ? 'selected' : '' }}>{{ $pp }} รายการ</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="sort" value="{{ $sortCol }}">
                <input type="hidden" name="dir" value="{{ $sortDir }}">
                @if($statusFilter)
                <input type="hidden" name="status_filter" value="{{ $statusFilter }}">
                @endif
                {{-- Buttons --}}
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                        <i class="bi bi-search me-1"></i>ค้นหา
                    </button>
                    <a href="{{ route('staff.reports.stock') }}" class="btn btn-outline-secondary btn-sm" title="ล้างตัวกรอง">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </div>
            {{-- Quick Category Filter Chips --}}
            <div class="d-flex flex-wrap gap-1 mt-2">
                <a href="{{ route('staff.reports.stock', array_merge(request()->except('category_id','page'))) }}"
                   class="badge {{ !request('category_id') ? 'bg-primary' : 'bg-light text-dark border' }} text-decoration-none px-3 py-2"
                   style="border-radius:20px; font-size:.8rem;">ทั้งหมด</a>
                @foreach($categories as $cat)
                <a href="{{ route('staff.reports.stock', array_merge(request()->except('category_id','page'), ['category_id'=>$cat->id])) }}"
                   class="badge {{ request('category_id') == $cat->id ? 'bg-primary' : 'bg-light text-dark border' }} text-decoration-none px-3 py-2"
                   style="border-radius:20px; font-size:.8rem;">{{ $cat->name }}</a>
                @endforeach
            </div>
        </form>
    </div>

    {{-- ===== TABLE ===== --}}
    <div class="table-responsive">
        <table class="table table-stock table-hover mb-0">
            <thead>
                <tr>
                    <th style="width:40px">#</th>
                    <th style="width:50px">รูป</th>
                    @php
                        function sortLink($col, $label, $sortCol, $sortDir) {
                            $dir = ($sortCol === $col && $sortDir === 'asc') ? 'desc' : 'asc';
                            $icon = $sortCol === $col ? ($sortDir === 'asc' ? '↑' : '↓') : '⬍';
                            $params = array_merge(request()->all(), ['sort' => $col, 'dir' => $dir, 'page' => 1]);
                            return '<a href="'.route('staff.reports.stock', $params).'" class="text-dark text-decoration-none">'.$label.' <small class="text-muted">'.$icon.'</small></a>';
                        }
                    @endphp
                    <th class="sortable">{!! sortLink('code', 'รหัส', $sortCol, $sortDir) !!}</th>
                    <th class="sortable">{!! sortLink('name', 'ชื่อเวชภัณฑ์', $sortCol, $sortDir) !!}</th>
                    <th class="sortable">{!! sortLink('category_id', 'หมวดหมู่', $sortCol, $sortDir) !!}</th>
                    <th class="sortable">{!! sortLink('unit', 'หน่วย', $sortCol, $sortDir) !!}</th>
                    <th>ผู้ผลิต</th>
                    <th>ตำแหน่ง</th>
                    <th>เลขล็อต</th>
                    <th>วันหมดอายุ</th>
                    <th class="sortable">{!! sortLink('stock', 'คงเหลือ', $sortCol, $sortDir) !!}</th>
                    <th>ขั้นต่ำ</th>
                    <th>สถานะ</th>
                    <th>อัปเดต</th>
                    <th class="text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody>
            @forelse($supplies as $idx => $supply)
                @php
                    $stock    = (int)($supply->total_stock_calc ?? 0);
                    $nearest  = $supply->lots->where('remaining_quantity', '>', 0)->sortBy('expiry_date')->first();
                    $minStock = (int)$supply->min_stock;

                    // สถานะ
                    if ($stock <= 0) {
                        $statusCode  = 'out_of_stock';
                        $statusLabel = 'หมดสต็อก';
                        $statusClass = 'bg-danger text-white';
                        $rowClass    = 'row-out-of-stock';
                    } elseif ($nearest && $nearest->expiry_date && $nearest->expiry_date->isPast()) {
                        $statusCode  = 'expired';
                        $statusLabel = 'หมดอายุ';
                        $statusClass = 'bg-dark text-white';
                        $rowClass    = 'row-expired';
                    } elseif ($stock <= $minStock) {
                        $statusCode  = 'low_stock';
                        $statusLabel = 'ใกล้หมด';
                        $statusClass = 'bg-warning text-dark';
                        $rowClass    = 'row-low-stock';
                    } elseif ($nearest && $nearest->expiry_date && $nearest->expiry_date->diffInDays(now()) <= 90) {
                        $statusCode  = 'near_expiry';
                        $statusLabel = 'ใกล้หมดอายุ';
                        $statusClass = 'bg-warning bg-opacity-75 text-dark';
                        $rowClass    = 'row-near-expiry';
                    } else {
                        $statusCode  = 'normal';
                        $statusLabel = 'ปกติ';
                        $statusClass = 'bg-success text-white';
                        $rowClass    = '';
                    }

                    // สต็อก % เพื่อแสดง Progress Bar
                    $target      = max(1, $minStock * 2);
                    $stockPct    = min(100, max(0, round(($stock / $target) * 100)));
                    $barColor    = $stockPct >= 60 ? '#22c55e' : ($stockPct >= 25 ? '#f59e0b' : '#ef4444');

                    // วันหมดอายุ
                    $expiryClass = '';
                    $expiryLabel = '-';
                    $daysLeft    = null;
                    if ($nearest && $nearest->expiry_date) {
                        if ($nearest->expiry_date->isPast()) {
                            $expiryClass = 'expiry-danger';
                            $expiryLabel = $nearest->expiry_date->format('d/m/Y').' (หมดแล้ว)';
                        } else {
                            $daysLeft = (int)now()->diffInDays($nearest->expiry_date);
                            if ($daysLeft <= 90) {
                                $expiryClass = 'expiry-warn';
                            } else {
                                $expiryClass = 'expiry-ok';
                            }
                            $expiryLabel = $nearest->expiry_date->format('d/m/Y').($daysLeft <= 180 ? ' ('.$daysLeft.' วัน)' : '');
                        }
                    }

                    // กรอง status_filter
                    if ($statusFilter && $statusFilter !== $statusCode) {
                        continue;
                    }
                @endphp
                <tr class="{{ $rowClass }}">
                    <td class="text-muted">{{ $supplies->firstItem() + $loop->index }}</td>
                    <td>
                        @if($supply->image_url && !str_contains($supply->image_url, 'default.svg'))
                        <img src="{{ $supply->image_url }}" alt="{{ $supply->name }}" class="rounded" style="width:36px;height:36px;object-fit:cover;">
                        @else
                        <div class="supply-avatar">💊</div>
                        @endif
                    </td>
                    <td>
                        <code class="text-primary fw-bold" style="font-size:.82rem">{{ $supply->code }}</code>
                    </td>
                    <td>
                        <strong>{{ $supply->name }}</strong>
                        @if($supply->description)
                        <div class="text-muted" style="font-size:.75rem;max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $supply->description }}</div>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border" style="border-radius:20px;font-size:.78rem;">{{ $supply->category->name ?? '-' }}</span>
                    </td>
                    <td class="text-center text-muted small">{{ $supply->unit }}</td>
                    <td class="text-muted small">{{ $supply->manufacturer ?? '-' }}</td>
                    <td>
                        @if($supply->storage_location)
                        <span class="badge bg-light border text-secondary" style="border-radius:6px;font-size:.78rem;"><i class="bi bi-geo-alt me-1"></i>{{ $supply->storage_location }}</span>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($nearest && $nearest->lot_number)
                        <code style="font-size:.78rem;color:#6366f1;">{{ $nearest->lot_number }}</code>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($expiryLabel !== '-')
                        <span class="expiry-badge {{ $expiryClass }}">{{ $expiryLabel }}</span>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <div class="stock-bar-wrap">
                            <div class="fw-bold" style="font-size:.9rem;">{{ number_format($stock) }}</div>
                            <div class="stock-bar" style="width:90px;">
                                <div class="stock-bar-fill" style="width:{{ $stockPct }}%;background:{{ $barColor }};"></div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center text-muted small">{{ $minStock }}</td>
                    <td>
                        <span class="status-badge {{ $statusClass }}" style="border-radius:20px;">{{ $statusLabel }}</span>
                    </td>
                    <td class="text-muted" style="font-size:.75rem;white-space:nowrap;">
                        {{ $supply->updated_at ? $supply->updated_at->format('d/m/Y H:i') : '-' }}
                    </td>
                    <td class="text-center">
                        <div class="dropdown">
                            <button class="action-btn" data-bs-toggle="dropdown" title="ตัวเลือก">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="font-size:.86rem;min-width:160px;">
                                <li>
                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#viewModal{{ $supply->id }}">
                                        <i class="bi bi-eye me-2 text-info"></i>ดูรายละเอียด
                                    </button>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('staff.supplies.edit', $supply) }}">
                                        <i class="bi bi-pencil me-2 text-primary"></i>แก้ไข
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('staff.transactions.create', ['supply_id'=>$supply->id,'type'=>'receive']) }}">
                                        <i class="bi bi-box-arrow-in-down me-2 text-success"></i>รับเข้าสต็อก
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('staff.transactions.create', ['supply_id'=>$supply->id,'type'=>'dispense']) }}">
                                        <i class="bi bi-box-arrow-up me-2 text-warning"></i>เบิกจ่าย
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('staff.transactions.index', ['supply_id'=>$supply->id]) }}">
                                        <i class="bi bi-clock-history me-2 text-secondary"></i>ดูประวัติธุรกรรม
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('staff.supplies.destroy', $supply) }}" method="POST" class="d-inline" onsubmit="return confirm('ยืนยันลบเวชภัณฑ์ {{ $supply->name }}?')">
                                        @csrf @method('DELETE')
                                        <button class="dropdown-item text-danger">
                                            <i class="bi bi-trash me-2"></i>ลบ
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="14" class="text-center text-muted py-5"><i class="bi bi-inbox fs-3 d-block mb-2"></i>ไม่พบข้อมูลเวชภัณฑ์ที่ตรงกับเงื่อนไข</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- ===== MODALS (outside table to prevent layout bugs) ===== --}}
    @foreach($supplies as $supply)
    @php
        $stock    = (int)($supply->total_stock_calc ?? 0);
        $nearest  = $supply->lots->where('remaining_quantity', '>', 0)->sortBy('expiry_date')->first();
        $minStock = (int)$supply->min_stock;
        if ($stock <= 0) { $statusClass='bg-danger text-white'; $statusLabel='หมดสต็อก'; }
        elseif ($nearest && $nearest->expiry_date && $nearest->expiry_date->isPast()) { $statusClass='bg-dark text-white'; $statusLabel='หมดอายุ'; }
        elseif ($stock <= $minStock) { $statusClass='bg-warning text-dark'; $statusLabel='ใกล้หมด'; }
        elseif ($nearest && $nearest->expiry_date && $nearest->expiry_date->diffInDays(now()) <= 90) { $statusClass='bg-warning bg-opacity-75 text-dark'; $statusLabel='ใกล้หมดอายุ'; }
        else { $statusClass='bg-success text-white'; $statusLabel='ปกติ'; }
        $target   = max(1, $minStock * 2);
        $stockPct = min(100, max(0, round(($stock / $target) * 100)));
        $barColor = $stockPct >= 60 ? '#22c55e' : ($stockPct >= 25 ? '#f59e0b' : '#ef4444');
    @endphp
                <div class="modal fade text-start" id="viewModal{{ $supply->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-light">
                                <h5 class="modal-title"><i class="bi bi-capsule text-primary me-2"></i>รายละเอียดเวชภัณฑ์</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="row g-3">
                                    <div class="col-md-4 text-center">
                                        @if($supply->image_url && !str_contains($supply->image_url, 'default.svg'))
                                        <img src="{{ $supply->image_url }}" class="img-fluid rounded-3 border shadow-sm mb-2" style="max-height:180px;object-fit:cover;">
                                        @else
                                        <div style="width:100%;height:150px;background:linear-gradient(135deg,#e0e7ff,#c7d2fe);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:4rem;">💊</div>
                                        @endif
                                        <div class="mt-2">
                                            <code class="text-primary fw-bold fs-6">{{ $supply->code }}</code>
                                        </div>
                                        <div class="mt-1">
                                            <span class="status-badge {{ $statusClass }}" style="border-radius:20px;">{{ $statusLabel }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h4 class="fw-bold mb-1">{{ $supply->name }}</h4>
                                        <p class="text-muted small mb-3">{{ $supply->description ?? 'ไม่มีคำอธิบาย' }}</p>
                                        <div class="row g-2">
                                            <div class="col-6"><div class="p-2 bg-light rounded-2 border"><div class="text-muted" style="font-size:.75rem">หมวดหมู่</div><strong>{{ $supply->category->name ?? '-' }}</strong></div></div>
                                            <div class="col-6"><div class="p-2 bg-light rounded-2 border"><div class="text-muted" style="font-size:.75rem">หน่วยนับ</div><strong>{{ $supply->unit }}</strong></div></div>
                                            <div class="col-6"><div class="p-2 bg-light rounded-2 border"><div class="text-muted" style="font-size:.75rem">ผู้ผลิต</div><strong>{{ $supply->manufacturer ?? '-' }}</strong></div></div>
                                            <div class="col-6"><div class="p-2 bg-light rounded-2 border"><div class="text-muted" style="font-size:.75rem">ตำแหน่งจัดเก็บ</div><strong>{{ $supply->storage_location ?? '-' }}</strong></div></div>
                                            <div class="col-4">
                                                <div class="p-2 rounded-2 border text-center" style="background:#f0fdf4">
                                                    <div class="text-muted" style="font-size:.75rem">คงเหลือ</div>
                                                    <div class="fw-bold fs-4 text-success">{{ number_format($stock) }}</div>
                                                </div>
                                            </div>
                                            <div class="col-4"><div class="p-2 bg-light rounded-2 border text-center"><div class="text-muted" style="font-size:.75rem">สต็อกขั้นต่ำ</div><strong class="fs-5">{{ $minStock }}</strong></div></div>
                                            <div class="col-4">
                                                <div class="p-2 rounded-2 border text-center" style="background:#fef9c3">
                                                    <div class="text-muted" style="font-size:.75rem">% สต็อก</div>
                                                    <div class="fw-bold fs-5" style="color:{{ $barColor }}">{{ $stockPct }}%</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Lot details --}}
                                @if($supply->lots->count() > 0)
                                <div class="mt-4">
                                    <h6 class="fw-bold border-bottom pb-2 mb-2"><i class="bi bi-archive me-2"></i>รายการล็อตทั้งหมด</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>เลขล็อต</th>
                                                    <th class="text-center">จำนวนเริ่มต้น</th>
                                                    <th class="text-center">คงเหลือ</th>
                                                    <th>วันรับเข้า</th>
                                                    <th>วันหมดอายุ</th>
                                                    <th>สถานะล็อต</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($supply->lots->sortBy('expiry_date') as $lot)
                                                @php
                                                    if (!$lot->expiry_date) $lotStatus = ['bg-secondary','ไม่ระบุ'];
                                                    elseif ($lot->expiry_date->isPast()) $lotStatus = ['bg-danger','หมดอายุ'];
                                                    elseif ($lot->expiry_date->diffInDays(now()) <= 90) $lotStatus = ['bg-warning text-dark','ใกล้หมดอายุ'];
                                                    else $lotStatus = ['bg-success','ปกติ'];
                                                @endphp
                                                <tr>
                                                    <td><code>{{ $lot->lot_number ?? '-' }}</code></td>
                                                    <td class="text-center">{{ number_format($lot->quantity) }}</td>
                                                    <td class="text-center fw-bold">{{ number_format($lot->remaining_quantity) }}</td>
                                                    <td>{{ $lot->received_date ? $lot->received_date->format('d/m/Y') : '-' }}</td>
                                                    <td>{{ $lot->expiry_date ? $lot->expiry_date->format('d/m/Y') : '-' }}</td>
                                                    <td><span class="badge {{ $lotStatus[0] }}">{{ $lotStatus[1] }}</span></td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="modal-footer bg-light">
                                <a href="{{ route('staff.supplies.edit', $supply) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>แก้ไขข้อมูล</a>
                                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">ปิด</button>
                            </div>
                        </div>
                    </div>
                </div>
    @endforeach

    {{-- TABLE CLOSE was moved up --}}


    {{-- ===== PAGINATION & SUMMARY ===== --}}
    <div class="p-3">
        <div class="pag-wrapper">
            <div class="pag-wrapper-row">
                <span class="pagination-info">
                    📄 แสดง <strong>{{ $supplies->firstItem() ?? 0 }}</strong>-<strong>{{ $supplies->lastItem() ?? 0 }}</strong> 
                    จาก <strong>{{ $supplies->total() }}</strong> รายการ
                </span>
                <div>
                    {{ $supplies->withQueryString()->links() }}
                </div>
            </div>
            <div class="pag-wrapper-stats">
                <span class="stat-badge normal">
                    <i class="bi bi-check-circle-fill"></i>ปกติ {{ $stats['normal'] }}
                </span>
                <span class="stat-badge low">
                    <i class="bi bi-exclamation-triangle-fill"></i>ใกล้หมด {{ $stats['low_stock'] }}
                </span>
                <span class="stat-badge out">
                    <i class="bi bi-x-circle-fill"></i>หมดสต็อก {{ $stats['out_of_stock'] }}
                </span>
                <span class="stat-badge expiry">
                    <i class="bi bi-clock-history"></i>ใกล้หมดอายุ {{ $stats['near_expiry'] }}
                </span>
                <span class="stat-badge expired">
                    <i class="bi bi-slash-circle-fill"></i>หมดอายุ {{ $stats['expired'] }}
                </span>
            </div>
        </div>
    </div>
</div>

</div>
@endsection

@push('scripts')
<script>
// Auto submit form when select changes
document.querySelectorAll('.filter-bar select[name="category_id"], .filter-bar select[name="unit"], .filter-bar select[name="per_page"]').forEach(el => {
    el.addEventListener('change', () => el.closest('form').submit());
});

// Toggle critical low stock list
function toggleCriticalList() {
    const list = document.getElementById('criticalList');
    const toggle = document.getElementById('criticalToggleText');
    if (!list) return;
    const isVisible = list.style.display !== 'none';
    list.style.display = isVisible ? 'none' : 'flex';
    toggle.textContent = isVisible ? 'คลิกเพื่อดูรายละเอียด ▼' : 'ซ่อน ▲';
}

// Highlight table rows where stock < 10 with a red left border indicator
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('tr.row-out-of-stock, tr.row-low-stock').forEach(row => {
        // Find the stock cell (10th td = index 9)
        const tds = row.querySelectorAll('td');
        if (tds.length >= 10) {
            const stockCell = tds[9]; // คงเหลือ column
            const stockText = stockCell.querySelector('.fw-bold');
            if (stockText) {
                const val = parseInt(stockText.textContent.replace(/,/g, ''), 10);
                if (val < 10 && val > 0) {
                    row.style.outline = '2px solid #fca5a5';
                    row.style.outlineOffset = '-2px';
                    // Add a small warning icon next to the stock number
                    stockText.insertAdjacentHTML('afterend', '<div style="font-size:.72rem;color:#dc2626;font-weight:700;margin-top:1px;">⚠ ต่ำกว่า 10</div>');
                }
            }
        }
    });
});

</script>
@endpush
