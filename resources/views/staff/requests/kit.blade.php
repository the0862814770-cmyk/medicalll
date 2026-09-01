@extends('layouts.app')
@section('title', 'คำร้องยืมกระเป๋าปฐมพยาบาล')
@section('page-title', 'คำร้องยืมกระเป๋าปฐมพยาบาล')
@section('sidebar') @include('partials.sidebar-staff') @endsection

@php
    // Define status configuration
    $statusConfig = [
        'pending' => [
            'class' => 'kb-pending',
            'icon' => '⏳',
            'label' => 'รอดำเนินการ',
            'bgColor' => '#fffbeb',
            'iconColor' => '#d97706',
        ],
        'executive_approved' => [
            'class' => 'kb-exec-approved',
            'icon' => '✅',
            'label' => 'อนุมัติแล้ว',
            'bgColor' => '#dcfce7',
            'iconColor' => '#16a34a',
        ],
        'borrowed' => [
            'class' => 'kb-borrowed',
            'icon' => '📦',
            'label' => 'กำลังยืม',
            'bgColor' => '#dbeafe',
            'iconColor' => '#2563eb',
        ],
        'return_pending' => [
            'class' => 'kb-return-pending',
            'icon' => '🔄',
            'label' => 'รอรับคืน',
            'bgColor' => '#ffedd5',
            'iconColor' => '#ea580c',
        ],
        'returned' => [
            'class' => 'kb-returned',
            'icon' => '✔️',
            'label' => 'คืนแล้ว',
            'bgColor' => '#f3f4f6',
            'iconColor' => '#374151',
        ],
        'rejected' => [
            'class' => 'kb-rejected',
            'icon' => '❌',
            'label' => 'ปฏิเสธ',
            'bgColor' => '#fee2e2',
            'iconColor' => '#dc2626',
        ],
    ];
@endphp

@push('styles')
<style>
/* =========================================
   Kit Requests — Premium Page Styles
   ========================================= */

/* Stat mini cards */
.kit-stat-row {
    display: flex; gap: 12px; flex-wrap: wrap;
    margin-bottom: 22px;
}
.kit-stat {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    padding: 14px 20px;
    display: flex; align-items: center; gap: 12px;
    flex: 1; min-width: 130px;
    box-shadow: 0 2px 8px rgba(0,0,0,.04);
    transition: transform .2s, box-shadow .2s;
    text-decoration: none; color: inherit;
}
.kit-stat:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,.09); color: inherit; }
.kit-stat.active { outline: 2.5px solid var(--c); }
.kit-stat-icon {
    width: 42px; height: 42px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem; flex-shrink: 0;
}
.kit-stat-val { font-size: 1.5rem; font-weight: 800; line-height: 1; }
.kit-stat-lbl { font-size: .75rem; color: #6b7280; margin-top: 2px; font-weight: 500; }

/* Filter bar */
.filter-bar-kit {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 14px 18px;
    margin-bottom: 18px;
    display: flex; gap: 10px; align-items: center; flex-wrap: wrap;
}
.filter-bar-kit select, .filter-bar-kit input {
    border-radius: 8px;
    border: 1.5px solid #d1d5db;
    font-size: .86rem;
    padding: 6px 12px;
    transition: border-color .15s, box-shadow .15s;
}
.filter-bar-kit select:focus, .filter-bar-kit input:focus {
    border-color: #7c3aed;
    box-shadow: 0 0 0 3px rgba(124,58,237,.12);
    outline: none;
}

/* Table */
.kit-table { font-size: .87rem; }
.kit-table thead th {
    background: #f8fafc;
    color: #374151;
    font-weight: 700;
    font-size: .8rem;
    text-transform: uppercase;
    letter-spacing: .4px;
    border-bottom: 2px solid #e5e7eb;
    padding: 12px 14px;
    white-space: nowrap;
}
.kit-table tbody tr { transition: background .1s; }
.kit-table tbody tr:hover { background: #faf5ff !important; }
.kit-table td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }

/* Status badge */
.kit-badge {
    font-size: .76rem; font-weight: 700;
    padding: 4px 12px; border-radius: 20px;
    white-space: nowrap; display: inline-flex; align-items: center; gap: 5px;
}
.kb-pending        { background: #fef9c3; color: #92400e; }
.kb-exec-approved  { background: #dcfce7; color: #166534; }
.kb-borrowed       { background: #dbeafe; color: #1e40af; }
.kb-return-pending { background: #ffedd5; color: #9a3412; }
.kb-returned       { background: #f3f4f6; color: #374151; }
.kb-rejected       { background: #fee2e2; color: #991b1b; }

/* Row highlight */
tr.row-exec-approved td { background: #f0fdf4 !important; }
tr.row-return-pending td { background: #fff7ed !important; }

/* Action buttons */
.kit-action-btn {
    border: none; border-radius: 8px;
    padding: 5px 12px; font-size: .78rem; font-weight: 600;
    display: inline-flex; align-items: center; gap: 4px;
    cursor: pointer; transition: all .15s;
    text-decoration: none;
}
.btn-view-kit  { background: #ede9fe; color: #7c3aed; }
.btn-view-kit:hover  { background: #ddd6fe; color: #6d28d9; }
.btn-approve-kit  { background: #dcfce7; color: #166534; }
.btn-approve-kit:hover  { background: #bbf7d0; color: #14532d; }
.btn-reject-kit  { background: #fee2e2; color: #dc2626; }
.btn-reject-kit:hover  { background: #fecaca; color: #b91c1c; }
.btn-return-kit  { background: #dbeafe; color: #1d4ed8; }
.btn-return-kit:hover  { background: #bfdbfe; color: #1e40af; }
.btn-print-kit  { background: #f3f4f6; color: #374151; }
.btn-print-kit:hover  { background: #e5e7eb; color: #111827; }

/* User avatar chip */
.user-chip {
    display: flex; align-items: center; gap: 8px;
}
.user-chip-avatar {
    width: 32px; height: 32px; border-radius: 50%;
    background: linear-gradient(135deg, #ede9fe, #ddd6fe);
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; color: #7c3aed; font-size: .85rem;
    flex-shrink: 0; overflow: hidden;
}

/* Kit name chip */
.kit-chip {
    display: inline-flex; align-items: center; gap: 5px;
    background: #eff6ff; color: #1d4ed8;
    border-radius: 8px; padding: 3px 10px;
    font-size: .8rem; font-weight: 600;
}

/* Date range */
.date-range { font-size: .82rem; color: #374151; }
.date-range .separator { color: #d1d5db; margin: 0 4px; }

/* Modal */
.modal-kit .modal-header {
    background: linear-gradient(135deg, #7c3aed, #a855f7);
    color: #fff;
    border-radius: 16px 16px 0 0;
}
.modal-kit .modal-header .btn-close { filter: invert(1); }
.modal-kit .info-block {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 14px 16px;
}
.modal-kit .info-block .info-label { font-size: .75rem; color: #6b7280; margin-bottom: 2px; }
.modal-kit .info-block .info-value { font-weight: 600; color: #1e293b; font-size: .9rem; }

/* Pagination */
.pag-wrap { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; padding: 14px 18px; }

/* Empty state */
.kit-empty { text-align: center; padding: 50px 20px; }
.kit-empty i { font-size: 3rem; opacity: .25; display: block; margin-bottom: 12px; }
.kit-empty p { color: #9ca3af; font-size: .9rem; }

/* Animate */
.animate-in { animation: fadeUp .35s ease both; }
@keyframes fadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:translateY(0); } }

/* Info section headers */
.info-section-header {
    font-weight: bold;
    color: #1e293b;
    border-bottom: 2px solid;
    padding-bottom: 8px;
    margin-bottom: 12px;
}

/* Modal footer */
.modal-kit .modal-footer {
    background: #f8fafc;
    border-top: 1px solid #e5e7eb;
}

/* Items table in modal */
.items-table { font-size: .85rem; }
.items-table thead { background: #f8fafc; }
.items-table th { padding: 8px 12px; color: #6b7280; font-weight: 600; }
.items-table td { padding: 8px 12px; border-bottom: 1px solid #f1f5f9; }
.items-table .row-index { color: #9ca3af; }
.items-table .item-quantity {
    background: #eff6ff;
    color: #1d4ed8;
    border-radius: 20px;
    padding: 3px 10px;
    font-weight: 700;
    font-size: .82rem;
    display: inline-block;
    text-align: center;
}
</style>
@endpush

@section('content')


{{-- ===== STAT MINI CARDS ===== --}}
@php
    $statusCounts = $requests->getCollection()->groupBy('status');
    $allCount     = $requests->total();
    $pendingCount = \App\Models\KitRequest::where('status','pending')->count();
    $execCount    = \App\Models\KitRequest::where('status','executive_approved')->count();
    $borrowedCount= \App\Models\KitRequest::where('status','borrowed')->count();
    $returnPCount = \App\Models\KitRequest::where('status','return_pending')->count();
    $returnedCount= \App\Models\KitRequest::where('status','returned')->count();
@endphp
<div class="kit-stat-row animate-in" style="animation-delay:.08s">
    <a href="{{ route('staff.requests.kit', request()->except('status')) }}" class="kit-stat {{ !request('status') ? 'active' : '' }}" style="--c:#7c3aed">
        <div class="kit-stat-icon" style="background:#f5f3ff;color:#7c3aed"><i class="bi bi-list-ul"></i></div>
        <div><div class="kit-stat-val" style="color:#7c3aed">{{ $allCount }}</div><div class="kit-stat-lbl">ทั้งหมด</div></div>
    </a>
    <a href="{{ route('staff.requests.kit', array_merge(request()->except('status'), ['status'=>'pending'])) }}" class="kit-stat {{ request('status')=='pending' ? 'active' : '' }}" style="--c:#d97706">
        <div class="kit-stat-icon" style="background:#fffbeb;color:#d97706"><i class="bi bi-hourglass-split"></i></div>
        <div><div class="kit-stat-val" style="color:#d97706">{{ $pendingCount }}</div><div class="kit-stat-lbl">รอดำเนินการ</div></div>
    </a>
    <a href="{{ route('staff.requests.kit', array_merge(request()->except('status'), ['status'=>'executive_approved'])) }}" class="kit-stat {{ request('status')=='executive_approved' ? 'active' : '' }}" style="--c:#16a34a">
        <div class="kit-stat-icon" style="background:#dcfce7;color:#16a34a"><i class="bi bi-patch-check-fill"></i></div>
        <div><div class="kit-stat-val" style="color:#16a34a">{{ $execCount }}</div><div class="kit-stat-lbl">อนุมัติแล้ว</div></div>
    </a>
    <a href="{{ route('staff.requests.kit', array_merge(request()->except('status'), ['status'=>'borrowed'])) }}" class="kit-stat {{ request('status')=='borrowed' ? 'active' : '' }}" style="--c:#2563eb">
        <div class="kit-stat-icon" style="background:#dbeafe;color:#2563eb"><i class="bi bi-bag-check-fill"></i></div>
        <div><div class="kit-stat-val" style="color:#2563eb">{{ $borrowedCount }}</div><div class="kit-stat-lbl">กำลังยืม</div></div>
    </a>
    <a href="{{ route('staff.requests.kit', array_merge(request()->except('status'), ['status'=>'return_pending'])) }}" class="kit-stat {{ request('status')=='return_pending' ? 'active' : '' }}" style="--c:#ea580c">
        <div class="kit-stat-icon" style="background:#ffedd5;color:#ea580c"><i class="bi bi-arrow-return-left"></i></div>
        <div><div class="kit-stat-val" style="color:#ea580c">{{ $returnPCount }}</div><div class="kit-stat-lbl">รอรับคืน</div></div>
    </a>
    <a href="{{ route('staff.requests.kit', array_merge(request()->except('status'), ['status'=>'returned'])) }}" class="kit-stat {{ request('status')=='returned' ? 'active' : '' }}" style="--c:#374151">
        <div class="kit-stat-icon" style="background:#f3f4f6;color:#374151"><i class="bi bi-check2-all"></i></div>
        <div><div class="kit-stat-val" style="color:#374151">{{ $returnedCount }}</div><div class="kit-stat-lbl">คืนแล้ว</div></div>
    </a>
</div>

{{-- ===== PANEL ===== --}}
<div class="panel animate-in" style="animation-delay:.15s">
    <div class="panel-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <h5 class="mb-0"><i class="bi bi-table me-2 text-primary"></i>รายการคำร้องทั้งหมด</h5>
        {{-- Filter --}}
        <form method="GET" action="{{ route('staff.requests.kit') }}" class="filter-bar-kit" style="margin:0;padding:8px 14px;">
            <label class="text-muted small fw-semibold mb-0">สถานะ:</label>
            <select name="status" onchange="this.form.submit()" style="min-width:160px;">
                <option value="">ทุกสถานะ</option>
                <option value="pending"             {{ request('status')=='pending'             ? 'selected' : '' }}>รอดำเนินการ</option>
                <option value="executive_approved"  {{ request('status')=='executive_approved'  ? 'selected' : '' }}>อนุมัติโดยผู้บริหาร</option>
                <option value="borrowed"            {{ request('status')=='borrowed'            ? 'selected' : '' }}>กำลังยืม</option>
                <option value="return_pending"      {{ request('status')=='return_pending'      ? 'selected' : '' }}>รอรับคืน</option>
                <option value="returned"            {{ request('status')=='returned'            ? 'selected' : '' }}>คืนแล้ว</option>
                <option value="rejected"            {{ request('status')=='rejected'            ? 'selected' : '' }}>ปฏิเสธ</option>
            </select>
            @if(request('status'))
            <a href="{{ route('staff.requests.kit') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;font-size:.8rem;">
                <i class="bi bi-x-lg"></i>
            </a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table kit-table mb-0">
            <thead>
                <tr>
                    <th style="width:40px">#</th>
                    <th>เลขที่คำร้อง</th>
                    <th>ผู้ขอยืม</th>
                    <th>กระเป๋า</th>
                    <th>วัตถุประสงค์</th>
                    <th>วันยืม</th>
                    <th>กำหนดคืน</th>
                    <th>สถานะ</th>
                    <th>วันที่ยื่น</th>
                    <th class="text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody>
            @forelse($requests as $idx => $req)
                @php
                    $statusInfo = $statusConfig[$req->status] ?? $statusConfig['pending'];
                    $rowClass = match($req->status) {
                        'executive_approved' => 'row-exec-approved',
                        'return_pending'     => 'row-return-pending',
                        default => '',
                    };
                @endphp
                <tr class="{{ $rowClass }}">
                    <td class="text-muted">{{ $requests->firstItem() + $loop->index }}</td>
                    <td>
                        <code class="text-primary fw-bold" style="font-size:.82rem;">{{ $req->request_number }}</code>
                    </td>
                    <td>
                        <div class="user-chip">
                            <div class="user-chip-avatar">
                                @if($req->user->profile_photo_path)
                                <img src="{{ \Storage::url($req->user->profile_photo_path) }}" style="width:100%;height:100%;object-fit:cover;">
                                @else
                                {{ mb_substr($req->user->name, 0, 1) }}
                                @endif
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:.85rem;">{{ $req->user->name }}</div>
                                <div style="font-size:.74rem;color:#9ca3af;">{{ $req->user->student_id ?? $req->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="kit-chip">
                            <i class="bi bi-briefcase"></i>
                            {{ $req->kit->name ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <span style="color:#374151;font-size:.83rem;" title="{{ $req->purpose }}">
                            {{ Str::limit($req->purpose, 28) }}
                        </span>
                    </td>
                    <td>
                        <div class="date-range">
                            <i class="bi bi-calendar-event me-1 text-muted"></i>
                            {{ $req->borrow_date ? $req->borrow_date->format('d/m/Y') : '-' }}
                        </div>
                    </td>
                    <td>
                        <div class="date-range">
                            @if($req->expected_return_date)
                                @php $overdue = $req->expected_return_date->isPast() && !in_array($req->status, ['returned','rejected']); @endphp
                                <span style="{{ $overdue ? 'color:#dc2626;font-weight:700;' : '' }}">
                                    <i class="bi bi-calendar-check me-1 {{ $overdue ? 'text-danger' : 'text-muted' }}"></i>
                                    {{ $req->expected_return_date->format('d/m/Y') }}
                                    @if($overdue) <span class="badge bg-danger ms-1" style="font-size:.65rem;">เกินกำหนด</span>@endif
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="kit-badge {{ $statusInfo['class'] }}">
                            {{ $statusInfo['icon'] }} {{ $req->status_label }}
                        </span>
                    </td>
                    <td style="font-size:.78rem;color:#6b7280;white-space:nowrap;">
                        {{ $req->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 align-items-center justify-content-center flex-nowrap">
                            {{-- ดูรายละเอียด --}}
                            <button type="button" class="kit-action-btn btn-view-kit"
                                data-bs-toggle="modal" data-bs-target="#viewModal{{ $req->id }}"
                                title="ดูรายละเอียด">
                                <i class="bi bi-eye"></i> ดู
                            </button>

                            {{-- อนุมัติ/ปฏิเสธ (executive_approved) --}}
                            @if($req->status === 'executive_approved')
                            <form action="{{ route('staff.requests.kit.approve', $req) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('ยืนยันอนุมัติและจ่ายกระเป๋า {{ $req->kit->name ?? '' }}?')">
                                @csrf
                                <button type="submit" class="kit-action-btn btn-approve-kit">
                                    <i class="bi bi-check-lg"></i> จ่าย
                                </button>
                            </form>
                            <form action="{{ route('staff.requests.kit.reject', $req) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('ยืนยันปฏิเสธคำร้องนี้?')">
                                @csrf
                                <button type="submit" class="kit-action-btn btn-reject-kit">
                                    <i class="bi bi-x-lg"></i> ปฏิเสธ
                                </button>
                            </form>
                            @endif

                            {{-- รับคืน (return_pending) --}}
                            @if($req->status === 'return_pending')
                            <form action="{{ route('staff.requests.kit.confirm-return', $req) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('ยืนยันรับคืนกระเป๋า {{ $req->kit->name ?? '' }}?')">
                                @csrf
                                <button type="submit" class="kit-action-btn btn-return-kit">
                                    <i class="bi bi-arrow-return-left"></i> รับคืน
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">
                        <div class="kit-empty">
                            <i class="bi bi-inbox"></i>
                            <p>ไม่พบคำร้องที่ตรงกับเงื่อนไข</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    </div>

    {{-- Pagination --}}
    <div class="pag-wrap">
        <div class="text-muted small">
            แสดง <strong>{{ $requests->firstItem() ?? 0 }}</strong>–<strong>{{ $requests->lastItem() ?? 0 }}</strong>
            จากทั้งหมด <strong>{{ $requests->total() }}</strong> รายการ
        </div>
        <div>{{ $requests->withQueryString()->links() }}</div>
    </div>
</div>

{{-- ===== MODALS (outside table) ===== --}}
@foreach($requests as $req)
<div class="modal fade text-start modal-kit" id="viewModal{{ $req->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px;overflow:hidden;">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-bag-heart me-2"></i>
                    รายละเอียดคำร้อง — <code style="color:#e9d5ff;font-size:.9em;">{{ $req->request_number }}</code>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">

                {{-- Status row --}}
                @php $statusInfo = $statusConfig[$req->status] ?? $statusConfig['pending']; @endphp
                <div class="d-flex align-items-center gap-3 mb-4 p-3" style="background:#f8fafc;border-radius:12px;border:1px solid #e5e7eb;">
                    <div>
                        <div style="font-size:.75rem;color:#6b7280;">สถานะคำร้อง</div>
                        <span class="kit-badge {{ $statusInfo['class'] }}" style="font-size:.85rem;margin-top:4px;">
                            {{ $statusInfo['icon'] }} {{ $req->status_label }}
                        </span>
                    </div>
                    <div class="vr mx-2"></div>
                    <div>
                        <div style="font-size:.75rem;color:#6b7280;">วันที่ยื่น</div>
                        <div style="font-weight:600;font-size:.88rem;">{{ $req->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    @if($req->approver)
                    <div class="vr mx-2"></div>
                    <div>
                        <div style="font-size:.75rem;color:#6b7280;">ผู้อนุมัติ</div>
                        <div style="font-weight:600;font-size:.88rem;">{{ $req->approver->name }}</div>
                    </div>
                    @endif
                </div>

                <div class="row g-3 mb-3">
                    {{-- ข้อมูลผู้ขอยืม --}}
                    <div class="col-md-6">
                        <h6 class="info-section-header" style="border-color:#ede9fe;color:#7c3aed;">
                            <i class="bi bi-person-fill me-2"></i>ข้อมูลผู้ขอยืม
                        </h6>
                        <div class="d-flex flex-column gap-2">
                            <div class="info-block">
                                <div class="info-label">ชื่อ-นามสกุล</div>
                                <div class="info-value">{{ $req->user->name }}</div>
                            </div>
                            <div class="info-block">
                                <div class="info-label">รหัส / ID</div>
                                <div class="info-value">{{ $req->user->student_id ?? '-' }}</div>
                            </div>
                            <div class="info-block">
                                <div class="info-label">สังกัด/สาขา</div>
                                <div class="info-value">{{ $req->user->department ?? '-' }}</div>
                            </div>
                            <div class="info-block">
                                <div class="info-label">อีเมล</div>
                                <div class="info-value">{{ $req->user->email }}</div>
                            </div>
                            @if($req->user->phone)
                            <div class="info-block">
                                <div class="info-label">เบอร์โทรศัพท์</div>
                                <div class="info-value">{{ $req->user->phone }}</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- รายละเอียดการยืม --}}
                    <div class="col-md-6">
                        <h6 class="info-section-header" style="border-color:#dbeafe;color:#2563eb;">
                            <i class="bi bi-calendar-event me-2"></i>รายละเอียดการยืม
                        </h6>
                        <div class="d-flex flex-column gap-2">
                            <div class="info-block">
                                <div class="info-label">กระเป๋าที่ยืม</div>
                                <div class="info-value">
                                    <span class="kit-chip">
                                        <i class="bi bi-briefcase"></i>
                                        {{ $req->kit->name ?? '-' }}
                                    </span>
                                    @if(isset($req->kit->kit_code))
                                    <span class="text-muted ms-1" style="font-size:.8rem;">({{ $req->kit->kit_code }})</span>
                                    @endif
                                </div>
                            </div>
                            <div class="info-block">
                                <div class="info-label">วันที่ยืม</div>
                                <div class="info-value">{{ $req->borrow_date ? $req->borrow_date->format('d/m/Y') : '-' }}</div>
                            </div>
                            <div class="info-block">
                                <div class="info-label">กำหนดคืน</div>
                                <div class="info-value">{{ $req->expected_return_date ? $req->expected_return_date->format('d/m/Y') : '-' }}</div>
                            </div>
                            <div class="info-block">
                                <div class="info-label">วันที่คืนจริง</div>
                                <div class="info-value">{{ $req->actual_return_date ? $req->actual_return_date->format('d/m/Y') : '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- วัตถุประสงค์ --}}
                <div class="mb-3">
                    <h6 class="info-section-header" style="border-color:#dcfce7;color:#16a34a;">
                        <i class="bi bi-card-text me-2"></i>วัตถุประสงค์การยืม
                    </h6>
                    <div style="background:#f8fafc;border:1px solid #e5e7eb;border-radius:10px;padding:14px;color:#374151;font-size:.9rem;line-height:1.6;">
                        {{ $req->purpose ?? 'ไม่ได้ระบุ' }}
                    </div>
                </div>

                {{-- ไฟล์แนบ --}}
                @if($req->document_path)
                <div class="mb-3">
                    <h6 class="info-section-header" style="border-color:#e0e7ff;color:#6366f1;">
                        <i class="bi bi-paperclip me-2"></i>ไฟล์หนังสือขอเบิก
                    </h6>
                    <div style="background:#f8fafc;border:1px solid #e5e7eb;border-radius:10px;padding:14px;">
                        @php
                            $ext = strtolower(pathinfo($req->document_path, PATHINFO_EXTENSION));
                            $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                        @endphp
                        @if($isImage)
                        <div class="text-center mb-3">
                            <a href="{{ asset($req->document_path) }}" target="_blank">
                                <img src="{{ asset($req->document_path) }}" class="img-fluid rounded shadow-sm" style="max-height:280px;object-fit:contain;">
                            </a>
                        </div>
                        @endif
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-file-earmark-check text-success fs-5"></i>
                                <span class="fw-semibold" style="font-size:.85rem;">{{ basename($req->document_path) }}</span>
                            </div>
                            <a href="{{ asset($req->document_path) }}" target="_blank" class="btn btn-sm btn-primary" style="border-radius:8px;font-size:.8rem;">
                                <i class="bi bi-box-arrow-up-right me-1"></i>เปิดไฟล์
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                {{-- รายการอุปกรณ์ภายในกระเป๋า --}}
                @if($req->kit && $req->kit->items && $req->kit->items->count() > 0)
                <div>
                    <h6 class="info-section-header" style="border-color:#fef9c3;color:#d97706;">
                        <i class="bi bi-box-seam me-2"></i>รายการอุปกรณ์/เวชภัณฑ์ในกระเป๋า
                        <span class="badge ms-1" style="background:#fef9c3;color:#92400e;font-size:.75rem;">{{ $req->kit->items->count() }} รายการ</span>
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0 items-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ชื่อเวชภัณฑ์</th>
                                    <th style="text-align:center;">จำนวน</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($req->kit->items as $idx2 => $item)
                                <tr>
                                    <td class="row-index">{{ $idx2+1 }}</td>
                                    <td style="font-weight:600;">
                                        <i class="bi bi-capsule me-1 text-primary" style="font-size:.85rem;"></i>
                                        {{ $item->supply->name ?? '-' }}
                                    </td>
                                    <td style="text-align:center;">
                                        <span class="item-quantity">
                                            {{ $item->quantity }} {{ $item->supply->unit ?? 'ชิ้น' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

            </div>
            <div class="modal-kit modal-footer">
                {{-- Action buttons inside modal footer --}}
                @if($req->status === 'executive_approved')
                <form action="{{ route('staff.requests.kit.approve', $req) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('ยืนยันอนุมัติและจ่ายกระเป๋า?')">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm" style="border-radius:8px;">
                        <i class="bi bi-check-lg me-1"></i>อนุมัติ / จ่ายกระเป๋า
                    </button>
                </form>
                <form action="{{ route('staff.requests.kit.reject', $req) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('ยืนยันปฏิเสธคำร้องนี้?')">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm" style="border-radius:8px;">
                        <i class="bi bi-x-lg me-1"></i>ปฏิเสธ
                    </button>
                </form>
                @elseif($req->status === 'return_pending')
                <form action="{{ route('staff.requests.kit.confirm-return', $req) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('ยืนยันรับคืนกระเป๋า?')">
                    @csrf
                    <button type="submit" class="btn btn-info btn-sm text-white" style="border-radius:8px;">
                        <i class="bi bi-arrow-return-left me-1"></i>ยืนยันรับคืน
                    </button>
                </form>
                @endif

                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="border-radius:8px;">ปิด</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('scripts')
<script>
// Highlight rows ที่ต้องการจัดการด่วน
document.addEventListener('DOMContentLoaded', function () {
    // Auto-open modal if hash matches
    const hash = window.location.hash;
    if (hash && hash.startsWith('#viewModal')) {
        const modal = document.querySelector(hash);
        if (modal) new bootstrap.Modal(modal).show();
    }
});
</script>
@endpush