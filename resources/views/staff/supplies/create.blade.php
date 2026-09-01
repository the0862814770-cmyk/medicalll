@extends('layouts.app')
@section('title', 'เพิ่มเวชภัณฑ์')
@section('page-title', 'เพิ่มเวชภัณฑ์ใหม่')
@section('sidebar') @include('partials.sidebar-staff') @endsection

@section('content')
<div class="panel">
    <div class="panel-header"><h5><i class="bi bi-plus-circle me-2"></i>เพิ่มเวชภัณฑ์ใหม่</h5></div>
    <div class="panel-body">
        <form action="{{ route('staff.supplies.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">รหัสเวชภัณฑ์ *</label><input type="text" name="code" class="form-control" required value="{{ old('code') }}" placeholder="เช่น MED001"></div>
                <div class="col-md-6"><label class="form-label">ชื่อเวชภัณฑ์ *</label><input type="text" name="name" class="form-control" required value="{{ old('name') }}"></div>
                <div class="col-md-4"><label class="form-label">หมวดหมู่ *</label><select name="category_id" class="form-select" required><option value="">-- เลือก --</option>@foreach($categories as $cat)<option value="{{ $cat->id }}">{{ $cat->name }}</option>@endforeach</select></div>
                <div class="col-md-4"><label class="form-label">หน่วยนับ *</label><input type="text" name="unit" class="form-control" required value="{{ old('unit') }}" placeholder="เช่น เม็ด, ขวด, กล่อง"></div>
                <div class="col-md-4"><label class="form-label">สต็อกขั้นต่ำ *</label><input type="number" name="min_stock" class="form-control" required value="{{ old('min_stock', 10) }}" min="0"></div>
                <div class="col-12"><label class="form-label">รายละเอียด</label><textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea></div>
                <div class="col-md-6"><label class="form-label">รูปภาพ</label><input type="file" name="image" class="form-control" accept="image/*"></div>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>บันทึก</button>
            <a href="{{ route('staff.supplies.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
        </form>
    </div>
</div>
@endsection
