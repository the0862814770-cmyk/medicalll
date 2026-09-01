<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'supply_id', 'supply_lot_id', 'type', 'quantity',
        'notes', 'reference', 'performed_by'
    ];

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }

    public function lot()
    {
        return $this->belongsTo(SupplyLot::class, 'supply_lot_id');
    }

    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'receive' => 'รับเข้า',
            'dispense' => 'เบิกจ่าย',
            'return' => 'รับคืน',
            'adjust' => 'ปรับยอด',
            default => $this->type,
        };
    }

    public function getTypeColorAttribute()
    {
        return match($this->type) {
            'receive' => 'success',
            'dispense' => 'danger',
            'return' => 'info',
            'adjust' => 'warning',
            default => 'secondary',
        };
    }
}
