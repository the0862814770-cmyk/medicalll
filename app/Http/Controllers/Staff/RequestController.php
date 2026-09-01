<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\MedicineRequest;
use App\Models\KitRequest;
use App\Models\SupplyLot;
use App\Models\SupplyTransaction;
use App\Models\FirstAidKit;
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
        return view('staff.requests.medicine', compact('requests'));
    }

    public function showMedicineRequest(MedicineRequest $medicineRequest)
    {
        $medicineRequest->load('user', 'items.supply.lots', 'approver');
        return view('staff.requests.medicine-show', compact('medicineRequest'));
    }

    public function approveMedicineRequest(Request $request, MedicineRequest $medicineRequest)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:medicine_request_items,id',
            'items.*.quantity_approved' => 'required|integer|min:0',
            'staff_notes' => 'nullable|string',
        ]);

        if (!in_array($medicineRequest->status, ['pending', 'executive_approved'])) {
            return back()->with('error', 'คำร้องนี้ไม่สามารถอนุมัติได้ในสถานะปัจจุบัน');
        }

        foreach ($request->items as $itemData) {
            $item = $medicineRequest->items()->findOrFail($itemData['id']);
            $item->update(['quantity_approved' => $itemData['quantity_approved']]);
        }

        $medicineRequest->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'staff_notes' => $request->staff_notes,
        ]);

        return redirect()->route('staff.requests.medicine')
            ->with('success', 'อนุมัติคำร้องขอรับยาสำเร็จ');
    }

    public function rejectMedicineRequest(Request $request, MedicineRequest $medicineRequest)
    {
        if (!in_array($medicineRequest->status, ['pending', 'executive_approved'])) {
            return back()->with('error', 'คำร้องนี้ไม่สามารถปฏิเสธได้ในสถานะปัจจุบัน');
        }

        $medicineRequest->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'staff_notes' => $request->staff_notes ?? 'ไม่อนุมัติ',
        ]);

        return redirect()->route('staff.requests.medicine')
            ->with('success', 'ปฏิเสธคำร้องสำเร็จ');
    }

    public function dispenseMedicineRequest(MedicineRequest $medicineRequest)
    {
        if ($medicineRequest->status !== 'approved') {
            return back()->with('error', 'คำร้องนี้ยังไม่ได้รับการอนุมัติ');
        }

        // ตัดสต็อกจาก lot ที่ใกล้หมดอายุก่อน (FEFO)
        foreach ($medicineRequest->items as $item) {
            $remaining = $item->quantity_approved;
            $lots = SupplyLot::where('supply_id', $item->supply_id)
                ->where('remaining_quantity', '>', 0)
                ->orderBy('expiry_date', 'asc')
                ->get();

            foreach ($lots as $lot) {
                if ($remaining <= 0) break;

                $deduct = min($remaining, $lot->remaining_quantity);
                $lot->decrement('remaining_quantity', $deduct);
                $remaining -= $deduct;

                SupplyTransaction::create([
                    'supply_id' => $item->supply_id,
                    'supply_lot_id' => $lot->id,
                    'type' => 'dispense',
                    'quantity' => $deduct,
                    'notes' => "จ่ายตามคำร้อง {$medicineRequest->request_number}",
                    'reference' => $medicineRequest->request_number,
                    'performed_by' => auth()->id(),
                ]);
            }
        }

        $medicineRequest->update(['status' => 'dispensed']);

        return redirect()->route('staff.requests.medicine')
            ->with('success', 'จ่ายยาตามคำร้องสำเร็จ');
    }

    // === Kit Requests ===

    public function kitRequests(Request $request)
    {
        $query = KitRequest::with('user', 'kit.items.supply', 'approver');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $requests = $query->latest()->paginate(15);
        return view('staff.requests.kit', compact('requests'));
    }

    public function approveKitRequest(KitRequest $kitRequest)
    {
        if ($kitRequest->status !== 'executive_approved') {
            return back()->with('error', 'คำร้องนี้ยังไม่ได้รับการอนุมัติจากผู้บริหาร');
        }

        $kit = $kitRequest->kit;
        if ($kit->status !== 'available') {
            return back()->with('error', 'กระเป๋าปฐมพยาบาลไม่พร้อมให้ยืม');
        }

        $kitRequest->update([
            'status' => 'borrowed',
            'approved_by' => auth()->id(),
        ]);

        $kit->update(['status' => 'borrowed']);

        return back()->with('success', 'อนุมัติการยืมกระเป๋าปฐมพยาบาลสำเร็จ');
    }

    public function rejectKitRequest(KitRequest $kitRequest)
    {
        if ($kitRequest->status !== 'executive_approved') {
            return back()->with('error', 'คำร้องนี้ยังไม่ได้รับการอนุมัติจากผู้บริหาร');
        }

        $kitRequest->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', 'ปฏิเสธคำร้องยืมกระเป๋าสำเร็จ');
    }

    public function confirmReturnKit(KitRequest $kitRequest)
    {
        $kitRequest->update([
            'status' => 'returned',
            'actual_return_date' => now(),
        ]);

        $kitRequest->kit->update(['status' => 'available']);

        return back()->with('success', 'รับคืนกระเป๋าปฐมพยาบาลสำเร็จ');
    }

    public function printKitRequest(KitRequest $kitRequest)
    {
        $kitRequest->load('user', 'kit.items.supply', 'approver');
        return view('staff.requests.kit-print', compact('kitRequest'));
    }
}
