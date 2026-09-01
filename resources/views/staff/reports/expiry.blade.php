@extends('layouts.app')
@section('title', 'รายงานวันหมดอายุ')
@section('page-title', 'รายงานวันหมดอายุเวชภัณฑ์')
@section('sidebar') @include('partials.sidebar-staff') @endsection

@section('content')
@if($expiredLots->isNotEmpty())
<div class="panel mb-3">
    <div class="panel-header"><h5><i class="bi bi-exclamation-octagon me-2 text-danger"></i>หมดอายุแล้ว ({{ $expiredLots->count() }} ล็อต)</h5></div>
    <div class="table-responsive">
        <table class="table table-modern">
            <thead><tr><th>เวชภัณฑ์</th><th>หมวดหมู่</th><th>ล็อต</th><th>คงเหลือ</th><th>วันหมดอายุ</th></tr></thead>
            <tbody>
            @foreach($expiredLots as $lot)
                <tr class="table-danger">
                    <td><strong>{{ $lot->supply->name }}</strong></td>
                    <td>{{ $lot->supply->category->name ?? '-' }}</td>
                    <td>{{ $lot->lot_number }}</td>
                    <td>{{ $lot->remaining_quantity }} {{ $lot->supply->unit }}</td>
                    <td><strong class="text-danger">{{ $lot->expiry_date->format('d/m/Y') }}</strong></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div class="panel">
    <div class="panel-header"><h5><i class="bi bi-calendar-event me-2 text-warning"></i>ใกล้หมดอายุ (ภายใน 90 วัน) — {{ $nearExpiryLots->count() }} ล็อต</h5></div>
    <div class="table-responsive">
        <table class="table table-modern">
            <thead><tr><th>เวชภัณฑ์</th><th>หมวดหมู่</th><th>ล็อต</th><th>คงเหลือ</th><th>วันหมดอายุ</th><th>เหลือ</th></tr></thead>
            <tbody>
            @forelse($nearExpiryLots as $lot)
                <tr class="table-warning">
                    <td><strong>{{ $lot->supply->name }}</strong></td>
                    <td>{{ $lot->supply->category->name ?? '-' }}</td>
                    <td>{{ $lot->lot_number }}</td>
                    <td>{{ $lot->remaining_quantity }} {{ $lot->supply->unit }}</td>
                    <td>{{ $lot->expiry_date->format('d/m/Y') }}</td>
                    <td>{{ $lot->expiry_date->diffInDays(now()) }} วัน</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">ไม่มีเวชภัณฑ์ใกล้หมดอายุ</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
