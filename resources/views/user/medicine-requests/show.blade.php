@extends('layouts.app')
@section('title', 'รายละเอียดคำร้อง')
@section('page-title', 'รายละเอียดคำร้อง #' . $medicineRequest->request_number)
@section('sidebar') @include('partials.sidebar-user') @endsection

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="panel mb-3">
            <div class="panel-header"><h5><i class="bi bi-info-circle me-2"></i>ข้อมูลคำร้อง</h5></div>
            <div class="panel-body">
                <div class="row mb-3">
                    <div class="col-md-6"><strong>เลขที่คำร้อง:</strong> {{ $medicineRequest->request_number }}</div>
                    <div class="col-md-6"><strong>สถานะ:</strong> <span class="badge bg-{{ $medicineRequest->status_color }} badge-status">{{ $medicineRequest->status_label }}</span></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6"><strong>วันที่ยื่น:</strong> {{ $medicineRequest->created_at->format('d/m/Y H:i') }}</div>
                    <div class="col-md-6"><strong>อนุมัติโดย:</strong> {{ $medicineRequest->approver->name ?? '-' }}</div>
                </div>
                <div class="mb-3">
                    <strong>อาการ:</strong>
                    <p class="mt-1 p-3 bg-light rounded">{{ $medicineRequest->symptoms }}</p>
                </div>
                @if($medicineRequest->staff_notes)
                <div><strong>หมายเหตุเจ้าหน้าที่:</strong>
                    <p class="mt-1 p-3 bg-light rounded">{{ $medicineRequest->staff_notes }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel">
            <div class="panel-header"><h5><i class="bi bi-capsule me-2"></i>รายการยา</h5></div>
            <div class="panel-body p-0">
                @foreach($medicineRequest->items as $item)
                <div class="p-3 border-bottom">
                    <strong>{{ $item->supply->name }}</strong>
                    <div class="d-flex justify-content-between mt-1 small text-muted">
                        <span>ขอ: {{ $item->quantity_requested }} {{ $item->supply->unit }}</span>
                        <span>อนุมัติ: {{ $item->quantity_approved }} {{ $item->supply->unit }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<a href="{{ route('user.medicine-requests.index') }}" class="btn btn-outline-secondary mt-3"><i class="bi bi-arrow-left me-1"></i>กลับ</a>
@endsection
