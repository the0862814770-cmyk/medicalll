<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หนังสือขอเบิก-ยืมกระเป๋าปฐมพยาบาล - {{ $kitRequest->request_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background-color: #f4f6f9;
            color: #2b2b2b;
            font-size: 14px;
            line-height: 1.6;
        }

        .action-bar {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 24px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .paper-container {
            max-width: 800px;
            margin: 30px auto;
            background: #ffffff;
            padding: 48px 56px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: 1px solid #e5e7eb;
        }

        .doc-header {
            text-align: center;
            border-bottom: 2px double #1e293b;
            padding-bottom: 20px;
            margin-bottom: 24px;
        }

        .doc-logo {
            width: 75px;
            height: 75px;
            object-fit: contain;
            margin-bottom: 10px;
        }

        .doc-title {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin: 4px 0;
        }

        .doc-subtitle {
            font-size: 13px;
            color: #475569;
        }

        .info-badge {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 8px 16px;
        }

        .section-title {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
            border-left: 4px solid #4f46e5;
            padding-left: 10px;
            margin-top: 24px;
            margin-bottom: 12px;
        }

        .signature-box {
            text-align: center;
            padding-top: 40px;
        }

        .dots-line {
            border-bottom: 1px dotted #64748b;
            display: inline-block;
            width: 200px;
            margin-bottom: 6px;
        }

        @media print {
            .action-bar {
                display: none !important;
            }
            body {
                background: #ffffff;
                padding: 0;
            }
            .paper-container {
                max-width: 100%;
                margin: 0;
                padding: 20px 30px;
                box-shadow: none;
                border: none;
            }
            @page {
                size: A4;
                margin: 15mm;
            }
        }
    </style>
</head>
<body>

    <!-- Action Bar (Hidden on print) -->
    <div class="action-bar d-flex justify-content-between align-items-center">
        <div>
            <button onclick="history.back()" class="btn btn-outline-secondary btn-sm me-2">
                <i class="bi bi-arrow-left me-1"></i> ย้อนกลับ
            </button>
            <span class="fw-bold text-dark me-2">หนังสือขอเบิก-ยืมกระเป๋าปฐมพยาบาล</span>
            <span class="badge bg-primary">{{ $kitRequest->request_number }}</span>
        </div>
        <div>
            <button onclick="window.print()" class="btn btn-primary btn-sm px-3">
                <i class="bi bi-printer me-1"></i> พิมพ์หนังสือขอเบิก (Print)
            </button>
        </div>
    </div>

    <!-- Official Paper Document Container -->
    <div class="paper-container">
        <!-- Header -->
        <div class="doc-header">
            <img src="{{ asset('images/logo.png') }}" alt="ตรามหาวิทยาลัย" class="doc-logo">
            <h5 class="doc-title">แบบฟอร์มคำร้องขอเบิก-ยืมกระเป๋าปฐมพยาบาล</h5>
            <div class="doc-subtitle">งานพยาบาลและอนามัย กองกิจการนักศึกษา มหาวิทยาลัยราชภัฏนครศรีธรรมราช</div>
        </div>

        <!-- Document Ref Info -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="info-badge">
                <span class="text-muted small">เลขที่คำร้อง:</span> <strong>{{ $kitRequest->request_number }}</strong>
            </div>
            <div class="text-end">
                <div><small class="text-muted">วันที่ยื่นคำร้อง:</small> {{ $kitRequest->created_at->format('d/m/Y H:i') }} น.</div>
                <div><small class="text-muted">สถานะ:</small> <strong class="text-primary">{{ $kitRequest->status_label }}</strong></div>
            </div>
        </div>

        <!-- Section 1: Requester Details -->
        <div class="section-title">1. ข้อมูลผู้ยื่นคำร้องขอเบิก-ยืม</div>
        <table class="table table-sm table-borderless mb-2 ms-2">
            <tr>
                <td style="width: 150px;" class="fw-bold text-secondary">ชื่อ-นามสกุลผู้ขอยืม:</td>
                <td>{{ $kitRequest->user->name }}</td>
                <td style="width: 160px;" class="fw-bold text-secondary">รหัสนักศึกษา/พนักงาน:</td>
                <td>{{ $kitRequest->user->student_id ?? '-' }}</td>
            </tr>
            <tr>
                <td class="fw-bold text-secondary">สังกัด/คณะ/สาขา:</td>
                <td>{{ $kitRequest->user->department ?? '-' }}</td>
                <td class="fw-bold text-secondary">เบอร์โทรศัพท์ติดต่อ:</td>
                <td>{{ $kitRequest->user->phone ?? '-' }}</td>
            </tr>
            <tr>
                <td class="fw-bold text-secondary">อีเมล:</td>
                <td colspan="3">{{ $kitRequest->user->email ?? '-' }}</td>
            </tr>
        </table>

        <!-- Section 2: Request Details -->
        <div class="section-title">2. รายละเอียดการขอเบิก-ยืมกระเป๋าปฐมพยาบาล</div>
        <table class="table table-sm table-borderless mb-2 ms-2">
            <tr>
                <td style="width: 150px;" class="fw-bold text-secondary">รายการกระเป๋ายา:</td>
                <td><strong>{{ $kitRequest->kit->name ?? '-' }}</strong> @if(isset($kitRequest->kit->kit_code)) (รหัส: {{ $kitRequest->kit->kit_code }}) @endif</td>
            </tr>
            <tr>
                <td class="fw-bold text-secondary">วัตถุประสงค์ในการยืม:</td>
                <td>{{ $kitRequest->purpose }}</td>
            </tr>
            <tr>
                <td class="fw-bold text-secondary">ระยะเวลาการยืม:</td>
                <td>ตั้งแต่วันที่ <strong>{{ $kitRequest->borrow_date ? $kitRequest->borrow_date->format('d/m/Y') : '-' }}</strong> ถึงวันที่ <strong>{{ $kitRequest->expected_return_date ? $kitRequest->expected_return_date->format('d/m/Y') : '-' }}</strong></td>
            </tr>
            @if($kitRequest->actual_return_date)
            <tr>
                <td class="fw-bold text-secondary">วันที่คืนกระเป๋าจริง:</td>
                <td>{{ $kitRequest->actual_return_date->format('d/m/Y') }}</td>
            </tr>
            @endif
        </table>

        <!-- Section 3: Kit items -->
        @if($kitRequest->kit && $kitRequest->kit->items && $kitRequest->kit->items->count() > 0)
        <div class="section-title">3. รายการเวชภัณฑ์และอุปกรณ์ภายในกระเป๋า</div>
        <table class="table table-sm table-bordered my-2 align-middle">
            <thead class="table-light">
                <tr class="text-center">
                    <th style="width: 50px;">ลำดับ</th>
                    <th>รายการเวชภัณฑ์ / อุปกรณ์</th>
                    <th style="width: 120px;">จำนวนจัดส่ง</th>
                    <th style="width: 120px;">สภาพอุปกรณ์</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kitRequest->kit->items as $idx => $item)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ $item->supply->name ?? '-' }}</td>
                    <td class="text-center">{{ $item->quantity }} {{ $item->supply->unit ?? 'ชิ้น' }}</td>
                    <td class="text-center text-muted">สมบูรณ์</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <!-- Section 4: Terms -->
        <div class="section-title">4. ข้อตกลงและความรับผิดชอบในการยืม</div>
        <div class="px-3 py-2 bg-light rounded text-muted small mb-4">
            1. ผู้ยืมต้องดูแลรักษากระเป๋าปฐมพยาบาลและอุปกรณ์เวชภัณฑ์ให้อยู่ในสภาพพร้อมใช้งานเสมอ<br>
            2. ต้องส่งคืนกระเป๋ายาตรงตามกำหนดเวลา หากอุปกรณ์เกิดความชำรุด สูญหาย หรือเสียหาย ผู้ยืมยินยอมรับผิดชอบชดใช้ตามระเบียบของมหาวิทยาลัยฯ
        </div>

        <!-- Section 5: Signatures -->
        <div class="row signature-box">
            <div class="col-4">
                <div class="dots-line"></div>
                <div>( {{ $kitRequest->user->name }} )</div>
                <div class="small text-muted">ผู้ขอยืมกระเป๋าปฐมพยาบาล</div>
                <div class="small text-muted mt-1">วันที่ ....../....../..........</div>
            </div>
            <div class="col-4">
                <div class="dots-line"></div>
                <div>( {{ $kitRequest->approver->name ?? '........................................' }} )</div>
                <div class="small text-muted">เจ้าหน้าที่ผู้ตรวจรับ / อนุมัติ</div>
                <div class="small text-muted mt-1">วันที่ ....../....../..........</div>
            </div>
            <div class="col-4">
                <div class="dots-line"></div>
                <div>( ........................................ )</div>
                <div class="small text-muted">หัวหน้างานพยาบาลและอนามัย</div>
                <div class="small text-muted mt-1">วันที่ ....../....../..........</div>
            </div>
        </div>
    </div>

</body>
</html>
