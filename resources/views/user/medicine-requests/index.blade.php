@extends('layouts.app')
@section('title', 'คำร้องขอยาของฉัน')
@section('page-title', 'คำร้องขอยาของฉัน')
@section('sidebar') @include('partials.sidebar-user') @endsection

@section('content')
<div class="panel">
    <div class="panel-header">
        <h5><i class="bi bi-list-check me-2"></i>ประวัติคำร้องขอรับยา</h5>
        <a href="{{ route('user.medicine-requests.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus me-1"></i>ยื่นคำร้องใหม่</a>
    </div>
    <div class="panel-body p-0">
        @if($requests->isEmpty())
            <div class="empty-state"><i class="bi bi-inbox d-block"></i>ยังไม่มีคำร้อง</div>
        @else
            <table class="table table-modern">
                <thead><tr><th>เลขที่</th><th>อาการ</th><th>รายการยา</th><th>สถานะ</th><th>วันที่ยื่น</th><th></th></tr></thead>
                <tbody>
                @foreach($requests as $req)
                    <tr>
                        <td><strong>{{ $req->request_number }}</strong></td>
                        <td>{{ Str::limit($req->symptoms, 40) }}</td>
                        <td>{{ $req->items->count() }} รายการ</td>
                        <td><span class="badge bg-{{ $req->status_color }} badge-status">{{ $req->status_label }}</span></td>
                        <td>{{ $req->created_at->format('d/m/Y H:i') }}</td>
                        <td><a href="{{ route('user.medicine-requests.show', $req) }}" class="btn btn-sm btn-outline-custom"><i class="bi bi-eye"></i></a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="p-3">{{ $requests->links() }}</div>
        @endif
    </div>
</div>
@endsection
