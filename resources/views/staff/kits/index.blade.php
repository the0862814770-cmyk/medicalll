@extends('layouts.app')
@section('title', 'กระเป๋าปฐมพยาบาล')
@section('page-title', 'จัดการกระเป๋าปฐมพยาบาล')
@section('sidebar') @include('partials.sidebar-staff') @endsection

@push('styles')
<style>
/* ======= Kit Page Premium Styles ======= */
.kit-stat-cards { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 20px; }
@media(max-width:768px){ .kit-stat-cards { grid-template-columns: repeat(2,1fr); } }
.kit-stat-card {
    background: #fff; border-radius: 14px; padding: 18px 20px;
    border: 1px solid #e5e7eb; box-shadow: 0 1px 6px rgba(0,0,0,.05);
    display: flex; align-items: center; gap: 14px;
    transition: box-shadow .15s, transform .15s;
    text-decoration: none; color: inherit;
}
.kit-stat-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,.10); transform: translateY(-3px); }
.kit-stat-card.active { outline: 2.5px solid var(--card-accent, #6366f1); }
.kit-stat-icon {
    width: 50px; height: 50px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; flex-shrink: 0;
}
.kit-stat-info .count { font-size: 2rem; font-weight: 800; line-height: 1; }
.kit-stat-info .label { font-size: .82rem; color: #6b7280; margin-top: 3px; }

/* Filter Bar */
.kit-filter-bar {
    background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px;
    padding: 14px 18px; margin-bottom: 16px;
}
.kit-filter-bar input, .kit-filter-bar select {
    border-radius: 8px; border: 1.5px solid #d1d5db; font-size: .88rem;
}
.kit-filter-bar input:focus, .kit-filter-bar select:focus {
    border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.12); outline: none;
}

/* Table */
.table-kit { font-size: .88rem; }
.table-kit thead th {
    background: #f1f5f9; color: #374151; font-weight: 700;
    border-bottom: 2px solid #e2e8f0; white-space: nowrap; padding: 10px 14px;
}
.table-kit tbody td { padding: 10px 14px; vertical-align: middle; }
.table-kit tbody tr { transition: background .1s; }
.table-kit tbody tr:hover { background: #f8fafc !important; }

/* Action Buttons */
.kit-action-btn {
    border: none; background: #f3f4f6; border-radius: 8px;
    padding: 6px 10px; cursor: pointer; transition: background .15s;
}
.kit-action-btn:hover { background: #e5e7eb; }

/* Kit avatar */
.kit-avatar {
    width: 42px; height: 42px; border-radius: 10px;
    background: linear-gradient(135deg, #dcfce7, #bbf7d0);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; flex-shrink: 0;
}

/* Status badge */
.kit-badge { font-size: .8rem; padding: 5px 12px; border-radius: 20px; font-weight: 700; white-space: nowrap; }

/* Item chips in table */
.item-chip {
    display: inline-block; background: #f0f4ff; color: #4338ca; border-radius: 20px;
    font-size: .75rem; padding: 2px 10px; margin: 1px 2px; white-space: nowrap;
}
</style>
@endpush

@section('content')
<div>

{{-- ===== SUMMARY STAT CARDS ===== --}}
<div class="kit-stat-cards">
    <a href="{{ route('staff.kits.index', request()->except('status','page')) }}" class="kit-stat-card {{ !request('status') ? 'active' : '' }}" style="--card-accent:#6366f1">
        <div class="kit-stat-icon" style="background:#ede9fe;color:#6366f1">
            <i class="bi bi-briefcase-fill"></i>
        </div>
        <div class="kit-stat-info">
            <div class="count" style="color:#6366f1">{{ $stats['total'] }}</div>
            <div class="label">กระเป๋าทั้งหมด</div>
        </div>
    </a>
    <a href="{{ route('staff.kits.index', array_merge(request()->except('status','page'), ['status'=>'available'])) }}" class="kit-stat-card {{ request('status')==='available' ? 'active' : '' }}" style="--card-accent:#22c55e">
        <div class="kit-stat-icon" style="background:#dcfce7;color:#22c55e">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div class="kit-stat-info">
            <div class="count" style="color:#22c55e">{{ $stats['available'] }}</div>
            <div class="label">พร้อมใช้งาน</div>
        </div>
    </a>
    <a href="{{ route('staff.kits.index', array_merge(request()->except('status','page'), ['status'=>'borrowed'])) }}" class="kit-stat-card {{ request('status')==='borrowed' ? 'active' : '' }}" style="--card-accent:#f59e0b">
        <div class="kit-stat-icon" style="background:#fef3c7;color:#f59e0b">
            <i class="bi bi-arrow-left-right"></i>
        </div>
        <div class="kit-stat-info">
            <div class="count" style="color:#f59e0b">{{ $stats['borrowed'] }}</div>
            <div class="label">กำลังถูกยืม</div>
        </div>
    </a>
    <a href="{{ route('staff.kits.index', array_merge(request()->except('status','page'), ['status'=>'maintenance'])) }}" class="kit-stat-card {{ request('status')==='maintenance' ? 'active' : '' }}" style="--card-accent:#6b7280">
        <div class="kit-stat-icon" style="background:#f3f4f6;color:#6b7280">
            <i class="bi bi-tools"></i>
        </div>
        <div class="kit-stat-info">
            <div class="count" style="color:#6b7280">{{ $stats['maintenance'] }}</div>
            <div class="label">ซ่อมบำรุง</div>
        </div>
    </a>
</div>

{{-- ===== PANEL ===== --}}
<div class="panel">
    <div class="panel-header d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h5 class="mb-0"><i class="bi bi-briefcase me-2 text-primary"></i>กระเป๋าปฐมพยาบาล</h5>
        <a href="{{ route('staff.kits.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>+ เพิ่มกระเป๋า
        </a>
    </div>

    {{-- ===== SEARCH BAR ===== --}}
    <div class="panel-body pb-0">
        <form method="GET" action="{{ route('staff.kits.index') }}" class="kit-filter-bar">
            <div class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold mb-1">🔍 ค้นหารหัสหรือชื่อกระเป๋า</label>
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="รหัสกระเป๋า / ชื่อกระเป๋า..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold mb-1">สถานะ</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">ทั้งหมด</option>
                        <option value="available" {{ request('status')==='available' ? 'selected' : '' }}>พร้อมใช้งาน</option>
                        <option value="borrowed" {{ request('status')==='borrowed' ? 'selected' : '' }}>กำลังถูกยืม</option>
                        <option value="maintenance" {{ request('status')==='maintenance' ? 'selected' : '' }}>ซ่อมบำรุง</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                        <i class="bi bi-search me-1"></i>ค้นหา
                    </button>
                    <a href="{{ route('staff.kits.index') }}" class="btn btn-outline-secondary btn-sm" title="ล้างตัวกรอง">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- ===== TABLE ===== --}}
    <div class="table-responsive">
        <table class="table table-kit table-hover mb-0">
            <thead>
                <tr>
                    <th style="width:40px">#</th>
                    <th style="width:50px"></th>
                    <th>รหัส</th>
                    <th>ชื่อกระเป๋า</th>
                    <th>รายการเวชภัณฑ์</th>
                    <th>สถานะ</th>
                    <th>อัปเดตล่าสุด</th>
                    <th class="text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody>
            @forelse($kits as $kit)
                @php
                    $statusMap = [
                        'available'   => ['พร้อมใช้งาน',  'bg-success text-white',  'bi-check-circle-fill'],
                        'borrowed'    => ['กำลังถูกยืม',    'bg-warning text-dark',   'bi-arrow-left-right'],
                        'maintenance' => ['ซ่อมบำรุง',     'bg-secondary text-white', 'bi-tools'],
                    ];
                    $st = $statusMap[$kit->status] ?? ['ไม่ทราบ', 'bg-light text-dark', 'bi-question-circle'];
                @endphp
                <tr>
                    <td class="text-muted small">{{ $kits->firstItem() + $loop->index }}</td>
                    <td>
                        <div class="kit-avatar">🩺</div>
                    </td>
                    <td>
                        <code class="text-primary fw-bold" style="font-size:.85rem">{{ $kit->kit_code }}</code>
                    </td>
                    <td>
                        <strong>{{ $kit->name }}</strong>
                        @if($kit->description)
                        <div class="text-muted" style="font-size:.75rem;max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $kit->description }}</div>
                        @endif
                    </td>
                    <td>
                        @if($kit->items && $kit->items->count() > 0)
                            @foreach($kit->items->take(3) as $item)
                                <span class="item-chip">{{ $item->supply->name ?? '?' }} ×{{ $item->quantity }}</span>
                            @endforeach
                            @if($kit->items->count() > 3)
                                <span class="item-chip" style="background:#fef3c7;color:#92400e;">+{{ $kit->items->count() - 3 }} อื่นๆ</span>
                            @endif
                        @else
                            <span class="text-muted small">ยังไม่มีรายการ</span>
                        @endif
                    </td>
                    <td>
                        <span class="kit-badge {{ $st[1] }}">
                            <i class="bi {{ $st[2] }} me-1"></i>{{ $st[0] }}
                        </span>
                    </td>
                    <td class="text-muted" style="font-size:.78rem;white-space:nowrap;">
                        {{ $kit->updated_at ? $kit->updated_at->format('d/m/Y H:i') : '-' }}
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            {{-- ดูรายละเอียด --}}
                            <button type="button" class="kit-action-btn" data-bs-toggle="modal" data-bs-target="#viewKitModal{{ $kit->id }}" title="ดูรายละเอียด">
                                <i class="bi bi-eye text-info"></i>
                            </button>
                            {{-- เพิ่ม/จัดการรายการยา --}}
                            <button type="button" class="kit-action-btn" data-bs-toggle="modal" data-bs-target="#addItemModal{{ $kit->id }}" title="เพิ่ม/จัดการรายการยา">
                                <i class="bi bi-plus-circle text-success fw-bold"></i>
                            </button>
                            {{-- แก้ไข --}}
                            <a href="{{ route('staff.kits.edit', $kit) }}" class="kit-action-btn" title="แก้ไข">
                                <i class="bi bi-pencil text-primary"></i>
                            </a>
                            {{-- ลบ --}}
                            <form action="{{ route('staff.kits.destroy', $kit) }}" method="POST" class="d-inline" onsubmit="return confirm('ยืนยันลบกระเป๋า {{ $kit->name }}?')">
                                @csrf @method('DELETE')
                                <button class="kit-action-btn" title="ลบ">
                                    <i class="bi bi-trash text-danger"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted py-5"><i class="bi bi-inbox fs-3 d-block mb-2"></i>ไม่พบกระเป๋าปฐมพยาบาล</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- ===== MODALS (outside table) ===== --}}
    @foreach($kits as $kit)
    @php
        $statusMap2 = [
            'available'   => ['พร้อมใช้งาน',  'bg-success text-white',  'bi-check-circle-fill'],
            'borrowed'    => ['กำลังถูกยืม',    'bg-warning text-dark',   'bi-arrow-left-right'],
            'maintenance' => ['ซ่อมบำรุง',     'bg-secondary text-white', 'bi-tools'],
        ];
        $st2 = $statusMap2[$kit->status] ?? ['ไม่ทราบ', 'bg-light text-dark', 'bi-question-circle'];
    @endphp
    <div class="modal fade text-start" id="viewKitModal{{ $kit->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">
                        <i class="bi bi-briefcase text-primary me-2"></i>รายละเอียดกระเป๋าปฐมพยาบาล
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 text-center">
                            <div style="width:100%;height:120px;background:linear-gradient(135deg,#dcfce7,#bbf7d0);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:3.5rem;">🩺</div>
                            <div class="mt-2">
                                <code class="text-primary fw-bold fs-6">{{ $kit->kit_code }}</code>
                            </div>
                            <div class="mt-1">
                                <span class="kit-badge {{ $st2[1] }}"><i class="bi {{ $st2[2] }} me-1"></i>{{ $st2[0] }}</span>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <h4 class="fw-bold mb-1">{{ $kit->name }}</h4>
                            <p class="text-muted small mb-3">{{ $kit->description ?? 'ไม่มีคำอธิบาย' }}</p>
                            <div class="row g-2">
                                <div class="col-4">
                                    <div class="p-2 bg-light rounded-2 border text-center">
                                        <div class="text-muted" style="font-size:.75rem">สถานะ</div>
                                        <strong>{{ $st2[0] }}</strong>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-2 bg-light rounded-2 border text-center">
                                        <div class="text-muted" style="font-size:.75rem">จำนวนรายการ</div>
                                        <strong class="fs-5 text-primary">{{ $kit->items ? $kit->items->count() : 0 }}</strong>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-2 bg-light rounded-2 border text-center">
                                        <div class="text-muted" style="font-size:.75rem">อัปเดตล่าสุด</div>
                                        <strong style="font-size:.85rem">{{ $kit->updated_at ? $kit->updated_at->format('d/m/Y') : '-' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- รายการเวชภัณฑ์ภายในกระเป๋า --}}
                    <div>
                        <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
                            <h6 class="fw-bold mb-0"><i class="bi bi-box-seam me-2"></i>รายการเวชภัณฑ์ภายในกระเป๋า ({{ $kit->items ? $kit->items->count() : 0 }} รายการ)</h6>
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addItemModal{{ $kit->id }}">
                                <i class="bi bi-plus-lg me-1"></i>เพิ่ม/จัดการรายการยา
                            </button>
                        </div>
                        @if($kit->items && $kit->items->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:50px">#</th>
                                        <th>ชื่อเวชภัณฑ์</th>
                                        <th class="text-center" style="width:100px">รหัส</th>
                                        <th class="text-center" style="width:120px">จำนวน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($kit->items as $idx => $item)
                                    <tr>
                                        <td class="text-muted">{{ $idx + 1 }}</td>
                                        <td><strong>{{ $item->supply->name ?? '-' }}</strong></td>
                                        <td class="text-center"><code>{{ $item->supply->code ?? '-' }}</code></td>
                                        <td class="text-center fw-bold">{{ $item->quantity }} {{ $item->supply->unit ?? 'ชิ้น' }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            ยังไม่มีรายการเวชภัณฑ์ในกระเป๋านี้
                        </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-success btn-sm me-auto" data-bs-toggle="modal" data-bs-target="#addItemModal{{ $kit->id }}">
                        <i class="bi bi-plus-circle me-1"></i>+ เพิ่มยาลงกระเป๋านี้
                    </button>
                    <a href="{{ route('staff.kits.edit', $kit) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil me-1"></i>แก้ไขข้อมูล
                    </a>
                    <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal เพิ่ม/จัดการรายการยา -->
    <div class="modal fade text-start" id="addItemModal{{ $kit->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success bg-opacity-10 border-bottom">
                    <h5 class="modal-title fw-bold text-success">
                        <i class="bi bi-capsule me-2"></i>เพิ่ม/จัดการรายการยา: {{ $kit->name }} (<code>{{ $kit->kit_code }}</code>)
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    {{-- ฟอร์มเพิ่มยา --}}
                    <div class="card border-0 bg-light mb-4 shadow-sm">
                        <div class="card-body p-3">
                            <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-plus-circle-fill text-success me-2"></i>เพิ่มรายการยาลงในกระเป๋า</h6>
                            <form action="{{ route('staff.kits.items.add', $kit) }}" method="POST">
                                @csrf
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-7">
                                        <label class="form-label small fw-semibold mb-1">เลือกยา/เวชภัณฑ์ *</label>
                                        <select name="supply_id" class="form-select form-select-sm" required>
                                            <option value="">-- เลือกยา/เวชภัณฑ์ --</option>
                                            @foreach($supplies as $supply)
                                                <option value="{{ $supply->id }}">
                                                    [{{ $supply->code }}] {{ $supply->name }} (คลัง: {{ $supply->total_stock }} {{ $supply->unit }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold mb-1">จำนวน *</label>
                                        <input type="number" name="quantity" class="form-control form-control-sm text-center" value="1" min="1" required placeholder="จำนวน">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-success btn-sm w-100 fw-bold">
                                            <i class="bi bi-plus-lg me-1"></i>เพิ่ม
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- ตารางรายการยาปัจจุบันในกระเป๋า --}}
                    <h6 class="fw-bold mb-2"><i class="bi bi-list-check me-2"></i>รายการยาปัจจุบันในกระเป๋า ({{ $kit->items ? $kit->items->count() : 0 }} รายการ)</h6>
                    @if($kit->items && $kit->items->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:40px" class="text-center">#</th>
                                        <th style="width:90px" class="text-center">รหัส</th>
                                        <th>ชื่อรายการยา</th>
                                        <th style="width:170px" class="text-center">จำนวน</th>
                                        <th style="width:80px" class="text-center">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kit->items as $idx => $item)
                                        <tr>
                                            <td class="text-muted text-center">{{ $idx + 1 }}</td>
                                            <td class="text-center"><code class="text-primary fw-bold">{{ $item->supply->code ?? '-' }}</code></td>
                                            <td><strong>{{ $item->supply->name ?? '-' }}</strong></td>
                                            <td>
                                                <form action="{{ route('staff.kits.items.update', [$kit, $item]) }}" method="POST" class="d-flex gap-1 align-items-center justify-content-center">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control form-control-sm text-center" style="width:65px">
                                                    <span class="text-muted small me-1">{{ $item->supply->unit ?? 'ชิ้น' }}</span>
                                                    <button type="submit" class="btn btn-outline-primary btn-sm px-2" title="บันทึกจำนวน">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('staff.kits.items.remove', [$kit, $item]) }}" method="POST" class="d-inline" onsubmit="return confirm('ยืนยันลบ {{ $item->supply->name ?? 'รายการนี้' }} ออกจากกระเป๋า?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm px-2" title="ลบออกจากกระเป๋า">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4 bg-light rounded-3 border">
                            <i class="bi bi-inbox fs-3 d-block mb-1 text-secondary"></i>
                            ยังไม่มีรายการยาในกระเป๋านี้ เลือกยาจากด้านบนแล้วกด "เพิ่ม" ได้ทันที
                        </div>
                    @endif
                </div>
                <div class="modal-footer bg-light">
                    <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">ปิดหน้าต่าง</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    {{-- ===== PAGINATION ===== --}}
    <div class="p-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="text-muted small">
                แสดง <strong>{{ $kits->firstItem() ?? 0 }}</strong>–<strong>{{ $kits->lastItem() ?? 0 }}</strong>
                จากทั้งหมด <strong>{{ $kits->total() }}</strong> รายการ
            </div>
            {{ $kits->withQueryString()->links() }}
        </div>
    </div>
</div>

</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.kit-filter-bar select[name="status"]').forEach(el => {
    el.addEventListener('change', () => el.closest('form').submit());
});

@if(session('open_modal_id'))
document.addEventListener('DOMContentLoaded', function() {
    var modalId = 'addItemModal{{ session("open_modal_id") }}';
    var kitModalEl = document.getElementById(modalId);
    if (kitModalEl) {
        var modal = new bootstrap.Modal(kitModalEl);
        modal.show();
    }
});
@endif
</script>
@endpush
