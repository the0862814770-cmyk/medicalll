@extends('layouts.app')
@section('title', 'บันทึกธุรกรรม')
@section('page-title', 'บันทึกรับเข้า/เบิกจ่ายเวชภัณฑ์')
@section('sidebar') @include('partials.sidebar-staff') @endsection

@section('content')
<div class="panel">
    <div class="panel-header"><h5><i class="bi bi-arrow-left-right me-2"></i>บันทึกธุรกรรมเวชภัณฑ์</h5></div>
    <div class="panel-body">
        <form action="{{ route('staff.transactions.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">ประเภท *</label>
                    <select name="type" id="txnType" class="form-select" required onchange="toggleFields()">
                        <option value="">-- เลือก --</option>
                        <option value="receive">รับเข้า</option>
                        <option value="dispense">เบิกจ่าย</option>
                        <option value="return">รับคืน</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">เวชภัณฑ์ *</label>
                    <select name="supply_id" id="supplySelect" class="form-select" required onchange="loadLots()">
                        <option value="">-- เลือก --</option>
                        @foreach($supplies as $supply)
                            <option value="{{ $supply->id }}" data-lots="{{ $supply->lots->toJson() }}">{{ $supply->code }} - {{ $supply->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">จำนวน *</label>
                    <input type="number" name="quantity" class="form-control" required min="1">
                </div>

                {{-- Fields for รับเข้า --}}
                <div class="col-md-4 receive-fields" style="display:none;">
                    <label class="form-label">เลขล็อต *</label>
                    <input type="text" name="lot_number" class="form-control" placeholder="เช่น LOT2024001">
                </div>
                <div class="col-md-4 receive-fields" style="display:none;">
                    <label class="form-label">วันหมดอายุ *</label>
                    <input type="date" name="expiry_date" class="form-control">
                </div>

                {{-- Fields for เบิกจ่าย/คืน --}}
                <div class="col-md-8 lot-fields" style="display:none;">
                    <label class="form-label">เลือกล็อต *</label>
                    <select name="supply_lot_id" id="lotSelect" class="form-select">
                        <option value="">-- เลือกเวชภัณฑ์ก่อน --</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">หมายเหตุ</label>
                    <textarea name="notes" class="form-control" rows="2" placeholder="ระบุหมายเหตุ (ถ้ามี)"></textarea>
                </div>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>บันทึก</button>
            <a href="{{ route('staff.transactions.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleFields() {
    const type = document.getElementById('txnType').value;
    document.querySelectorAll('.receive-fields').forEach(el => el.style.display = type === 'receive' ? 'block' : 'none');
    document.querySelectorAll('.lot-fields').forEach(el => el.style.display = (type === 'dispense' || type === 'return') ? 'block' : 'none');
}

function loadLots() {
    const select = document.getElementById('supplySelect');
    const option = select.options[select.selectedIndex];
    const lots = JSON.parse(option.dataset.lots || '[]');
    const lotSelect = document.getElementById('lotSelect');
    lotSelect.innerHTML = '<option value="">-- เลือกล็อต --</option>';
    lots.forEach(lot => {
        if (lot.remaining_quantity > 0) {
            const o = document.createElement('option');
            o.value = lot.id;
            o.text = `${lot.lot_number} (คงเหลือ: ${lot.remaining_quantity}, หมดอายุ: ${lot.expiry_date})`;
            lotSelect.appendChild(o);
        }
    });
}
</script>
@endpush
