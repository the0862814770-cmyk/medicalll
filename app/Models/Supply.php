<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'code', 'unit', 'min_stock', 'description', 'image',
        'manufacturer', 'storage_location', 'image_path'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function lots()
    {
        return $this->hasMany(SupplyLot::class);
    }

    public function activeLots()
    {
        return $this->hasMany(SupplyLot::class)->where('remaining_quantity', '>', 0);
    }

    public function transactions()
    {
        return $this->hasMany(SupplyTransaction::class);
    }

    public function requestItems()
    {
        return $this->hasMany(MedicineRequestItem::class);
    }

    public function kitItems()
    {
        return $this->hasMany(KitItem::class);
    }

    // คำนวณจำนวนคงเหลือจากทุก lot
    public function getTotalStockAttribute()
    {
        if (isset($this->attributes['total_stock_calc'])) {
            return (int)$this->attributes['total_stock_calc'];
        }
        return (int)$this->lots()->sum('remaining_quantity');
    }

    // สัดส่วนเปอร์เซ็นต์สต็อกเทียบกับเป้าหมายขั้นต่ำ
    public function getStockPercentAttribute()
    {
        $target = max(1, $this->min_stock * 2);
        return min(100, max(0, round(($this->total_stock / $target) * 100)));
    }

    // ตรวจสอบสต็อกต่ำ
    public function getIsLowStockAttribute()
    {
        return $this->total_stock <= $this->min_stock && $this->total_stock > 0;
    }

    // หา lot ที่ใกล้หมดอายุที่สุดที่มีของ
    public function getNearestExpiryAttribute()
    {
        return $this->lots
            ->where('remaining_quantity', '>', 0)
            ->sortBy('expiry_date')
            ->first();
    }

    // สถานะอย่างละเอียด (5 ระดับ)
    public function getDetailedStatusAttribute()
    {
        $stock = $this->total_stock;
        $nearest = $this->nearest_expiry;

        if ($stock <= 0) {
            return [
                'code' => 'out_of_stock',
                'label' => 'หมดสต็อก',
                'color' => 'danger',
                'badge' => 'bg-danger text-white',
                'icon' => 'bi-x-circle-fill',
                'row_class' => 'table-danger'
            ];
        }

        if ($nearest && $nearest->expiry_date && $nearest->expiry_date->isPast()) {
            return [
                'code' => 'expired',
                'label' => 'หมดอายุ',
                'color' => 'dark',
                'badge' => 'bg-dark text-white',
                'icon' => 'bi-slash-circle-fill',
                'row_class' => 'table-dark text-white'
            ];
        }

        if ($stock <= $this->min_stock) {
            return [
                'code' => 'low_stock',
                'label' => 'ใกล้หมด',
                'color' => 'warning',
                'badge' => 'bg-warning text-dark',
                'icon' => 'bi-exclamation-triangle-fill',
                'row_class' => 'table-warning'
            ];
        }

        if ($nearest && $nearest->expiry_date && $nearest->expiry_date->diffInDays(now()) <= 90) {
            return [
                'code' => 'near_expiry',
                'label' => 'ใกล้หมดอายุ',
                'color' => 'orange',
                'badge' => 'bg-warning bg-opacity-75 text-dark',
                'icon' => 'bi-clock-history',
                'row_class' => 'table-warning'
            ];
        }

        return [
            'code' => 'normal',
            'label' => 'ปกติ',
            'color' => 'success',
            'badge' => 'bg-success text-white',
            'icon' => 'bi-check-circle-fill',
            'row_class' => ''
        ];
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image_path) {
            return asset($this->image_path);
        }

        if ($this->image) {
            if (str_starts_with($this->image, 'images/')) {
                return asset($this->image);
            }
            return asset('storage/' . $this->image);
        }

        return asset('images/supplies/default.svg');
    }
}
