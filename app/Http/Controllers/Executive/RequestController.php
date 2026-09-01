<?php

namespace App\Http\Controllers\Executive;

use App\Http\Controllers\Controller;
use App\Models\KitRequest;
use App\Models\MedicineRequest;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function medicineRequests(Request $request)
    {
        $query = MedicineRequest::with('user', 'items.supply');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $requests = $query->latest()->paginate(15);
        return view('executive.requests.medicine', compact('requests'));
    }

    public function showMedicineRequest(MedicineRequest $medicineRequest)
    {
        $medicineRequest->load('user', 'items.supply');
        return view('executive.requests.medicine-show', compact('medicineRequest'));
    }

    public function approveMedicineRequest(MedicineRequest $medicineRequest)
    {
        return redirect()->route('executive.requests.medicine')
            ->with('info', 'คำร้องขอรับยาไม่ต้องผ่านการอนุมัติจากผู้บริหาร');
    }

    public function rejectMedicineRequest(MedicineRequest $medicineRequest)
    {
        return redirect()->route('executive.requests.medicine')
            ->with('info', 'คำร้องขอรับยาไม่ต้องผ่านการอนุมัติจากผู้บริหาร');
    }

    public function kitRequests(Request $request)
    {
        $query = KitRequest::with('user', 'kit');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $requests = $query->latest()->paginate(15);
        return view('executive.requests.kit', compact('requests'));
    }

    public function showKitRequest(KitRequest $kitRequest)
    {
        $kitRequest->load('user', 'kit', 'kit.items.supply');
        return view('executive.requests.kit-show', compact('kitRequest'));
    }

    public function approveKitRequest(KitRequest $kitRequest)
    {
        if ($kitRequest->status !== 'pending') {
            return back()->with('error', 'คำร้องนี้ไม่สามารถอนุมัติได้ในสถานะปัจจุบัน');
        }

        $kitRequest->update([
            'status' => 'executive_approved',
            'executive_approved_by' => auth()->id(),
            'executive_approved_at' => now(),
        ]);

        return redirect()->route('executive.requests.kit')
            ->with('success', 'อนุมัติคำร้องยืมกระเป๋าปฐมพยาบาลโดยผู้บริหารเรียบร้อยแล้ว');
    }

    public function rejectKitRequest(KitRequest $kitRequest)
    {
        if ($kitRequest->status !== 'pending') {
            return back()->with('error', 'คำร้องนี้ไม่สามารถปฏิเสธได้ในสถานะปัจจุบัน');
        }

        $kitRequest->update([
            'status' => 'rejected',
            'executive_approved_by' => auth()->id(),
            'executive_approved_at' => now(),
        ]);

        return redirect()->route('executive.requests.kit')
            ->with('success', 'ปฏิเสธคำร้องยืมกระเป๋าปฐมพยาบาลโดยผู้บริหารเรียบร้อยแล้ว');
    }
}
