<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SupplyLot;

// ดึง lot 3 รายการแรก แล้วตั้ง remaining_quantity ให้น้อยกว่า 10
$lots = SupplyLot::take(3)->get();
$stocks = [3, 7, 5];

foreach ($lots as $i => $lot) {
    $lot->remaining_quantity = $stocks[$i];
    $lot->save();
    echo "Updated lot ID {$lot->id} -> remaining_quantity = {$stocks[$i]}\n";
}

echo "Done! กรุณารีเฟรชหน้า /staff/reports/stock\n";
