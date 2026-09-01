@extends('layouts.app')
@section('title', 'จัดการเวชภัณฑ์')
@section('page-title', 'จัดการเวชภัณฑ์')
@section('sidebar') @include('partials.sidebar-staff') @endsection

@push('styles')
<style>
.table-supply { font-size: .86rem; }
.table-supply thead th {
    background: #f1f5f9; color: #374151; font-weight: 700;
    border-bottom: 2px solid #e2e8f0; white-space: nowrap; padding: 10px 12px; user-select: none;
}
.table-supply thead th.sortable { cursor: pointer; }
.table-supply thead th.sortable:hover { background: #e2e8f0; }
.table-supply tbody tr { transition: background .1s; }
.table-supply tbody tr:hover { background: #f8fafc !important; }
.table-supply td { padding: 8px 12px; vertical-align: middle; }
.stock-bar { height:7px; border-radius:4px; background:#e5e7eb; overflow:hidden; margin-top:3px; width:80px; }
.stock-bar-fill { height:100%; border-radius:4px; }
.supply-avatar { width:34px;height:34px;border-radius:8px;background:linear-gradient(135deg,#e0e7ff,#c7d2fe);display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0; }
.action-btn { border:none;background:#f3f4f6;border-radius:8px;padding:5px 10px;cursor:pointer;transition:background .15s; }
.action-btn:hover { background:#e5e7eb; }
.filter-bar { background:#f9fafb;border:1px solid #e5e7eb;border-radius:12px;padding:16px 18px;margin-bottom:18px; }
.filter-bar input, .filter-bar select { border-radius:8px;border:1.5px solid #d1d5db;font-size:.88rem;transition:border-color .15s; }
.filter-bar input:focus, .filter-bar select:focus { border-color:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,.12);outline:none; }
tr.row-out-of-stock td { background:#fee2e2 !important; }
tr.row-low-stock td { background:#fef9c3 !important; }
tr.row-near-expiry td { background:#fff7ed !important; }
.pag-wrapper { display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px; }
</style>
@endpush

@section('content')
<div class="panel">
    <div class="panel-header d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h5 class="mb-0"><i class="bi bi-capsule me-2 text-primary"></i>รายการเวชภัณฑ์</h5>
        <a href="{{ route('staff.supplies.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>+ เพิ่มเวชภัณฑ์
        </a>
    </div>

    <div class="panel-body pb-0">
        <form method="GET" action="{{ route('staff.supplies.index') }}" class="filter-bar">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold mb-1">🔍 ค้นหา</label>
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="รหัส / ชื่อยา / หมวดหมู่ / ผู้ผลิต / ตำแหน่ง..."
                        value="{{ request('search') }}">
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
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                        <i class="bi bi-search me-1"></i>ค้นหา
                    </button>
                    <a href="{{ route('staff.supplies.index') }}" class="btn btn-outline-secondary btn-sm" title="ล้าง">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </div>
            {{-- Quick Category Chips --}}
            <div class="d-flex flex-wrap gap-1 mt-2">
                <a href="{{ route('staff.supplies.index', array_merge(request()->except('category_id','page'))) }}"
                   class="badge {{ !request('category_id') ? 'bg-primary' : 'bg-light text-dark border' }} text-decoration-none px-3 py-2"
                   style="border-radius:20px;font-size:.8rem;">ทั้งหมด</a>
                @foreach($categories as $cat)
                <a href="{{ route('staff.supplies.index', array_merge(request()->except('category_id','page'), ['category_id'=>$cat->id])) }}"
                   class="badge {{ request('category_id') == $cat->id ? 'bg-primary' : 'bg-light text-dark border' }} text-decoration-none px-3 py-2"
                   style="border-radius:20px;font-size:.8rem;">{{ $cat->name }}</a>
                @endforeach
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-supply table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>รูป</th>
                    @php
                        function supSortLink($col, $label, $sortCol, $sortDir) {
                            $dir = ($sortCol === $col && $sortDir === 'asc') ? 'desc' : 'asc';
                            $icon = $sortCol === $col ? ($sortDir === 'asc' ? '↑' : '↓') : '⬍';
                            $params = array_merge(request()->all(), ['sort'=>$col,'dir'=>$dir,'page'=>1]);
                            return '<a href="'.route('staff.supplies.index',$params).'" class="text-dark text-decoration-none">'.$label.' <small class="text-muted">'.$icon.'</small></a>';
                        }
                    @endphp
                    <th class="sortable">{!! supSortLink('code','รหัส',$sortCol,$sortDir) !!}</th>
                    <th class="sortable">{!! supSortLink('name','ชื่อเวชภัณฑ์',$sortCol,$sortDir) !!}</th>
                    <th class="sortable">{!! supSortLink('category_id','หมวดหมู่',$sortCol,$sortDir) !!}</th>
                    <th class="sortable">{!! supSortLink('unit','หน่วย',$sortCol,$sortDir) !!}</th>
                    <th>ผู้ผลิต</th>
                    <th>ตำแหน่ง</th>
                    <th class="sortable">{!! supSortLink('stock','คงเหลือ',$sortCol,$sortDir) !!}</th>
                    <th>ขั้นต่ำ</th>
                    <th>สถานะ</th>
                    <th class="text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody>
            @forelse($supplies as $supply)
                @php
                    $stock    = (int)$supply->lots->sum('remaining_quantity');
                    $minStock = (int)$supply->min_stock;
                    $nearest  = $supply->lots->where('remaining_quantity', '>', 0)->sortBy('expiry_date')->first();
                    $target   = max(1, $minStock * 2);
                    $pct      = min(100, max(0, round(($stock / $target) * 100)));
                    $barColor = $pct >= 60 ? '#22c55e' : ($pct >= 25 ? '#f59e0b' : '#ef4444');

                    if ($stock <= 0) { $statusClass='bg-danger text-white'; $statusLabel='หมดสต็อก'; $rowClass='row-out-of-stock'; }
                    elseif ($nearest && $nearest->expiry_date && $nearest->expiry_date->isPast()) { $statusClass='bg-dark text-white'; $statusLabel='หมดอายุ'; $rowClass=''; }
                    elseif ($stock <= $minStock) { $statusClass='bg-warning text-dark'; $statusLabel='ใกล้หมด'; $rowClass='row-low-stock'; }
                    elseif ($nearest && $nearest->expiry_date && $nearest->expiry_date->diffInDays(now()) <= 90) { $statusClass='bg-warning bg-opacity-75 text-dark'; $statusLabel='ใกล้หมดอายุ'; $rowClass='row-near-expiry'; }
                    else { $statusClass='bg-success text-white'; $statusLabel='ปกติ'; $rowClass=''; }
                @endphp
                <tr class="{{ $rowClass }}">
                    <td class="text-muted small">{{ $supplies->firstItem() + $loop->index }}</td>
                    <td>
                        @if($supply->image_url && !str_contains($supply->image_url, 'default.svg'))
                        <img src="{{ $supply->image_url }}" class="rounded" style="width:34px;height:34px;object-fit:cover;">
                        @else
                        <div class="supply-avatar">💊</div>
                        @endif
                    </td>
                    <td><code class="text-primary fw-bold" style="font-size:.82rem">{{ $supply->code }}</code></td>
                    <td><strong>{{ $supply->name }}</strong></td>
                    <td><span class="badge bg-light text-dark border" style="border-radius:20px;font-size:.78rem;">{{ $supply->category->name ?? '-' }}</span></td>
                    <td class="text-center text-muted small">{{ $supply->unit }}</td>
                    <td class="text-muted small">{{ $supply->manufacturer ?? '-' }}</td>
                    <td>
                        @if($supply->storage_location)
                        <span class="badge bg-light border text-secondary" style="border-radius:6px;font-size:.78rem;"><i class="bi bi-geo-alt me-1"></i>{{ $supply->storage_location }}</span>
                        @else<span class="text-muted">-</span>@endif
                    </td>
                    <td>
                        <div class="fw-bold" style="font-size:.9rem;">{{ number_format($stock) }}</div>
                        <div class="stock-bar"><div class="stock-bar-fill" style="width:{{ $pct }}%;background:{{ $barColor }};"></div></div>
                    </td>
                    <td class="text-center text-muted small">{{ $minStock }}</td>
                    <td><span class="badge {{ $statusClass }}" style="border-radius:20px;font-size:.78rem;padding:4px 10px;">{{ $statusLabel }}</span></td>
                    <td class="text-center">
                        <div class="dropdown">
                            <button class="action-btn" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="font-size:.86rem;min-width:160px;">
                                <li><a class="dropdown-item" href="{{ route('staff.supplies.edit', $supply) }}"><i class="bi bi-pencil me-2 text-primary"></i>แก้ไข</a></li>
                                <li><a class="dropdown-item" href="{{ route('staff.transactions.create', ['supply_id'=>$supply->id,'type'=>'receive']) }}"><i class="bi bi-box-arrow-in-down me-2 text-success"></i>รับเข้าสต็อก</a></li>
                                <li><a class="dropdown-item" href="{{ route('staff.transactions.create', ['supply_id'=>$supply->id,'type'=>'dispense']) }}"><i class="bi bi-box-arrow-up me-2 text-warning"></i>เบิกจ่าย</a></li>
                                <li><a class="dropdown-item" href="{{ route('staff.transactions.index', ['supply_id'=>$supply->id]) }}"><i class="bi bi-clock-history me-2 text-secondary"></i>ดูประวัติ</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('staff.supplies.destroy', $supply) }}" method="POST" class="d-inline" onsubmit="return confirm('ยืนยันลบ {{ $supply->name }}?')">
                                        @csrf @method('DELETE')
                                        <button class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>ลบ</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="12" class="text-center text-muted py-5"><i class="bi bi-inbox fs-3 d-block mb-2"></i>ไม่พบเวชภัณฑ์</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-3">
        <div class="pag-wrapper">
            <div class="text-muted small">
                แสดง <strong>{{ $supplies->firstItem() ?? 0 }}</strong>–<strong>{{ $supplies->lastItem() ?? 0 }}</strong>
                จากทั้งหมด <strong>{{ $supplies->total() }}</strong> รายการ
            </div>
            {{ $supplies->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.filter-bar select[name="category_id"],.filter-bar select[name="unit"],.filter-bar select[name="per_page"]').forEach(el => {
    el.addEventListener('change', () => el.closest('form').submit());
});
</script>
@endpush
