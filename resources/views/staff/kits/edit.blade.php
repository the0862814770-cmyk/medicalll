@extends('layouts.app')
@section('title', 'แก้ไขกระเป๋า')
@section('page-title', 'แก้ไขกระเป๋า: ' . $kit->name)
@section('sidebar') @include('partials.sidebar-staff') @endsection

@section('content')
<div class="panel">
    <div class="panel-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0"><i class="bi bi-pencil me-2 text-primary"></i>แก้ไขกระเป๋าปฐมพยาบาล: {{ $kit->name }}</h5>
        <a href="{{ route('staff.kits.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>ย้อนกลับ</a>
    </div>
    <div class="panel-body">
        <form action="{{ route('staff.kits.update', $kit) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-bold">รหัสกระเป๋า *</label>
                    <input type="text" name="kit_code" class="form-control" required value="{{ old('kit_code', $kit->kit_code) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">ชื่อกระเป๋า *</label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name', $kit->name) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">คำอธิบาย</label>
                    <input type="text" name="description" class="form-control" value="{{ old('description', $kit->description) }}">
                </div>
            </div>

            <div class="card border-0 bg-light mb-4">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center pt-3 pb-0">
                    <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-capsule me-2"></i>รายการยา / เวชภัณฑ์ในกระเป๋า</h6>
                    <button type="button" class="btn btn-sm btn-success" id="add-item-row"><i class="bi bi-plus-lg me-1"></i>เพิ่มแถวยา</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered bg-white align-middle mb-0" id="items-table">
                            <thead class="table-light">
                                <tr>
                                    <th>รายการยา / เวชภัณฑ์</th>
                                    <th style="width: 150px;" class="text-center">จำนวน</th>
                                    <th style="width: 70px;" class="text-center">ลบ</th>
                                </tr>
                            </thead>
                            <tbody id="items-container">
                                @forelse($kit->items as $index => $item)
                                    <tr class="item-row">
                                        <td>
                                            <select name="items[{{ $index }}][supply_id]" class="form-select form-select-sm">
                                                <option value="">-- เลือกรายการยา/เวชภัณฑ์ --</option>
                                                @foreach($supplies as $supply)
                                                    <option value="{{ $supply->id }}" {{ $item->supply_id == $supply->id ? 'selected' : '' }}>
                                                        [{{ $supply->code }}] {{ $supply->name }} (คลัง: {{ $supply->total_stock }} {{ $supply->unit }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="items[{{ $index }}][quantity]" class="form-control form-control-sm text-center" value="{{ $item->quantity }}" min="1">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-row-btn"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="item-row">
                                        <td>
                                            <select name="items[0][supply_id]" class="form-select form-select-sm">
                                                <option value="">-- เลือกรายการยา/เวชภัณฑ์ --</option>
                                                @foreach($supplies as $supply)
                                                    <option value="{{ $supply->id }}">[{{ $supply->code }}] {{ $supply->name }} (คลัง: {{ $supply->total_stock }} {{ $supply->unit }})</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][quantity]" class="form-control form-control-sm text-center" value="1" min="1">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-row-btn"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <hr>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>บันทึกการแก้ไข</button>
                <a href="{{ route('staff.kits.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = {{ count($kit->items) > 0 ? count($kit->items) : 1 }};
    const suppliesOptions = `@foreach($supplies as $supply)<option value="{{ $supply->id }}">[{{ $supply->code }}] {{ $supply->name }} (คลัง: {{ $supply->total_stock }} {{ $supply->unit }})</option>@endforeach`;

    document.getElementById('add-item-row').addEventListener('click', function() {
        const container = document.getElementById('items-container');
        const tr = document.createElement('tr');
        tr.className = 'item-row';
        tr.innerHTML = `
            <td>
                <select name="items[${itemIndex}][supply_id]" class="form-select form-select-sm">
                    <option value="">-- เลือกรายการยา/เวชภัณฑ์ --</option>
                    ${suppliesOptions}
                </select>
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][quantity]" class="form-control form-control-sm text-center" value="1" min="1">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-outline-danger btn-sm remove-row-btn"><i class="bi bi-trash"></i></button>
            </td>
        `;
        container.appendChild(tr);
        itemIndex++;
    });

    document.getElementById('items-container').addEventListener('click', function(e) {
        if (e.target.closest('.remove-row-btn')) {
            const row = e.target.closest('tr');
            if (document.querySelectorAll('#items-container tr').length > 1) {
                row.remove();
            } else {
                row.querySelector('select').value = '';
                row.querySelector('input').value = '1';
            }
        }
    });
});
</script>
@endpush
