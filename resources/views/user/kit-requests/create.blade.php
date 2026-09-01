@extends('layouts.app')
@section('title', 'ยื่นขอเบิกกระเป๋าปฐมพยาบาล')
@section('page-title')
    <div style="display:flex;align-items:center;gap:0.6rem;">
        <i class="bi bi-file-earmark-plus" style="font-size:1.1rem;color:#6366f1;"></i>
        <span style="font-size:0.95rem;font-weight:700;color:#334155;">ยื่นขอเบิกกระเป๋าปฐมพยาบาล</span>
    </div>
@endsection
@section('sidebar') @include('partials.sidebar-user') @endsection

@section('content')
<style>
    .animate-in {
        animation: slideUp 0.3s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card {
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .card-header {
        background: linear-gradient(135deg, rgba(99,102,241,0.08), rgba(56,189,248,0.08));
        border-bottom: 1px solid #e2e8f0;
        padding: 1.25rem 1.5rem;
        border-radius: 16px 16px 0 0;
    }

    .card-header-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #334155;
    }

    .card-body {
        padding: 1.75rem 1.5rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        font-weight: 600;
        color: #334155;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control {
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.7rem 1rem;
        font-size: 0.95rem;
        background: #f8fafc;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #6366f1;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
    }

    .form-control[readonly] {
        background: #f1f5f9;
        cursor: not-allowed;
    }

    .form-error {
        color: #ef4444;
        font-size: 0.85rem;
        margin-top: 0.35rem;
    }

    .btn {
        padding: 0.7rem 1.5rem;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        border: none;
        color: #ffffff;
        box-shadow: 0 6px 16px -4px rgba(79, 70, 229, 0.45);
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 20px -4px rgba(79, 70, 229, 0.55);
    }

    .btn-secondary {
        background: #f1f5f9;
        border: 1.5px solid #e2e8f0;
        color: #64748b;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
        border-color: #cbd5e1;
        color: #475569;
    }
</style>

<div class="row justify-content-center" style="margin-top:1.5rem;">
    <div class="col-md-8 col-lg-6">
        <div class="card animate-in">
            <div class="card-header">
                <span class="card-header-title">
                    <i class="bi bi-bag-heart me-1" style="color:#6366f1;"></i>
                    แบบฟอร์มขอเบิกกระเป๋าปฐมพยาบาล
                </span>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('user.kit-requests.store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Choose kit --}}
                    <div class="form-group">
                        <label class="form-label" for="first_aid_kit_id">
                            เลือกกระเป๋าปฐมพยาบาล <span style="color:#ef4444;">*</span>
                        </label>
                        @if(isset($kits) && $kits->count())
                            <select name="first_aid_kit_id" id="first_aid_kit_id" class="form-control" required>
                                <option value="">-- เลือกกระเป๋า --</option>
                                @foreach($kits as $kit)
                                    <option value="{{ $kit->id }}" {{ old('first_aid_kit_id') == $kit->id ? 'selected' : '' }}>
                                        {{ $kit->kit_code }} - {{ $kit->name }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" class="form-control" value="ยังไม่มีกระเป๋าว่างในขณะนี้" readonly>
                        @endif
                        @error('first_aid_kit_id')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Activity Name --}}
                    <div class="form-group">
                        <label class="form-label" for="activity_name">
                            ชื่อกิจกรรมที่เข้าร่วม <span style="color:#ef4444;">*</span>
                        </label>
                        <input type="text" name="activity_name" id="activity_name" 
                               class="form-control" 
                               placeholder="ระบุชื่อกิจกรรม" 
                               value="{{ old('activity_name') }}"
                               required>
                        @error('activity_name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Activity Date Range --}}
                    <div class="form-group">
                        <label class="form-label">
                            วันเวลาการจัดกิจกรรม <span style="color:#ef4444;">*</span>
                        </label>
                        <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
                            <div style="flex:1;min-width:200px;">
                                <label class="form-label" for="borrow_date" style="font-weight:400;font-size:0.85rem;color:#64748b;">
                                    เริ่มวันที่
                                </label>
                                <input type="date" name="borrow_date" id="borrow_date" 
                                       class="form-control" 
                                       value="{{ old('borrow_date', date('Y-m-d')) }}"
                                       required>
                                @error('borrow_date')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div style="flex:1;min-width:200px;">
                                <label class="form-label" for="expected_return_date" style="font-weight:400;font-size:0.85rem;color:#64748b;">
                                    สิ้นสุดวันที่
                                </label>
                                <input type="date" name="expected_return_date" id="expected_return_date" 
                                       class="form-control" 
                                       value="{{ old('expected_return_date') }}"
                                       required>
                                @error('expected_return_date')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Quantity --}}
                    <div class="form-group">
                        <label class="form-label" for="quantity">
                            จำนวนกระเป๋า <span style="color:#ef4444;">*</span>
                        </label>
                        <input type="number" name="quantity" id="quantity" 
                               class="form-control" 
                               value="{{ old('quantity', 1) }}" 
                               min="1" 
                               required>
                        @error('quantity')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Activity Participants --}}
                    <div class="form-group">
                        <label class="form-label" for="participants_count">
                            จำนวนคนที่เข้าร่วมกิจกรรม (คน) <span style="color:#ef4444;">*</span>
                        </label>
                        <input type="number" name="participants_count" id="participants_count" 
                               class="form-control" 
                               placeholder="ระบุจำนวนคน เช่น 50" 
                               value="{{ old('participants_count') }}"
                               min="1" 
                               required>
                        @error('participants_count')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Purpose/Reason --}}
                    <div class="form-group">
                        <label class="form-label" for="purpose">
                            วัตถุประสงค์/เหตุผลการเบิกกระเป๋าปฐมพยาบาล <span style="color:#ef4444;">*</span>
                        </label>
                        <textarea name="purpose" id="purpose" 
                                  class="form-control" 
                                  rows="4" 
                                  placeholder="ระบุวัตถุประสงค์และเหตุผลเพื่อประกอบการพิจารณาอนุมัติ..." 
                                  required>{{ old('purpose') }}</textarea>
                        @error('purpose')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Upload request letter (optional) --}}
                    <div class="form-group">
                        <label class="form-label" for="letter_form">
                            แนบหนังสือขอเบิกกระเป๋าปฐมพยาบาล (ถ้ามี)
                        </label>
                        <input type="file" name="letter_form" id="letter_form" 
                               class="form-control"
                               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                        <small style="display:block;margin-top:0.35rem;color:#94a3b8;">
                            รองรับไฟล์ PDF, JPG, PNG, DOC, DOCX ขนาดไม่เกิน 10MB
                        </small>
                        @error('letter_form')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Form Buttons --}}
                    <div style="display:flex;align-items:center;justify-content:flex-end;gap:0.75rem;margin-top:1.5rem;">
                        <a href="{{ route('user.dashboard') }}" class="btn btn-secondary" style="border-radius:10px;">
                            ยกเลิก
                        </a>
                        <button type="submit" class="btn btn-primary" style="border-radius:10px;">
                            <i class="bi bi-send me-1"></i> ส่งคำร้อง
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startInput = document.getElementById('borrow_date');
        const endInput = document.getElementById('expected_return_date');

        function syncEndMin() {
            if (startInput.value) {
                endInput.setAttribute('min', startInput.value);
                if (endInput.value && endInput.value < startInput.value) {
                    endInput.value = '';
                }
            }
        }

        startInput.addEventListener('change', syncEndMin);
        syncEndMin(); // Run once on load
    });
</script>
@endsection
