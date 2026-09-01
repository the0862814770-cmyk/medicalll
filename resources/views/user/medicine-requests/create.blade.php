@extends('layouts.app')
@section('title', 'ยื่นคำร้องขอรับยา')
@section('page-title', 'ยื่นคำร้องขอรับยา')
@section('sidebar') @include('partials.sidebar-user') @endsection

@section('content')
<div class="panel">
    <div class="panel-header"><h5><i class="bi bi-plus-circle me-2"></i>แจ้งอาการและขอรับยา</h5></div>
    <div class="panel-body">
        <form action="{{ route('user.medicine-requests.store') }}" method="POST" id="requestForm">
            @csrf
            <div class="mb-4">
                <label class="form-label"><i class="bi bi-heart-pulse me-1"></i> อาการป่วย / อาการที่ต้องการยา <span class="text-danger">*</span></label>
                <textarea name="symptoms" class="form-control" rows="4" required placeholder="อธิบายอาการของคุณ เช่น ปวดหัว มีไข้ ปวดท้อง...">{{ old('symptoms') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="bi bi-capsule me-1"></i> เลือกยาที่ต้องการ <span class="text-danger">*</span></label>
                <div id="supplyItems">
                    <div class="supply-item row g-2 mb-2 align-items-end">
                        <div class="col-md-7">
                            <select name="supplies[0][supply_id]" class="form-select" required>
                                <option value="">-- เลือกยา --</option>
                                @foreach($supplies as $categoryName => $items)
                                    <optgroup label="{{ $categoryName }}">
                                        @foreach($items as $supply)
                                            <option value="{{ $supply->id }}">{{ $supply->name }} ({{ $supply->unit }})</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="supplies[0][quantity]" class="form-control" placeholder="จำนวน" min="1" value="1" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeItem(this)" style="display:none;"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-custom btn-sm mt-2" onclick="addItem()">
                    <i class="bi bi-plus me-1"></i>เพิ่มรายการยา
                </button>
            </div>

            <hr>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i>ยื่นคำร้อง</button>
                <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary">ยกเลิก</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let itemIndex = 1;
function addItem() {
    const template = document.querySelector('.supply-item').cloneNode(true);
    template.querySelector('select').name = `supplies[${itemIndex}][supply_id]`;
    template.querySelector('select').value = '';
    template.querySelector('input[type="number"]').name = `supplies[${itemIndex}][quantity]`;
    template.querySelector('input[type="number"]').value = 1;
    template.querySelector('button').style.display = 'block';
    document.getElementById('supplyItems').appendChild(template);
    itemIndex++;
    updateRemoveButtons();
}
function removeItem(btn) {
    btn.closest('.supply-item').remove();
    updateRemoveButtons();
}
function updateRemoveButtons() {
    const items = document.querySelectorAll('.supply-item');
    items.forEach((item, i) => {
        const btn = item.querySelector('button');
        btn.style.display = items.length > 1 ? 'block' : 'none';
    });
}
</script>
@endpush
