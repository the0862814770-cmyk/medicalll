<?php

namespace App\Http\Controllers\Executive;

use App\Http\Controllers\Controller;
use App\Models\Supply;
use App\Models\SupplyLot;
use App\Models\SupplyTransaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function stock(Request $request)
    {
        $query = Supply::with(['category', 'lots']);

        if ($request->search) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('code', 'like', "%{$term}%")
                  ->orWhere('manufacturer', 'like', "%{$term}%")
                  ->orWhere('storage_location', 'like', "%{$term}%")
                  ->orWhereHas('category', function ($q2) use ($term) {
                      $q2->where('name', 'like', "%{$term}%");
                  });
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

        $supplies = $query->paginate($perPage)->withQueryString();
        $supplies->getCollection()->transform(function ($supply) {
            $supply->total_stock_calc = $supply->lots->sum('remaining_quantity');
            return $supply;
        });

        $allSupplies = Supply::with('lots')->get()->map(function ($s) {
            $s->total_stock_calc = $s->lots->sum('remaining_quantity');
            return $s;
        });

        $stats = [
            'total'        => $allSupplies->count(),
            'normal'       => 0,
            'low_stock'    => 0,
            'out_of_stock' => 0,
            'near_expiry'  => 0,
            'expired'      => 0,
        ];

        foreach ($allSupplies as $s) {
            $stock = (int)$s->total_stock_calc;
            $nearestLot = $s->lots->where('remaining_quantity', '>', 0)->sortBy('expiry_date')->first();

            if ($stock <= 0) {
                $stats['out_of_stock']++;
            } elseif ($nearestLot && $nearestLot->expiry_date && $nearestLot->expiry_date->isPast()) {
                $stats['expired']++;
            } elseif ($stock <= $s->min_stock) {
                $stats['low_stock']++;
            } elseif ($nearestLot && $nearestLot->expiry_date && $nearestLot->expiry_date->diffInDays(now()) <= 90) {
                $stats['near_expiry']++;
            } else {
                $stats['normal']++;
            }
        }

        $categories = Category::orderBy('name')->get();
        $units = Supply::distinct()->pluck('unit')->filter()->sort()->values();

        $alertLowStock = $stats['low_stock'] + $stats['out_of_stock'];
        $alertNearExpiry = $stats['near_expiry'] + $stats['expired'];
        $statusFilter = $request->get('status_filter');

        return view('executive.reports.stock', compact(
            'supplies', 'categories', 'units', 'stats',
            'alertLowStock', 'alertNearExpiry', 'sortCol', 'sortDir',
            'perPage', 'statusFilter'
        ));
    }

    public function dispensing(Request $request)
    {
        $period = $request->period ?? 'daily';
        $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->format('Y-m-d');

        $query = SupplyTransaction::where('type', 'dispense')
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59']);

        if ($period === 'daily') {
            $data = $query->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(quantity) as total')
            )->groupBy('date')->orderBy('date')->get();
        } elseif ($period === 'monthly') {
            $data = $query->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(quantity) as total')
            )->groupBy('year', 'month')->orderBy('year')->orderBy('month')->get();
        } else {
            $data = $query->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(quantity) as total')
            )->groupBy('year')->orderBy('year')->get();
        }

        $details = SupplyTransaction::with('supply', 'performer')
            ->where('type', 'dispense')
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->latest()
            ->paginate(20);

        return view('executive.reports.dispensing', compact('data', 'details', 'period', 'dateFrom', 'dateTo'));
    }

    public function search(Request $request)
    {
        $supplies = collect();
        $transactions = collect();

        if ($request->search) {
            $supplies = Supply::with('category', 'lots')
                ->where('name', 'like', "%{$request->search}%")
                ->orWhere('code', 'like', "%{$request->search}%")
                ->get();

            $supplyIds = $supplies->pluck('id');

            $query = SupplyTransaction::with('supply', 'performer')
                ->whereIn('supply_id', $supplyIds);

            if ($request->date_from) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->date_to) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $transactions = $query->latest()->paginate(20);
        }

        return view('executive.search', compact('supplies', 'transactions'));
    }
}
