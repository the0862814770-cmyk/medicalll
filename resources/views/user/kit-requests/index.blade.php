@extends('layouts.app')
@section('title', 'คำร้องยืมกระเป๋าของฉัน')
@section('page-title', 'คำร้องยืมกระเป๋าปฐมพยาบาล')
@section('sidebar') @include('partials.sidebar-user') @endsection

@section('content')
<div class="panel">
    <div class="panel-header">
        <h5><i class="bi bi-bag-check me-2"></i>ประวัติการยืมกระเป๋า</h5>
        <a href="{{ route('user.kit-requests.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus me-1"></i>ยื่นคำร้องใหม่</a>
    </div>
    <div class="panel-body p-0">
        @if($requests->isEmpty())
            <div class="empty-state"><i class="bi bi-inbox d-block"></i>ยังไม่มีการยืม</div>
        @else
        <table class="table table-modern">
            <thead><tr><th>เลขที่</th><th>กระเป๋า</th><th>วัตถุประสงค์</th><th>วันยืม</th><th>วันคืน</th><th>สถานะ</th><th>ไฟล์แนบ</th><th>จัดการ</th></tr></thead>
            <tbody>
            @foreach($requests as $req)
                <tr>
                    <td><strong>{{ $req->request_number }}</strong></td>
                    <td>{{ $req->kit->name ?? '-' }}</td>
                    <td>{{ Str::limit($req->purpose, 30) }}</td>
                    <td>{{ $req->borrow_date? $req->borrow_date->format('d/m/Y') : '-' }}</td>
                    <td>{{ $req->expected_return_date? $req->expected_return_date->format('d/m/Y') : '-' }}</td>
                    <td><span class="badge bg-{{ $req->status_color }} badge-status">{{ $req->status_label }}</span></td>
                    <td>
                        @if($req->document_path)
                            <div class="text-truncate">
                                <i class="bi bi-paperclip text-secondary me-1"></i>
                                <a href="{{ asset($req->document_path) }}" target="_blank" class="text-decoration-none text-dark">
                                    {{ basename($req->document_path) }}
                                </a>
                            </div>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1 flex-nowrap align-items-center">
                            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#userViewModal{{ $req->id }}" title="ดูรายละเอียด">
                                <i class="bi bi-eye"></i>
                            </button>
                            @if($req->status === 'pending')
                                <a href="{{ route('user.kit-requests.edit', $req) }}" class="btn btn-sm btn-outline-primary" title="แก้ไข">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('user.kit-requests.destroy', $req) }}" method="POST" class="d-inline" onsubmit="return confirm('ยืนยันการลบคำร้องนี้?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="ลบ">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @elseif($req->status === 'borrowed')
                                <form action="{{ route('user.kit-requests.return', $req) }}" method="POST" class="d-inline" onsubmit="return confirm('ยืนยันแจ้งส่งคืนกระเป๋า?')">
                                    @csrf
                                    <button class="btn btn-sm btn-warning">
                                        <i class="bi bi-arrow-return-left me-1"></i>แจ้งคืน
                                    </button>
                                </form>
                            @endif
                        </div>

                        <!-- Modal ดูรายละเอียดสำหรับผู้ใช้งาน -->
                        <div class="modal fade text-start" id="userViewModal{{ $req->id }}" tabindex="-1" aria-labelledby="userViewModalLabel{{ $req->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-light">
                                        <h5 class="modal-title" id="userViewModalLabel{{ $req->id }}">
                                            <i class="bi bi-bag-check text-primary me-2"></i>รายละเอียดคำร้องยืมกระเป๋า
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="row g-3 mb-4">
                                            <div class="col-md-6">
                                                <div class="p-3 bg-light rounded-3 border">
                                                    <span class="text-muted small d-block">เลขที่คำร้อง</span>
                                                    <strong class="fs-6 text-primary">{{ $req->request_number }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="p-3 bg-light rounded-3 border">
                                                    <span class="text-muted small d-block">สถานะคำร้อง</span>
                                                    <span class="badge bg-{{ $req->status_color }} badge-status fs-6">{{ $req->status_label }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-3 mb-4">
                                            <div class="col-md-6">
                                                <h6 class="fw-bold border-bottom pb-2 mb-3"><i class="bi bi-bag-heart me-2"></i>ข้อมูลกระเป๋า</h6>
                                                <table class="table table-borderless table-sm mb-0">
                                                    <tr><th class="text-muted" style="width: 120px;">รายการ:</th><td><strong>{{ $req->kit->name ?? '-' }}</strong></td></tr>
                                                    <tr><th class="text-muted">รหัสกระเป๋า:</th><td>{{ $req->kit->kit_code ?? '-' }}</td></tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="fw-bold border-bottom pb-2 mb-3"><i class="bi bi-calendar-event me-2"></i>ระยะเวลาการยืม</h6>
                                                <table class="table table-borderless table-sm mb-0">
                                                    <tr><th class="text-muted" style="width: 120px;">วันที่ยืม:</th><td>{{ $req->borrow_date ? $req->borrow_date->format('d/m/Y') : '-' }}</td></tr>
                                                    <tr><th class="text-muted">กำหนดคืน:</th><td>{{ $req->expected_return_date ? $req->expected_return_date->format('d/m/Y') : '-' }}</td></tr>
                                                    <tr><th class="text-muted">วันที่คืนจริง:</th><td>{{ $req->actual_return_date ? $req->actual_return_date->format('d/m/Y') : '-' }}</td></tr>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <h6 class="fw-bold border-bottom pb-2 mb-2"><i class="bi bi-card-text me-2"></i>วัตถุประสงค์</h6>
                                            <div class="p-3 bg-light rounded-3 text-secondary border">
                                                {{ $req->purpose ?? 'ไม่ได้ระบุ' }}
                                            </div>
                                        </div>

                                        <!-- ไฟล์แนบหนังสือขอเบิกที่แนบมา -->
                                        @if($req->document_path)
                                        <div class="mb-4">
                                            <h6 class="fw-bold border-bottom pb-2 mb-2 text-primary"><i class="bi bi-paperclip me-2"></i>ไฟล์หนังสือขอเบิกที่แนบไว้</h6>
                                            <div class="p-3 bg-light rounded-3 border">
                                                @php
                                                    $ext = strtolower(pathinfo($req->document_path, PATHINFO_EXTENSION));
                                                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                                @endphp

                                                @if($isImage)
                                                    <div class="text-center mb-2">
                                                        <a href="{{ asset($req->document_path) }}" target="_blank" title="คลิกเพื่อดูภาพขยาย">
                                                            <img src="{{ asset($req->document_path) }}" alt="หนังสือขอเบิกแนบ" class="img-fluid rounded border shadow-sm" style="max-height: 350px; object-fit: contain;">
                                                        </a>
                                                    </div>
                                                @endif

                                                <div class="d-flex align-items-center justify-content-between pt-1">
                                                    <div>
                                                        <i class="bi bi-file-earmark-check text-success fs-5 me-2"></i>
                                                        <span class="fw-semibold text-dark">{{ basename($req->document_path) }}</span>
                                                    </div>
                                                    <a href="{{ asset($req->document_path) }}" target="_blank" class="btn btn-sm btn-primary px-3">
                                                        <i class="bi bi-box-arrow-up-right me-1"></i> เปิดดูไฟล์แนบ
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        @if($req->kit && $req->kit->items && $req->kit->items->count() > 0)
                                        <div>
                                            <h6 class="fw-bold border-bottom pb-2 mb-2"><i class="bi bi-box-seam me-2"></i>รายการอุปกรณ์/เวชภัณฑ์ในกระเป๋า</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th style="width: 50px;">#</th>
                                                            <th>รายการ</th>
                                                            <th class="text-center" style="width: 120px;">จำนวน</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($req->kit->items as $idx => $item)
                                                        <tr>
                                                            <td>{{ $idx + 1 }}</td>
                                                            <td>{{ $item->supply->name ?? '-' }}</td>
                                                            <td class="text-center">{{ $item->quantity }} {{ $item->supply->unit ?? 'ชิ้น' }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer bg-light">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="p-3">{{ $requests->links() }}</div>
        @endif
    </div>
</div>
@endsection
