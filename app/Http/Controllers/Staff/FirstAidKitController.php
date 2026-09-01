<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\FirstAidKit;
use App\Models\KitItem;
use App\Models\Supply;
use Illuminate\Http\Request;

class FirstAidKitController extends Controller
{
    public function index(Request $request)
    {
        $query = FirstAidKit::withCount('items')->with('items.supply');

        // ค้นหา
        if ($request->search) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('kit_code', 'like', "%{$term}%");
            });
        }

        // กรองสถานะ
        if ($request->status && in_array($request->status, ['available', 'borrowed', 'maintenance'])) {
            $query->where('status', $request->status);
        }

        $kits = $query->latest()->paginate(15)->withQueryString();

        // สถิติสรุป
        $stats = [
            'total'       => FirstAidKit::count(),
            'available'   => FirstAidKit::where('status', 'available')->count(),
            'borrowed'    => FirstAidKit::where('status', 'borrowed')->count(),
            'maintenance' => FirstAidKit::where('status', 'maintenance')->count(),
        ];

        $supplies = Supply::orderBy('name')->get();

        return view('staff.kits.index', compact('kits', 'stats', 'supplies'));
    }

    public function create()
    {
        $supplies = Supply::orderBy('name')->get();
        return view('staff.kits.create', compact('supplies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kit_code' => 'required|string|unique:first_aid_kits',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.supply_id' => 'required_with:items|exists:supplies,id',
            'items.*.quantity' => 'required_with:items|integer|min:1',
        ]);

        $kit = FirstAidKit::create($request->only('kit_code', 'name', 'description'));

        if ($request->items) {
            foreach ($request->items as $item) {
                if (!empty($item['supply_id']) && !empty($item['quantity'])) {
                    KitItem::create([
                        'first_aid_kit_id' => $kit->id,
                        'supply_id' => $item['supply_id'],
                        'quantity' => $item['quantity'],
                    ]);
                }
            }
        }

        return redirect()->route('staff.kits.index')
            ->with('success', 'เพิ่มกระเป๋าปฐมพยาบาลสำเร็จ');
    }

    public function edit(FirstAidKit $kit)
    {
        $kit->load('items.supply');
        $supplies = Supply::orderBy('name')->get();
        return view('staff.kits.edit', compact('kit', 'supplies'));
    }

    public function update(Request $request, FirstAidKit $kit)
    {
        $request->validate([
            'kit_code' => 'required|string|unique:first_aid_kits,kit_code,' . $kit->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.supply_id' => 'required_with:items|exists:supplies,id',
            'items.*.quantity' => 'required_with:items|integer|min:1',
        ]);

        $kit->update($request->only('kit_code', 'name', 'description'));

        // อัพเดทรายการเวชภัณฑ์ในกระเป๋า
        if ($request->has('items')) {
            $kit->items()->delete();
            foreach ($request->items as $item) {
                if (!empty($item['supply_id']) && !empty($item['quantity'])) {
                    KitItem::create([
                        'first_aid_kit_id' => $kit->id,
                        'supply_id' => $item['supply_id'],
                        'quantity' => $item['quantity'],
                    ]);
                }
            }
        }

        return redirect()->route('staff.kits.index')
            ->with('success', 'แก้ไขกระเป๋าปฐมพยาบาลสำเร็จ');
    }

    public function destroy(FirstAidKit $kit)
    {
        $kit->delete();
        return redirect()->route('staff.kits.index')
            ->with('success', 'ลบกระเป๋าปฐมพยาบาลสำเร็จ');
    }

    public function addItem(Request $request, FirstAidKit $kit)
    {
        $request->validate([
            'supply_id' => 'required|exists:supplies,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $existingItem = KitItem::where('first_aid_kit_id', $kit->id)
            ->where('supply_id', $request->supply_id)
            ->first();

        if ($existingItem) {
            $existingItem->increment('quantity', $request->quantity);
        } else {
            KitItem::create([
                'first_aid_kit_id' => $kit->id,
                'supply_id' => $request->supply_id,
                'quantity' => $request->quantity,
            ]);
        }

        if ($request->wantsJson()) {
            $kit->load('items.supply');
            return response()->json([
                'success' => true,
                'message' => 'เพิ่มรายการยา/เวชภัณฑ์ลงในกระเป๋าสำเร็จ',
                'items' => $kit->items
            ]);
        }

        return redirect()->back()
            ->with('success', 'เพิ่มรายการยา/เวชภัณฑ์ลงในกระเป๋าสำเร็จ')
            ->with('open_modal_id', $kit->id);
    }

    public function updateItem(Request $request, FirstAidKit $kit, KitItem $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($item->first_aid_kit_id !== $kit->id) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'ไม่พบรายการยานี้ในกระเป๋า'], 404);
            }
            return redirect()->back()->with('error', 'ไม่พบรายการยานี้ในกระเป๋า')->with('open_modal_id', $kit->id);
        }

        $item->update(['quantity' => $request->quantity]);

        if ($request->wantsJson()) {
            $kit->load('items.supply');
            return response()->json([
                'success' => true,
                'message' => 'อัปเดตจำนวนยา/เวชภัณฑ์ในกระเป๋าสำเร็จ',
                'items' => $kit->items
            ]);
        }

        return redirect()->back()
            ->with('success', 'อัปเดตจำนวนยา/เวชภัณฑ์ในกระเป๋าสำเร็จ')
            ->with('open_modal_id', $kit->id);
    }

    public function removeItem(Request $request, FirstAidKit $kit, KitItem $item)
    {
        if ($item->first_aid_kit_id !== $kit->id) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'ไม่พบรายการยานี้ในกระเป๋า'], 404);
            }
            return redirect()->back()->with('error', 'ไม่พบรายการยานี้ในกระเป๋า')->with('open_modal_id', $kit->id);
        }

        $item->delete();

        if ($request->wantsJson()) {
            $kit->load('items.supply');
            return response()->json([
                'success' => true,
                'message' => 'ลบรายการยา/เวชภัณฑ์ออกจากกระเป๋าสำเร็จ',
                'items' => $kit->items
            ]);
        }

        return redirect()->back()
            ->with('success', 'ลบรายการยา/เวชภัณฑ์ออกจากกระเป๋าสำเร็จ')
            ->with('open_modal_id', $kit->id);
    }
}
