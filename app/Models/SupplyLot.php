<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyLot extends Model
{
    use HasFactory;

    protected $fillable = [
        'supply_id', 'lot_number', 'quantity', 'remaining_quantity',
        'expiry_date', 'received_date', 'notes'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'received_date' => 'date',
    ];

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }

    public function transactions()
    {
        return $this->hasMany(SupplyTransaction::class);
    }

    public function getIsExpiredAttribute()
    {
        return $this->expiry_date->isPast();
    }

    public function getIsNearExpiryAttribute()
    {
        return $this->expiry_date->diffInDays(now()) <= 90 && !$this->is_expired;
    }
}
