<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('supplies')->latest()->paginate(15);
        return view('staff.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);

        Category::create($request->only('name', 'description'));

        return redirect()->route('staff.categories.index')
            ->with('success', 'เพิ่มหมวดหมู่สำเร็จ');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($request->only('name', 'description'));

        return redirect()->route('staff.categories.index')
            ->with('success', 'แก้ไขหมวดหมู่สำเร็จ');
    }

    public function destroy(Category $category)
    {
        if ($category->supplies()->count() > 0) {
            return back()->with('error', 'ไม่สามารถลบหมวดหมู่ที่มีเวชภัณฑ์อยู่ได้');
        }

        $category->delete();
        return redirect()->route('staff.categories.index')
            ->with('success', 'ลบหมวดหมู่สำเร็จ');
    }
}
