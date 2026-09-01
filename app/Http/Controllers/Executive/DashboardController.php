<?php

namespace App\Http\Controllers\Executive;

use App\Http\Controllers\Controller;
use App\Models\Supply;
use App\Models\SupplyTransaction;
use App\Models\SupplyLot;
use App\Models\MedicineRequest;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_supplies' => Supply::count(),
            'total_stock_value' => SupplyLot::sum('remaining_quantity'),
            'total_dispensed_today' => SupplyTransaction::where('type', 'dispense')
                ->whereDate('created_at', today())->sum('quantity'),
            'total_dispensed_month' => SupplyTransaction::where('type', 'dispense')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('quantity'),
            'low_stock' => Supply::all()->filter(fn($s) => $s->is_low_stock)->count(),
            'expired' => SupplyLot::where('remaining_quantity', '>', 0)
                ->where('expiry_date', '<', now())->count(),
        ];

        // กราฟเบิกจ่ายรายเดือน (12 เดือนย้อนหลัง)
        $isSqlite = DB::getDriverName() === 'sqlite';
        $yearExpr = $isSqlite ? "CAST(strftime('%Y', created_at) AS INTEGER) as year" : "YEAR(created_at) as year";
        $monthExpr = $isSqlite ? "CAST(strftime('%m', created_at) AS INTEGER) as month" : "MONTH(created_at) as month";

        $monthlyDispensing = SupplyTransaction::where('type', 'dispense')
            ->where('created_at', '>=', now()->subMonths(12))
            ->select(
                DB::raw($yearExpr),
                DB::raw($monthExpr),
                DB::raw('SUM(quantity) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Top 10 เวชภัณฑ์ที่เบิกมากที่สุด
        $topSupplies = SupplyTransaction::where('type', 'dispense')
            ->whereMonth('created_at', now()->month)
            ->select('supply_id', DB::raw('SUM(quantity) as total'))
            ->groupBy('supply_id')
            ->orderByDesc('total')
            ->with('supply')
            ->take(10)
            ->get();

        return view('executive.dashboard', compact('stats', 'monthlyDispensing', 'topSupplies'));
    }
}
