<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * แสดงหน้าแก้ไขโปรไฟล์
     */
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    /**
     * อัปเดตข้อมูลโปรไฟล์
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => 'nullable|string|max:20',
            'student_id' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'กรุณากรอกชื่อ-นามสกุล',
            'name.max' => 'ชื่อ-นามสกุลต้องไม่เกิน 255 ตัวอักษร',
            'email.required' => 'กรุณากรอกอีเมล',
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique' => 'อีเมลนี้ถูกใช้งานแล้ว',
            'phone.max' => 'เบอร์โทรศัพท์ต้องไม่เกิน 20 ตัวอักษร',
            'student_id.max' => 'รหัสนักศึกษาต้องไม่เกิน 20 ตัวอักษร',
            'profile_photo.image' => 'ไฟล์ที่อัปโหลดต้องเป็นรูปภาพ',
            'profile_photo.mimes' => 'รองรับเฉพาะไฟล์ jpeg, png, jpg, gif เท่านั้น',
            'profile_photo.max' => 'ขนาดไฟล์รูปภาพต้องไม่เกิน 2MB',
        ]);

        $data = $request->only('name', 'email', 'phone', 'student_id');

        if ($request->hasFile('profile_photo')) {
            // Delete old photo
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $data['profile_photo_path'] = $path;
        }

        $user->update($data);

        return back()->with('success', 'อัปเดตข้อมูลโปรไฟล์สำเร็จ');
    }

    /**
     * เปลี่ยนรหัสผ่าน
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'กรุณากรอกรหัสผ่านปัจจุบัน',
            'password.required' => 'กรุณากรอกรหัสผ่านใหม่',
            'password.min' => 'รหัสผ่านใหม่ต้องมีอย่างน้อย 8 ตัวอักษร',
            'password.confirmed' => 'ยืนยันรหัสผ่านใหม่ไม่ตรงกัน',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'รหัสผ่านปัจจุบันไม่ถูกต้อง']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'เปลี่ยนรหัสผ่านสำเร็จ');
    }
}
