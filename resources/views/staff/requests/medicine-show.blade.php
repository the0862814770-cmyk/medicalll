@extends('layouts.app')
@section('title', 'รายละเอียดคำร้อง')
@section('page-title', 'คำร้อง #' . $medicineRequest->request_number)
@section('sidebar') @include('partials.sidebar-staff') @endsection

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="panel mb-3">
            <div class="panel-header"><h5><i class="bi bi-info-circle me-2"></i>ข้อมูลคำร้อง</h5></div>
            <div class="panel-body">
                <div class="row mb-2">
                    <div class="col-md-6"><strong>ผู้ขอ:</strong> {{ $medicineRequest->user->name }}</div>
                    <div class="col-md-6"><strong>รหัสนักศึกษา:</strong> {{ $medicineRequest->user->student_id ?? '-' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6"><strong>เบอร์โทร:</strong> {{ $medicineRequest->user->phone ?? '-' }}</div>
                    <div class="col-md-6"><strong>สถานะ:</strong> <span class="badge bg-{{ $medicineRequest->status_color }} badge-status">{{ $medicineRequest->status_label }}</span></div>
                </div>
                <div class="mt-3"><strong>อาการ:</strong><p class="p-3 bg-light rounded mt-1">{{ $medicineRequest->symptoms }}</p></div>
            </div>
        </div>

        @if(in_array($medicineRequest->status, ['pending', 'executive_approved']))
        <div class="panel">
            <div class="panel-header"><h5><i class="bi bi-check-circle me-2"></i>อนุมัติ/ปฏิเสธ</h5></div>
            <div class="panel-body">
                <div class="mb-3 text-muted">คำร้องนี้กำลังรอการดำเนินการโดยเจ้าหน้าที่พยาบาล กรุณาตรวจสอบและตัดสินใจอนุมัติหรือปฏิเสธคำร้อง</div>
                <form action="{{ route('staff.requests.medicine.approve', $medicineRequest) }}" method="POST">
                    @csrf
                    @foreach($medicineRequest->items as $item)
                    <div class="d-flex align-items-center justify-content-between p-2 border rounded mb-2">
                        <div>
                            <strong>{{ $item->supply->name }}</strong><br>
                            <small>ขอ: {{ $item->quantity_requested }} {{ $item->supply->unit }} | คงเหลือ: {{ $item->supply->total_stock }} {{ $item->supply->unit }}</small>
                        </div>
                        <div style="width:120px">
                            <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $item->id }}">
                            <input type="number" name="items[{{ $loop->index }}][quantity_approved]" class="form-control form-control-sm" value="{{ $item->quantity_requested }}" min="0" max="{{ $item->supply->total_stock }}">
                        </div>
                    </div>
                    @endforeach
                    <div class="mt-3"><label class="form-label">หมายเหตุ</label><textarea name="staff_notes" class="form-control" rows="2"></textarea></div>
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i>อนุมัติ</button>
                        <button type="button" class="btn btn-danger" onclick="document.getElementById('rejectForm').submit()"><i class="bi bi-x-lg me-1"></i>ปฏิเสธ</button>
                    </div>
                </form>
                <form id="rejectForm" action="{{ route('staff.requests.medicine.reject', $medicineRequest) }}" method="POST" style="display:none">@csrf</form>
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
                    <div class="mt-1 small">
                        <span class="text-muted">ขอ: {{ $item->quantity_requested }} {{ $item->supply->unit }}</span> |
                        <span class="text-success">อนุมัติ: {{ $item->quantity_approved }} {{ $item->supply->unit }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<a href="{{ route('staff.requests.medicine') }}" class="btn btn-outline-secondary mt-3"><i class="bi bi-arrow-left me-1"></i>กลับ</a>
@endsection
