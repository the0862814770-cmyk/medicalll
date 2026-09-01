<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Supply;
use App\Models\SupplyLot;
use App\Models\FirstAidKit;
use App\Models\KitItem;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // ===== ผู้ใช้ตัวอย่าง =====
        User::create([
            'name' => 'ผู้ดูแลระบบ',
            'email' => 'admin@nstru.ac.th',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'สมศรี รักพยาบาล',
            'email' => 'staff@nstru.ac.th',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'phone' => '081-234-5678',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'ดร.สมชาย ผู้บริหาร',
            'email' => 'executive@nstru.ac.th',
            'password' => Hash::make('password'),
            'role' => 'executive',
            'phone' => '082-345-6789',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'สมหญิง ใจดี',
            'email' => 'user@nstru.ac.th',
            'password' => Hash::make('password'),
            'role' => 'user',
            'student_id' => '6401012345',
            'phone' => '083-456-7890',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'สมชาย เรียนดี',
            'email' => 'user2@nstru.ac.th',
            'password' => Hash::make('password'),
            'role' => 'user',
            'student_id' => '6401012346',
            'phone' => '084-567-8901',
            'status' => 'active',
        ]);

        // ===== หมวดหมู่ =====
        $categories = [
            Category::create(['name' => 'ยาสามัญ', 'description' => 'ยาสามัญทั่วไป']),
            Category::create(['name' => 'ยาแก้ปวดลดไข้', 'description' => 'ยาแก้ปวด ลดไข้']),
            Category::create(['name' => 'ยาทาภายนอก', 'description' => 'ยาทาแผล ครีม ขี้ผึ้ง']),
            Category::create(['name' => 'วัสดุทำแผล', 'description' => 'ผ้าพันแผล พลาสเตอร์ สำลี']),
            Category::create(['name' => 'อุปกรณ์ปฐมพยาบาล', 'description' => 'ปรอท ถุงมือ กรรไกร']),
            Category::create(['name' => 'ยาระบบทางเดินอาหาร', 'description' => 'ยาแก้ท้องเสีย ยาลดกรด']),
        ];

        // ===== เวชภัณฑ์ =====
        $categoryColors = ['#2563eb', '#dc2626', '#16a34a', '#ea580c', '#7c3aed', '#0891b2'];

        $supplies = [
            ['category' => 1, 'code' => 'MED001', 'name' => 'พาราเซตามอล 500 mg', 'unit' => 'เม็ด', 'min_stock' => 100],
            ['category' => 1, 'code' => 'MED002', 'name' => 'แอมม็อกซีซิลลิน 500 mg', 'unit' => 'แคปซูล', 'min_stock' => 50],
            ['category' => 0, 'code' => 'MED003', 'name' => 'ไอบูโพรเฟน 400 mg', 'unit' => 'เม็ด', 'min_stock' => 50],
            ['category' => 2, 'code' => 'MED004', 'name' => 'เบตาดีน 30 ml', 'unit' => 'ขวด', 'min_stock' => 20],
            ['category' => 2, 'code' => 'MED005', 'name' => 'ยาหม่อง', 'unit' => 'กระปุก', 'min_stock' => 15],
            ['category' => 3, 'code' => 'MED006', 'name' => 'ผ้าพันแผลแบบม้วน', 'unit' => 'ม้วน', 'min_stock' => 30],
            ['category' => 3, 'code' => 'MED007', 'name' => 'พลาสเตอร์ปิดแผล', 'unit' => 'ชิ้น', 'min_stock' => 100],
            ['category' => 3, 'code' => 'MED008', 'name' => 'สำลีก้อน', 'unit' => 'ห่อ', 'min_stock' => 20],
            ['category' => 4, 'code' => 'MED009', 'name' => 'ปรอทวัดไข้ดิจิตอล', 'unit' => 'ชิ้น', 'min_stock' => 5],
            ['category' => 4, 'code' => 'MED010', 'name' => 'ถุงมือยาง', 'unit' => 'คู่', 'min_stock' => 50],
            ['category' => 5, 'code' => 'MED011', 'name' => 'ยาธาตุน้ำขาว', 'unit' => 'ขวด', 'min_stock' => 10],
            ['category' => 5, 'code' => 'MED012', 'name' => 'ผงเกลือแร่ ORS', 'unit' => 'ซอง', 'min_stock' => 50],
            ['category' => 0, 'code' => 'MED013', 'name' => 'ยาแก้แพ้ (คลอร์เฟนิรามีน)', 'unit' => 'เม็ด', 'min_stock' => 50],
            ['category' => 2, 'code' => 'MED014', 'name' => 'แอลกอฮอล์เช็ดแผล 70%', 'unit' => 'ขวด', 'min_stock' => 15],
        ];

        $this->ensureDefaultSupplyImage();

        foreach ($supplies as $supplyData) {
            $color = $categoryColors[$supplyData['category']];
            $imagePath = $this->ensureSupplyImage($supplyData['code'], $supplyData['name'], $color);

            $supply = Supply::create([
                'category_id' => $categories[$supplyData['category']]->id,
                'code' => $supplyData['code'],
                'name' => $supplyData['name'],
                'unit' => $supplyData['unit'],
                'min_stock' => $supplyData['min_stock'],
                'image' => $imagePath,
            ]);

            // สร้าง lot ตัวอย่าง
            SupplyLot::create([
                'supply_id' => $supply->id,
                'lot_number' => 'LOT' . date('Y') . str_pad($supply->id, 3, '0', STR_PAD_LEFT),
                'quantity' => rand(50, 500),
                'remaining_quantity' => rand(30, 300),
                'expiry_date' => now()->addMonths(rand(3, 24)),
                'received_date' => now()->subDays(rand(1, 90)),
            ]);
        }

        // ===== กระเป๋าปฐมพยาบาล =====
        $kit1 = FirstAidKit::create([
            'kit_code' => 'KIT001',
            'name' => 'กระเป๋าปฐมพยาบาลชุดเล็ก',
            'status' => 'available',
            'description' => 'สำหรับกิจกรรมขนาดเล็ก 10-30 คน',
        ]);

        $kit2 = FirstAidKit::create([
            'kit_code' => 'KIT002',
            'name' => 'กระเป๋าปฐมพยาบาลชุดกลาง',
            'status' => 'available',
            'description' => 'สำหรับกิจกรรมขนาดกลาง 30-100 คน',
        ]);

        $kit3 = FirstAidKit::create([
            'kit_code' => 'KIT003',
            'name' => 'กระเป๋าปฐมพยาบาลชุดใหญ่',
            'status' => 'available',
            'description' => 'สำหรับค่ายอาสาพัฒนาหรือกิจกรรมขนาดใหญ่',
        ]);

        // ใส่รายการยาในกระเป๋าตัวอย่าง
        $allSupplies = Supply::all();
        if ($allSupplies->count() >= 4) {
            KitItem::create(['first_aid_kit_id' => $kit1->id, 'supply_id' => $allSupplies[0]->id, 'quantity' => 20]);
            KitItem::create(['first_aid_kit_id' => $kit1->id, 'supply_id' => $allSupplies[3]->id, 'quantity' => 2]);
            KitItem::create(['first_aid_kit_id' => $kit1->id, 'supply_id' => $allSupplies[6]->id, 'quantity' => 30]);

            KitItem::create(['first_aid_kit_id' => $kit2->id, 'supply_id' => $allSupplies[0]->id, 'quantity' => 50]);
            KitItem::create(['first_aid_kit_id' => $kit2->id, 'supply_id' => $allSupplies[2]->id, 'quantity' => 20]);
            KitItem::create(['first_aid_kit_id' => $kit2->id, 'supply_id' => $allSupplies[5]->id, 'quantity' => 5]);
            KitItem::create(['first_aid_kit_id' => $kit2->id, 'supply_id' => $allSupplies[6]->id, 'quantity' => 50]);

            KitItem::create(['first_aid_kit_id' => $kit3->id, 'supply_id' => $allSupplies[0]->id, 'quantity' => 100]);
            KitItem::create(['first_aid_kit_id' => $kit3->id, 'supply_id' => $allSupplies[1]->id, 'quantity' => 30]);
            KitItem::create(['first_aid_kit_id' => $kit3->id, 'supply_id' => $allSupplies[3]->id, 'quantity' => 5]);
            KitItem::create(['first_aid_kit_id' => $kit3->id, 'supply_id' => $allSupplies[8]->id, 'quantity' => 2]);
        }

        echo "✅ Seeded: 5 users, 6 categories, 14 supplies, 3 first aid kits with items\n";
    }

    private function ensureDefaultSupplyImage(): void
    {
        $dir = public_path('images/supplies');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $path = $dir . '/default.svg';
        if (file_exists($path)) {
            return;
        }

        file_put_contents($path, <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200">
  <rect width="200" height="200" fill="#f8fafc" rx="16"/>
  <rect x="55" y="55" width="90" height="50" rx="25" fill="#94a3b8"/>
  <rect x="100" y="55" width="45" height="50" fill="#ffffff" opacity="0.35"/>
  <text x="100" y="145" text-anchor="middle" font-family="Arial,sans-serif" font-size="13" fill="#64748b">ไม่มีรูปภาพ</text>
</svg>
SVG);
    }

    private function ensureSupplyImage(string $code, string $name, string $color): string
    {
        $dir = public_path('images/supplies');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filename = strtolower($code) . '.svg';
        $path = $dir . '/' . $filename;

        if (! file_exists($path)) {
            $shortName = htmlspecialchars(mb_substr($name, 0, 24), ENT_QUOTES | ENT_XML1);
            $safeCode = htmlspecialchars($code, ENT_QUOTES | ENT_XML1);

            file_put_contents($path, <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200">
  <rect width="200" height="200" fill="#f8fafc" rx="16"/>
  <rect x="20" y="20" width="160" height="120" fill="{$color}" opacity="0.12" rx="12"/>
  <rect x="55" y="55" width="90" height="50" rx="25" fill="{$color}"/>
  <rect x="100" y="55" width="45" height="50" fill="#ffffff" opacity="0.35"/>
  <text x="100" y="165" text-anchor="middle" font-family="Arial,sans-serif" font-size="11" fill="#334155">{$shortName}</text>
  <text x="100" y="185" text-anchor="middle" font-family="Arial,sans-serif" font-size="10" fill="#94a3b8">{$safeCode}</text>
</svg>
SVG);
        }

        return 'images/supplies/' . $filename;
    }
}
