@extends('layouts.app')
@section('title', 'สต็อกเวชภัณฑ์')
@section('page-title', 'รายงานสต็อกเวชภัณฑ์')
@section('sidebar') @include('partials.sidebar-executive') @endsection

@push('styles')
<style>
.stock-page { font-family: 'Sarabun', 'Inter', sans-serif; }
.alert-stock-banner { background: linear-gradient(135deg, #fff3cd 0%, #fff8e1 100%); border-left: 4px solid #f59e0b; border-radius: 10px; padding: 12px 18px; display: flex; align-items: center; gap: 10px; font-size: .92rem; }
.alert-stock-banner.danger { background: linear-gradient(135deg, #fee2e2 0%, #fff5f5 100%); border-left-color: #ef4444; }
.stat-cards { display: grid; grid-template-columns: repeat(6, 1fr); gap: 12px; margin-bottom: 20px; }
@media(max-width:1100px){ .stat-cards { grid-template-columns: repeat(3,1fr); } }
@media(max-width:600px){ .stat-cards { grid-template-columns: repeat(2,1fr); } }
.stat-card { background: #fff; border-radius: 12px; padding: 14px 16px; border: 1px solid #e5e7eb; box-shadow: 0 1px 4px rgba(0,0,0,.05); display: flex; align-items: center; gap: 12px; cursor: pointer; transition: box-shadow .15s, transform .15s; }
.stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.10); transform: translateY(-2px); }
.stat-card.active { outline: 2.5px solid var(--card-color, #3b82f6); }
.stat-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; }
.stat-info .count { font-size: 1.6rem; font-weight: 800; line-height: 1; }
.stat-info .label { font-size: .78rem; color: #6b7280; margin-top: 2px; }
.filter-bar { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px 18px; margin-bottom: 18px; }
.filter-bar input, .filter-bar select { border-radius: 8px; border: 1.5px solid #d1d5db; font-size: .88rem; transition: border-color .15s, box-shadow .15s; }
.filter-bar input:focus, .filter-bar select:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.12); outline: none; }
.table-stock { font-size: .86rem; }
.table-stock thead th { background: #f1f5f9; color: #374151; font-weight: 700; border-bottom: 2px solid #e2e8f0; white-space: nowrap; padding: 10px 12px; user-select: none; }
.table-stock thead th.sortable { cursor: pointer; }
.table-stock thead th.sortable:hover { background: #e2e8f0; }
.table-stock tbody tr { transition: background .1s; }
.table-stock tbody tr:hover { background: #f8fafc !important; }
.table-stock td { padding: 8px 12px; vertical-align: middle; }
.stock-bar-wrap { min-width: 80px; }
.stock-bar { height: 7px; border-radius: 4px; background: #e5e7eb; overflow: hidden; margin-top: 3px; }
.stock-bar-fill { height: 100%; border-radius: 4px; transition: width .4s; }
.expiry-badge { font-size: .78rem; padding: 3px 8px; border-radius: 20px; font-weight: 600; }
.expiry-ok { background: #dcfce7; color: #166534; }
.expiry-warn { background: #fef3c7; color: #92400e; }
.expiry-danger { background: #fee2e2; color: #991b1b; }
.status-badge { font-size: .78rem; padding: 4px 10px; border-radius: 20px; font-weight: 700; white-space: nowrap; }
.action-btn { border: none; background: #f3f4f6; border-radius: 8px; padding: 5px 10px; cursor: pointer; transition: background .15s; }
.action-btn:hover { background: #e5e7eb; }
.supply-avatar { width: 36px; height: 36px; border-radius: 8px; background: linear-gradient(135deg, #e0e7ff, #c7d2fe); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
.pag-wrapper { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
tr.row-out-of-stock td { background: #fee2e2 !important; }
tr.row-low-stock td { background: #fef9c3 !important; }
tr.row-expired td { background: #f1f5f9 !important; }
tr.row-near-expiry td { background: #fff7ed !important; }
.chart-card { background: #fff; border-radius: 12px; border: 1px solid #e5e7eb; padding: 18px; box-shadow: 0 1px 4px rgba(0,0,0,.05); }
</style>
@endpush

@section('content')
<div class="stock-page">

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

<div class="stat-cards mb-3">
    <a href="{{ route('executive.reports.stock', array_merge(request()->except('status_filter','page'), [])) }}" class="stat-card text-decoration-none {{ !$statusFilter ? 'active' : '' }}" style="--card-color:#6366f1">
        <div class="stat-icon" style="background:#ede9fe;color:#6366f1"><i class="bi bi-boxes"></i></div>
        <div class="stat-info"><div class="count" style="color:#6366f1">{{ $stats['total'] }}</div><div class="label">รายการทั้งหมด</div></div>
    </a>
    <a href="{{ route('executive.reports.stock', array_merge(request()->except('status_filter','page'), ['status_filter'=>'normal'])) }}" class="stat-card text-decoration-none {{ $statusFilter==='normal' ? 'active' : '' }}" style="--card-color:#22c55e">
        <div class="stat-icon" style="background:#dcfce7;color:#22c55e"><i class="bi bi-check-circle-fill"></i></div>
        <div class="stat-info"><div class="count" style="color:#22c55e">{{ $stats['normal'] }}</div><div class="label">ปกติ</div></div>
    </a>
    <a href="{{ route('executive.reports.stock', array_merge(request()->except('status_filter','page'), ['status_filter'=>'low_stock'])) }}" class="stat-card text-decoration-none {{ $statusFilter==='low_stock' ? 'active' : '' }}" style="--card-color:#eab308">
        <div class="stat-icon" style="background:#fef9c3;color:#ca8a04"><i class="bi bi-exclamation-triangle-fill"></i></div>
        <div class="stat-info"><div class="count" style="color:#ca8a04">{{ $stats['low_stock'] }}</div><div class="label">ใกล้หมด</div></div>
    </a>
    <a href="{{ route('executive.reports.stock', array_merge(request()->except('status_filter','page'), ['status_filter'=>'out_of_stock'])) }}" class="stat-card text-decoration-none {{ $statusFilter==='out_of_stock' ? 'active' : '' }}" style="--card-color:#ef4444">
        <div class="stat-icon" style="background:#fee2e2;color:#ef4444"><i class="bi bi-x-circle-fill"></i></div>
        <div class="stat-info"><div class="count" style="color:#ef4444">{{ $stats['out_of_stock'] }}</div><div class="label">หมดสต็อก</div></div>
    </a>
    <a href="{{ route('executive.reports.stock', array_merge(request()->except('status_filter','page'), ['status_filter'=>'near_expiry'])) }}" class="stat-card text-decoration-none {{ $statusFilter==='near_expiry' ? 'active' : '' }}" style="--card-color:#f97316">
        <div class="stat-icon" style="background:#ffedd5;color:#f97316"><i class="bi bi-clock-history"></i></div>
        <div class="stat-info"><div class="count" style="color:#f97316">{{ $stats['near_expiry'] }}</div><div class="label">ใกล้หมดอายุ</div></div>
    </a>
    <a href="{{ route('executive.reports.stock', array_merge(request()->except('status_filter','page'), ['status_filter'=>'expired'])) }}" class="stat-card text-decoration-none {{ $statusFilter==='expired' ? 'active' : '' }}" style="--card-color:#6b7280">
        <div class="stat-icon" style="background:#f3f4f6;color:#374151"><i class="bi bi-slash-circle-fill"></i></div>
        <div class="stat-info"><div class="count" style="color:#374151">{{ $stats['expired'] }}</div><div class="label">หมดอายุ</div></div>
    </a>
</div>

<div class="panel">
    <div class="panel-header d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h5 class="mb-0"><i class="bi bi-box-seam me-2 text-primary"></i>สต็อกเวชภัณฑ์ทั้งหมด</h5>
    </div>

    <div class="panel-body pb-0">
        <form method="GET" action="{{ route('executive.reports.stock') }}" class="filter-bar">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold mb-1">🔍 ค้นหา</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="รหัส / ชื่อยา / หมวดหมู่ / ผู้ผลิต / ตำแหน่ง..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold mb-1">หมวดหมู่</label>
                    <select name="category_id" class="form-select form-select-sm">
                        <option value="">ทั้งหมด</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold mb-1">หน่วย</label>
                    <select name="unit" class="form-select form-select-sm">
                        <option value="">ทุกหน่วย</option>
                        @foreach($units as $u)
                        <option value="{{ $u }}" {{ request('unit') == $u ? 'selected' : '' }}>{{ $u }}</option>
                        @endforeach
                    </select>
                </div>
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
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-search me-1"></i>ค้นหา</button>
                    <a href="{{ route('executive.reports.stock') }}" class="btn btn-outline-secondary btn-sm" title="ล้างตัวกรอง"><i class="bi bi-x-lg"></i></a>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-1 mt-2">
                <a href="{{ route('executive.reports.stock', array_merge(request()->except('category_id','page'))) }}" class="badge {{ !request('category_id') ? 'bg-primary' : 'bg-light text-dark border' }} text-decoration-none px-3 py-2" style="border-radius:20px; font-size:.8rem;">ทั้งหมด</a>
                @foreach($categories as $cat)
                <a href="{{ route('executive.reports.stock', array_merge(request()->except('category_id','page'), ['category_id'=>$cat->id])) }}" class="badge {{ request('category_id') == $cat->id ? 'bg-primary' : 'bg-light text-dark border' }} text-decoration-none px-3 py-2" style="border-radius:20px; font-size:.8rem;">{{ $cat->name }}</a>
                @endforeach
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-stock table-hover mb-0">
            <thead>
                <tr>
                    <th style="width:40px">#</th>
                    <th style="width:50px">รูป</th>
                    @php
                        function sortLinkExec($col, $label, $sortCol, $sortDir) {
                            $dir = ($sortCol === $col && $sortDir === 'asc') ? 'desc' : 'asc';
                            $icon = $sortCol === $col ? ($sortDir === 'asc' ? '↑' : '↓') : '⬍';
                            $params = array_merge(request()->all(), ['sort' => $col, 'dir' => $dir, 'page' => 1]);
                            return '<a href="'.route('executive.reports.stock', $params).'" class="text-dark text-decoration-none">'.$label.' <small class="text-muted">'.$icon.'</small></a>';
                        }
                    @endphp
                    <th class="sortable">{!! sortLinkExec('code', 'รหัส', $sortCol, $sortDir) !!}</th>
                    <th class="sortable">{!! sortLinkExec('name', 'ชื่อเวชภัณฑ์', $sortCol, $sortDir) !!}</th>
                    <th class="sortable">{!! sortLinkExec('category_id', 'หมวดหมู่', $sortCol, $sortDir) !!}</th>
                    <th class="sortable">{!! sortLinkExec('unit', 'หน่วย', $sortCol, $sortDir) !!}</th>
                    <th>ผู้ผลิต</th>
                    <th>ตำแหน่ง</th>
                    <th>เลขล็อต</th>
                    <th>วันหมดอายุ</th>
                    <th class="sortable">{!! sortLinkExec('stock', 'คงเหลือ', $sortCol, $sortDir) !!}</th>
                    <th>ขั้นต่ำ</th>
                    <th>สถานะ</th>
                    <th>อัปเดต</th>
                </tr>
            </thead>
            <tbody>
                @foreach($supplies as $idx => $supply)
                    @php
                        $stock = (int)($supply->total_stock_calc ?? 0);
                        $nearest = $supply->lots->where('remaining_quantity', '>', 0)->sortBy('expiry_date')->first();
                        $minStock = (int)$supply->min_stock;
                        if ($stock <= 0) {
                            $statusClass = 'bg-danger text-white';
                            $statusLabel = 'หมดสต็อก';
                            $rowClass = 'row-out-of-stock';
                        } elseif ($nearest && $nearest->expiry_date && $nearest->expiry_date->isPast()) {
                            $statusClass = 'bg-dark text-white';
                            $statusLabel = 'หมดอายุ';
                            $rowClass = 'row-expired';
                        } elseif ($stock <= $minStock) {
                            $statusClass = 'bg-warning text-dark';
                            $statusLabel = 'ใกล้หมด';
                            $rowClass = 'row-low-stock';
                        } elseif ($nearest && $nearest->expiry_date && $nearest->expiry_date->diffInDays(now()) <= 90) {
                            $statusClass = 'bg-warning bg-opacity-75 text-dark';
                            $statusLabel = 'ใกล้หมดอายุ';
                            $rowClass = 'row-near-expiry';
                        } else {
                            $statusClass = 'bg-success text-white';
                            $statusLabel = 'ปกติ';
                            $rowClass = '';
                        }
                        $expiryLabel = '-';
                        if ($nearest && $nearest->expiry_date) {
                            if ($nearest->expiry_date->isPast()) {
                                $expiryLabel = $nearest->expiry_date->format('d/m/Y').' (หมดแล้ว)';
                            } else {
                                $daysLeft = now()->diffInDays($nearest->expiry_date);
                                $expiryLabel = $nearest->expiry_date->format('d/m/Y');
                                if ($daysLeft <= 180) {
                                    $expiryLabel .= ' ('.$daysLeft.' วัน)';
                                }
                            }
                        }
                    @endphp
                    <tr class="{{ $rowClass }}">
                        <td>{{ $supplies->firstItem() + $idx }}</td>
                        <td><div class="supply-avatar">{{ strtoupper(substr($supply->name, 0, 1)) }}</div></td>
                        <td>{{ $supply->code }}</td>
                        <td><strong>{{ $supply->name }}</strong></td>
                        <td>{{ $supply->category->name ?? '-' }}</td>
                        <td>{{ $supply->unit }}</td>
                        <td>{{ $supply->manufacturer ?? '-' }}</td>
                        <td>{{ $supply->storage_location ?? '-' }}</td>
                        <td>{{ $nearest->lot_number ?? '-' }}</td>
                        <td>{{ $expiryLabel }}</td>
                        <td>{{ $stock }}</td>
                        <td>{{ $minStock }}</td>
                        <td><span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span></td>
                        <td>{{ $supply->updated_at ? $supply->updated_at->format('d/m/Y') : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pag-wrapper mt-3">
        <div>แสดง {{ $supplies->firstItem() }}-{{ $supplies->lastItem() }} จาก {{ $supplies->total() }} รายการ</div>
        <div>{{ $supplies->links() }}</div>
    </div>
</div>
@endsection
