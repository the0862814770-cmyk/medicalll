<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\MedicineRequest;
use App\Models\KitRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total_medicine_requests' => MedicineRequest::where('user_id', $user->id)->count(),
            'pending_medicine_requests' => MedicineRequest::where('user_id', $user->id)->where('status', 'pending')->count(),
            'total_kit_requests' => KitRequest::where('user_id', $user->id)->count(),
            'active_kit_borrows' => KitRequest::where('user_id', $user->id)->whereIn('status', ['approved', 'borrowed'])->count(),
        ];

        $recentMedicineRequests = MedicineRequest::where('user_id', $user->id)
            ->with('items.supply')
            ->latest()
            ->take(5)
            ->get();

        $recentKitRequests = KitRequest::where('user_id', $user->id)
            ->with('kit')
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard', compact('stats', 'recentMedicineRequests', 'recentKitRequests'));
    }
}
