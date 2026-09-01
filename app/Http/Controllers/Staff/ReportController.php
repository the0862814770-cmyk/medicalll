<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Supply;
use App\Models\SupplyLot;
use App\Models\SupplyTransaction;
use App\Models\Category;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function stock(Request $request)
    {
        $query = Supply::with(['category', 'lots']);

        // ค้นหา
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

        // กรองหมวดหมู่
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // กรองหน่วย
        if ($request->unit) {
            $query->where('unit', $request->unit);
        }

        // เรียงลำดับ
        $sortCol = $request->get('sort', 'code');
        $sortDir = $request->get('dir', 'asc');
        $allowedSort = ['code', 'name', 'unit', 'min_stock'];
        if (in_array($sortCol, $allowedSort)) {
            $query->orderBy($sortCol, $sortDir === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('code', 'asc');
        }

        // จำนวนต่อหน้า
        $perPage = (int) $request->get('per_page', 20);
        if (!in_array($perPage, [10, 20, 50, 100])) {
            $perPage = 20;
        }

        $statusFilter = $request->get('status_filter');

        // เรียกข้อมูลทั้งหมดก่อน paginate เพื่อให้ status filter ทำงานได้ถูกต้อง
        $allResults = $query->get()->map(function ($supply) {
            $supply->total_stock_calc = $supply->lots->sum('remaining_quantity');
            $supply->_nearest = $supply->lots->where('remaining_quantity', '>', 0)->sortBy('expiry_date')->first();
            return $supply;
        });

        if ($statusFilter) {
            $allResults = $allResults->filter(function ($supply) use ($statusFilter) {
                return $this->getSupplyStatus($supply) === $statusFilter;
            })->values();
        }

        if ($sortCol === 'stock') {
            $allResults = $sortDir === 'desc'
                ? $allResults->sortByDesc('total_stock_calc')->values()
                : $allResults->sortBy('total_stock_calc')->values();
        }

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $supplies = new LengthAwarePaginator(
            $allResults->slice(($currentPage - 1) * $perPage, $perPage)->values(),
            $allResults->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $supplies->withPath(route('staff.reports.stock'));

        // สถิติสรุป (ทั้งหมด ไม่แบ่งหน้า)
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

        // แจ้งเตือน
        $alertLowStock    = $stats['low_stock'] + $stats['out_of_stock'];
        $alertNearExpiry  = $stats['near_expiry'] + $stats['expired'];

        // รายการยาที่ stock น้อยกว่า 10 (critical low stock warning)
        $criticalLowItems = $allSupplies->filter(function ($s) {
            return (int)$s->total_stock_calc < 10 && (int)$s->total_stock_calc > 0;
        })->values();

        $criticalLowCount = $criticalLowItems->count();

        // กรองสถานะ (ทำหลัง paginate เพื่อแสดงเฉพาะสถานะที่ filter)
        $statusFilter = $request->get('status_filter');

        return view('staff.reports.stock', compact(
            'supplies', 'categories', 'units', 'stats',
            'alertLowStock', 'alertNearExpiry', 'sortCol', 'sortDir',
            'perPage', 'statusFilter', 'criticalLowItems', 'criticalLowCount'
        ));
    }

    protected function getSupplyStatus(Supply $supply): string
    {
        $stock = (int) ($supply->total_stock_calc ?? $supply->lots->sum('remaining_quantity'));
        $nearest = $supply->_nearest ?? $supply->lots->where('remaining_quantity', '>', 0)->sortBy('expiry_date')->first();

        if ($stock <= 0) {
            return 'out_of_stock';
        }
        if ($nearest && $nearest->expiry_date && $nearest->expiry_date->isPast()) {
            return 'expired';
        }
        if ($stock <= (int) $supply->min_stock) {
            return 'low_stock';
        }
        if ($nearest && $nearest->expiry_date && $nearest->expiry_date->diffInDays(now()) <= 90) {
            return 'near_expiry';
        }

        return 'normal';
    }

    protected function getSupplyStatusLabel(string $status): string
    {
        switch ($status) {
            case 'out_of_stock':
                return 'หมดสต็อก';
            case 'expired':
                return 'หมดอายุ';
            case 'low_stock':
                return 'ใกล้หมด';
            case 'near_expiry':
                return 'ใกล้หมดอายุ';
            default:
                return 'ปกติ';
        }
    }

    public function stockExportCsv(Request $request)
    {
        $query = Supply::with(['category', 'lots']);

        if ($request->search) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('code', 'like', "%{$term}%");
            });
        }
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->unit) {
            $query->where('unit', $request->unit);
        }

        $supplies = $query->orderBy('code')->get()->map(function ($s) {
            $s->total_stock_calc = $s->lots->sum('remaining_quantity');
            $nearest = $s->lots->where('remaining_quantity', '>', 0)->sortBy('expiry_date')->first();
            $s->_nearest = $nearest;
            return $s;
        });

        if ($request->status_filter) {
            $statusFilter = $request->status_filter;
            $supplies = $supplies->filter(function ($supply) use ($statusFilter) {
                return $this->getSupplyStatus($supply) === $statusFilter;
            })->values();
        }

        $filename = 'stock_report_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($supplies) {
            $file = fopen('php://output', 'w');
            // BOM for Excel Thai
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['รหัส', 'ชื่อเวชภัณฑ์', 'หมวดหมู่', 'หน่วย', 'คงเหลือ', 'ขั้นต่ำ', 'ผู้ผลิต', 'ตำแหน่ง', 'เลขล็อต', 'วันหมดอายุ', 'สถานะ']);

            foreach ($supplies as $s) {
                $nearest = $s->_nearest;
                $expiryStr = $nearest && $nearest->expiry_date ? $nearest->expiry_date->format('d/m/Y') : '-';
                $lotStr    = $nearest ? ($nearest->lot_number ?? '-') : '-';
                $stock     = (int)$s->total_stock_calc;

                if ($stock <= 0) $status = 'หมดสต็อก';
                elseif ($nearest && $nearest->expiry_date && $nearest->expiry_date->isPast()) $status = 'หมดอายุ';
                elseif ($stock <= $s->min_stock) $status = 'ใกล้หมด';
                elseif ($nearest && $nearest->expiry_date && $nearest->expiry_date->diffInDays(now()) <= 90) $status = 'ใกล้หมดอายุ';
                else $status = 'ปกติ';

                fputcsv($file, [
                    $s->code,
                    $s->name,
                    $s->category->name ?? '-',
                    $s->unit,
                    $stock,
                    $s->min_stock,
                    $s->manufacturer ?? '-',
                    $s->storage_location ?? '-',
                    $lotStr,
                    $expiryStr,
                    $status,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function stockExportExcel(Request $request)
    {
        $query = Supply::with(['category', 'lots']);

        if ($request->search) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('code', 'like', "%{$term}%");
            });
        }
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->unit) {
            $query->where('unit', $request->unit);
        }

        $supplies = $query->orderBy('code')->get()->map(function ($s) {
            $s->total_stock_calc = $s->lots->sum('remaining_quantity');
            $nearest = $s->lots->where('remaining_quantity', '>', 0)->sortBy('expiry_date')->first();
            $s->_nearest = $nearest;
            return $s;
        });

        if ($request->status_filter) {
            $statusFilter = $request->status_filter;
            $supplies = $supplies->filter(function ($supply) use ($statusFilter) {
                return $this->getSupplyStatus($supply) === $statusFilter;
            })->values();
        }

        $safe = fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
        $filename = 'stock_report_' . now()->format('Ymd_His') . '.xls';

        $logoTag = '';
        $logoPath = public_path('images/logo.png');
        if (file_exists($logoPath)) {
            $logoData = base64_encode(file_get_contents($logoPath));
            $logoTag = '<img src="data:image/png;base64,' . $logoData . '" alt="Logo" style="max-height:80px;display:block;" />';
        }

        $rows = '';
        foreach ($supplies as $s) {
            $nearest = $s->_nearest;
            $expiryStr = $nearest && $nearest->expiry_date ? $nearest->expiry_date->format('d/m/Y') : '-';
            $lotStr = $nearest ? ($nearest->lot_number ?? '-') : '-';
            $stock = (int) $s->total_stock_calc;
            $status = $this->getSupplyStatusLabel($this->getSupplyStatus($s));

            $rows .= '<tr>' .
                '<td>' . $safe($s->code) . '</td>' .
                '<td>' . $safe($s->name) . '</td>' .
                '<td>' . $safe($s->category->name ?? '-') . '</td>' .
                '<td>' . $safe($s->unit) . '</td>' .
                '<td>' . $safe($stock) . '</td>' .
                '<td>' . $safe($s->min_stock) . '</td>' .
                '<td>' . $safe($s->manufacturer ?? '-') . '</td>' .
                '<td>' . $safe($s->storage_location ?? '-') . '</td>' .
                '<td>' . $safe($lotStr) . '</td>' .
                '<td>' . $safe($expiryStr) . '</td>' .
                '<td>' . $safe($status) . '</td>' .
            '</tr>';
        }

        $html = '<html><head><meta charset="UTF-8" /><style>' .
            'table{border-collapse:collapse;font-family:Arial,sans-serif;font-size:12px;}' .
            'th,td{border:1px solid #777;padding:6px;}' .
            'th{background:#f3f4f6;color:#111;font-weight:700;}' .
            '.header-table{width:100%;margin-bottom:18px;border:none;}' .
            '.header-table td{border:none;padding:4px;vertical-align:middle;}' .
            '.report-title{font-size:18px;font-weight:700;margin:0 0 4px 0;}' .
            '.report-subtitle{font-size:13px;color:#444;margin:0;}' .
            '</style></head><body>' .
            '<table class="header-table"><tr>' .
                '<td style="width:120px;">' . $logoTag . '</td>' .
                '<td>' .
                    '<div class="report-title">รายงานสต็อกเวชภัณฑ์</div>' .
                    '<div class="report-subtitle">วันที่ส่งออก: ' . $safe(now()->format('d/m/Y H:i')) . '</div>' .
                '</td>' .
            '</tr></table>' .
            '<table>' .
            '<thead><tr>' .
                '<th>รหัส</th>' .
                '<th>ชื่อเวชภัณฑ์</th>' .
                '<th>หมวดหมู่</th>' .
                '<th>หน่วย</th>' .
                '<th>คงเหลือ</th>' .
                '<th>ขั้นต่ำ</th>' .
                '<th>ผู้ผลิต</th>' .
                '<th>ตำแหน่ง</th>' .
                '<th>เลขล็อต</th>' .
                '<th>วันหมดอายุ</th>' .
                '<th>สถานะ</th>' .
            '</tr></thead><tbody>' .
            $rows .
            '</tbody></table></body></html>';

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function stockExportPdf(Request $request)
    {
        $query = Supply::with(['category', 'lots']);

        if ($request->search) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('code', 'like', "%{$term}%");
            });
        }
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->unit) {
            $query->where('unit', $request->unit);
        }

        $supplies = $query->orderBy('code')->get()->map(function ($s) {
            $s->total_stock_calc = $s->lots->sum('remaining_quantity');
            $nearest = $s->lots->where('remaining_quantity', '>', 0)->sortBy('expiry_date')->first();
            $s->_nearest = $nearest;
            return $s;
        });

        if ($request->status_filter) {
            $statusFilter = $request->status_filter;
            $supplies = $supplies->filter(function ($supply) use ($statusFilter) {
                return $this->getSupplyStatus($supply) === $statusFilter;
            })->values();
        }

        $logoData = null;
        $logoPath = public_path('images/logo.png');
        if (file_exists($logoPath)) {
            $logoData = base64_encode(file_get_contents($logoPath));
        }

        $pdf = Pdf::loadView('staff.reports.stock-pdf', [
            'supplies' => $supplies,
            'logoData' => $logoData,
            'exportedAt' => now(),
        ])->setPaper('a3', 'landscape');

        return $pdf->download('stock_report_' . now()->format('Ymd_His') . '.pdf');
    }

    public function dispensing(Request $request)
    {
        $query = SupplyTransaction::with('supply', 'performer')
            ->where('type', 'dispense');

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->latest()->paginate(20);

        // สรุปรายการเบิกจ่ายตามเวชภัณฑ์
        $summaryQuery = SupplyTransaction::select('supply_id', DB::raw('SUM(quantity) as total_quantity'))
            ->where('type', 'dispense');

        if ($request->date_from) {
            $summaryQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $summaryQuery->whereDate('created_at', '<=', $request->date_to);
        }

        $summary = $summaryQuery->groupBy('supply_id')
            ->with('supply')
            ->get();

        return view('staff.reports.dispensing', compact('transactions', 'summary'));
    }

    public function expiry()
    {
        $expiredLots = SupplyLot::with('supply.category')
            ->where('remaining_quantity', '>', 0)
            ->where('expiry_date', '<', now())
            ->orderBy('expiry_date')
            ->get();

        $nearExpiryLots = SupplyLot::with('supply.category')
            ->where('remaining_quantity', '>', 0)
            ->where('expiry_date', '>', now())
            ->where('expiry_date', '<=', now()->addDays(90))
            ->orderBy('expiry_date')
            ->get();

        return view('staff.reports.expiry', compact('expiredLots', 'nearExpiryLots'));
    }
}
