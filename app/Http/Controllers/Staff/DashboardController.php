<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Supply;
use App\Models\SupplyLot;
use App\Models\MedicineRequest;
use App\Models\KitRequest;
use App\Models\FirstAidKit;
use App\Models\SupplyTransaction;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ---- สถิติหลัก ----
        $allSupplies = Supply::with(['lots', 'category'])->get()->map(function ($s) {
            $s->total_stock_calc = $s->lots->sum('remaining_quantity');
            return $s;
        });

        $stats = [
            'total_supplies'            => $allSupplies->count(),
            'low_stock_count'           => $allSupplies->filter(fn($s) => $s->total_stock_calc > 0 && $s->total_stock_calc <= $s->min_stock)->count(),
            'out_of_stock_count'        => $allSupplies->filter(fn($s) => $s->total_stock_calc <= 0)->count(),
            'critical_stock_count'      => $allSupplies->filter(fn($s) => $s->total_stock_calc > 0 && $s->total_stock_calc < 10)->count(),
            'pending_medicine_requests' => MedicineRequest::where('status', 'pending')->count(),
            'pending_kit_requests'      => KitRequest::where('status', 'executive_approved')->count(),
            'total_kits'                => FirstAidKit::count(),
            'borrowed_kits'             => FirstAidKit::where('status', 'borrowed')->count(),
            'near_expiry'               => SupplyLot::where('remaining_quantity', '>', 0)
                ->where('expiry_date', '<=', now()->addDays(90))
                ->where('expiry_date', '>', now())
                ->count(),
            'expired'                   => SupplyLot::where('remaining_quantity', '>', 0)
                ->where('expiry_date', '<', now())
                ->count(),
            'dispensed_today'           => SupplyTransaction::where('type', 'dispense')
                ->whereDate('created_at', today())
                ->sum('quantity'),
            'dispensed_this_month'      => SupplyTransaction::where('type', 'dispense')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('quantity'),
        ];

        // ---- คำร้องยารอดำเนินการ ----
        $recentRequests = MedicineRequest::with('user')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // ---- ยาสต็อกต่ำ (top 6) ----
        $lowStockSupplies = $allSupplies
            ->filter(fn($s) => $s->total_stock_calc > 0 && $s->total_stock_calc <= $s->min_stock)
            ->sortBy('total_stock_calc')
            ->take(6)
            ->values();

        // ---- ยาใกล้หมด critical (stock < 10) ----
        $criticalSupplies = $allSupplies
            ->filter(fn($s) => $s->total_stock_calc > 0 && $s->total_stock_calc < 10)
            ->sortBy('total_stock_calc')
            ->take(5)
            ->values();

        // ---- ล็อตใกล้หมดอายุ (90 วัน) ----
        $nearExpiryLots = SupplyLot::with('supply')
            ->where('remaining_quantity', '>', 0)
            ->where('expiry_date', '<=', now()->addDays(90))
            ->where('expiry_date', '>', now())
            ->orderBy('expiry_date')
            ->take(5)
            ->get();

        // ---- กราฟการเบิกจ่าย 7 วันย้อนหลัง ----
        $dispensingTrend = SupplyTransaction::where('type', 'dispense')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as date, SUM(quantity) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $trendLabels = [];
        $trendValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i)->toDateString();
            $trendLabels[] = now()->subDays($i)->locale('th')->translatedFormat('D d');
            $trendValues[] = $dispensingTrend->has($day) ? (int)$dispensingTrend[$day]->total : 0;
        }

        return view('staff.dashboard', compact(
            'stats', 'recentRequests', 'lowStockSupplies',
            'criticalSupplies', 'nearExpiryLots',
            'trendLabels', 'trendValues'
        ));
    }
}
