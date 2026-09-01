<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Supply;
use App\Models\Category;
use Illuminate\Http\Request;

class SupplyController extends Controller
{
    public function index(Request $request)
    {
        $query = Supply::with('category', 'lots');

        if ($request->search) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('code', 'like', "%{$term}%")
                  ->orWhere('manufacturer', 'like', "%{$term}%")
                  ->orWhere('storage_location', 'like', "%{$term}%")
                  ->orWhereHas('category', fn($q2) => $q2->where('name', 'like', "%{$term}%"));
            });
        }

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->unit) {
            $query->where('unit', $request->unit);
        }

        $sortCol = $request->get('sort', 'code');
        $sortDir = $request->get('dir', 'asc');
        $allowedSort = ['code', 'name', 'unit', 'min_stock'];
        if (in_array($sortCol, $allowedSort)) {
            $query->orderBy($sortCol, $sortDir === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('code', 'asc');
        }

        $perPage = (int) $request->get('per_page', 20);
        if (!in_array($perPage, [10, 20, 50, 100])) {
            $perPage = 20;
        }

        $supplies   = $query->paginate($perPage)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $units      = Supply::distinct()->pluck('unit')->filter()->sort()->values();

        return view('staff.supplies.index', compact('supplies', 'categories', 'units', 'sortCol', 'sortDir', 'perPage'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('staff.supplies.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:supplies',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string|max:50',
            'min_stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('supplies', 'public');
        }

        Supply::create($data);

        return redirect()->route('staff.supplies.index')
            ->with('success', 'เพิ่มเวชภัณฑ์สำเร็จ');
    }

    public function edit(Supply $supply)
    {
        $categories = Category::all();
        return view('staff.supplies.edit', compact('supply', 'categories'));
    }

    public function update(Request $request, Supply $supply)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:supplies,code,' . $supply->id,
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string|max:50',
            'min_stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('supplies', 'public');
        }

        $supply->update($data);

        return redirect()->route('staff.supplies.index')
            ->with('success', 'แก้ไขข้อมูลเวชภัณฑ์สำเร็จ');
    }

    public function destroy(Supply $supply)
    {
        $supply->delete();
        return redirect()->route('staff.supplies.index')
            ->with('success', 'ลบเวชภัณฑ์สำเร็จ');
    }
}
