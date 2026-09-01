<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Supply;
use App\Models\SupplyLot;
use App\Models\SupplyTransaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = SupplyTransaction::with('supply', 'lot', 'performer');

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->search) {
            $query->whereHas('supply', function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            });
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->latest()->paginate(20);
        $supplies = Supply::all();

        return view('staff.transactions.index', compact('transactions', 'supplies'));
    }

    public function create()
    {
        $supplies = Supply::with('lots')->get();
        return view('staff.transactions.create', compact('supplies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supply_id' => 'required|exists:supplies,id',
            'type' => 'required|in:receive,dispense,return,adjust',
            'quantity' => 'required|integer|min:1',
            'lot_number' => 'required_if:type,receive|string|nullable',
            'expiry_date' => 'required_if:type,receive|date|nullable',
            'supply_lot_id' => 'required_if:type,dispense|nullable',
            'notes' => 'nullable|string',
        ]);

        $supply = Supply::findOrFail($request->supply_id);

        if ($request->type === 'receive') {
            // รับเข้า - สร้าง lot ใหม่
            $lot = SupplyLot::create([
                'supply_id' => $supply->id,
                'lot_number' => $request->lot_number,
                'quantity' => $request->quantity,
                'remaining_quantity' => $request->quantity,
                'expiry_date' => $request->expiry_date,
                'received_date' => now(),
                'notes' => $request->notes,
            ]);

            SupplyTransaction::create([
                'supply_id' => $supply->id,
                'supply_lot_id' => $lot->id,
                'type' => 'receive',
                'quantity' => $request->quantity,
                'notes' => $request->notes,
                'performed_by' => auth()->id(),
            ]);

        } elseif ($request->type === 'dispense') {
            // เบิกจ่าย - ลดจำนวนจาก lot
            $lot = SupplyLot::findOrFail($request->supply_lot_id);

            if ($lot->remaining_quantity < $request->quantity) {
                return back()->with('error', 'จำนวนเบิกมากกว่าจำนวนคงเหลือในล็อต');
            }

            $lot->decrement('remaining_quantity', $request->quantity);

            SupplyTransaction::create([
                'supply_id' => $supply->id,
                'supply_lot_id' => $lot->id,
                'type' => 'dispense',
                'quantity' => $request->quantity,
                'notes' => $request->notes,
                'performed_by' => auth()->id(),
            ]);

        } elseif ($request->type === 'return') {
            // รับคืน
            $lot = SupplyLot::findOrFail($request->supply_lot_id);
            $lot->increment('remaining_quantity', $request->quantity);

            SupplyTransaction::create([
                'supply_id' => $supply->id,
                'supply_lot_id' => $lot->id,
                'type' => 'return',
                'quantity' => $request->quantity,
                'notes' => $request->notes,
                'performed_by' => auth()->id(),
            ]);
        }

        $typeLabels = ['receive' => 'รับเข้า', 'dispense' => 'เบิกจ่าย', 'return' => 'รับคืน'];
        return redirect()->route('staff.transactions.index')
            ->with('success', "บันทึกการ{$typeLabels[$request->type]}เวชภัณฑ์สำเร็จ");
    }
}
