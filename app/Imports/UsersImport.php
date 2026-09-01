<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Support both English and Thai headings for flexibility
        $name = $row['name'] ?? $row['ชื่อ_นามสกุล'] ?? null;
        $email = $row['email'] ?? $row['อีเมล'] ?? null;
        
        if (!$name || !$email) {
            return null; // Skip invalid rows
        }

        $passwordRaw = $row['password'] ?? $row['รหัสผ่าน'] ?? 'password123';
        $roleRaw = strtolower($row['role'] ?? $row['สิทธิ์'] ?? 'user');
        
        // Ensure valid roles
        $validRoles = ['user', 'staff', 'executive', 'admin'];
        if (!in_array($roleRaw, $validRoles)) {
            $roleRaw = 'user';
        }

        return new User([
            'name'       => $name,
            'email'      => $email,
            'password'   => Hash::make($passwordRaw),
            'role'       => $roleRaw,
            'phone'      => $row['phone'] ?? $row['เบอร์โทรศัพท์'] ?? null,
            'student_id' => $row['student_id'] ?? $row['รหัสนักศึกษา'] ?? null,
            'status'     => 'active',
        ]);
    }

    public function rules(): array
    {
        return [
            '*.email' => ['nullable', 'email', 'unique:users,email'],
            '*.อีเมล' => ['nullable', 'email', 'unique:users,email'],
        ];
    }
}
