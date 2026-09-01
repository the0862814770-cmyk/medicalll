@extends('layouts.app')
@section('title', 'รายละเอียดคำร้อง')
@section('page-title', 'คำร้อง #' . $kitRequest->request_number)
@section('sidebar') @include('partials.sidebar-executive') @endsection

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="panel mb-3">
            <div class="panel-header"><h5><i class="bi bi-info-circle me-2"></i>ข้อมูลคำร้อง</h5></div>
            <div class="panel-body">
                <div class="row mb-2">
                    <div class="col-md-6"><strong>ผู้ขอ:</strong> {{ $kitRequest->user->name }}</div>
                    <div class="col-md-6"><strong>สถานะ:</strong> <span class="badge bg-{{ $kitRequest->status_color }} badge-status">{{ $kitRequest->status_label }}</span></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6"><strong>วันที่ส่งคำร้อง:</strong> {{ $kitRequest->created_at->format('d/m/Y H:i') }}</div>
                    <div class="col-md-6"><strong>วันที่ยืม:</strong> {{ $kitRequest->borrow_date->format('d/m/Y') }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6"><strong>กำหนดคืน:</strong> {{ $kitRequest->expected_return_date->format('d/m/Y') }}</div>
                    <div class="col-md-6"><strong>ผู้อนุมัติ:</strong> {{ $kitRequest->approver->name ?? '-' }}</div>
                </div>
                <div class="mt-3"><strong>วัตถุประสงค์:</strong><p class="p-3 bg-light rounded mt-1">{{ $kitRequest->purpose }}</p></div>

                @if($kitRequest->document_path)
                <div class="mb-4">
                    <h6 class="fw-bold border-bottom pb-2 mb-2 text-primary"><i class="bi bi-paperclip me-2"></i>ไฟล์แนบจากผู้ขอ</h6>
                    <div class="p-3 bg-light rounded-3 border">
                        @php
                            $ext = strtolower(pathinfo($kitRequest->document_path, PATHINFO_EXTENSION));
                            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                        @endphp

                        @if($isImage)
                        <div class="text-center mb-3">
                            <a href="{{ asset($kitRequest->document_path) }}" target="_blank" title="คลิกเพื่อดูภาพขยาย">
                                <img src="{{ asset($kitRequest->document_path) }}" alt="ไฟล์แนบ" class="img-fluid rounded border shadow-sm" style="max-height: 320px; object-fit: contain;">
                            </a>
                        </div>
                        @endif

                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <div>
                                <i class="bi bi-file-earmark-check text-success fs-5 me-2"></i>
                                <span class="fw-semibold text-dark">{{ basename($kitRequest->document_path) }}</span>
                            </div>
                            <a href="{{ asset($kitRequest->document_path) }}" target="_blank" class="btn btn-sm btn-primary px-3">
                                <i class="bi bi-box-arrow-up-right me-1"></i>เปิดดูไฟล์แนบ
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        @if($kitRequest->status === 'pending')
        <div class="panel">
            <div class="panel-header"><h5><i class="bi bi-check-circle me-2"></i>อนุมัติ/ปฏิเสธ</h5></div>
            <div class="panel-body">
                <form action="{{ route('executive.requests.kit.approve', $kitRequest) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-success"><i class="bi bi-check2-circle me-1"></i>อนุมัติ</button>
                </form>
                <form action="{{ route('executive.requests.kit.reject', $kitRequest) }}" method="POST" class="d-inline ms-2">
                    @csrf
                    <button class="btn btn-danger"><i class="bi bi-x-circle me-1"></i>ปฏิเสธ</button>
                </form>
            </div>
        </div>
        @endif
    </div>
    <div class="col-lg-4">
        <div class="panel">
            <div class="panel-header"><h5><i class="bi bi-box-seam me-2"></i>รายการยาที่มีในกระเป๋า</h5></div>
            <div class="panel-body p-0">
                @foreach($kitRequest->kit->items as $item)
                    <div class="p-3 border-bottom">
                        <strong>{{ $item->supply->name ?? '-' }}</strong>
                        <div class="mt-1 small text-muted">{{ $item->quantity }} {{ $item->supply->unit ?? 'ชิ้น' }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<a href="{{ route('executive.requests.kit') }}" class="btn btn-outline-secondary mt-3"><i class="bi bi-arrow-left me-1"></i>กลับ</a>
@endsection
