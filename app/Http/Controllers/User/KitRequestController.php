<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\KitRequest;
use App\Models\FirstAidKit;
use Illuminate\Http\Request;

class KitRequestController extends Controller
{
    public function index()
    {
        $requests = KitRequest::where('user_id', auth()->id())
            ->with('kit.items.supply', 'approver')
            ->latest()
            ->paginate(10);

        return view('user.kit-requests.index', compact('requests'));
    }

    public function create()
    {
        $kits = FirstAidKit::where('status', 'available')->get();
        return view('user.kit-requests.create', compact('kits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_aid_kit_id' => 'required|exists:first_aid_kits,id',
            'purpose' => 'required|string',
            'borrow_date' => 'required|date|after_or_equal:today',
            'expected_return_date' => 'required|date|after:borrow_date',
            'letter_form' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ], [
            'first_aid_kit_id.required' => 'กรุณาเลือกกระเป๋าปฐมพยาบาล',
            'purpose.required' => 'กรุณาระบุวัตถุประสงค์',
            'borrow_date.required' => 'กรุณาระบุวันที่ยืม',
            'expected_return_date.required' => 'กรุณาระบุวันที่คืน',
            'letter_form.max' => 'ขนาดไฟล์หนังสือแนบต้องไม่เกิน 10MB',
        ]);

        $kit = FirstAidKit::findOrFail($request->first_aid_kit_id);
        if ($kit->status !== 'available') {
            return back()->with('error', 'กระเป๋าปฐมพยาบาลนี้ไม่พร้อมใช้งาน');
        }

        $documentPath = null;
        if ($request->hasFile('letter_form')) {
            $file = $request->file('letter_form');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/documents'), $filename);
            $documentPath = 'uploads/documents/' . $filename;
        }

        KitRequest::create([
            'request_number' => KitRequest::generateRequestNumber(),
            'user_id' => auth()->id(),
            'first_aid_kit_id' => $request->first_aid_kit_id,
            'activity_name' => $request->activity_name,
            'quantity' => $request->quantity ?? 1,
            'participants_count' => $request->participants_count,
            'purpose' => $request->purpose,
            'borrow_date' => $request->borrow_date,
            'expected_return_date' => $request->expected_return_date,
            'document_path' => $documentPath,
            'status' => 'pending',
        ]);

        return redirect()->route('user.kit-requests.index')
            ->with('success', 'ยื่นคำร้องขอยืมกระเป๋าปฐมพยาบาลสำเร็จ');
    }

    public function requestReturn(KitRequest $kitRequest)
    {
        if ($kitRequest->user_id !== auth()->id()) {
            abort(403);
        }

        if ($kitRequest->status !== 'borrowed') {
            return back()->with('error', 'ไม่สามารถแจ้งคืนได้ในสถานะปัจจุบัน');
        }

        $kitRequest->update(['status' => 'return_pending']);

        return back()->with('success', 'แจ้งส่งคืนกระเป๋าปฐมพยาบาลสำเร็จ รอเจ้าหน้าที่ตรวจรับ');
    }

    public function edit(KitRequest $kitRequest)
    {
        // ตรวจสอบว่าเป็นเจ้าของคำร้อง
        if ($kitRequest->user_id !== auth()->id()) {
            abort(403);
        }

        // อนุญาตแก้ไขเฉพาะสถานะ pending
        if ($kitRequest->status !== 'pending') {
            return redirect()->route('user.kit-requests.index')
                ->with('error', 'ไม่สามารถแก้ไขคำร้องนี้ได้ เนื่องจากอยู่ในสถานะ: ' . $kitRequest->status_label);
        }

        $kits = FirstAidKit::where('status', 'available')
            ->orWhere('id', $kitRequest->first_aid_kit_id)
            ->get();

        return view('user.kit-requests.edit', compact('kitRequest', 'kits'));
    }

    public function update(Request $request, KitRequest $kitRequest)
    {
        // ตรวจสอบว่าเป็นเจ้าของคำร้อง
        if ($kitRequest->user_id !== auth()->id()) {
            abort(403);
        }

        // อนุญาตแก้ไขเฉพาะสถานะ pending
        if ($kitRequest->status !== 'pending') {
            return redirect()->route('user.kit-requests.index')
                ->with('error', 'ไม่สามารถแก้ไขคำร้องนี้ได้');
        }

        $request->validate([
            'first_aid_kit_id' => 'required|exists:first_aid_kits,id',
            'purpose' => 'required|string',
            'borrow_date' => 'required|date|after_or_equal:today',
            'expected_return_date' => 'required|date|after:borrow_date',
        ], [
            'first_aid_kit_id.required' => 'กรุณาเลือกกระเป๋าปฐมพยาบาล',
            'purpose.required' => 'กรุณาระบุวัตถุประสงค์',
            'borrow_date.required' => 'กรุณาระบุวันที่ยืม',
            'expected_return_date.required' => 'กรุณาระบุวันที่คืน',
        ]);

        $kit = FirstAidKit::findOrFail($request->first_aid_kit_id);
        if ($kit->status !== 'available' && $kit->id !== $kitRequest->first_aid_kit_id) {
            return back()->with('error', 'กระเป๋าปฐมพยาบาลนี้ไม่พร้อมใช้งาน');
        }

        $kitRequest->update([
            'first_aid_kit_id' => $request->first_aid_kit_id,
            'purpose' => $request->purpose,
            'borrow_date' => $request->borrow_date,
            'expected_return_date' => $request->expected_return_date,
        ]);

        return redirect()->route('user.kit-requests.index')
            ->with('success', 'แก้ไขคำร้องสำเร็จ');
    }

    public function destroy(KitRequest $kitRequest)
    {
        // ตรวจสอบว่าเป็นเจ้าของคำร้อง
        if ($kitRequest->user_id !== auth()->id()) {
            abort(403);
        }

        // อนุญาตลบเฉพาะสถานะ pending
        if ($kitRequest->status !== 'pending') {
            return redirect()->route('user.kit-requests.index')
                ->with('error', 'ไม่สามารถลบคำร้องนี้ได้ เนื่องจากอยู่ในสถานะ: ' . $kitRequest->status_label);
        }

        $kitRequest->delete();

        return redirect()->route('user.kit-requests.index')
            ->with('success', 'ลบคำร้องสำเร็จ');
    }

    public function printLetter(KitRequest $kitRequest)
    {
        if ($kitRequest->user_id !== auth()->id()) {
            abort(403);
        }

        $kitRequest->load('user', 'kit.items.supply', 'approver');
        return view('staff.requests.kit-print', compact('kitRequest'));
    }
}
