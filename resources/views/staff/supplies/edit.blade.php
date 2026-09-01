@extends('layouts.app')
@section('title', 'แก้ไขเวชภัณฑ์')
@section('page-title', 'แก้ไขเวชภัณฑ์: ' . $supply->name)
@section('sidebar') @include('partials.sidebar-staff') @endsection

@section('content')
<div class="panel">
    <div class="panel-header"><h5><i class="bi bi-pencil me-2"></i>แก้ไขข้อมูลเวชภัณฑ์</h5></div>
    <div class="panel-body">
        <form action="{{ route('staff.supplies.update', $supply) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">รหัสเวชภัณฑ์ *</label><input type="text" name="code" class="form-control" required value="{{ old('code', $supply->code) }}"></div>
                <div class="col-md-6"><label class="form-label">ชื่อเวชภัณฑ์ *</label><input type="text" name="name" class="form-control" required value="{{ old('name', $supply->name) }}"></div>
                <div class="col-md-4"><label class="form-label">หมวดหมู่ *</label><select name="category_id" class="form-select" required>@foreach($categories as $cat)<option value="{{ $cat->id }}" {{ $supply->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach</select></div>
                <div class="col-md-4"><label class="form-label">หน่วยนับ *</label><input type="text" name="unit" class="form-control" required value="{{ old('unit', $supply->unit) }}"></div>
                <div class="col-md-4"><label class="form-label">สต็อกขั้นต่ำ *</label><input type="number" name="min_stock" class="form-control" required value="{{ old('min_stock', $supply->min_stock) }}" min="0"></div>
                <div class="col-12"><label class="form-label">รายละเอียด</label><textarea name="description" class="form-control" rows="3">{{ old('description', $supply->description) }}</textarea></div>
                <div class="col-md-6"><label class="form-label">รูปภาพ</label><input type="file" name="image" class="form-control" accept="image/*"></div>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>บันทึกการแก้ไข</button>
            <a href="{{ route('staff.supplies.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </form>
    </div>
</div>
@endsection
