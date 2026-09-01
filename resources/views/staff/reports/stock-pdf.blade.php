<!doctype html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <style>
        @font-face {
            font-family: 'Tahoma';
            font-style: normal;
            font-weight: 400;
            src: url('{{ storage_path("fonts/tahoma.ttf") }}') format('truetype');
        }
        @font-face {
            font-family: 'Tahoma';
            font-style: normal;
            font-weight: 700;
            src: url('{{ storage_path("fonts/tahomabd.ttf") }}') format('truetype');
        }
        @page {
            size: A3 landscape;
            margin: 10mm;
        }
        body { 
            font-family: 'Tahoma', sans-serif; 
            color: #333333; 
            line-height: 1.35;
            margin: 0;
            padding: 0;
            font-size: 10px;
        }
        .document-header { 
            width: 100%; 
            margin-bottom: 18px; 
            border-bottom: 2px solid #5b4fe0;
            padding-bottom: 12px;
        }
        .document-header td { vertical-align: middle; }
        .logo img { max-height: 70px; }
        .title { font-size: 22px; font-weight: 700; color: #2d2470; margin: 0 0 5px 0; }
        .subtitle { font-size: 13px; color: #666; margin: 2px 0; }
        
        .stock-table { 
            width: 100%; 
            border-collapse: collapse; 
            font-size: 11px; 
            margin-top: 10px;
        }
        .stock-table th, .stock-table td { 
            border: 1px solid #dee2e6; 
            padding: 8px; 
            word-wrap: break-word; 
        }
        .stock-table th { 
            background-color: #5b4fe0; 
            color: #ffffff; 
            font-weight: 700; 
            text-align: left;
            border-color: #5b4fe0;
            padding-top: 10px;
            padding-bottom: 10px;
        }
        .stock-table tbody tr:nth-child(even) td { 
            background-color: #f8f9fa; 
        }
        .status-badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-normal { color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; }
        .status-low { color: #856404; background-color: #fff3cd; border: 1px solid #ffeeba; }
        .status-out { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; }
        .status-expired { color: #383d41; background-color: #e2e3e5; border: 1px solid #d6d8db; }
        
        .header-left { padding-left: 20px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <table class="document-header">
        <tr>
            <td class="logo" style="width: 100px; text-align: center;">
                @if($logoData)
                    <img src="data:image/png;base64,{{ $logoData }}" alt="Logo">
                @endif
            </td>
            <td class="header-left">
                <div class="title">รายงานสต็อกเวชภัณฑ์</div>
                <div class="subtitle" style="font-size: 14px; font-weight: 700; color: #444;">ระบบบริหารคลังเวชภัณฑ์ มหาวิทยาลัยราชภัฏนครศรีธรรมราช</div>
                <div class="subtitle">วันที่พิมพ์รายงาน: {{ $exportedAt->format('d/m/Y H:i') }} | จำนวนรายการ: {{ $supplies->count() }} รายการ</div>
            </td>
        </tr>
    </table>

    <table class="stock-table">
        <thead>
            <tr>
                <th style="width: 6%;">รหัส</th>
                <th style="width: 18%;">ชื่อเวชภัณฑ์</th>
                <th style="width: 12%;">หมวดหมู่</th>
                <th style="width: 7%;">หน่วย</th>
                <th style="width: 7%; text-align: right;">คงเหลือ</th>
                <th style="width: 7%; text-align: right;">ขั้นต่ำ</th>
                <th style="width: 12%;">ผู้ผลิต</th>
                <th style="width: 10%;">ตำแหน่ง</th>
                <th style="width: 8%;">เลขล็อต</th>
                <th style="width: 8%;">วันหมดอายุ</th>
                <th style="width: 8%; text-align: center;">สถานะ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($supplies as $s)
                @php
                    $nearest = $s->_nearest;
                    $expiryStr = $nearest && $nearest->expiry_date ? $nearest->expiry_date->format('d/m/Y') : '-';
                    $lotStr = $nearest ? ($nearest->lot_number ?? '-') : '-';
                    $stock = (int)$s->total_stock_calc;
                    if ($stock <= 0) {
                        $status = 'หมดสต็อก';
                        $statusClass = 'status-out';
                    } elseif ($nearest && $nearest->expiry_date && $nearest->expiry_date->isPast()) {
                        $status = 'หมดอายุ';
                        $statusClass = 'status-expired';
                    } elseif ($stock <= $s->min_stock) {
                        $status = 'ใกล้หมด';
                        $statusClass = 'status-low';
                    } elseif ($nearest && $nearest->expiry_date && $nearest->expiry_date->diffInDays(now()) <= 90) {
                        $status = 'ใกล้หมดอายุ';
                        $statusClass = 'status-low';
                    } else {
                        $status = 'ปกติ';
                        $statusClass = 'status-normal';
                    }
                @endphp
                <tr>
                    <td>{{ $s->code }}</td>
                    <td><strong>{{ $s->name }}</strong></td>
                    <td>{{ $s->category->name ?? '-' }}</td>
                    <td>{{ $s->unit }}</td>
                    <td class="text-right" style="font-weight: 700; color: {{ $stock <= $s->min_stock ? '#dc3545' : '#28a745' }};">{{ $stock }}</td>
                    <td class="text-right">{{ $s->min_stock }}</td>
                    <td>{{ $s->manufacturer ?? '-' }}</td>
                    <td>{{ $s->storage_location ?? '-' }}</td>
                    <td>{{ $lotStr }}</td>
                    <td>{{ $expiryStr }}</td>
                    <td class="text-center"><span class="status-badge {{ $statusClass }}">{{ $status }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
