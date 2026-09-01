<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    public function importTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="users_template.csv"',
        ];
        
        $callback = function() {
            $file = fopen('php://output', 'w');
            // Adding BOM for UTF-8 Excel support
            fputs($file, $bom =(chr(0xEF) . chr(0xBB) . chr(0xBF)));
            
            // Header row
            fputcsv($file, ['ชื่อ_นามสกุล', 'อีเมล', 'รหัสผ่าน', 'สิทธิ์', 'เบอร์โทรศัพท์', 'รหัสนักศึกษา']);
            
            // Example data row
            fputcsv($file, ['สมชาย ใจดี', 'somchai@example.com', 'password123', 'user', '0812345678', '631234567']);
            
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:csv,txt,xls,xlsx|max:5120',
        ]);

        try {
            Excel::import(new UsersImport, $request->file('import_file'));
            return back()->with('success', 'นำเข้าข้อมูลผู้ใช้งานสำเร็จ');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $messages = [];
            foreach ($failures as $failure) {
                $messages[] = "แถวที่ " . $failure->row() . ": " . implode(', ', $failure->errors());
            }
            return back()->with('error', 'พบข้อผิดพลาดในข้อมูล: ' . implode(' | ', $messages));
        } catch (\Exception $e) {
            return back()->with('error', 'เกิดข้อผิดพลาดในการนำเข้า: ' . $e->getMessage());
        }
    }
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('student_id', 'like', "%{$request->search}%");
            });
        }

        if ($request->role) {
            $query->where('role', $request->role);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:user,staff,executive,admin',
            'phone' => 'nullable|string|max:20',
            'student_id' => 'nullable|string|max:20',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'student_id' => $request->student_id,
            'status' => 'active',
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'เพิ่มผู้ใช้สำเร็จ');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,staff,executive,admin',
            'phone' => 'nullable|string|max:20',
            'student_id' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8',
        ]);

        $data = $request->only('name', 'email', 'role', 'phone', 'student_id');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'แก้ไขข้อมูลผู้ใช้สำเร็จ');
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'ไม่สามารถเปลี่ยนสถานะบัญชีตัวเองได้');
        }

        $user->update([
            'status' => $user->status === 'active' ? 'suspended' : 'active'
        ]);

        $statusText = $user->status === 'active' ? 'เปิดใช้งาน' : 'ระงับการใช้งาน';
        return back()->with('success', "{$statusText}บัญชี {$user->name} สำเร็จ");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'ไม่สามารถลบบัญชีตัวเองได้');
        }

        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'ลบผู้ใช้สำเร็จ');
    }
}
