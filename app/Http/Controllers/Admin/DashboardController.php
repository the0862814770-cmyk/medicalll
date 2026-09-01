<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Supply;
use App\Models\MedicineRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'suspended_users' => User::where('status', 'suspended')->count(),
            'user_count' => User::where('role', 'user')->count(),
            'staff_count' => User::where('role', 'staff')->count(),
            'executive_count' => User::where('role', 'executive')->count(),
            'admin_count' => User::where('role', 'admin')->count(),
        ];

        $recentUsers = User::latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers'));
    }
}
