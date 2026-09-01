@extends('layouts.app')
@section('title', 'รายละเอียดคำร้อง')
@section('page-title', 'คำร้อง #' . $medicineRequest->request_number)
@section('sidebar') @include('partials.sidebar-executive') @endsection

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="panel mb-3">
            <div class="panel-header"><h5><i class="bi bi-info-circle me-2"></i>ข้อมูลคำร้อง</h5></div>
            <div class="panel-body">
                <div class="row mb-2">
                    <div class="col-md-6"><strong>ผู้ขอ:</strong> {{ $medicineRequest->user->name }}</div>
                    <div class="col-md-6"><strong>สถานะ:</strong> <span class="badge bg-{{ $medicineRequest->status_color }} badge-status">{{ $medicineRequest->status_label }}</span></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6"><strong>รหัสผู้ใช้:</strong> {{ $medicineRequest->user->student_id ?? '-' }}</div>
                    <div class="col-md-6"><strong>วันที่ส่งคำร้อง:</strong> {{ $medicineRequest->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <div class="mt-3"><strong>อาการ:</strong><p class="p-3 bg-light rounded mt-1">{{ $medicineRequest->symptoms }}</p></div>
            </div>
        </div>

        @if($medicineRequest->status === 'pending')
        <div class="panel">
            <div class="panel-header"><h5><i class="bi bi-info-circle me-2"></i>สถานะคำร้อง</h5></div>
            <div class="panel-body">
                <div class="mb-3 text-muted">คำร้องนี้อยู่ในสถานะรอดำเนินการ เจ้าหน้าที่พยาบาลจะเป็นผู้ดำเนินการต่อ</div>
            </div>
        </div>
        @endif
    </div>
    <div class="col-lg-4">
        <div class="panel">
            <div class="panel-header"><h5><i class="bi bi-capsule me-2"></i>รายการยา</h5></div>
            <div class="panel-body p-0">
                @foreach($medicineRequest->items as $item)
                    <div class="p-3 border-bottom">
                        <strong>{{ $item->supply->name }}</strong>
                        <div class="mt-1 small text-muted">ขอ {{ $item->quantity_requested }} {{ $item->supply->unit }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<a href="{{ route('executive.requests.medicine') }}" class="btn btn-outline-secondary mt-3"><i class="bi bi-arrow-left me-1"></i>กลับ</a>
@endsection
