<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\MedicineRequest;
use App\Models\MedicineRequestItem;
use App\Models\Supply;
use Illuminate\Http\Request;

class MedicineRequestController extends Controller
{
    public function index()
    {
        $requests = MedicineRequest::where('user_id', auth()->id())
            ->with('items.supply')
            ->latest()
            ->paginate(10);

        return view('user.medicine-requests.index', compact('requests'));
    }

    public function create()
    {
        $supplies = Supply::with('category')
            ->whereHas('lots', function($q) {
                $q->where('remaining_quantity', '>', 0);
            })
            ->get()
            ->groupBy('category.name');

        return view('user.medicine-requests.create', compact('supplies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'symptoms' => 'required|string',
            'supplies' => 'required|array|min:1',
            'supplies.*.supply_id' => 'required|exists:supplies,id',
            'supplies.*.quantity' => 'required|integer|min:1',
        ], [
            'symptoms.required' => 'กรุณาระบุอาการ',
            'supplies.required' => 'กรุณาเลือกยาอย่างน้อย 1 รายการ',
        ]);

        $medicineRequest = MedicineRequest::create([
            'request_number' => MedicineRequest::generateRequestNumber(),
            'user_id' => auth()->id(),
            'symptoms' => $request->symptoms,
            'status' => 'pending',
        ]);

        foreach ($request->supplies as $item) {
            MedicineRequestItem::create([
                'medicine_request_id' => $medicineRequest->id,
                'supply_id' => $item['supply_id'],
                'quantity_requested' => $item['quantity'],
            ]);
        }

        return redirect()->route('user.medicine-requests.index')
            ->with('success', 'ยื่นคำร้องขอรับยาสำเร็จ หมายเลข: ' . $medicineRequest->request_number);
    }

    public function show(MedicineRequest $medicineRequest)
    {
        if ($medicineRequest->user_id !== auth()->id()) {
            abort(403);
        }

        $medicineRequest->load('items.supply', 'approver');
        return view('user.medicine-requests.show', compact('medicineRequest'));
    }
}
